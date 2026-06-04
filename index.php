<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiapTrek - Sewa Alat Camping Terpercaya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ===== ROOT ===== */
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

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--charcoal);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, .brand-text {
            font-family: 'Syne', sans-serif;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: rgba(247, 244, 238, 0.85) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(46, 125, 50, 0.12);
            padding: 1rem 0;
            transition: all 0.4s ease;
        }

        .navbar.scrolled {
            background: rgba(247, 244, 238, 0.97) !important;
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

        .nav-link {
            color: var(--charcoal) !important;
            font-weight: 500;
            font-size: 0.9rem;
            letter-spacing: 0.01em;
            padding: 0.5rem 1rem !important;
            position: relative;
            transition: color 0.3s;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 1rem;
            right: 1rem;
            height: 2px;
            background: var(--sage);
            border-radius: 2px;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .nav-link:hover { color: var(--sage) !important; }
        .nav-link:hover::after { transform: scaleX(1); }

        .btn-nav-outline {
            border: 1.5px solid var(--sage);
            color: var(--sage) !important;
            border-radius: 100px;
            padding: 0.45rem 1.2rem !important;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-nav-outline:hover {
            background: var(--sage);
            color: white !important;
        }

        .btn-nav-filled {
            background: var(--sage);
            color: white !important;
            border-radius: 100px;
            padding: 0.45rem 1.2rem !important;
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            transition: all 0.3s;
        }

        .btn-nav-filled:hover {
            background: var(--pine);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(46, 125, 50, 0.3);
        }

        /* ===== HERO ===== */
        .hero-section {
            min-height: 100vh;
            position: relative;
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
        }

        .hero-left {
            background: var(--forest);
            display: flex;
            align-items: center;
            padding: 120px 80px 80px 10%;
            position: relative;
            z-index: 2;
        }

        .hero-left::after {
            content: '';
            position: absolute;
            right: -60px;
            top: 0;
            bottom: 0;
            width: 120px;
            background: var(--forest);
            clip-path: polygon(0 0, 0% 100%, 100% 100%);
            z-index: 3;
        }

        .hero-right {
            position: relative;
            overflow: hidden;
        }

        .hero-right img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.75) saturate(0.9);
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15, 34, 24, 0.5) 0%, transparent 60%);
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(134, 239, 172, 0.15);
            border: 1px solid rgba(134, 239, 172, 0.3);
            color: var(--mint);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 100px;
            margin-bottom: 28px;
            animation: fadeInUp 0.8s ease both;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4.2rem);
            font-weight: 800;
            color: var(--white);
            line-height: 1.08;
            letter-spacing: -0.03em;
            margin-bottom: 24px;
            animation: fadeInUp 0.8s ease 0.1s both;
        }

        .hero-title span {
            color: var(--mint);
            display: block;
        }

        .hero-sub {
            color: rgba(255,255,255,0.65);
            font-size: 1.05rem;
            line-height: 1.65;
            margin-bottom: 40px;
            max-width: 380px;
            font-weight: 300;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .hero-cta {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease 0.3s both;
        }

        .btn-hero-primary {
            background: var(--mint);
            color: var(--forest) !important;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 14px 28px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            border: none;
        }

        .btn-hero-primary:hover {
            background: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(134, 239, 172, 0.35);
            color: var(--forest) !important;
        }

        .btn-hero-secondary {
            background: transparent;
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 14px 28px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1.5px solid rgba(255,255,255,0.25);
            transition: all 0.3s;
        }

        .btn-hero-secondary:hover {
            border-color: rgba(255,255,255,0.6);
            color: white !important;
            background: rgba(255,255,255,0.08);
        }

        .hero-stats {
            display: flex;
            gap: 40px;
            margin-top: 56px;
            padding-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.1);
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        .stat-item {}
        .stat-number {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: var(--mint);
            line-height: 1;
        }
        .stat-label {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* Floating info on hero right */
        .hero-floating-card {
            position: absolute;
            bottom: 48px;
            left: 40px;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: fadeInUp 1s ease 0.5s both;
        }

        .hfc-icon {
            width: 44px;
            height: 44px;
            background: var(--sage);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .hfc-text strong {
            font-family: 'Syne', sans-serif;
            font-size: 0.95rem;
            color: var(--charcoal);
            display: block;
        }

        .hfc-text span {
            font-size: 0.78rem;
            color: var(--mist);
        }

        /* ===== FEATURE STRIP ===== */
        .feature-strip {
            background: var(--moss);
            padding: 28px 0;
        }

        .feature-strip-inner {
            display: flex;
            justify-content: center;
            gap: 60px;
            flex-wrap: wrap;
        }

        .fstrip-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.85);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .fstrip-item i {
            color: var(--mint);
            font-size: 1.2rem;
        }

        /* ===== SECTION HEADERS ===== */
        .section-eyebrow {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--sage);
            margin-bottom: 12px;
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(1.8rem, 3vw, 2.8rem);
            font-weight: 800;
            color: var(--forest);
            letter-spacing: -0.03em;
            line-height: 1.15;
        }

        /* ===== PRODUCTS SECTION ===== */
        .products-section {
            padding: 100px 0;
            background: var(--cream);
        }

        .products-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 56px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .btn-view-all {
            background: transparent;
            border: 1.5px solid var(--sage);
            color: var(--sage);
            font-family: 'Syne', sans-serif;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 10px 22px;
            border-radius: 100px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-view-all:hover {
            background: var(--sage);
            color: white;
        }

        /* Product Card */
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
        }

        .product-price small {
            font-family: 'DM Sans', sans-serif;
            font-weight: 400;
            font-size: 0.75rem;
            color: var(--mist);
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
        }

        .btn-sewa:hover {
            background: var(--sage);
            transform: scale(1.04);
        }

        .btn-sewa-outline {
            background: transparent;
            color: var(--sage) !important;
            border: 1.5px solid var(--sage);
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
        }

        .btn-sewa-outline:hover {
            background: var(--sage);
            color: white !important;
        }

        /* ===== ABOUT SECTION ===== */
        .about-section {
            padding: 100px 0;
            background: var(--white);
            overflow: hidden;
        }

        .about-img-wrap {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
        }

        .about-img-wrap img {
            width: 100%;
            height: 520px;
            object-fit: cover;
            border-radius: 24px;
        }

        .about-accent {
            position: absolute;
            bottom: -20px;
            right: -20px;
            width: 160px;
            height: 160px;
            background: var(--mint);
            border-radius: 50%;
            opacity: 0.25;
            z-index: 0;
        }

        .about-content { padding-left: 40px; }

        .about-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 40px;
        }

        .about-stat-card {
            background: var(--cream);
            border-radius: 16px;
            padding: 24px;
        }

        .about-stat-num {
            font-family: 'Syne', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--sage);
        }

        .about-stat-label {
            font-size: 0.85rem;
            color: var(--mist);
            margin-top: 4px;
        }

        .about-features {
            margin-top: 36px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .about-feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 0.9rem;
            color: var(--charcoal);
            font-weight: 500;
        }

        .about-feature-icon {
            width: 36px;
            height: 36px;
            background: rgba(46, 125, 50, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sage);
            flex-shrink: 0;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--forest);
            color: rgba(255,255,255,0.75);
            padding: 72px 0 32px;
        }

        .footer-brand {
            font-family: 'Syne', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .footer-brand-icon {
            width: 38px;
            height: 38px;
            background: var(--sage);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .footer-desc {
            font-size: 0.875rem;
            line-height: 1.65;
            max-width: 260px;
        }

        .footer-social {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .footer-social a {
            width: 38px;
            height: 38px;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.6);
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
        }

        .footer-social a:hover {
            border-color: var(--mint);
            color: var(--mint);
            background: rgba(134, 239, 172, 0.08);
        }

        .footer-heading {
            font-family: 'Syne', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .footer-links a:hover { color: var(--mint); }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.875rem;
            color: rgba(255,255,255,0.7);
            margin-bottom: 12px;
        }

        .footer-contact-item i {
            color: var(--mint);
            margin-top: 2px;
            flex-shrink: 0;
        }

        .footer-divider {
            border-color: rgba(255,255,255,0.08);
            margin: 40px 0 24px;
        }

        .footer-bottom {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.35);
            text-align: center;
        }

        /* ===== WHATSAPP FLOAT ===== */
        .whatsapp-float {
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 58px;
            height: 58px;
            background: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            text-decoration: none;
            box-shadow: 0 8px 32px rgba(37, 211, 102, 0.45);
            z-index: 1000;
            transition: all 0.3s;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 40px rgba(37, 211, 102, 0.55);
            color: white;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .hero-section {
                grid-template-columns: 1fr;
                min-height: auto;
            }
            .hero-left {
                padding: 140px 40px 64px;
            }
            .hero-left::after { display: none; }
            .hero-right { height: 360px; }
            .about-content { padding-left: 0; margin-top: 40px; }
            .about-img-wrap img { height: 360px; }
            .products-header { justify-content: flex-start; }
        }

        @media (max-width: 576px) {
            .hero-left { padding: 120px 24px 56px; }
            .hero-stats { gap: 24px; }
            .feature-strip-inner { gap: 28px; }
            .hero-floating-card { bottom: 24px; left: 20px; right: 20px; }
        }
    </style>
</head>
<body>
    <?php
    $gambar_db = [
        'Tenda Dome 4 Orang' => 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=400&h=300&fit=crop',
        'Sleeping Bag Winter' => 'https://images.unsplash.com/photo-1510672981848-a1c4f1cb5f05?w=400&h=300&fit=crop',
        'Carrier Osprey 60L' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=300&fit=crop',
        'Matras Camping' => 'https://images.unsplash.com/photo-1523987355523-c7b5b0dd90a7?w=400&h=300&fit=crop',
        'Kompor Portable + Tabung' => 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=400&h=300&fit=crop',
        'Headlamp' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?w=400&h=300&fit=crop',
        'Jas Hujan Ponco' => 'https://images.unsplash.com/photo-1605218427306-6354db696faa?w=400&h=300&fit=crop',
        'Kacamata Outdoor' => 'https://images.unsplash.com/photo-1572635196237-14b0f281f9cb?w=400&h=300&fit=crop',
        'Kursi Lipat Speeds' => 'https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?w=400&h=300&fit=crop',
        'Lampu Sorot' => 'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?w=400&h=300&fit=crop',
        'Power Bank 10000mAh' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=400&h=300&fit=crop',
        'Sarung Tangan Outdoor' => 'https://www.eigeradventure.com/blog/wp-content/uploads/2025/09/All-New-Scaldino-3.jpg',
        'Sepatu Gunung' => 'https://asset.kompas.com/crops/GUV-aiZ0M3A2SVzGKeGIRNk0MU8=/0x183:828x735/750x500/data/photo/2024/04/05/660f7d7039a5b.jpeg',
        'Celana Outdoor' => 'https://img.lazcdn.com/g/p/0186457f518a5b4a10063cd88519f0d6.jpg_720x720q80.jpg',
        'Jaket Gorpcore' => 'https://down-id.img.susercontent.com/file/id-11134207-7rbk1-mav81yq5jf6ad8',
        'Manset' => 'https://antarestar.com/wp-content/uploads/2022/08/Desain-tanpa-judul-11.png'
    ];
    
    function getGambar($nama_produk, $gambar_db) {
        if(isset($gambar_db[$nama_produk])) { return $gambar_db[$nama_produk]; }
        return 'https://via.placeholder.com/400x250/2E7D32/ffffff?text=' . urlencode($nama_produk);
    }
    ?>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg sticky-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <div class="brand-icon"><i class="bi bi-campground"></i></div>
                SiapTrek
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#produk">Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="catalog.php" class="btn-nav-filled"><i class="bi bi-bag me-1"></i> Sewa Sekarang</a>
                        <a href="logout.php" class="btn-nav-outline">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn-nav-outline">Login</a>
                        <a href="register.php" class="btn-nav-filled">Daftar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <section id="beranda" class="hero-section">
        <!-- Left dark panel -->
        <div class="hero-left">
            <div>
                <div class="hero-badge">
                    <i class="bi bi-patch-check-fill"></i>
                    Terpercaya & Berkualitas
                </div>
                <h1 class="hero-title">
                    Petualangan<br>
                    <span>Tanpa Batas</span>
                    Dimulai<br>Di Sini.
                </h1>
                <p class="hero-sub">
                    Sewa alat camping terlengkap dengan harga terjangkau. Siap antar ke lokasi kamu di Medan & sekitarnya.
                </p>
                <div class="hero-cta">
                    <a href="catalog.php" class="btn-hero-primary">
                        <i class="bi bi-compass-fill"></i> Lihat Katalog
                    </a>
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <a href="register.php" class="btn-hero-secondary">
                            <i class="bi bi-person-plus"></i> Daftar Gratis
                        </a>
                    <?php endif; ?>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Pelanggan Puas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">15+</div>
                        <div class="stat-label">Jenis Alat</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Siap Melayani</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right image panel -->
        <div class="hero-right">
            <img src="img/sibayak.jpg" alt="Camping Adventure">
            <div class="hero-overlay"></div>
            <div class="hero-floating-card">
                <div class="hfc-icon"><i class="bi bi-shield-check"></i></div>
                <div class="hfc-text">
                    <strong>Kualitas Terjamin</strong>
                    <span>Semua alat dicek sebelum sewa</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FEATURE STRIP ===== -->
    <div class="feature-strip">
        <div class="container">
            <div class="feature-strip-inner">
                <div class="fstrip-item">
                    <i class="bi bi-shield-check"></i>
                    <span>Alat Berkualitas</span>
                </div>
                <div class="fstrip-item">
                    <i class="bi bi-currency-exchange"></i>
                    <span>Harga Terjangkau</span>
                </div>
                <div class="fstrip-item">
                    <i class="bi bi-clock-history"></i>
                    <span>Siap 24 Jam</span>
                </div>
                <div class="fstrip-item">
                    <i class="bi bi-truck"></i>
                    <span>Antar Jemput Tersedia</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== PRODUK UNGGULAN ===== -->
    <section id="produk" class="products-section">
        <div class="container">
            <div class="products-header">
                <div>
                    <p class="section-eyebrow">Koleksi Kami</p>
                    <h2 class="section-title">Produk Unggulan</h2>
                </div>
                <a href="catalog.php" class="btn-view-all">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="row g-4">
                <?php
                $stmt = $pdo->query("SELECT * FROM products WHERE stok > 0 ORDER BY id DESC LIMIT 6");
                $products = $stmt->fetchAll();
                foreach($products as $i => $p):
                ?>
                <div class="col-lg-4 col-md-6 reveal" style="transition-delay: <?= $i * 0.07 ?>s">
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <img src="<?= getGambar($p['nama_produk'], $gambar_db) ?>" class="product-img" alt="<?= htmlspecialchars($p['nama_produk']) ?>">
                            <div class="product-stok-badge"><i class="bi bi-box-seam me-1"></i>Stok: <?= (int)$p['stok'] ?></div>
                        </div>
                        <div class="product-body">
                            <h5 class="product-name"><?= htmlspecialchars($p['nama_produk']) ?></h5>
                            <p class="product-desc"><?= htmlspecialchars($p['deskripsi']) ?></p>
                            <div class="product-footer">
                                <div>
                                    <div class="product-price">Rp <?= number_format($p['harga_sewa']) ?></div>
                                    <small class="text-muted" style="font-size:0.75rem">per hari</small>
                                </div>
                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <a href="catalog.php" class="btn-sewa">Sewa <i class="bi bi-arrow-right"></i></a>
                                <?php else: ?>
                                    <a href="login.php" class="btn-sewa-outline">Login</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ===== TENTANG ===== -->
    <section id="tentang" class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="about-img-wrap reveal">
                        <img src="img/fotosaya.jpg" alt="SiapTrek Team">
                        <div class="about-accent"></div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="about-content reveal">
                        <p class="section-eyebrow">Siapa Kami</p>
                        <h2 class="section-title">Tentang SiapTrek</h2>
                        <p class="mt-20 text-muted" style="margin-top:20px;font-size:0.95rem;line-height:1.7">
                            SiapTrek hadir untuk memudahkan pecinta alam mendapatkan peralatan camping berkualitas tanpa harus beli. Kami menyediakan alat terlengkap dengan harga yang sangat terjangkau, siap antar ke lokasi kamu.
                        </p>

                        <div class="about-stats">
                            <div class="about-stat-card">
                                <div class="about-stat-num">500+</div>
                                <div class="about-stat-label">Pelanggan Puas</div>
                            </div>
                            <div class="about-stat-card">
                                <div class="about-stat-num">15+</div>
                                <div class="about-stat-label">Jenis Alat Tersedia</div>
                            </div>
                        </div>

                        <div class="about-features">
                            <div class="about-feature-item">
                                <div class="about-feature-icon"><i class="bi bi-patch-check-fill"></i></div>
                                <span>Semua alat dicek kualitasnya sebelum disewakan</span>
                            </div>
                            <div class="about-feature-item">
                                <div class="about-feature-icon"><i class="bi bi-headset"></i></div>
                                <span>Support 24 jam via WhatsApp siap membantu kamu</span>
                            </div>
                            <div class="about-feature-item">
                                <div class="about-feature-icon"><i class="bi bi-geo-alt-fill"></i></div>
                                <span>Melayani area Medan & sekitarnya, antar jemput tersedia</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <div class="footer-brand-icon"><i class="bi bi-campground"></i></div>
                        SiapTrek
                    </div>
                    <p class="footer-desc">Partner terbaik untuk petualangan camping Anda. Kualitas terpercaya, harga terjangkau.</p>
                    <div class="footer-social">
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="https://wa.me/6283160722123" target="_blank"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <p class="footer-heading">Navigasi</p>
                    <ul class="footer-links">
                        <li><a href="#beranda">Beranda</a></li>
                        <li><a href="#produk">Produk</a></li>
                        <li><a href="#tentang">Tentang Kami</a></li>
                        <li><a href="catalog.php">Katalog Lengkap</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <p class="footer-heading">Kontak Kami</p>
                    <div class="footer-contact-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Medan, Sumatera Utara, Indonesia</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-whatsapp"></i>
                        <span>0831-6072-2123</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <span>rezkantal1@gmail.com</span>
                    </div>
                </div>
            </div>
            <hr class="footer-divider">
            <p class="footer-bottom">&copy; 2026 SiapTrek. All rights reserved.</p>
        </div>
    </footer>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/6283160722123" class="whatsapp-float" target="_blank">
        <i class="bi bi-whatsapp"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('mainNavbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 40);
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
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        revealEls.forEach(el => observer.observe(el));
    </script>
</body>
</html>