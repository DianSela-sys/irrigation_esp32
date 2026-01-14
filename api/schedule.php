<?php
header("Content-Type: application/json");
include "../database.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

$start    = $_POST['start'] ?? null;
$duration = $_POST['duration'] ?? null;

if (!$start || !$duration) {
    echo json_encode([
        "status" => "error",
        "msg" => "data tidak lengkap"
    ]);
    exit;
}

// Validasi format waktu HH:MM
if (!preg_match('/^\d{2}:\d{2}$/', $start)) {
    echo json_encode([
        "status" => "error",
        "msg" => "format waktu tidak valid"
    ]);
    exit;
}

$duration = (int)$duration;
if ($duration <= 0) {
    echo json_encode([
        "status" => "error",
        "msg" => "durasi harus > 0"
    ]);
    exit;
}

// NONAKTIFKAN JADWAL LAMA
if (!$conn->query("UPDATE schedules SET active=0")) {
    echo json_encode([
        "status" => "error",
        "msg" => "gagal menonaktifkan jadwal lama",
        "error" => $conn->error
    ]);
    exit;
}

// SIMPAN JADWAL BARU
$sql = "
INSERT INTO schedules (start_time, duration, active)
VALUES ('$start', $duration, 1)
";

if (!$conn->query($sql)) {
    echo json_encode([
        "status" => "error",
        "msg" => "gagal menyimpan jadwal",
        "error" => $conn->error
    ]);
    exit;
}

// AKTIFKAN MODE TERJADWAL
$conn->query("
UPDATE settings 
SET mode='schedule',
    manual_pump=0
WHERE id=1
");

echo json_encode([
    "status"   => "ok",
    "mode"     => "schedule",
    "start"    => $start,
    "duration" => $duration
]);
