<?php
include 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role    = $_SESSION['role'];

if($role == 'admin' && isset($_GET['action']) && isset($_GET['id'])) {
    $new_status = $_GET['action'];
    $order_id   = $_GET['id'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);
    header("Location: dashboard.php");
    exit;
}

if($role == 'admin') {
    $query = "SELECT o.*, u.nama, u.email, p.nama_produk 
              FROM orders o 
              JOIN users u ON o.user_id = u.id 
              JOIN products p ON o.product_id = p.id 
              ORDER BY o.created_at DESC";
    $stmt  = $pdo->query($query);
} else {
    $query = "SELECT o.*, p.nama_produk 
              FROM orders o 
              JOIN products p ON o.product_id = p.id 
              WHERE o.user_id = ? 
              ORDER BY o.created_at DESC";
    $stmt  = $pdo->prepare($query);
    $stmt->execute([$user_id]);
}
$orders = $stmt->fetchAll();

$total    = count($orders);
$aktif    = 0;
$menunggu = 0;
$selesai  = 0;
foreach($orders as $o) {
    if($o['status'] == 'Menunggu Konfirmasi') $menunggu++;
    if($o['status'] == 'Selesai' || $o['status'] == 'Dikembalikan') $selesai++;
    if($o['status'] != 'Dikembalikan' && $o['status'] != 'Selesai') $aktif++;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SiapTrek</title>
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
            --mint:     #86EFAC;
            --cream:    #F7F4EE;
            --sand:     #EDE9DE;
            --charcoal: #1A1A1A;
            --mist:     #6B7280;
            --white:    #FFFFFF;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; display: flex; }
        h1,h2,h3,h4,h5,h6 { font-family: 'Syne', sans-serif; }

        /* OVERLAY */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 199; backdrop-filter: blur(2px); }
        .sidebar-overlay.active { display: block; }

        /* SIDEBAR */
        .sidebar { position: fixed; left: 0; top: 0; width: 256px; height: 100vh; background: var(--forest); display: flex; flex-direction: column; z-index: 200; overflow: hidden; transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); }
        .sidebar::before { content: ''; position: absolute; bottom: -80px; right: -80px; width: 240px; height: 240px; background: radial-gradient(circle, rgba(46,125,50,0.25) 0%, transparent 70%); border-radius: 50%; pointer-events: none; }
        .sidebar-top { padding: 32px 24px 24px; border-bottom: 1px solid rgba(255,255,255,0.07); }
        .sidebar-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; margin-bottom: 28px; }
        .sidebar-brand-icon { width: 38px; height: 38px; background: var(--sage); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1rem; flex-shrink: 0; }
        .sidebar-brand-name { font-family: 'Syne', sans-serif; font-size: 1.35rem; font-weight: 800; color: white; letter-spacing: -0.02em; }
        .sidebar-user { display: flex; align-items: center; gap: 12px; }
        .sidebar-avatar { width: 40px; height: 40px; border-radius: 50%; background: rgba(134,239,172,0.15); border: 1.5px solid rgba(134,239,172,0.25); display: flex; align-items: center; justify-content: center; color: var(--mint); font-size: 1rem; flex-shrink: 0; }
        .sidebar-user-name { font-family: 'Syne', sans-serif; font-size: 0.9rem; font-weight: 700; color: white; line-height: 1.2; }
        .sidebar-user-role { font-size: 0.72rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.06em; }
        .sidebar-nav { flex: 1; padding: 24px 16px; overflow-y: auto; }
        .nav-section-label { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(255,255,255,0.25); padding: 0 12px; margin-bottom: 8px; margin-top: 24px; }
        .nav-section-label:first-child { margin-top: 0; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 11px 14px; border-radius: 12px; color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: all 0.25s ease; margin-bottom: 3px; }
        .nav-item i { font-size: 1rem; width: 18px; text-align: center; }
        .nav-item:hover { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.9); }
        .nav-item.active { background: var(--sage); color: white; font-weight: 600; }
        .nav-item.danger { color: rgba(248,113,113,0.75); }
        .nav-item.danger:hover { background: rgba(220,38,38,0.12); color: #F87171; }

        /* MAIN */
        .main-content { margin-left: 256px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; min-width: 0; }

        /* TOPBAR */
        .topbar { background: var(--white); border-bottom: 1px solid rgba(0,0,0,0.06); padding: 16px 28px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; gap: 12px; }
        .topbar-left { display: flex; align-items: center; gap: 14px; min-width: 0; }
        .hamburger { display: none; width: 38px; height: 38px; background: var(--cream); border: 1px solid rgba(0,0,0,0.08); border-radius: 10px; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; color: var(--forest); font-size: 1.1rem; transition: all 0.2s; }
        .hamburger:hover { background: var(--sand); }
        .topbar-title { font-size: 1.1rem; font-weight: 800; color: var(--forest); letter-spacing: -0.02em; display: flex; align-items: center; gap: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .topbar-title i { color: var(--sage); flex-shrink: 0; }
        .btn-sewa-now { background: var(--forest); color: white !important; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.82rem; padding: 9px 18px; border-radius: 100px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.3s; border: none; flex-shrink: 0; white-space: nowrap; }
        .btn-sewa-now:hover { background: var(--sage); transform: translateY(-1px); box-shadow: 0 8px 24px rgba(46,125,50,0.3); }

        /* PAGE BODY */
        .page-body { padding: 28px; flex: 1; }

        /* STAT CARDS */
        .stats-row { display: grid; gap: 16px; margin-bottom: 24px; }
        .stats-row.admin-stats { grid-template-columns: repeat(3, 1fr); }
        .stats-row.user-stats  { grid-template-columns: repeat(2, 1fr); }
        .stat-card { background: var(--white); border-radius: 18px; padding: 22px; border: 1px solid rgba(0,0,0,0.06); position: relative; overflow: hidden; transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 36px rgba(15,34,24,0.1); }
        .stat-card::after { content: ''; position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; border-radius: 50%; background: var(--sand); opacity: 0.6; }
        .stat-card.highlight::after { background: rgba(134,239,172,0.15); }
        .stat-label { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--mist); margin-bottom: 10px; }
        .stat-number { font-family: 'Syne', sans-serif; font-size: 2.2rem; font-weight: 800; color: var(--forest); line-height: 1; }
        .stat-card.highlight .stat-number { color: var(--sage); }
        .stat-icon { position: absolute; top: 20px; right: 20px; width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; background: var(--sand); color: var(--mist); z-index: 1; }
        .stat-card.highlight .stat-icon { background: rgba(134,239,172,0.18); color: var(--sage); }
        .stat-sub { font-size: 0.75rem; color: var(--mist); margin-top: 6px; }

        /* TABLE CARD */
        .table-card { background: var(--white); border-radius: 20px; border: 1px solid rgba(0,0,0,0.06); overflow: hidden; }
        .table-card-header { padding: 20px 24px; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .table-card-title { font-size: 0.95rem; font-weight: 700; color: var(--forest); letter-spacing: -0.01em; }
        .table-count { font-size: 0.75rem; color: var(--mist); background: var(--cream); border: 1px solid rgba(0,0,0,0.07); padding: 4px 12px; border-radius: 100px; white-space: nowrap; flex-shrink: 0; }
        .table-count b { color: var(--sage); font-family: 'Syne', sans-serif; }

        /* DESKTOP TABLE */
        .orders-table { width: 100%; border-collapse: collapse; }
        .orders-table thead th { background: var(--cream); padding: 12px 18px; text-align: left; font-family: 'Syne', sans-serif; font-size: 0.68rem; font-weight: 700; color: var(--mist); text-transform: uppercase; letter-spacing: 0.08em; white-space: nowrap; border-bottom: 1px solid rgba(0,0,0,0.06); }
        .orders-table tbody td { padding: 14px 18px; border-bottom: 1px solid rgba(0,0,0,0.04); color: var(--charcoal); font-size: 0.85rem; vertical-align: middle; }
        .orders-table tbody tr:last-child td { border-bottom: none; }
        .orders-table tbody tr:hover td { background: rgba(247,244,238,0.7); }
        .order-id { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.78rem; color: var(--mist); }
        .user-name { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.85rem; color: var(--forest); }
        .user-email { font-size: 0.72rem; color: var(--mist); margin-top: 2px; }
        .product-name-cell { font-weight: 500; color: var(--forest); }
        .price-text { font-family: 'Syne', sans-serif; font-weight: 700; color: var(--sage); font-size: 0.875rem; }

        /* STATUS BADGES */
        .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 100px; font-size: 0.68rem; font-weight: 700; white-space: nowrap; }
        .status-badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; flex-shrink: 0; }
        .status-menunggu     { background: #FEF9C3; color: #A16207; }
        .status-diproses     { background: #DBEAFE; color: #1D4ED8; }
        .status-siap         { background: rgba(134,239,172,0.2); color: var(--pine); }
        .status-dikembalikan { background: rgba(134,239,172,0.35); color: var(--sage); }
        .status-selesai      { background: var(--sand); color: var(--mist); }

        /* ACTION BUTTONS */
        .action-btn { display: inline-flex; align-items: center; gap: 5px; padding: 6px 12px; border-radius: 100px; font-size: 0.72rem; font-weight: 700; font-family: 'Syne', sans-serif; text-decoration: none; transition: all 0.25s; border: none; cursor: pointer; white-space: nowrap; }
        .action-proses        { background: #DBEAFE; color: #1D4ED8; }
        .action-proses:hover  { background: #1D4ED8; color: white; }
        .action-siap          { background: rgba(134,239,172,0.2); color: var(--pine); }
        .action-siap:hover    { background: var(--sage); color: white; }
        .action-kembali       { background: #FEF9C3; color: #A16207; }
        .action-kembali:hover { background: #D97706; color: white; }
        .action-selesai       { background: var(--sand); color: var(--mist); }
        .action-selesai:hover { background: var(--charcoal); color: white; }

        /* MOBILE CARDS */
        .mobile-orders { display: none; }
        .order-card { background: var(--white); border-bottom: 1px solid rgba(0,0,0,0.05); padding: 18px 20px; }
        .order-card:last-child { border-bottom: none; }
        .order-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 12px; }
        .order-card-product { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.95rem; color: var(--forest); line-height: 1.3; }
        .order-card-id { font-size: 0.72rem; color: var(--mist); margin-top: 3px; }
        .order-card-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px; }
        .order-meta-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: var(--mist); margin-bottom: 3px; }
        .order-meta-value { font-size: 0.82rem; color: var(--charcoal); font-weight: 500; }
        .order-card-footer { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding-top: 12px; border-top: 1px solid rgba(0,0,0,0.05); }
        .order-card-price { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.05rem; color: var(--sage); }
        .order-card-user { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; padding: 8px 10px; background: var(--cream); border-radius: 10px; }
        .order-card-user-icon { width: 28px; height: 28px; background: rgba(46,125,50,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--sage); font-size: 0.8rem; flex-shrink: 0; }
        .order-card-user-name { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.82rem; color: var(--forest); }
        .order-card-user-email { font-size: 0.72rem; color: var(--mist); }

        /* EMPTY STATE */
        .empty-state { text-align: center; padding: 60px 24px; }
        .empty-icon { width: 68px; height: 68px; background: var(--cream); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.7rem; color: var(--mist); margin: 0 auto 18px; }
        .empty-state h5 { color: var(--forest); font-size: 1rem; margin-bottom: 8px; }
        .empty-state p  { color: var(--mist); font-size: 0.875rem; margin-bottom: 20px; }
        .btn-empty { background: var(--forest); color: white !important; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 0.85rem; padding: 12px 24px; border-radius: 100px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .btn-empty:hover { background: var(--sage); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(46,125,50,0.3); }

        /* BOTTOM NAV */
        .bottom-nav { display: none; position: fixed; bottom: 0; left: 0; right: 0; background: var(--white); border-top: 1px solid rgba(0,0,0,0.08); padding: 8px 0 max(8px, env(safe-area-inset-bottom)); z-index: 150; }
        .bottom-nav-inner { display: flex; justify-content: space-around; align-items: center; }
        .bn-item { display: flex; flex-direction: column; align-items: center; gap: 3px; text-decoration: none; color: var(--mist); font-size: 0.62rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; padding: 4px 10px; border-radius: 10px; transition: color 0.2s; }
        .bn-item i { font-size: 1.25rem; }
        .bn-item.active { color: var(--sage); }
        .bn-item.danger { color: #EF4444; }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .sidebar { transform: translateX(-256px); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .hamburger { display: flex; }
            .stats-row.admin-stats { grid-template-columns: 1fr 1fr; }
            .page-body { padding: 18px 14px 96px; }
            .topbar { padding: 12px 14px; }
            .desktop-table { display: none; }
            .mobile-orders { display: block; }
            .bottom-nav { display: block; }
        }
        @media (max-width: 576px) {
            .stats-row.admin-stats,
            .stats-row.user-stats { grid-template-columns: 1fr 1fr; }
            .stat-number { font-size: 1.8rem; }
        }
        @media (max-width: 360px) {
            .stats-row.admin-stats,
            .stats-row.user-stats { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-top">
        <a href="index.php" class="sidebar-brand">
            <div class="sidebar-brand-icon"><i class="bi bi-campground"></i></div>
            <span class="sidebar-brand-name">SiapTrek</span>
        </a>
        <div class="sidebar-user">
            <div class="sidebar-avatar"><i class="bi bi-person"></i></div>
            <div>
                <div class="sidebar-user-name"><?= htmlspecialchars($_SESSION['nama']) ?></div>
                <div class="sidebar-user-role"><?= $role == 'admin' ? 'Administrator' : 'Member' ?></div>
            </div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Menu</div>
        <a href="index.php"     class="nav-item"><i class="bi bi-house-door"></i> Beranda</a>
        <a href="catalog.php"   class="nav-item"><i class="bi bi-bag"></i> Sewa Alat</a>
        <a href="dashboard.php" class="nav-item active"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <div class="nav-section-label">Akun</div>
        <a href="logout.php" class="nav-item danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
</aside>

<!-- MAIN -->
<div class="main-content">
    <div class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="openSidebar()"><i class="bi bi-list"></i></button>
            <div class="topbar-title">
                <i class="bi bi-<?= $role == 'admin' ? 'speedometer2' : 'box-seam' ?>"></i>
                <?= $role == 'admin' ? 'Kelola Pesanan' : 'Pesanan Saya' ?>
            </div>
        </div>
        <?php if($role != 'admin'): ?>
            <a href="catalog.php" class="btn-sewa-now"><i class="bi bi-plus-lg"></i><span>Sewa Alat</span></a>
        <?php endif; ?>
    </div>

    <div class="page-body">
        <!-- Stats -->
        <div class="stats-row <?= $role == 'admin' ? 'admin-stats' : 'user-stats' ?>">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-receipt"></i></div>
                <div class="stat-label">Total Pesanan</div>
                <div class="stat-number"><?= $total ?></div>
                <div class="stat-sub">Semua waktu</div>
            </div>
            <div class="stat-card highlight">
                <div class="stat-icon"><i class="bi bi-activity"></i></div>
                <div class="stat-label">Sedang Aktif</div>
                <div class="stat-number"><?= $aktif ?></div>
                <div class="stat-sub">Sedang berjalan</div>
            </div>
            <?php if($role == 'admin'): ?>
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-label">Menunggu</div>
                <div class="stat-number"><?= $menunggu ?></div>
                <div class="stat-sub">Perlu konfirmasi</div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Table Card -->
        <div class="table-card">
            <div class="table-card-header">
                <h4 class="table-card-title"><?= $role == 'admin' ? 'Daftar Pesanan' : 'Riwayat Pesanan' ?></h4>
                <span class="table-count"><b><?= $total ?></b> pesanan</span>
            </div>

            <?php if($total > 0): ?>

            <!-- DESKTOP TABLE -->
            <div class="desktop-table" style="overflow-x:auto">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <?php if($role == 'admin'): ?><th>Penyewa</th><?php endif; ?>
                            <th>Produk</th>
                            <th>Tgl Sewa</th>
                            <th>Tgl Kembali</th>
                            <th>Total</th>
                            <th>Status</th>
                            <?php if($role == 'admin'): ?><th>Aksi</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $o):
                            $cls = 'status-selesai';
                            if($o['status'] == 'Menunggu Konfirmasi') $cls = 'status-menunggu';
                            elseif($o['status'] == 'Diproses')        $cls = 'status-diproses';
                            elseif($o['status'] == 'Siap Diambil')    $cls = 'status-siap';
                            elseif($o['status'] == 'Dikembalikan')    $cls = 'status-dikembalikan';
                        ?>
                        <tr>
                            <td><span class="order-id">#<?= $o['id'] ?></span></td>
                            <?php if($role == 'admin'): ?>
                            <td>
                                <div class="user-name"><?= htmlspecialchars($o['nama']) ?></div>
                                <div class="user-email"><?= htmlspecialchars($o['email']) ?></div>
                            </td>
                            <?php endif; ?>
                            <td><span class="product-name-cell"><?= htmlspecialchars($o['nama_produk']) ?></span></td>
                            <td><?= date('d M Y', strtotime($o['tanggal_sewa'])) ?></td>
                            <td><?= date('d M Y', strtotime($o['tanggal_kembali'])) ?></td>
                            <td><span class="price-text">Rp <?= number_format($o['total_harga']) ?></span></td>
                            <td><span class="status-badge <?= $cls ?>"><?= htmlspecialchars($o['status']) ?></span></td>
                            <?php if($role == 'admin'): ?>
                            <td>
                                <?php if($o['status'] == 'Menunggu Konfirmasi'): ?>
                                    <a href="?action=Diproses&id=<?= $o['id'] ?>" class="action-btn action-proses"><i class="bi bi-arrow-right-circle"></i> Proses</a>
                                <?php elseif($o['status'] == 'Diproses'): ?>
                                    <a href="?action=Siap Diambil&id=<?= $o['id'] ?>" class="action-btn action-siap"><i class="bi bi-check-circle"></i> Siap</a>
                                <?php elseif($o['status'] == 'Siap Diambil'): ?>
                                    <a href="?action=Dikembalikan&id=<?= $o['id'] ?>" class="action-btn action-kembali"><i class="bi bi-arrow-return-left"></i> Kembali</a>
                                <?php elseif($o['status'] == 'Dikembalikan'): ?>
                                    <a href="?action=Selesai&id=<?= $o['id'] ?>" class="action-btn action-selesai"><i class="bi bi-flag-fill"></i> Selesai</a>
                                <?php else: ?><span style="color:var(--mist);font-size:0.8rem">—</span>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- MOBILE CARDS -->
            <div class="mobile-orders">
                <?php foreach($orders as $o):
                    $cls = 'status-selesai';
                    if($o['status'] == 'Menunggu Konfirmasi') $cls = 'status-menunggu';
                    elseif($o['status'] == 'Diproses')        $cls = 'status-diproses';
                    elseif($o['status'] == 'Siap Diambil')    $cls = 'status-siap';
                    elseif($o['status'] == 'Dikembalikan')    $cls = 'status-dikembalikan';
                ?>
                <div class="order-card">
                    <?php if($role == 'admin'): ?>
                    <div class="order-card-user">
                        <div class="order-card-user-icon"><i class="bi bi-person-fill"></i></div>
                        <div>
                            <div class="order-card-user-name"><?= htmlspecialchars($o['nama']) ?></div>
                            <div class="order-card-user-email"><?= htmlspecialchars($o['email']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="order-card-top">
                        <div>
                            <div class="order-card-product"><?= htmlspecialchars($o['nama_produk']) ?></div>
                            <div class="order-card-id">Pesanan #<?= $o['id'] ?></div>
                        </div>
                        <span class="status-badge <?= $cls ?>"><?= htmlspecialchars($o['status']) ?></span>
                    </div>
                    <div class="order-card-meta">
                        <div class="order-meta-item">
                            <div class="order-meta-label">Tgl Sewa</div>
                            <div class="order-meta-value"><?= date('d M Y', strtotime($o['tanggal_sewa'])) ?></div>
                        </div>
                        <div class="order-meta-item">
                            <div class="order-meta-label">Tgl Kembali</div>
                            <div class="order-meta-value"><?= date('d M Y', strtotime($o['tanggal_kembali'])) ?></div>
                        </div>
                    </div>
                    <div class="order-card-footer">
                        <div class="order-card-price">Rp <?= number_format($o['total_harga']) ?></div>
                        <?php if($role == 'admin'): ?>
                            <?php if($o['status'] == 'Menunggu Konfirmasi'): ?>
                                <a href="?action=Diproses&id=<?= $o['id'] ?>" class="action-btn action-proses"><i class="bi bi-arrow-right-circle"></i> Proses</a>
                            <?php elseif($o['status'] == 'Diproses'): ?>
                                <a href="?action=Siap Diambil&id=<?= $o['id'] ?>" class="action-btn action-siap"><i class="bi bi-check-circle"></i> Siap</a>
                            <?php elseif($o['status'] == 'Siap Diambil'): ?>
                                <a href="?action=Dikembalikan&id=<?= $o['id'] ?>" class="action-btn action-kembali"><i class="bi bi-arrow-return-left"></i> Kembali</a>
                            <?php elseif($o['status'] == 'Dikembalikan'): ?>
                                <a href="?action=Selesai&id=<?= $o['id'] ?>" class="action-btn action-selesai"><i class="bi bi-flag-fill"></i> Selesai</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                <h5>Belum ada pesanan</h5>
                <p>Silakan sewa alat camping terlebih dahulu</p>
                <a href="catalog.php" class="btn-empty"><i class="bi bi-bag-plus"></i> Sewa Sekarang</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- BOTTOM NAV (mobile) -->
<nav class="bottom-nav">
    <div class="bottom-nav-inner">
        <a href="index.php"     class="bn-item"><i class="bi bi-house-door"></i>Beranda</a>
        <a href="catalog.php"   class="bn-item"><i class="bi bi-bag"></i>Sewa</a>
        <a href="dashboard.php" class="bn-item active"><i class="bi bi-speedometer2"></i>Dashboard</a>
        <a href="logout.php"    class="bn-item danger"><i class="bi bi-box-arrow-right"></i>Logout</a>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }
    window.addEventListener('resize', () => { if(window.innerWidth > 900) closeSidebar(); });
</script>
</body>
</html>
