<?php
$data = file_exists("data.json")
  ? json_decode(file_get_contents("data.json"), true)
  : [];
?>
<!DOCTYPE html>
<html>
<head>
  <title>IoT Penyiraman Tanaman</title>
  <meta charset="utf-8">
</head>
<body>

<h2>Dashboard IoT Penyiraman</h2>

<p><b>Kelembapan:</b> <?= $data['moisture'] ?? '-' ?> %</p>
<p><b>Status Pompa:</b> <?= ($data['pump_status'] ?? 0) ? 'ON' : 'OFF' ?></p>
<p><b>LED:</b> <?= $data['led_status'] ?? '-' ?></p>
<p><b>Buzzer:</b> <?= ($data['buzzer_status'] ?? 0) ? 'ON' : 'OFF' ?></p>

</body>
</html>
