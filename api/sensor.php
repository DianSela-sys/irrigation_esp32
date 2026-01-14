<?php
header("Content-Type: application/json");
include "../database.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['moisture']) || !isset($data['pump_status'])) {
    echo json_encode(["status"=>"error","msg"=>"invalid data"]);
    exit;
}

$moisture = intval($data['moisture']);
$pump     = intval($data['pump_status']);

$conn->query("
    INSERT INTO sensor_logs (moisture, pump_status)
    VALUES ($moisture, $pump)
");

echo json_encode([
    "status" => "ok",
    "saved"  => true
]);
