#include <dht.h>
#include <Arduino_FreeRTOS.h>
#include <semphr.h>  // add the FreeRTOS functions for Semaphores (or Flags).
#include <Wire.h>

/* Varibale and Port Declaration*/
#define lightSensorPort A0
#define dhtSensorPort   A1
#define flameSensorPort 3
#define gasSensorPin    A3
#define SLAVE_ADDRESS 0x04

int temperature_read, humidity_read;
int lightSensorValue, lightMeasureMap;
int flameDetection;
int gasDetection;
int wireReadValue;

// Declare a mutex Semaphore Handle which we will use to manage the Serial Port.
// It will be used to ensure only only one Task is accessing this resource at any time.
SemaphoreHandle_t xSerialSemaphore;

// define two Tasks for DigitalRead & AnalogRead
void TaskDigital( void *pvParameters );
void TaskLightMeasure( void *pvParameters );
void TaskTempAndHumid( void *pvParameters );
void TaskFlameDetection( void *pvParameters );
//void TaskGasDetection( void *pvParameters );
//void TaskPrint( void *pvParameters );
//void TaskSendData( void *pvParameters );

// the setup function runs once when you press reset or power the board
void setup() {

  // initialize serial communication at 9600 bits per second:
  Serial.begin(9600);

  // initialize i2c as slave
  //Wire.begin(SLAVE_ADDRESS);

  // define callbacks for i2c communication
  //Wire.onReceive(receiveData);
  //Wire.onRequest(sendData);

  while (!Serial) {
    ; // wait for serial port to connect. Needed for native USB, on LEONARDO, MICRO, YUN, and other 32u4 based boards.
  }

  // Semaphores are useful to stop a Task proceeding, where it should be paused to wait,
  // because it is sharing a resource, such as the Serial port.
  // Semaphores should only be used whilst the scheduler is running, but we can set it up here.
  if ( xSerialSemaphore == NULL )  // Check to confirm that the Serial Semaphore has not already been created.
  {
    xSerialSemaphore = xSemaphoreCreateMutex();  // Create a mutex semaphore we will use to manage the Serial Port
    if ( ( xSerialSemaphore ) != NULL )
      xSemaphoreGive( ( xSerialSemaphore ) );  // Make the Serial Port available for use, by "Giving" the Semaphore.
  }

  // Now set up two Tasks to run independently.
  xTaskCreate(
    TaskDigital
    ,  (const portCHAR *)"Digital"  // A name just for humans
    ,  128  // This stack size can be checked & adjusted by reading the Stack Highwater
    ,  NULL
    ,  0  // Priority, with 3 (configMAX_PRIORITIES - 1) being the highest, and 0 being the lowest.
    ,  NULL );

  xTaskCreate(
    TaskLightMeasure
    ,  (const portCHAR *) "LightMeasure"
    ,  128  // Stack size
    ,  NULL
    ,  1  // Priority
    ,  NULL );

  xTaskCreate(
    TaskTempAndHumid
    ,  (const portCHAR *) "TempAndHumid"
    ,  128  // Stack size
    ,  NULL
    ,  1  // Priority
    ,  NULL );
//  xTaskCreate(
//    TaskPrint
//    ,  (const portCHAR *) "Print"
//    ,  256  // Stack size
//    ,  NULL
//    ,  2 // Priority
//    ,  NULL );
  xTaskCreate(
    TaskFlameDetection
    ,  (const portCHAR *) "FlameDetection"
    ,  128  // Stack size
    ,  NULL
    ,  3  // Priority
    ,  NULL );

  //  xTaskCreate(
  //    TaskGasDetection
  //    ,  (const portCHAR *) "GasDetection"
  //    ,  128  // Stack size
  //    ,  NULL
  //    ,  1  // Priority
  //    ,  NULL );


  //  xTaskCreate(
  //    TaskSendData
  //    ,  (const portCHAR *) "SendData"
  //    ,  128  // Stack size
  //    ,  NULL
  //    ,  3  // Priority
  //    ,  NULL );

  // Now the Task scheduler, which takes over control of scheduling individual Tasks, is automatically started.
}

void loop()
{
  // Empty. Things are done in Tasks.
}

/*--------------------------------------------------*/
/*---------------------- Tasks ---------------------*/
/*--------------------------------------------------*/

void TaskDigital( void *pvParameters __attribute__((unused)) )  // This is a Task.
{
  /*
    DigitalReadSerial
    Reads a digital input on pin 2, prints the result to the serial monitor

    This example code is in the public doma#include <Wire.h>
  */

  // digital pin 2 has a pushbutton attached to it. Give it a name:
  uint8_t pushButton = 2;

  // make the pushbutton's pin an input:
  pinMode(pushButton, INPUT);

  for (;;) // A Task shall never return or exit.
  {
    // read the input pin:
    int buttonState = digitalRead(pushButton);

    // See if we can obtain or "Take" the Serial Semaphore.
    // If the semaphore is not available, wait 5 ticks of the Scheduler to see if it becomes free.
    if ( xSemaphoreTake( xSerialSemaphore, ( TickType_t ) 5 ) == pdTRUE )
    {
      // We were able to obtain or "Take" the semaphore and can now access the shared resource.
      // We want to have the Serial Port for us alone, as it takes some time to print,
      // so we don't want it getting stolen during the middle of a conversion.
      // print out the state of the button:
      Serial.println(buttonState);

      xSemaphoreGive( xSerialSemaphore ); // Now free or "Give" the Serial Port for others.
    }

    vTaskDelay(1);  // one tick delay (15ms) in between reads for stability
  }
}

