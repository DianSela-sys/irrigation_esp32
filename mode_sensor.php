<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Mode Otomatis Kelembapan | IoT Penyiraman</title>

    <style>
        :root {
            --hijau: #2e7d32;
            --hijau-muda: #eaf6ee;
            --hijau-tua: #1b5e20;
            --merah: #c62828;
            --oranye: #ef6c00;
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
            max-width: 1000px;
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

        .rules {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 40px;
        }

        .rule-box {
            border-radius: 16px;
            padding: 25px;
            color: #fff;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .kering {
            background: linear-gradient(135deg, var(--merah), #e53935);
        }

        .basah {
            background: linear-gradient(135deg, var(--hijau), #43a047);
        }

        .rule-box h3 {
            margin-top: 0;
            font-size: 20px;
        }

        .rule-box ul {
            padding-left: 18px;
        }

        .rule-box li {
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .btn {
            display: block;
            margin: auto;
            width: 320px;
            text-align: center;
            padding: 18px;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            color: #fff;
            background: linear-gradient(135deg, var(--hijau-tua), var(--hijau));
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
            text-decoration: none;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(0,0,0,0.25);
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 35px;
            text-decoration: none;
            color: var(--hijau);
            font-weight: 600;
        }

        .back:hover {
            text-decoration: underline;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            color: #777;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .rules {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>🌱 IoT Penyiraman Tanaman</h1>
    <p>Mode otomatis berdasarkan kelembapan tanah</p>
</header>

<div class="container">
    <div class="card">
        <h2>Mode Otomatis Berdasarkan Kelembapan</h2>
        <p class="subtitle">
            Sistem akan mengontrol pompa air secara otomatis berdasarkan nilai kelembapan tanah.
        </p>

        <div class="rules">
            <div class="rule-box kering">
                <h3>🌵 Tanah Kering</h3>
                <ul>
                    <li>Kelembapan: <strong>20% – 70%</strong></li>
                    <li>Pompa: <strong>MENYALA</strong></li>
                    <li>LED: <strong>Merah</strong></li>
                    <li>Buzzer: <strong>Aktif</strong></li>
                </ul>
            </div>

            <div class="rule-box basah">
                <h3>🌱 Tanah Basah</h3>
                <ul>
                    <li>Kelembapan: <strong>71% – 100%</strong></li>
                    <li>Pompa: <strong>MATI</strong></li>
                    <li>LED: <strong>Hijau</strong></li>
                    <li>Buzzer: <strong>Nonaktif</strong></li>
                </ul>
            </div>
        </div>

        <button class="btn" onclick="aktifkanMoisture()">
            🌧️ AKTIFKAN MODE OTOMATIS
        </button>

        <a href="dashboard.php" class="back">
            ⬅ Kembali ke Dashboard
        </a>
    </div>
</div>

<footer>
    Sistem IoT Penyiraman Tanaman • Mode Otomatis Kelembapan
</footer>

<script>
function aktifkanMoisture() {
    fetch('api/moisture.php', {
        method: 'POST'
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'ok') {
            alert('✅ Mode otomatis kelembapan AKTIF');
        } else {
            alert('❌ Gagal mengaktifkan mode');
        }
    })
    .catch(err => {
        alert('⚠️ Koneksi ke server gagal');
        console.error(err);
    });
}
</script>

</body>

</html>
