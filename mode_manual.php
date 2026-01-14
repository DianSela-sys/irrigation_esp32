<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Mode Manual | IoT Penyiraman</title>

    <style>
        :root {
            --hijau: #2e7d32;
            --hijau-muda: #eaf6ee;
            --biru: #1565c0;
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
            text-align: center;
        }

        .card h2 {
            margin-top: 0;
            color: var(--hijau);
            font-size: 26px;
        }

        .subtitle {
            color: var(--abu);
            margin-bottom: 30px;
        }

        .button-group {
            display: flex;
            gap: 30px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            width: 260px;
            padding: 18px;
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(0,0,0,0.25);
        }

        .btn-on {
            background: linear-gradient(135deg, #2e7d32, #66bb6a);
        }

        .btn-off {
            background: linear-gradient(135deg, #c62828, #ef5350);
        }

        .status {
            margin-top: 25px;
            font-weight: 600;
        }

        .back {
            display: inline-block;
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
    </style>
</head>
<body>

<header>
    <h1>🌱 IoT Penyiraman Tanaman</h1>
    <p>Kontrol pompa air secara manual</p>
</header>

<div class="container">
    <div class="card">
        <h2>Mode Manual</h2>
        <p class="subtitle">
            Gunakan tombol di bawah untuk menyalakan atau mematikan pompa air secara langsung.
        </p>

        <div class="button-group">
            <button class="btn btn-on" onclick="setPump(1)">
                💧 POMPA ON
            </button>

            <button class="btn btn-off" onclick="setPump(0)">
                ⛔ POMPA OFF
            </button>
        </div>

        <div id="status" class="status"></div>

        <a href="dashboard.php" class="back">
            ⬅ Kembali ke Dashboard
        </a>
    </div>
</div>

<footer>
    Sistem IoT Penyiraman Tanaman • Mode Manual
</footer>

<script>
function setPump(val) {
    fetch("api/manual.php?pump=" + val)
        .then(response => response.json())
        .then(data => {
            if (data.status === "ok") {
                document.getElementById("status").innerHTML =
                    "✅ Pompa berhasil " + (val ? "DINYALAKAN" : "DIMATIKAN");
            } else {
                document.getElementById("status").innerHTML =
                    "❌ Gagal mengirim perintah";
            }
        })
        .catch(() => {
            document.getElementById("status").innerHTML =
                "❌ Tidak dapat terhubung ke server";
        });
}
</script>

</body>
</html>
