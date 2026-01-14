<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

include "../database.php";

/* ================= TIMEZONE ================= */
date_default_timezone_set("Asia/Jakarta");

/* ================= SETTINGS ================= */
$set = $conn->query("
    SELECT * FROM settings WHERE id = 1
")->fetch_assoc();

/* ================= SENSOR TERAKHIR ================= */
$last = $conn->query("
    SELECT moisture 
    FROM sensor_logs 
    ORDER BY id DESC 
    LIMIT 1
")->fetch_assoc();

/* ================= JADWAL AKTIF ================= */
$schedule = $conn->query("
    SELECT * 
    FROM schedules 
    WHERE active = 1 
    ORDER BY id DESC 
    LIMIT 1
")->fetch_assoc();

/* ================= NILAI DASAR ================= */
$mode        = $set['mode'];
$moisture    = $last ? (int)$last['moisture'] : 0;
$pump_status = 0;

/* ================= LOGIKA STATUS POMPA ================= */

/* === MODE MANUAL === */
if ($mode === "manual") {

    // Ikuti perintah manual secara langsung
    $pump_status = (int)$set['manual_pump'];

}

/* === MODE KELEMBAPAN === */
elseif ($mode === "moisture") {

    $min = (int)$set['moisture_min'];
    $max = (int)$set['moisture_max'];

    // KERING → ON | BASAH → OFF
    if ($moisture >= $min && $moisture <= $max) {
        $pump_status = 1;
    } else {
        $pump_status = 0;
    }

}

elseif ($mode === "schedule" && $schedule) {

    date_default_timezone_set("Asia/Jakarta");

    $now = time();

    $start_time = strtotime(date("Y-m-d") . " " . $schedule['start_time']);
    $duration   = (int)$schedule['duration'];
    $end_time   = $start_time + $duration;

    // ⛔ JIKA BELUM WAKTUNYA → PAKSA OFF
    if ($now < $start_time) {
        $pump_status = 0;
    }
    // ✅ JIKA DI DALAM WINDOW → ON
    elseif ($now >= $start_time && $now < $end_time) {
        $pump_status = 1;
    }
    // ⛔ JIKA SUDAH LEWAT → OFF
    else {
        $pump_status = 0;
    }
}


/* ================= OUTPUT JSON ================= */
echo json_encode([
    "mode" => $mode,

    "manual" => [
        "pump" => (int)$set['manual_pump']
    ],

    "moisture" => [
        "min" => (int)$set['moisture_min'],
        "max" => (int)$set['moisture_max']
    ],

    "schedule" => [
        "active"   => $schedule ? true : false,
        "start"    => $schedule['start_time'] ?? null,
        "duration" => $schedule['duration'] ?? 0
    ],

    "last_moisture" => $moisture,
    "pump_status"   => $pump_status
]);
