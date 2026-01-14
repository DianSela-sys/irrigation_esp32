<?php
header("Content-Type: application/json");
include "../database.php";

$conn->query("
  UPDATE settings 
  SET mode='moisture'
  WHERE id=1
");

echo json_encode([
  "status" => "ok",
  "mode"   => "moisture"
]);
