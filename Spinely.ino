#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "YourWiFiSSID";
const char* password = "YourWiFiPassword";
const char* serverIP = "192.168.1.100";
const int serverPort = 80;
const char* endpoint = "/functions.php";
const char* deviceName = "MyDevice";
const char* deviceIP = "192.168.1.200";

bool isOperating = false;

void setup() {
  Serial.begin(115200);

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }

  Serial.println("Connected to WiFi");
  
  sendDeviceInfo();

  isOperating = false;
}

void loop() {
  // Check if device is operating or waiting for command
  if (isOperating) {
    // Code for operating the device goes here
    // Execute the appropriate function based on the received command
    if (receivedCommand == "calibration") {
      performCalibration();
    } else if (receivedCommand == "monitoring") {
      startMonitoring();
    } else if (receivedCommand == "turn_on_motor") {
      turnOnMotor();
    }

    // After executing the command, set the device back to waiting state
    isOperating = false;
  } else {
    // Code for waiting for command goes here
    // Check for new commands from the server
    checkForCommands();
  }
}

void sendDeviceInfo() {
  HTTPClient http;
  
  String url = "http://" + String(serverIP) + ":" + String(serverPort) + endpoint;
  
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
  // Create the HTTP object
  HTTPClient http;

  // Construct the URL
  String url = "http://" + String(serverIP) + ":" + String(serverPort) + endpoint;

  // Send the GET request to check for commands
  http.begin(url);
  int httpResponseCode = http.GET();

  // Check if a command is received
  if (httpResponseCode == HTTP_CODE_OK) {
    String receivedCommand = http.getString();
    Serial.print("Received command: ");
    Serial.println(receivedCommand);

    // Set the device to operating state
    isOperating = true;
  } else {
    Serial.print("Failed to check for commands. Error code: ");
    Serial.println(httpResponseCode);
  }

  // Close the connection
  http.end();
}

void performCalibration() {
  Serial.println("Performing calibration...");
  // Code for calibration goes here
}

void startMonitoring() {
  Serial.println("Starting monitoring...");
  // Code for monitoring goes here
}

void turnOnMotor() {
  Serial.println("Turning on motor...");
  // Code to turn on the motor goes here
}
