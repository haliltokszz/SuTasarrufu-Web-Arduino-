#include <ESP8266WiFi.h>
#include <WiFiClient.h> 
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>

#define sensorpin 14
#define interruptPin digitalPinToInterrupt(sensorpin)//gpio14 = d5, interrupt pin ni ayarlanadı

const char* ssid = "Redmi";                  // Your wifi Name       
const char* password = "12345678";          // Your wifi Password

const char *host = "192.168.43.225"; //Your pc or server (database) IP, example : 192.168.0.0 , if you are a windows os user, open cmd, then type ipconfig then look at IPv4 Address.

float calibrationFactor = 4.5;//sensörün kalbresi

volatile int pulseCount; //  volatile interrupt dan döncek

float flowRate = 0.0;
unsigned int flowMilliLitres = 0;
unsigned long totalMilliLitres = 0;
unsigned long oldTime = 0;

ICACHE_RAM_ATTR  void pulseCounter()//kesme fonksiyonu
{
  pulseCount++;//pulseCount = pulseCount + 1;
}

void setup() {
  delay(1000);
  Serial.begin(115200);
  WiFi.mode(WIFI_OFF);        //Prevents reconnection issue (taking too long to connect)
  delay(1000);
  WiFi.mode(WIFI_STA);        //This line hides the viewing of ESP as wifi hotspot
  
  WiFi.begin(ssid, password);     //Connect to your WiFi router
  Serial.println("");

  Serial.print("Connecting");
  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(250);
    Serial.print(".");
    delay(250);
  }
  //If connection successful show IP address in serial monitor
  Serial.println("");
  Serial.println("Connected to Network/SSID");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());  //IP address assigned to your ESP

  pinMode(sensorpin, INPUT);//sensör pini giriş olarak ayalandı
  digitalWrite(sensorpin, LOW);//direkt kesmeye girmemesi için
  
  attachInterrupt(interruptPin, pulseCounter, FALLING);//kesme ayarlandı 1 -> 0 = FALLING
}

void loop() {
  // put your main code here, to run repeatedly:
  HTTPClient http;    //Declare object of class HTTPClient

  //sersör ölçümü
  if((millis() - oldTime) > 1000)
  { 
    detachInterrupt(interruptPin);
    flowRate = ((1000.0 / (millis() - oldTime)) * pulseCount) / calibrationFactor;
    oldTime = millis();
    flowMilliLitres = (flowRate / 60) * 1000;
    totalMilliLitres += flowMilliLitres;

    Serial.print("Akis hizi: " + String(flowRate) + "L/dk  ");
    Serial.println("TotalAkan sivi: " + String(totalMilliLitres) + "mL");

    pulseCount = 0;
    
    attachInterrupt(interruptPin, pulseCounter, FALLING);
  }
 
  String akanSivi, postData;
  akanSivi= String(flowMilliLitres);
 
  //Post Data
  postData = "anlik=" + akanSivi;
  
  http.begin("http://192.168.43.225/SuProjesi/anlikTuketimAlmaDB.php");              //Specify request destination
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");    //Specify content-type header
 
  int httpCode = http.POST(postData);   //Send the request
  String payload = http.getString();    //Get the response payload

  Serial.println(httpCode);   //Print HTTP return code
  Serial.println(payload);    //Print request response payload
  Serial.println("Anlık akan sıvı= " + akanSivi);
  
  http.end();  //Close connection

  delay(4000);  //Here there is 4 seconds delay plus 1 second delay below, so Post Data at every 5 seconds
  delay(1000);
}