void TaskLightMeasure( void *pvParameters __attribute__((unused)) )
{
  for (;;)
  {
    lightSensorValue = analogRead(lightSensorPort);

    if ( xSemaphoreTake( xSerialSemaphore, ( TickType_t ) 5 ) == pdTRUE )
    {
      lightMeasureMap = map(lightSensorValue, 0, 690, 0, 100);
      if (lightMeasureMap > 100) {
        lightMeasureMap = 100;
      }
      xSemaphoreGive( xSerialSemaphore );
    }

    vTaskDelay(1);
  }
}

void TaskTempAndHumid( void *pvParameters __attribute__((unused)) )
{
  dht tempAndHumid;

  for (;;)
  {
    /* save temperature and humidity reading to variables */
    tempAndHumid.read11(dhtSensorPort);


    if ( xSemaphoreTake( xSerialSemaphore, ( TickType_t ) 5 ) == pdTRUE )
    {
      temperature_read = tempAndHumid.temperature;
      humidity_read = tempAndHumid.humidity;

      xSemaphoreGive( xSerialSemaphore );
    }

    vTaskDelay(1);
  }
}

void TaskFlameDetection( void *pvParameters __attribute__((unused)) )
{
  pinMode(flameSensorPort, INPUT);
  for (;;)
  {

    flameDetection = digitalRead(flameSensorPort);

    if ( xSemaphoreTake( xSerialSemaphore, ( TickType_t ) 5 ) == pdTRUE )
    {

      if (flameDetection == LOW) {
        Serial.println("FIRE DETECTED!");
      }

      else {
        Serial.println("NO FIRE");
      }

      xSemaphoreGive( xSerialSemaphore );
    }

    vTaskDelay(1);
  }
}

//void TaskGasDetection( void *pvParameters __attribute__((unused)) )
//{
//
//  for (;;)
//  {
//    gasDetection = analogRead(gasSensorPin);
//
//    if ( xSemaphoreTake( xSerialSemaphore, ( TickType_t ) 5 ) == pdTRUE )
//    {
//
//      if (gasDetection > 400) {
//        Serial.println("HARMFUL GAS DETECTED!");
//      }
//
//      else {
//        Serial.println("NORMAL GAS LEVELS");
//      }
//
//      xSemaphoreGive( xSerialSemaphore );
//    }
//
//    vTaskDelay(1);
//  }
//}
//void TaskPrint( void *pvParameters __attribute__((unused)) )
//{
//
//  for (;;)
//  {
//
//    if ( xSemaphoreTake( xSerialSemaphore, ( TickType_t ) 5 ) == pdTRUE )
//    {
//      Serial.print("Light: ");
//      Serial.print(lightMeasureMap);
//      Serial.println(" %");
//
//      Serial.print("Temperature: ");
//      Serial.print(temperature_read);
//      Serial.println(" C");
//
//      Serial.print("Humidity: ");
//      Serial.print(humidity_read);
//      Serial.println(" %");
//
//      Serial.print("Gas Levels: ");
//      Serial.println(gasDetection);
//      Serial.println(" ppm");
//
//      Serial.print("Fire Detection:  ");
//      if (flameDetection == LOW) {
//        Serial.println("FIRE DETECTED!");
//      }
//      else {
//        Serial.println("NO FIRE");
//      }
//
//
//      xSemaphoreGive( xSerialSemaphore );
//    }
//
//    vTaskDelay(1);
//  }
//}
//void TaskSendData( void *pvParameters __attribute__((unused)) )
//{
//
//  for (;;)
//  {
//    /* save temperature and humidity reading to variables */
//
//    if ( xSemaphoreTake( xSerialSemaphore, ( TickType_t ) 5 ) == pdTRUE )
//    {
//
//      PrintData();
//      xSemaphoreGive( xSerialSemaphore );
//    }
//
//    vTaskDelay(20);
//  }
//}

//void receiveData(int byteCount) {
//
//  while (Wire.available()) {
//    wireReadValue = Wire.read();
//  }
//}
//
//void sendData() {
//
//  switch (wireReadValue) {
//    case 1:
//      Wire.write((byte)temperature_read);
//      break;
//    case 2:
//      Wire.write((byte)humidity_read);
//      break;
//    case 3:
//      Wire.write((byte)lightMeasureMap);
//      break;
//    case 4:
//      Wire.write((byte)flameDetection);
//      break;
//    case 5:
//      Wire.write((byte)gasDetection);
//      break;
//  }
//}
//
//void printData() {
//  Serial.print("Light: ");
//  Serial.print(lightMeasureMap);
//  Serial.println(" %");
//
//  Serial.print("Temperature: ");
//  Serial.print(temperature_read);
//  Serial.println(" C");
//
//  Serial.print("Humidity: ");
//  Serial.print(humidity_read);
//  Serial.println(" %");
//
//  Serial.print("Gas Levels: ");
//  Serial.println(gasDetection);
//  Serial.println(" ppm");
//
//  Serial.print("Fire Detection:  ");
//  if (flameDetection == LOW) {
//    Serial.println("FIRE DETECTED!");
//  }
//  else {
//    Serial.println("NO FIRE");
//  }
//}
