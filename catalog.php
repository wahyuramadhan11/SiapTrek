<?php
include 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM products WHERE stok > 0 ORDER BY id DESC");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog - SiapTrek</title>
    <link rel="icon" href="img/logo.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
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

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--charcoal);
            overflow-x: hidden;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, .brand-text {
            font-family: 'Syne', sans-serif;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: rgba(247, 244, 238, 0.92) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(46, 125, 50, 0.12);
            padding: 1rem 0;
            transition: all 0.4s ease;
        }

        .navbar.scrolled {
            box-shadow: 0 4px 30px rgba(0,0,0,0.06);
        }

        .navbar-brand {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--forest) !important;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: -0.02em;
            text-decoration: none;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: var(--sage);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .nav-user-greeting {
            font-size: 0.875rem;
            color: var(--mist);
            font-weight: 400;
        }

        .nav-user-greeting b {
            font-family: 'Syne', sans-serif;
            color: var(--forest);
            font-weight: 700;
        }

        .btn-nav-outline {
            border: 1.5px solid var(--sage);
            color: var(--sage) !important;
            border-radius: 100px;
            padding: 0.45rem 1.2rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-nav-outline:hover {
            background: var(--sage);
            color: white !important;
        }

        .btn-nav-danger {
            border: 1.5px solid #DC2626;
            color: #DC2626 !important;
            border-radius: 100px;
            padding: 0.45rem 1.2rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-nav-danger:hover {
            background: #DC2626;
            color: white !important;
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            background: var(--forest);
            padding: 52px 0 48px;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(134,239,172,0.12) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: 10%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(46,125,50,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-header-inner {
            position: relative;
            z-index: 2;
        }

        .page-eyebrow {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--mint);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-eyebrow::before {
            content: '';
            display: inline-block;
            width: 24px;
            height: 2px;
            background: var(--mint);
            border-radius: 2px;
        }

        .page-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            color: var(--white);
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 12px;
        }

        .page-title span { color: var(--mint); }

        .page-subtitle {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.55);
            font-weight: 300;
            max-width: 440px;
        }

        .page-header-stats {
            display: flex;
            gap: 32px;
            margin-top: 36px;
            padding-top: 32px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .phs-item {}
        .phs-num {
            font-family: 'Syne', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--mint);
        }
        .phs-label {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.45);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* ===== MAIN CONTENT ===== */
        .catalog-main {
            padding: 64px 0 80px;
        }

        /* Alert */
        .alert-custom {
            background: rgba(134, 239, 172, 0.12);
            border: 1px solid rgba(134, 239, 172, 0.35);
            color: var(--pine);
            border-radius: 14px;
            padding: 16px 20px;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 40px;
        }

        .alert-custom i { color: var(--sage); font-size: 1.1rem; }

        .alert-custom .btn-close {
            margin-left: auto;
            filter: none;
            opacity: 0.5;
        }

        /* ===== PRODUCT CARD ===== */
        .product-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.06);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 70px rgba(15, 34, 24, 0.14);
            border-color: transparent;
        }

        .product-img-wrap {
            position: relative;
            overflow: hidden;
        }

        .product-img {
            width: 100%;
            height: 210px;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .product-card:hover .product-img {
            transform: scale(1.07);
        }

        .product-stok-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            background: rgba(15, 34, 24, 0.75);
            backdrop-filter: blur(8px);
            color: var(--mint);
            font-size: 0.73rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 100px;
            letter-spacing: 0.04em;
        }

        .product-body {
            padding: 22px 24px 24px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .product-name {
            font-family: 'Syne', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--forest);
            margin-bottom: 8px;
            letter-spacing: -0.01em;
        }

        .product-desc {
            font-size: 0.845rem;
            color: var(--mist);
            line-height: 1.55;
            flex: 1;
        }

        .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid var(--sand);
        }

        .product-price {
            font-family: 'Syne', sans-serif;
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--sage);
            line-height: 1;
        }

        .product-price-label {
            font-size: 0.75rem;
            color: var(--mist);
            margin-top: 2px;
        }

        .btn-sewa {
            background: var(--forest);
            color: white !important;
            font-family: 'Syne', sans-serif;
            font-weight: 600;
            font-size: 0.82rem;
            padding: 10px 18px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
            border: none;
            white-space: nowrap;
        }

        .btn-sewa:hover {
            background: var(--sage);
            transform: scale(1.04);
            color: white !important;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: var(--white);
            border-radius: 24px;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: var(--sand);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--mist);
            margin: 0 auto 24px;
        }

        .empty-state h4 {
            font-family: 'Syne', sans-serif;
            color: var(--forest);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--mist);
            font-size: 0.9rem;
        }

        /* ===== BACK BUTTON ===== */
        .btn-back {
            background: transparent;
            border: 1.5px solid rgba(0,0,0,0.12);
            color: var(--charcoal) !important;
            font-family: 'Syne', sans-serif;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 12px 24px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-back:hover {
            border-color: var(--sage);
            color: var(--sage) !important;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--forest);
            padding: 24px 0;
        }

        .footer p {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.35);
            text-align: center;
            margin: 0;
        }

        .footer span { color: var(--mint); }

        /* ===== REVEAL ANIMATION ===== */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.55s ease, transform 0.55s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ===== SECTION LABEL ===== */
        .catalog-section-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .catalog-count {
            font-size: 0.85rem;
            color: var(--mist);
            background: var(--white);
            border: 1px solid rgba(0,0,0,0.07);
            padding: 6px 16px;
            border-radius: 100px;
        }

        .catalog-count b {
            font-family: 'Syne', sans-serif;
            color: var(--sage);
        }
    </style>
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg sticky-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="img/logo.jpg" alt="SiapTrek Logo" height="30" class="d-inline-block align-text-top me-2">
            SiapTrek
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="nav-user-greeting d-none d-md-block">
                    Halo, <b><?= htmlspecialchars($_SESSION['nama']) ?></b>
                </span>
                <a href="dashboard.php" class="btn-nav-outline">
                    <i class="bi bi-bag-check"></i> Pesanan Saya
                </a>
                <a href="logout.php" class="btn-nav-danger">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- ===== PAGE HEADER ===== -->
    <div class="page-header">
        <div class="container page-header-inner">
            <p class="page-eyebrow">Katalog Produk</p>
            <h1 class="page-title">Pilih Alat <span>Camping</span><br>Terbaik Kamu</h1>
            <p class="page-subtitle">Peralatan camping berkualitas, harga terjangkau — siap untuk petualanganmu berikutnya.</p>
            <div class="page-header-stats">
                <div class="phs-item">
                    <div class="phs-num"><?= count($products) ?>+</div>
                    <div class="phs-label">Produk Tersedia</div>
                </div>
                <div class="phs-item">
                    <div class="phs-num">24/7</div>
                    <div class="phs-label">Siap Melayani</div>
                </div>
                <div class="phs-item">
                    <div class="phs-num">500+</div>
                    <div class="phs-label">Pelanggan Puas</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MAIN CATALOG ===== -->
    <div class="catalog-main">
        <div class="container">

            <!-- Alert Success -->
            <?php if(isset($_GET['msg'])): ?>
                <div class="alert-custom" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <?= htmlspecialchars($_GET['msg']) ?>
                    <button type="button" class="btn-close btn-close-sm" onclick="this.parentElement.remove()"></button>
                </div>
            <?php endif; ?>

            <!-- Section label -->
            <?php if(count($products) > 0): ?>
            <div class="catalog-section-label reveal">
                <div>
                    <p style="font-size:0.75rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--sage);margin-bottom:6px">Semua Produk</p>
                    <h2 style="font-family:'Syne',sans-serif;font-size:1.6rem;font-weight:800;color:var(--forest);letter-spacing:-0.02em">Tersedia Sekarang</h2>
                </div>
                <span class="catalog-count"><b><?= count($products) ?></b> produk ditemukan</span>
            </div>
            <?php endif; ?>

            <!-- Product Grid -->
            <?php if(count($products) > 0): ?>
            <div class="row g-4">
                <?php foreach($products as $i => $p): ?>
                <div class="col-lg-4 col-md-6 reveal" style="transition-delay: <?= ($i % 6) * 0.07 ?>s">
                    <div class="product-card">
                        <!-- Image -->
                        <div class="product-img-wrap">
                            <?php
                            $gambar_path = 'img/' . $p['gambar'];
                            if(!empty($p['gambar']) && file_exists($gambar_path)):
                            ?>
                                <img src="<?= $gambar_path ?>" class="product-img" alt="<?= htmlspecialchars($p['nama_produk']) ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/400x250/0F2218/86EFAC?text=<?= urlencode($p['nama_produk']) ?>" class="product-img" alt="<?= htmlspecialchars($p['nama_produk']) ?>">
                            <?php endif; ?>
                            <div class="product-stok-badge">
                                <i class="bi bi-box-seam me-1"></i>Stok: <?= (int)$p['stok'] ?>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="product-body">
                            <h5 class="product-name"><?= htmlspecialchars($p['nama_produk']) ?></h5>
                            <p class="product-desc"><?= htmlspecialchars($p['deskripsi']) ?></p>

                            <div class="product-footer">
                                <div>
                                    <div class="product-price">Rp <?= number_format($p['harga_sewa']) ?></div>
                                    <div class="product-price-label">per hari</div>
                                </div>
                                <a href="booking.php?id=<?= (int)$p['id'] ?>&harga=<?= (int)$p['harga_sewa'] ?>" class="btn-sewa">
                                    <i class="bi bi-cart-plus"></i> Sewa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php else: ?>
            <!-- Empty State -->
            <div class="empty-state reveal">
                <div class="empty-state-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h4>Stok Sedang Kosong</h4>
                <p>Maaf, semua alat camping sedang disewa.<br>Coba cek kembali nanti atau hubungi kami.</p>
                <a href="https://wa.me/6283160722123" target="_blank" class="btn-sewa mt-4" style="display:inline-flex">
                    <i class="bi bi-whatsapp"></i> Hubungi Kami
                </a>
            </div>
            <?php endif; ?>

            <!-- Back Button -->
            <div class="text-center mt-5 pt-2 reveal">
                <a href="index.php" class="btn-back">
                    <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 <span>SiapTrek</span> — Sewa Alat Camping Terpercaya</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll
        const navbar = document.getElementById('mainNavbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 30);
        });

        // Scroll reveal
        const revealEls = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });

        revealEls.forEach(el => observer.observe(el));
    </script>
</body>
</html>
