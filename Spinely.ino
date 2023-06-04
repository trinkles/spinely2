#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "YourWiFiSSID";
const char* password = "YourWiFiPassword";
const char* serverIP = "192.168.1.100";
const int serverPort = 80;
const char* endpoint = "/update.php";
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

  isOperating = true;
}

void loop() {
  // Check if device is operating or waiting for command
  if (isOperating) {
    // Code for operating the device goes here
  } else {
    // Code for waiting for command goes here
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
