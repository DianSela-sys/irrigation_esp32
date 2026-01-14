<!DOCTYPE html>
<html>
<head>
  <title>IoT Penyiraman Tanaman</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">

  <h1>🌱 IoT Penyiraman Tanaman</h1>
  <p class="subtitle">Dashboard Sistem Monitoring & Kontrol</p>

  <div class="card status">
    <div>
      <h3>Mode Aktif</h3>
      <p id="mode">-</p>
    </div>
    <div>
      <h3>Kelembapan</h3>
      <p id="moisture">- %</p>
    </div>
    <div>
      <h3>Status Pompa</h3>
      <p id="pump">-</p>
    </div>
  </div>

  <h2>Pilih Mode</h2>

  <div class="menu">
    <a href="mode_manual.php" class="btn manual">Manual</a>
    <a href="mode_sensor.php" class="btn auto">Otomatis Kelembapan</a>
    <a href="mode_schedule.php" class="btn schedule">Terjadwal</a>
  </div>

</div>

<script>
function updateDashboard() {
  fetch('api/config.php?ts=' + Date.now(), { cache: "no-store" })
    .then(res => res.json())
    .then(data => {
      document.getElementById('mode').innerText = data.mode.toUpperCase();
      document.getElementById('moisture').innerText = data.last_moisture + ' %';
      document.getElementById('pump').innerText = data.pump_status ? 'ON' : 'OFF';
    })
    .catch(err => console.error(err));
}

setInterval(updateDashboard, 1000);

updateDashboard();
</script>


</body>
</html>
