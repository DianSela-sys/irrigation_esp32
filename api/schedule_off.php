<?php
header("Content-Type: application/json");
include "../database.php";

// NONAKTIFKAN SEMUA JADWAL
$conn->query("UPDATE schedules SET active=0");

// KEMBALIKAN MODE KE MANUAL (DEFAULT)
$conn->query("
  UPDATE settings 
  SET mode='manual', manual_pump=0
  WHERE id=1
");

echo json_encode([
  "status" => "ok",
  "message" => "Mode terjadwal dinonaktifkan"
]);
