<?php
header("Content-Type: application/json");
include "../database.php";

if (!isset($_GET['pump'])) {
    echo json_encode(["status"=>"error","msg"=>"pump parameter missing"]);
    exit;
}

$pump = ($_GET['pump'] == 1) ? 1 : 0;

$conn->query("
    UPDATE settings 
    SET mode = 'manual',
        manual_pump = $pump
    WHERE id = 1
");

echo json_encode([
    "status" => "ok",
    "mode"   => "manual",
    "pump"   => $pump
]);
