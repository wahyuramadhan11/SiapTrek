<?php
// Mulai session
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';

// Cek login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$msg = "";
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$harga = isset($_GET['harga']) ? (int)$_GET['harga'] : 0;
$nama_produk = "";

// Ambil nama produk
if($product_id > 0) {
    $stmt = $pdo->prepare("SELECT nama_produk, deskripsi FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $produk = $stmt->fetch();
    $nama_produk = $produk['nama_produk'] ?? "Produk Tidak Ditemukan";
} else {
    header("Location: catalog.php");
    exit;
}

// Proses pemesanan
if(isset($_POST['sewa'])) {
    $pid = (int)$_POST['product_id'];
    $tgl_sewa = $_POST['tgl_sewa'];
    $tgl_kembali = $_POST['tgl_kembali'];
    $harga = (int)$_POST['harga'];
    
    $date1 = new DateTime($tgl_sewa);
    $date2 = new DateTime($tgl_kembali);
    $days = $date1->diff($date2)->days;
    
    if($days < 1) {
        $msg = "Tanggal kembali tidak boleh sama!";
    } else {
        $total = $days * $harga;
        try {
            $sql = "INSERT INTO orders (user_id, product_id, tanggal_sewa, tanggal_kembali, total_harga, status) VALUES (?, ?, ?, ?, ?, 'Menunggu Konfirmasi')";
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
<html>
<head>
    <title>Booking - SiapTrek</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* === WHITE AESTHETIC === */
        :root {
            --white: #ffffff;
            --white-95: #fafafa;
            --white-90: #f5f5f5;
            --white-80: #f0f0f0;
            --white-70: #e5e5e5;
            
            --green-600: #16a34a;
            --green-500: #22c55e;
            --green-400: #4ade80;
            --green-100: #dcfce7;
            --green-50: #f0fdf4;
            
            --neutral-900: #171717;
            --neutral-700: #3d3d3d;
            --neutral-500: #737373;
            --neutral-400: #a3a3a4;
        }
        
        * { font-family: 'Outfit', sans-serif; }
        
        html, body { overflow-x: hidden; }
        
        body {
            background: var(--white);
            min-height: 100vh;
        }
        
        /* === ANIMATED BACKGROUND === */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            background: var(--white);
        }
        
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            animation: floatOrb 20s infinite ease-in-out;
        }
        
        .orb-1 { width: 500px; height: 500px; background: var(--green-100); top: -200px; right: -150px; }
        .orb-2 { width: 400px; height: 400px; background: #e0e7ff; bottom: -150px; left: -100px; animation-delay: -5s; }
        
        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, -20px); }
        }
        
        .geo-shape {
            position: absolute;
            opacity: 0.12;
            animation: drift 25s infinite linear;
        }
        
        .geo-1 { width: 80px; height: 80px; border: 3px solid var(--green-500); border-radius: 50%; top: 15%; left: 8%; }
        .geo-2 { border-left: 35px solid transparent; border-right: 35px solid transparent; border-bottom: 60px solid var(--green-500); top: 65%; right: 12%; }
        
        @keyframes drift {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-80px) rotate(180deg); }
            100% { transform: translateY(0) rotate(360deg); }
        }
        
        /* === CARD === */
        .booking-card {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid var(--white-80);
            border-radius: 24px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.08);
            max-width: 440px;
            margin: 40px auto;
        }
        
        .booking-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 20%;
            right: 20%;
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--green-500), var(--green-400), var(--green-500), transparent);
            border-radius: 0 0 6px 6px;
        }
        
        .booking-header {
            background: linear-gradient(135deg, var(--green-500), var(--green-400));
            padding: 30px;
            border-radius: 22px 22px 0 0;
        }
        
        .form-control {
            background: var(--white-90);
            border: 1px solid var(--white-70);
            color: var(--neutral-900);
            padding: 14px 16px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            background: var(--white);
            border-color: var(--green-500);
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
            outline: none;
        }
        
        input[type="date"] { color-scheme: light; }
        
        .form-label {
            color: var(--neutral-700);
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .price-box {
            background: var(--green-50);
            border: 1px solid var(--green-100);
            border-radius: 12px;
            padding: 16px;
        }
        
        .price-value {
            color: var(--green-600);
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .total-box {
            background: var(--green-500);
            color: var(--white);
            border-radius: 12px;
            padding: 16px;
        }
        
        .total-label { opacity: 0.9; font-size: 0.9rem; }
        .total-value { font-size: 1.5rem; font-weight: 700; }
        
        .btn-sewa {
            background: var(--green-500);
            color: var(--white);
            padding: 16px;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-sewa:hover {
            background: var(--green-600);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.35);
            color: var(--white);
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--white-70);
        }
        
        .navbar-brand { color: var(--green-600) !important; font-weight: 600; }
        
        .card-body { padding: 30px; }
        
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 10px;
        }
        
        .spacer { height: 40px; }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="geo-shape geo-1"></div>
        <div class="geo-shape geo-2"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand">
        <div class="container">
            <a class="navbar-brand" href="catalog.php">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </nav>

    <div class="spacer"></div>

    <div class="container">
        <div class="card booking-card">
            <div class="booking-header text-center">
                <i class="bi bi-calendar-check" style="font-size: 2rem; opacity: 0.85;"></i>
                <h4 class="mt-2" style="font-weight: 600;">Reservasi</h4>
                <p style="opacity: 0.8; font-size: 0.95rem;"><?= htmlspecialchars($nama_produk) ?></p>
            </div>
            
            <div class="card-body">
                <?php if($msg): ?>
                    <div class="alert-error px-3 py-2 mb-3"><?= $msg ?></div>
                <?php endif; ?>
                
                <form method="POST" id="bookingForm">
                    <input type="hidden" name="product_id" value="<?= $product_id ?>">
                    <input type="hidden" name="harga" value="<?= $harga ?>">
                    
                    <div class="mb-3">
                        <label class="form-label d-block">📅 Tanggal Mulai</label>
                        <input type="date" name="tgl_sewa" id="tglSewa" class="form-control" required onchange="hitungTotal()">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label d-block">📅 Tanggal Selesai</label>
                        <input type="date" name="tgl_kembali" id="tglKembali" class="form-control" required onchange="hitungTotal()">
                    </div>
                    
                    <div class="price-box mb-3">
                        <div class="d-flex justify-content-between">
                            <span style="color: var(--neutral-500);">Tarif/hari</span>
                            <span class="price-value">Rp <?= number_format($harga) ?></span>
                        </div>
                    </div>
                    
                    <div class="total-box mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="total-label">Total Pembayaran</span>
                            <span class="total-value" id="totalBayar">Rp 0</span>
                        </div>
                        <small id="lamaSewa" style="opacity: 0.8; display: block; margin-top: 4px;"></small>
                    </div>
                    
                    <button type="submit" name="sewa" class="btn btn-sewa">
                        <i class="bi bi-check-circle me-2"></i>Konfirmasi Pesanan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Format rupiah
        function formatRupiah(angka) {
            return 'Rp ' + angka.toLocaleString('id-ID');
        }
        
        // Hitung total
        function hitungTotal() {
            var harga = <?= $harga ?>;
            var tglSewa = document.getElementById('tglSewa').value;
            var tglKembali = document.getElementById('tglKembali').value;
            var totalBayar = document.getElementById('totalBayar');
            var lamaSewa = document.getElementById('lamaSewa');
            
            if(tglSewa && tglKembali) {
                var date1 = new Date(tglSewa);
                var date2 = new Date(tglKembali);
                
                // Hitung selisih hari
                var diffTime = date2 - date1;
                var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if(diffDays > 0) {
                    var total = diffDays * harga;
                    totalBayar.textContent = formatRupiah(total);
                    lamaSewa.textContent = diffDays + ' hari × ' + formatRupiah(harga);
                } else if(diffDays < 0) {
                    totalBayar.textContent = 'Tanggal tidak valid';
                    lamaSewa.textContent = '';
                } else {
                    totalBayar.textContent = 'Rp 0';
                    lamaSewa.textContent = 'Pilih tanggal berbeda';
                }
            }
        }
        
        // Init
        hitungTotal();
    </script>
</body>
</html>