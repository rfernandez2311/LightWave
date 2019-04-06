#include <ESP8266HTTPClient.h>
#include <esp8266wifi.h>
#include <ESP8266WiFi.h>
#include <cstring>

#define buzzerPin     16
#define fanRelayPin   5
#define lightRelayPin 4

const char* ssid = "Familia";
const char* password = "mistybutter485";
String temp, hum, gas, light;
String fire = "1";
int value = 1;

void setup () {

  Serial.begin(115200);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {

    delay(1000);
    Serial.print("Connecting..");

  }

  pinMode(buzzerPin, OUTPUT);
  pinMode(fanRelayPin, OUTPUT);
  pinMode(lightRelayPin, OUTPUT);

}

void loop() {

  if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status

    HTTPClient http;  //Declare an object of class HTTPClient
    Serial.print("Connected! HTTP Code: ");

    http.begin("http://192.168.1.218/system_info.txt");  //Specify request destination

    int httpCode = http.GET();                                                                  //Send the request
    Serial.println(httpCode);
    if (httpCode > 0) { //Check the returning code
      String payload = http.getString();   //Get the request response payload
      Serial.println(payload);                     //Print the response payload
      value = 1;
      assignValues(payload);
    }

    http.end();   //Close connection

  }
  printAll();
  actions();
  resetAll();
  delay(10000);    //Send a request every 30 seconds

}

void assignValues(String payload) {
  for (int i = 0; i < payload.length(); i++) {

    if (payload.charAt(i) == ' ') {
      value++;
    }
    else {
      if (value == 1) {
        hum += payload[i];
      }
      else if (value == 2) {
        temp += payload[i];
      }
      else if (value == 3) {
        light += payload[i];
      }
      else if (value == 4) {
        fire = payload[i];
      }
      else if (value == 5) {
        gas += payload[i];
      }
    }
  }
}

void actions() {
  //Set alarm to on if fire is detected
  if (fire == "0") {
    tone(buzzerPin, 261);
  }
  else {
    noTone(buzzerPin);
  }
  //Set alarm to on if gas > 400
  if (atoi(gas.c_str()) < 400) { //cambie
    tone(buzzerPin, 261);
  }
  else {
    noTone(buzzerPin);
  }   
  //Trun on Light if % is below 50%
  if (atoi(light.c_str()) < 50) {
    digitalWrite(lightRelayPin, HIGH);
  }
  else {
    digitalWrite(lightRelayPin, LOW);
  }
  //Turn on Fan if temp > 26
  if (atoi(temp.c_str()) > 26) {
    digitalWrite(fanRelayPin, HIGH);
  }
  else {
    digitalWrite(fanRelayPin, LOW);
  }
}

void resetAll() {
  value = 0;
  temp = "";
  hum = "";
  gas = "";
  light = "";
}

void printAll() {
  Serial.print("Hum = ");
  Serial.println(hum);
  Serial.print("Temp = ");
  Serial.println(temp);
  Serial.print("Light % = ");
  Serial.println(light);
  Serial.print("Fire = ");
  Serial.println(fire);
  Serial.print("Gas = ");
  Serial.println(gas);
}
