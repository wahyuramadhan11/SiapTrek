<?php
include 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$msg        = "";
$product_id = isset($_GET['id'])    ? (int)$_GET['id']    : 0;
$harga      = isset($_GET['harga']) ? (int)$_GET['harga'] : 0;
$nama_produk = "";
$deskripsi   = "";

if($product_id > 0) {
    $stmt = $pdo->prepare("SELECT nama_produk, deskripsi FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $produk      = $stmt->fetch();
    $nama_produk = $produk['nama_produk'] ?? "Produk Tidak Ditemukan";
    $deskripsi   = $produk['deskripsi']   ?? "";
} else {
    header("Location: catalog.php");
    exit;
}

if(isset($_POST['sewa'])) {
    $pid         = (int)$_POST['product_id'];
    $tgl_sewa    = $_POST['tgl_sewa'];
    $tgl_kembali = $_POST['tgl_kembali'];
    $harga       = (int)$_POST['harga'];

    $date1 = new DateTime($tgl_sewa);
    $date2 = new DateTime($tgl_kembali);
    $days  = $date1->diff($date2)->days;

    if($days < 1) {
        $msg = "Tanggal kembali harus berbeda dari tanggal mulai!";
    } else {
        $total = $days * $harga;
        try {
            $sql  = "INSERT INTO orders (user_id, product_id, tanggal_sewa, tanggal_kembali, total_harga, status) VALUES (?, ?, ?, ?, ?, 'Menunggu Konfirmasi')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$_SESSION['user_id'], $pid, $tgl_sewa, $tgl_kembali, $total]);
            header("Location: dashboard.php?msg=Pesanan berhasil dibuat!");
            exit;
        } catch(PDOException $e) {
            $msg = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking <?= htmlspecialchars($nama_produk) ?> - SiapTrek</title>
    <link rel="icon" href="img/logo.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --forest:   #0F2218;
            --moss:     #1C3D2A;
            --pine:     #1A5C35;
            --sage:     #2E7D32;
            --leaf:     #4CAF50;
            --mint:     #86EFAC;
            --cream:    #F7F4EE;
            --sand:     #EDE9DE;
            --charcoal: #1A1A1A;
            --mist:     #6B7280;
            --white:    #FFFFFF;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body { overflow-x: hidden; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        h1, h2, h3, h4, h5 { font-family: 'Syne', sans-serif; }

        /* ===== LEFT PANEL ===== */
        .left-panel {
            background: var(--forest);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 56px;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 360px; height: 360px;
            background: radial-gradient(circle, rgba(134,239,172,0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 280px; height: 280px;
            background: radial-gradient(circle, rgba(46,125,50,0.18) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        /* Brand */
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            position: relative;
            z-index: 2;
        }

        .brand-icon {
            width: 40px; height: 40px;
            background: var(--sage);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.1rem;
        }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem; font-weight: 800;
            color: white; letter-spacing: -0.02em;
        }

        /* Product showcase */
        .product-showcase {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .panel-eyebrow {
            font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--mint); margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
        }

        .panel-eyebrow::before {
            content: '';
            display: inline-block; width: 20px; height: 2px;
            background: var(--mint); border-radius: 2px;
        }

        .product-title {
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 800; color: white;
            letter-spacing: -0.03em; line-height: 1.1;
            margin-bottom: 16px;
        }

        .product-desc-text {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.5);
            line-height: 1.7; font-weight: 300;
            max-width: 320px; margin-bottom: 40px;
        }

        /* Price highlight */
        .price-highlight {
            background: rgba(134,239,172,0.1);
            border: 1px solid rgba(134,239,172,0.2);
            border-radius: 16px;
            padding: 22px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 320px;
        }

        .ph-label {
            font-size: 0.78rem; font-weight: 600;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase; letter-spacing: 0.08em;
            margin-bottom: 6px;
        }

        .ph-price {
            font-family: 'Syne', sans-serif;
            font-size: 1.8rem; font-weight: 800;
            color: var(--mint); line-height: 1;
        }

        .ph-unit {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.4);
            margin-top: 4px;
        }

        .ph-icon {
            width: 52px; height: 52px;
            background: rgba(134,239,172,0.12);
            border: 1px solid rgba(134,239,172,0.2);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            color: var(--mint); font-size: 1.4rem;
        }

        /* Info chips */
        .info-chips {
            display: flex; gap: 10px;
            flex-wrap: wrap; margin-top: 32px;
        }

        .info-chip {
            display: flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 100px;
            padding: 7px 14px;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.6);
        }

        .info-chip i { color: var(--mint); }

        /* Panel footer */
        .panel-footer {
            position: relative; z-index: 2;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.25);
        }

        /* ===== RIGHT PANEL ===== */
        .right-panel {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 56px 56px;
            background: var(--cream);
            overflow-y: auto;
        }

        .booking-box {
            width: 100%;
            max-width: 420px;
            animation: fadeInRight 0.6s ease both;
        }

        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 0.82rem; color: var(--mist);
            text-decoration: none; margin-bottom: 32px;
            transition: color 0.3s;
        }

        .back-link:hover { color: var(--sage); }

        .booking-heading {
            font-size: 1.9rem; font-weight: 800;
            color: var(--forest); letter-spacing: -0.03em;
            margin-bottom: 6px;
        }

        .booking-sub {
            font-size: 0.875rem; color: var(--mist);
            margin-bottom: 36px; font-weight: 400;
        }

        /* Error */
        .alert-error {
            background: rgba(220,38,38,0.07);
            border: 1px solid rgba(220,38,38,0.2);
            color: #B91C1C; border-radius: 12px;
            padding: 14px 18px; font-size: 0.875rem;
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 24px; font-weight: 500;
        }

        /* Form */
        .form-group { margin-bottom: 20px; }

        .form-label {
            font-family: 'Syne', sans-serif;
            font-size: 0.8rem; font-weight: 700;
            color: var(--forest); letter-spacing: 0.02em;
            margin-bottom: 8px; display: block;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute; left: 16px; top: 50%;
            transform: translateY(-50%);
            color: var(--mist); font-size: 1rem;
            pointer-events: none; transition: color 0.3s;
        }

        .form-input {
            width: 100%;
            background: var(--white);
            border: 1.5px solid rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 13px 16px 13px 44px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem; color: var(--charcoal);
            outline: none; transition: all 0.3s ease;
            color-scheme: light;
        }

        .form-input:focus {
            border-color: var(--sage);
            box-shadow: 0 0 0 4px rgba(46,125,50,0.1);
        }

        .form-input:focus ~ .input-icon,
        .input-wrap:focus-within .input-icon { color: var(--sage); }

        /* Divider */
        .form-divider {
            display: flex; align-items: center; gap: 12px;
            margin: 4px 0 20px; color: rgba(107,114,128,0.45);
            font-size: 0.75rem;
        }

        .form-divider::before, .form-divider::after {
            content: ''; flex: 1; height: 1px;
            background: rgba(0,0,0,0.07);
        }

        /* Summary box */
        .summary-box {
            background: var(--white);
            border: 1.5px solid rgba(0,0,0,0.08);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
        }

        .summary-row + .summary-row {
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        .summary-key {
            font-size: 0.8rem; color: var(--mist); font-weight: 500;
        }

        .summary-val {
            font-family: 'Syne', sans-serif;
            font-size: 0.88rem; font-weight: 700; color: var(--forest);
        }

        /* Total box */
        .total-box {
            background: var(--forest);
            border-radius: 16px;
            padding: 20px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .total-label-text {
            font-size: 0.78rem; font-weight: 600;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase; letter-spacing: 0.08em;
            margin-bottom: 4px;
        }

        .total-amount {
            font-family: 'Syne', sans-serif;
            font-size: 1.6rem; font-weight: 800;
            color: var(--mint); line-height: 1;
        }

        .total-days {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.4);
            margin-top: 4px;
        }

        .total-icon {
            width: 48px; height: 48px;
            background: rgba(134,239,172,0.12);
            border: 1px solid rgba(134,239,172,0.2);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--mint); font-size: 1.2rem;
        }

        /* Submit */
        .btn-submit {
            width: 100%;
            background: var(--sage);
            color: white;
            font-family: 'Syne', sans-serif;
            font-weight: 700; font-size: 0.95rem;
            padding: 15px;
            border-radius: 12px;
            border: none; cursor: pointer;
            transition: all 0.3s;
            display: flex; align-items: center;
            justify-content: center; gap: 8px;
        }

        .btn-submit:hover {
            background: var(--pine);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(46,125,50,0.35);
        }

        .btn-submit:active { transform: translateY(0); }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(24px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            body { grid-template-columns: 1fr; }
            .left-panel { display: none; }
            .right-panel { padding: 48px 28px; min-height: 100vh; }
        }
    </style>
</head>
<body>

    <!-- ===== LEFT PANEL ===== -->
    <div class="left-panel">
        <a href="index.php" class="brand">
            <img src="img/logo.jpg" alt="SiapTrek Logo" height="30" class="d-inline-block align-text-top me-2">
            <span class="brand-name">SiapTrek</span>
        </a>

        <div class="product-showcase">
            <p class="panel-eyebrow">Detail Produk</p>
            <h2 class="product-title"><?= htmlspecialchars($nama_produk) ?></h2>
            <p class="product-desc-text"><?= htmlspecialchars($deskripsi) ?></p>

            <div class="price-highlight">
                <div>
                    <div class="ph-label">Harga Sewa</div>
                    <div class="ph-price">Rp <?= number_format($harga) ?></div>
                    <div class="ph-unit">per hari</div>
                </div>
                <div class="ph-icon"><i class="bi bi-tag-fill"></i></div>
            </div>

            <div class="info-chips">
                <div class="info-chip"><i class="bi bi-shield-check-fill"></i> Kualitas Terjamin</div>
                <div class="info-chip"><i class="bi bi-headset"></i> Support 24/7</div>
                <div class="info-chip"><i class="bi bi-arrow-return-left"></i> Mudah Dikembalikan</div>
            </div>
        </div>

        <div class="panel-footer">&copy; 2026 SiapTrek. All rights reserved.</div>
    </div>

    <!-- ===== RIGHT PANEL ===== -->
    <div class="right-panel">
        <div class="booking-box">

            <a href="catalog.php" class="back-link">
                <i class="bi bi-arrow-left"></i> Kembali ke Katalog
            </a>

            <h1 class="booking-heading">Buat Reservasi</h1>
            <p class="booking-sub">Pilih tanggal sewa dan total akan dihitung otomatis</p>

            <?php if($msg): ?>
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="bookingForm">
                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                <input type="hidden" name="harga"      value="<?= $harga ?>">

                <!-- Tanggal Mulai -->
                <div class="form-group">
                    <label class="form-label" for="tglSewa">Tanggal Mulai Sewa</label>
                    <div class="input-wrap">
                        <input type="date" name="tgl_sewa" id="tglSewa"
                               class="form-input" required onchange="hitungTotal()">
                        <i class="bi bi-calendar-event input-icon"></i>
                    </div>
                </div>

                <div class="form-divider">sampai</div>

                <!-- Tanggal Selesai -->
                <div class="form-group">
                    <label class="form-label" for="tglKembali">Tanggal Selesai Sewa</label>
                    <div class="input-wrap">
                        <input type="date" name="tgl_kembali" id="tglKembali"
                               class="form-input" required onchange="hitungTotal()">
                        <i class="bi bi-calendar-check input-icon"></i>
                    </div>
                </div>

                <!-- Summary -->
                <div class="summary-box">
                    <div class="summary-row">
                        <span class="summary-key">Tarif per hari</span>
                        <span class="summary-val">Rp <?= number_format($harga) ?></span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-key">Durasi sewa</span>
                        <span class="summary-val" id="durasiText">— hari</span>
                    </div>
                </div>

                <!-- Total -->
                <div class="total-box">
                    <div>
                        <div class="total-label-text">Total Pembayaran</div>
                        <div class="total-amount" id="totalBayar">Rp 0</div>
                        <div class="total-days" id="lamaSewa">Pilih tanggal sewa terlebih dahulu</div>
                    </div>
                    <div class="total-icon"><i class="bi bi-wallet2"></i></div>
                </div>

                <button type="submit" name="sewa" class="btn-submit">
                    <i class="bi bi-check-circle-fill"></i> Konfirmasi Pesanan
                </button>
            </form>

        </div>
    </div>

    <script>
        function formatRupiah(n) {
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function hitungTotal() {
            const harga      = <?= $harga ?>;
            const tglSewa    = document.getElementById('tglSewa').value;
            const tglKembali = document.getElementById('tglKembali').value;
            const totalEl    = document.getElementById('totalBayar');
            const lamaEl     = document.getElementById('lamaSewa');
            const durasiEl   = document.getElementById('durasiText');

            if (tglSewa && tglKembali) {
                const d1 = new Date(tglSewa);
                const d2 = new Date(tglKembali);
                const days = Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24));

                if (days > 0) {
                    const total = days * harga;
                    totalEl.textContent  = formatRupiah(total);
                    lamaEl.textContent   = days + ' hari × ' + formatRupiah(harga);
                    durasiEl.textContent = days + ' hari';
                } else if (days < 0) {
                    totalEl.textContent  = 'Tanggal tidak valid';
                    lamaEl.textContent   = '';
                    durasiEl.textContent = '—';
                } else {
                    totalEl.textContent  = 'Rp 0';
                    lamaEl.textContent   = 'Pilih tanggal yang berbeda';
                    durasiEl.textContent = '0 hari';
                }
            }
        }

        // Set min date ke hari ini
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tglSewa').min    = today;
        document.getElementById('tglKembali').min = today;

        // Update min tgl kembali saat tgl sewa dipilih
        document.getElementById('tglSewa').addEventListener('change', function() {
            document.getElementById('tglKembali').min = this.value;
        });

        hitungTotal();
    </script>
</body>
</html>
