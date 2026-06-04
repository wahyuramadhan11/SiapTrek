<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role    = $_SESSION['role'];

// Proses update status (Hanya Admin)
if($role == 'admin' && isset($_GET['action']) && isset($_GET['id'])) {
    $new_status = $_GET['action'];
    $order_id   = $_GET['id'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);
    header("Location: dashboard.php");
    exit;
}

// Ambil data pesanan
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

// Hitung stat
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

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            display: flex;
        }

        h1, h2, h3, h4, h5, h6 { font-family: 'Syne', sans-serif; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            left: 0; top: 0;
            width: 256px;
            height: 100vh;
            background: var(--forest);
            display: flex;
            flex-direction: column;
            padding: 0;
            z-index: 200;
            overflow: hidden;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(46,125,50,0.25) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .sidebar-top {
            padding: 32px 24px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: 28px;
        }

        .sidebar-brand-icon {
            width: 38px;
            height: 38px;
            background: var(--sage);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .sidebar-brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: white;
            letter-spacing: -0.02em;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(134,239,172,0.15);
            border: 1.5px solid rgba(134,239,172,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--mint);
            font-size: 1rem;
            flex-shrink: 0;
        }

        .sidebar-user-name {
            font-family: 'Syne', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: white;
            line-height: 1.2;
        }

        .sidebar-user-role {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.4);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 24px 16px;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
            padding: 0 12px;
            margin-bottom: 8px;
            margin-top: 24px;
        }

        .nav-section-label:first-child { margin-top: 0; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 12px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.25s ease;
            margin-bottom: 3px;
        }

        .nav-item i { font-size: 1rem; width: 18px; text-align: center; }

        .nav-item:hover {
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.9);
        }

        .nav-item.active {
            background: var(--sage);
            color: white;
            font-weight: 600;
        }

        .nav-item.danger {
            color: rgba(248,113,113,0.75);
        }

        .nav-item.danger:hover {
            background: rgba(220,38,38,0.12);
            color: #F87171;
        }

        /* Sidebar bottom */
        .sidebar-bottom {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        /* ===== MAIN ===== */
        .main-content {
            margin-left: 256px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top bar */
        .topbar {
            background: var(--white);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            padding: 18px 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--forest);
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-title i { color: var(--sage); }

        .btn-sewa-now {
            background: var(--forest);
            color: white !important;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 10px 20px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.3s;
            border: none;
        }

        .btn-sewa-now:hover {
            background: var(--sage);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(46,125,50,0.3);
        }

        /* Page body */
        .page-body {
            padding: 36px;
            flex: 1;
        }

        /* ===== STAT CARDS ===== */
        .stats-row {
            display: grid;
            gap: 20px;
            margin-bottom: 32px;
        }

        .stats-row.admin-stats { grid-template-columns: repeat(3, 1fr); }
        .stats-row.user-stats  { grid-template-columns: repeat(2, 1fr); }

        .stat-card {
            background: var(--white);
            border-radius: 20px;
            padding: 26px 28px;
            border: 1px solid rgba(0,0,0,0.06);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(15,34,24,0.1);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--sand);
            opacity: 0.6;
        }

        .stat-card.highlight::after { background: rgba(134,239,172,0.15); }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--mist);
            margin-bottom: 12px;
        }

        .stat-number {
            font-family: 'Syne', sans-serif;
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--forest);
            line-height: 1;
        }

        .stat-card.highlight .stat-number { color: var(--sage); }

        .stat-icon {
            position: absolute;
            top: 24px;
            right: 24px;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            background: var(--sand);
            color: var(--mist);
            z-index: 1;
        }

        .stat-card.highlight .stat-icon {
            background: rgba(134,239,172,0.18);
            color: var(--sage);
        }

        .stat-sub {
            font-size: 0.78rem;
            color: var(--mist);
            margin-top: 8px;
        }

        /* ===== TABLE CARD ===== */
        .table-card {
            background: var(--white);
            border-radius: 20px;
            border: 1px solid rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .table-card-header {
            padding: 22px 28px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-card-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--forest);
            letter-spacing: -0.01em;
        }

        .table-count {
            font-size: 0.78rem;
            color: var(--mist);
            background: var(--cream);
            border: 1px solid rgba(0,0,0,0.07);
            padding: 5px 14px;
            border-radius: 100px;
        }

        .table-count b { color: var(--sage); font-family: 'Syne', sans-serif; }

        /* Table */
        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table thead th {
            background: var(--cream);
            padding: 13px 20px;
            text-align: left;
            font-family: 'Syne', sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--mist);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            white-space: nowrap;
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }

        .orders-table tbody td {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(0,0,0,0.04);
            color: var(--charcoal);
            font-size: 0.875rem;
            vertical-align: middle;
        }

        .orders-table tbody tr:last-child td { border-bottom: none; }

        .orders-table tbody tr:hover td { background: rgba(247,244,238,0.7); }

        .order-id {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.8rem;
            color: var(--mist);
        }

        .user-name {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.875rem;
            color: var(--forest);
        }

        .user-email {
            font-size: 0.75rem;
            color: var(--mist);
            margin-top: 2px;
        }

        .product-name {
            font-weight: 500;
            color: var(--forest);
        }

        .price-text {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            color: var(--sage);
            font-size: 0.9rem;
        }

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 100px;
            font-size: 0.72rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            flex-shrink: 0;
        }

        .status-menunggu  { background: #FEF9C3; color: #A16207; }
        .status-diproses  { background: #DBEAFE; color: #1D4ED8; }
        .status-siap      { background: rgba(134,239,172,0.2); color: var(--pine); }
        .status-dikembalikan { background: rgba(134,239,172,0.35); color: var(--sage); }
        .status-selesai   { background: var(--sand); color: var(--mist); }

        /* Action buttons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 100px;
            font-size: 0.76rem;
            font-weight: 700;
            font-family: 'Syne', sans-serif;
            text-decoration: none;
            transition: all 0.25s;
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .action-proses      { background: #DBEAFE; color: #1D4ED8; }
        .action-proses:hover { background: #1D4ED8; color: white; }

        .action-siap        { background: rgba(134,239,172,0.2); color: var(--pine); }
        .action-siap:hover  { background: var(--sage); color: white; }

        .action-kembali     { background: #FEF9C3; color: #A16207; }
        .action-kembali:hover { background: #D97706; color: white; }

        .action-selesai     { background: var(--sand); color: var(--mist); }
        .action-selesai:hover { background: var(--charcoal); color: white; }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 72px 24px;
        }

        .empty-icon {
            width: 72px;
            height: 72px;
            background: var(--cream);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--mist);
            margin: 0 auto 20px;
        }

        .empty-state h5 {
            color: var(--forest);
            font-size: 1.05rem;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--mist);
            font-size: 0.875rem;
            margin-bottom: 24px;
        }

        .btn-empty {
            background: var(--forest);
            color: white !important;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 12px 24px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-empty:hover {
            background: var(--sage);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(46,125,50,0.3);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .stats-row.admin-stats { grid-template-columns: 1fr 1fr; }
            .page-body { padding: 24px 16px; }
            .topbar { padding: 16px 20px; }
        }

        @media (max-width: 576px) {
            .stats-row.admin-stats,
            .stats-row.user-stats { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar">
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
            <a href="index.php"    class="nav-item"><i class="bi bi-house-door"></i> Beranda</a>
            <a href="catalog.php"  class="nav-item"><i class="bi bi-bag"></i> Sewa Alat</a>
            <a href="dashboard.php" class="nav-item active"><i class="bi bi-speedometer2"></i> Dashboard</a>

            <div class="nav-section-label">Akun</div>
            <a href="logout.php" class="nav-item danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </nav>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">

        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-title">
                <i class="bi bi-<?= $role == 'admin' ? 'speedometer2' : 'box-seam' ?>"></i>
                <?= $role == 'admin' ? 'Kelola Pesanan' : 'Pesanan Saya' ?>
            </div>
            <?php if($role != 'admin'): ?>
                <a href="catalog.php" class="btn-sewa-now">
                    <i class="bi bi-plus-lg"></i> Sewa Alat
                </a>
            <?php endif; ?>
        </div>

        <!-- Page body -->
        <div class="page-body">

            <!-- ===== STAT CARDS ===== -->
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
                    <div class="stat-sub">Perlu dikonfirmasi</div>
                </div>
                <?php endif; ?>
            </div>

            <!-- ===== TABLE ===== -->
            <div class="table-card">
                <div class="table-card-header">
                    <h4 class="table-card-title">
                        <?= $role == 'admin' ? 'Daftar Pesanan User' : 'Riwayat Pesanan' ?>
                    </h4>
                    <span class="table-count">
                        <b><?= $total ?></b> pesanan
                    </span>
                </div>

                <?php if($total > 0): ?>
                <div style="overflow-x:auto">
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
                            <?php foreach($orders as $o): ?>
                            <tr>
                                <td><span class="order-id">#<?= $o['id'] ?></span></td>

                                <?php if($role == 'admin'): ?>
                                <td>
                                    <div class="user-name"><?= htmlspecialchars($o['nama']) ?></div>
                                    <div class="user-email"><?= htmlspecialchars($o['email']) ?></div>
                                </td>
                                <?php endif; ?>

                                <td><span class="product-name"><?= htmlspecialchars($o['nama_produk']) ?></span></td>
                                <td><?= date('d M Y', strtotime($o['tanggal_sewa'])) ?></td>
                                <td><?= date('d M Y', strtotime($o['tanggal_kembali'])) ?></td>
                                <td><span class="price-text">Rp <?= number_format($o['total_harga']) ?></span></td>
                                <td>
                                    <?php
                                    $cls = 'status-selesai';
                                    if($o['status'] == 'Menunggu Konfirmasi') $cls = 'status-menunggu';
                                    elseif($o['status'] == 'Diproses')        $cls = 'status-diproses';
                                    elseif($o['status'] == 'Siap Diambil')    $cls = 'status-siap';
                                    elseif($o['status'] == 'Dikembalikan')    $cls = 'status-dikembalikan';
                                    ?>
                                    <span class="status-badge <?= $cls ?>"><?= htmlspecialchars($o['status']) ?></span>
                                </td>

                                <?php if($role == 'admin'): ?>
                                <td>
                                    <?php if($o['status'] == 'Menunggu Konfirmasi'): ?>
                                        <a href="?action=Diproses&id=<?= $o['id'] ?>" class="action-btn action-proses">
                                            <i class="bi bi-arrow-right-circle"></i> Proses
                                        </a>
                                    <?php elseif($o['status'] == 'Diproses'): ?>
                                        <a href="?action=Siap Diambil&id=<?= $o['id'] ?>" class="action-btn action-siap">
                                            <i class="bi bi-check-circle"></i> Siap
                                        </a>
                                    <?php elseif($o['status'] == 'Siap Diambil'): ?>
                                        <a href="?action=Dikembalikan&id=<?= $o['id'] ?>" class="action-btn action-kembali">
                                            <i class="bi bi-arrow-return-left"></i> Kembali
                                        </a>
                                    <?php elseif($o['status'] == 'Dikembalikan'): ?>
                                        <a href="?action=Selesai&id=<?= $o['id'] ?>" class="action-btn action-selesai">
                                            <i class="bi bi-flag-fill"></i> Selesai
                                        </a>
                                    <?php else: ?>
                                        <span style="color:var(--mist);font-size:0.8rem">—</span>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                    <h5>Belum ada pesanan</h5>
                    <p>Silakan sewa alat camping terlebih dahulu</p>
                    <a href="catalog.php" class="btn-empty">
                        <i class="bi bi-bag-plus"></i> Sewa Sekarang
                    </a>
                </div>
                <?php endif; ?>
            </div>

        </div><!-- /page-body -->
    </div><!-- /main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>