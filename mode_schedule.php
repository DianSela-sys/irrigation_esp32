<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Mode Terjadwal | IoT Penyiraman</title>

    <style>
        :root {
            --hijau: #2e7d32;
            --hijau-muda: #eaf6ee;
            --oranye: #ef6c00;
            --merah: #c62828;
            --putih: #ffffff;
            --abu: #555;
        }

        * {
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            margin: 0;
            background: var(--hijau-muda);
            color: #222;
        }

        header {
            padding: 30px 40px;
            background: var(--hijau-muda);
        }

        header h1 {
            margin: 0;
            color: var(--hijau);
            font-size: 32px;
        }

        header p {
            margin-top: 6px;
            color: var(--abu);
        }

        .container {
            max-width: 900px;
            margin: auto;
            padding: 0 30px 40px;
        }

        .card {
            background: var(--putih);
            border-radius: 18px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .card h2 {
            margin-top: 0;
            color: var(--hijau);
            font-size: 26px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: var(--abu);
            margin-bottom: 35px;
        }

        form {
            max-width: 420px;
            margin: auto;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input[type="time"],
        input[type="number"] {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .btn {
            display: block;
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
            padding: 16px;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            color: #fff;
            background: linear-gradient(135deg, var(--oranye), #ff9800);
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }

        .btn-off {
            margin-top: 25px;
            background: linear-gradient(135deg, var(--merah), #ef5350);
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 35px;
            text-decoration: none;
            color: var(--hijau);
            font-weight: 600;
        }
    </style>
</head>
<body>

<header>
    <h1>🌱 IoT Penyiraman Tanaman</h1>
    <p>Pengaturan penyiraman otomatis terjadwal</p>
</header>

<div class="container">
    <div class="card">
        <h2>Mode Terjadwal</h2>
        <p class="subtitle">
            Atur waktu pompa air menyala secara otomatis sesuai jadwal yang diinginkan.
        </p>

        <!-- FORM JADWAL -->
        <form id="scheduleForm">
            <div class="form-group">
                <label>⏰ Jam Mulai Menyiram</label>
                <input type="time" name="start" required>
            </div>

            <div class="form-group">
                <label>⏳ Durasi Penyiraman (detik)</label>
                <input type="number" name="duration" min="1" required>
            </div>

            <button type="submit" class="btn">
                💾 SIMPAN JADWAL
            </button>
        </form>

        <!-- NONAKTIFKAN -->
        <button id="offBtn" class="btn btn-off">
            ⛔ NONAKTIFKAN MODE TERJADWAL
        </button>

        <a href="dashboard.php" class="back">
            ⬅ Kembali ke Dashboard
        </a>
    </div>
</div>

<script>
/* ================= SIMPAN JADWAL ================= */
document.getElementById("scheduleForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("api/schedule.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert("✅ Jadwal berhasil disimpan & diaktifkan");
    })
    .catch(() => {
        alert("❌ Gagal menyimpan jadwal");
    });
});

/* ================= NONAKTIFKAN ================= */
document.getElementById("offBtn").addEventListener("click", function() {
    fetch("api/schedule_off.php")
        .then(res => res.json())
        .then(data => {
            alert("⛔ Mode terjadwal berhasil dinonaktifkan");
        })
        .catch(() => {
            alert("❌ Gagal menonaktifkan jadwal");
        });
});
</script>

</body>
</html>
