#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

/* ================= WIFI ================= */
const char* ssid     = "Disel";
const char* password = "diselaaa";

/* ================= SERVER API ================= */
String baseURL = "http://10.63.87.202/irigation_esp32/api";

/* ================= PIN ================= */
#define SOIL_PIN   34
#define RELAY_PIN  14   // relay aktif LOW
#define LED_MERAH  25
#define LED_HIJAU  26
#define BUZZER_PIN 5

/* ================= GLOBAL ================= */
int soilADC     = 0;
int soilPercent = 0;
int pumpStatus  = 0;

unsigned long lastLoop = 0;
const unsigned long LOOP_INTERVAL = 1000; // 1 detik (respons cepat)

/* ================= SETUP ================= */
void setup() {
  Serial.begin(115200);
  delay(1000);

  Serial.println("=== ESP32 BOOT ===");

  pinMode(RELAY_PIN, OUTPUT);
  pinMode(LED_MERAH, OUTPUT);
  pinMode(LED_HIJAU, OUTPUT);
  pinMode(BUZZER_PIN, OUTPUT);

  matikanSemua();

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  Serial.print("Connecting WiFi");
  int retry = 0;
  while (WiFi.status() != WL_CONNECTED && retry < 20) {
    Serial.print(".");
    delay(500);
    retry++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nWiFi CONNECTED");
    Serial.print("IP ESP32: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println("\nWiFi GAGAL");
    matikanSemua();
  }
}

/* ================= LOOP ================= */
void loop() {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi PUTUS → Pompa DIMATIKAN");
    matikanSemua();
    delay(3000);
    return;
  }
  if (millis() - lastLoop < LOOP_INTERVAL) return;
  lastLoop = millis();

  /* 1️⃣ Baca sensor */
  bacaSoil();

  /* 2️⃣ Ambil konfigurasi dari WEB */
  HTTPClient http;
  String url = baseURL + "/config.php";

  Serial.print("GET ");
  Serial.println(url);

  http.begin(url);
  int httpCode = http.GET();

  if (httpCode == 200) {

    String payload = http.getString();
    Serial.println("RESP:");
    Serial.println(payload);

    DynamicJsonDocument doc(512);
    DeserializationError err = deserializeJson(doc, payload);

    if (!err) {

      String mode = doc["mode"].as<String>();
      Serial.print("MODE: ");
      Serial.println(mode);

      /* ===== MODE MANUAL ===== */
      if (mode == "manual") {
        pumpStatus = doc["manual"]["pump"];
        
      }

      /* ===== MODE KELEMBAPAN ===== */
      else if (mode == "moisture") {

        int minMoist = doc["moisture"]["min"];
        int maxMoist = doc["moisture"]["max"];

        if (soilPercent >= minMoist && soilPercent <= maxMoist) {
          pumpStatus = 1;   // KERING
        } else {
          pumpStatus = 0;   // BASAH
        }
      }

      /* ===== MODE TERJADWAL ===== */
      else if (mode == "schedule") {
        pumpStatus = doc["pump_status"];
      }

      if (pumpStatus) pompaNyala();
      else pompaMati();

    } else {
      Serial.println("JSON ERROR");
      matikanSemua();
    }

  } else {
    Serial.print("HTTP ERROR: ");
    Serial.println(httpCode);
    matikanSemua();
  }

  http.end();

  /* 3️⃣ Kirim data ke WEB */
  kirimDataKeServer();

}

/* ================= SENSOR ================= */
void bacaSoil() {
  soilADC = analogRead(SOIL_PIN);

  soilPercent = map(soilADC, 4095, 1500, 0, 100);
  soilPercent = constrain(soilPercent, 0, 100);

  Serial.print("Kelembapan: ");
  Serial.print(soilPercent);
  Serial.println(" %");
}

/* ================= POMPA ================= */
void pompaNyala() {
  pumpStatus = 1;
  digitalWrite(RELAY_PIN, LOW);
  digitalWrite(LED_MERAH, HIGH);
  digitalWrite(LED_HIJAU, LOW);
  digitalWrite(BUZZER_PIN, HIGH);
}

void pompaMati() {
  pumpStatus = 0;
  digitalWrite(RELAY_PIN, HIGH);
  digitalWrite(LED_MERAH, LOW);
  digitalWrite(LED_HIJAU, HIGH);
  digitalWrite(BUZZER_PIN, LOW);
}

void matikanSemua() {
  pumpStatus = 0;
  digitalWrite(RELAY_PIN, HIGH);
  digitalWrite(LED_MERAH, LOW);
  digitalWrite(LED_HIJAU, LOW);
  digitalWrite(BUZZER_PIN, LOW);
}

/* ================= POST DATA ================= */
void kirimDataKeServer() {

  HTTPClient http;
  http.begin(baseURL + "/sensor.php");
  http.addHeader("Content-Type", "application/json");

  StaticJsonDocument<200> doc;
  doc["moisture"]     = soilPercent;
  doc["pump_status"] = pumpStatus;

  String body;
  serializeJson(doc, body);

  http.POST(body);
  http.end();
}
