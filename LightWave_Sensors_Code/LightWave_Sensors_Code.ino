#include <dht.h>
#include <Wire.h>
#include <avr/wdt.h>


/* Varibale and Port Declaration*/
#define lightSensorPort A0
#define dhtSensorPort   A1
#define gasSensorPin    A2
#define flameSensorPort 3

#define SLAVE_ADDRESS 0x04

int temperature_read, humidity_read;
int lightSensorValue, lightMeasureMap;
int flameDetection;
int gasDetection;
int wireReadValue;
char state = 'R';
int lastState = 1;
int i;
byte gas_ppm[2];

// the setup function runs once when you press reset or power the board
void setup() {

  // initialize serial communication at 9600 bits per second:
  Serial.begin(9600);

  pinMode(flameSensorPort, INPUT);

  // initialize i2c as slave
  Wire.begin(SLAVE_ADDRESS);

  // define callbacks for i2c communication
  Wire.onReceive(receiveData);
  Wire.onRequest(sendData);

  Serial.println("***************************************");
  Serial.println("*  Welcome to Your LightWave System   *");
  Serial.println("*  Enter R to Resume and S to Stop    *");
  Serial.println("***************************************");
  Serial.println();

}

void loop()
{
  state = Serial.read();

  if (state == 'R' || state == 'r') {
    lastState = 1;
  }
  else if (state == 'S' || state == 's') {
    lastState = 2;
  }

  if (lastState == 1) {
    ReadLight();
    TempAndHumid();
    FlameDetection();
    GasDetection();
    PrintData();
    Serial.println();
  }

  else if (lastState == 2) {
    Serial.println("************************************************");
    Serial.println("*              SYSTEM IS IDLE                  *");
    Serial.println("************************************************");
  }
  else {
    Serial.println("*      PLEASE ENTER VALID COMMAND        *");
  }
  delay(2000);
}
void receiveData(int byteCount) {

  while (Wire.available()) {
    wireReadValue = Wire.read();
  }
}

void sendData() {

  switch (wireReadValue) {
    case 1:
      Wire.write((byte)temperature_read);
      break;
    case 2:
      Wire.write((byte)humidity_read);
      break;
    case 3:
      Wire.write((byte)lightMeasureMap);
      break;
    case 4:
      Wire.write((byte)flameDetection);
      break;
    case 5:
      gas_ppm[0] = ((byte)gasDetection >> 8) & 0xFF;
      Wire.write(gas_ppm[0]);
      break;
    case 6:
      gas_ppm[1] = ((byte)gasDetection) & 0xFF;
      Wire.write(gas_ppm[1]);
      break;
    case 7:
      Wire.write((byte)lastState);
      break;
  }

  
}

void ReadLight() {

  lightSensorValue = analogRead(lightSensorPort);
  lightMeasureMap = map(lightSensorValue, 0, 690, 0, 100);
  if (lightMeasureMap > 100) {
    lightMeasureMap = 100;
  }

}

void TempAndHumid() {

  dht tempAndHumid;

  /* save temperature and humidity reading to variables */
  tempAndHumid.read11(dhtSensorPort);
  temperature_read = tempAndHumid.temperature;
  humidity_read = tempAndHumid.humidity;

}

void FlameDetection() {

  flameDetection = digitalRead(flameSensorPort);

}

void GasDetection() {

  gasDetection = analogRead(gasSensorPin);

}

void PrintData() {

  Serial.print("Light: ");
  Serial.print(lightMeasureMap);
  Serial.println(" %");

  Serial.print("Temperature: ");
  Serial.print(temperature_read);
  Serial.println(" C");

  Serial.print("Humidity: ");
  Serial.print(humidity_read);
  Serial.println(" %");

  Serial.print("Gas Levels: ");
  Serial.print(gasDetection);
  Serial.print(" ppm");

  if (gasDetection > 400) {
    Serial.println(" (HARMFUL GAS DETECTED!)");
  }
  else {
    Serial.println(" (Normal GAS Conditions)");
  }

  Serial.print("Fire Detection:  ");
  if (flameDetection == LOW) {
    Serial.println("FIRE DETECTED!");
  }
  else {
    Serial.println("NO FIRE");
  }
}
