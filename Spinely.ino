#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "YourWiFiSSID";
const char* password = "YourWiFiPassword";
const char* serverIP = "192.168.1.100";
const int serverPort = 80;
const char* deviceEndpoint = "/device.php";
const char* choicesEndpoint = "/choices.php";
const char* calibrationEndpoint = "/calibration.php";
const char* monitoringEndpoint = "/monitoring.php";
const char* deviceName = "MyDevice";
const char* deviceIP = "192.168.1.200";

bool isOperating = false;
String receivedCommand = "";

unsigned long previousTime = 0;
const unsigned long interval = 1000;
int elapsedTime = 0;

const int flexPins[] = {36, 39, 32, 33, 34, 35, 25};
const int numFlexPins = sizeof(flexPins) / sizeof(flexPins[0]);
const int motor = 12;

float sensorValues[numFlexPins];
int minFlexValues[numFlexPins];
int angles[numFlexPins];

void setup() {
  Serial.begin(115200);

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
  fetchMinFlexValues();
  sendDeviceInfo();
  isOperating = false;
}

void loop() {
  if (isOperating) {
    if (receivedCommand == "calibration") {
      performCalibration();
    } else if (receivedCommand == "monitoring") {
      startMonitoring();
    } else if (receivedCommand == "turn_on_motor") {
      turnOnMotor();
    }
    isOperating = false;
  } else {
    checkForCommands();
  }
}

void sendDeviceInfo() {
  HTTPClient http;

  String url = "http://" + String(serverIP) + ":" + String(serverPort) + deviceEndpoint;
  String data = "deviceName=" + String(deviceName) + "&deviceIP=" + String(deviceIP) + "&status=" + (isOperating ? "operating" : "waiting");
  http.begin(url);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpResponseCode = http.POST(data);
  if (httpResponseCode == HTTP_CODE_OK) {
    Serial.println("Device information sent successfully");
  } else {
    Serial.print("Failed to send device information. Error code: ");
    Serial.println(httpResponseCode);
  }

  http.end();
}

void checkForCommands() {
  HTTPClient http;

  String url = "http://" + String(serverIP) + ":" + String(serverPort) + choicesEndpoint;

  http.begin(url);
  int httpResponseCode = http.GET();

  if (httpResponseCode == HTTP_CODE_OK) {
    receivedCommand = http.getString();
    Serial.print("Received command: ");
    Serial.println(receivedCommand);

    isOperating = true;

    elapsedTime = 0;
  } else {
    Serial.print("Failed to check for commands. Error code: ");
    Serial.println(httpResponseCode);
  }

  http.end();
}

void performCalibration() {
  Serial.println("Performing calibration...");

  while (elapsedTime < 10000) {
    unsigned long currentTime = millis();
    if (currentTime - previousTime >= interval) {
      previousTime = currentTime;

      readSensorValues();
      sendSensorValuesCalibration();

      elapsedTime += interval;
    }
  }

  Serial.println("Calibration complete");
}

void startMonitoring() {
  Serial.println("Starting monitoring...");

  while (isOperating) {
    unsigned long currentTime = millis();
    if (currentTime - previousTime >= interval) {
      previousTime = currentTime;

      readSensorValues();
      sendSensorValuesMonitoring();

      elapsedTime += interval;
    }
  }
}

void turnOnMotor() {
  Serial.println("Turning on motor...");
  digitalWrite(motor, HIGH);
  delay(2000);
  digitalWrite(motor, LOW);
  Serial.println("Motor turned off");
}

void readSensorValues() {
  for (int i = 0; i < numFlexPins; i++) {
    sensorValues[i] = analogRead(flexPins[i]);
  }
}

void sendSensorValuesCalibration() {
  HTTPClient http;

  String url = "http://" + String(serverIP) + ":" + String(serverPort) + calibrationEndpoint;

  String data = "";
  for (int i = 0; i < numFlexPins; i++) {
    if (i > 0) {
      data += "&";
    }
    data += "sensor" + String(i) + "=" + String(sensorValues[i]);
  }

  http.begin(url);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpResponseCode = http.POST(data);

  if (httpResponseCode == HTTP_CODE_OK) {
    Serial.println("Sensor data sent successfully");
  } else {
    Serial.print("Failed to send sensor data. Error code: ");
    Serial.println(httpResponseCode);
  }

  http.end();
}

void sendSensorValuesMonitoring() {
  HTTPClient http;

  String url = "http://" + String(serverIP) + ":" + String(serverPort) + monitoringEndpoint;

  String data = "";
  for (int i = 0; i < numFlexPins; i++) {
    if (i > 0) {
      data += "&";
    }
    data += "sensor" + String(i) + "=" + String(sensorValues[i]);
  }

  http.begin(url);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpResponseCode = http.POST(data);

  if (httpResponseCode == HTTP_CODE_OK) {
    Serial.println("Sensor data sent successfully");
  } else {
    Serial.print("Failed to send sensor data. Error code: ");
    Serial.println(httpResponseCode);
  }

  http.end();
}

void fetchMinFlexValues() {
  HTTPClient http;
  String url = "http://" + String(serverIP) + ":" + String(serverPort) + "/fetch_min_flex_values.php";
  http.begin(url);
  int httpResponseCode = http.GET();
  if (httpResponseCode == HTTP_CODE_OK) {
    String response = http.getString();
    parseMinFlexValues(response);
  } else {
    Serial.print("Failed to fetch minimum flex values. Error code: ");
    Serial.println(httpResponseCode);
  }
  http.end();
}

void parseMinFlexValues(String response) {
  int index = 0;
  while (response.length() > 0) {
    int commaIndex = response.indexOf(',');
    if (commaIndex != -1) {
      String valueStr = response.substring(0, commaIndex);
      response = response.substring(commaIndex + 1);
      minFlexValues[index] = valueStr.toInt();
    } else {
      minFlexValues[index] = response.toInt();
      response = "";
    }

    index++;
  }

  Serial.println("Minimum Flex Values:");
  for (int i = 0; i < numFlexPins; i++) {
    Serial.print("Pin ");
    Serial.print(flexPins[i]);
    Serial.print(": ");
    Serial.println(minFlexValues[i]);
  }
}
