<?php
include 'config.php';

$error = "";
$success = "";

if(isset($_POST['daftar'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->execute([$nama, $email, $password]);
        $success = "Pendaftaran berhasil! Silakan <a href='login.php' style='color:var(--pine);font-weight:700'>Login di sini</a>";
    } catch(PDOException $e) {
        $error = "Email sudah terdaftar!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SiapTrek</title>
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

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
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
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 360px;
            height: 360px;
            background: radial-gradient(circle, rgba(134,239,172,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(46,125,50,0.18) 0%, transparent 70%);
            border-radius: 50%;
        }

        .panel-content {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: 64px;
            position: relative;
            z-index: 2;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: var(--sage);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
        }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            letter-spacing: -0.02em;
        }

        .panel-eyebrow {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--mint);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .panel-eyebrow::before {
            content: '';
            display: inline-block;
            width: 20px;
            height: 2px;
            background: var(--mint);
            border-radius: 2px;
        }

        .panel-title {
            font-size: clamp(2rem, 3.2vw, 2.8rem);
            font-weight: 800;
            color: white;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .panel-title span { color: var(--mint); }

        .panel-desc {
            font-size: 0.92rem;
            color: rgba(255,255,255,0.5);
            line-height: 1.7;
            font-weight: 300;
            max-width: 300px;
        }

        /* Steps */
        .panel-steps {
            margin-top: 48px;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .panel-step {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            position: relative;
            padding-bottom: 28px;
        }

        .panel-step:last-child { padding-bottom: 0; }

        .panel-step:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 16px;
            top: 34px;
            bottom: 0;
            width: 1px;
            background: rgba(134,239,172,0.15);
        }

        .step-num {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(134,239,172,0.12);
            border: 1px solid rgba(134,239,172,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: 0.8rem;
            font-weight: 800;
            color: var(--mint);
            flex-shrink: 0;
        }

        .step-text strong {
            display: block;
            font-family: 'Syne', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            color: rgba(255,255,255,0.85);
            margin-bottom: 2px;
        }

        .step-text span {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.4);
        }

        .panel-footer {
            position: relative;
            z-index: 2;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.25);
        }

        /* ===== RIGHT PANEL ===== */
        .right-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 56px;
            background: var(--cream);
            overflow-y: auto;
        }

        .register-box {
            width: 100%;
            max-width: 400px;
            animation: fadeInRight 0.6s ease both;
        }

        .register-heading {
            font-size: 1.9rem;
            font-weight: 800;
            color: var(--forest);
            letter-spacing: -0.03em;
            margin-bottom: 8px;
        }

        .register-subheading {
            font-size: 0.875rem;
            color: var(--mist);
            margin-bottom: 36px;
            font-weight: 400;
        }

        /* Alerts */
        .alert-error {
            background: rgba(220, 38, 38, 0.07);
            border: 1px solid rgba(220, 38, 38, 0.2);
            color: #B91C1C;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(134, 239, 172, 0.12);
            border: 1px solid rgba(134, 239, 172, 0.35);
            color: var(--pine);
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            font-weight: 500;
        }

        /* Form */
        .form-group { margin-bottom: 18px; }

        .form-label {
            font-family: 'Syne', sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--forest);
            letter-spacing: 0.02em;
            margin-bottom: 8px;
            display: block;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--mist);
            font-size: 1rem;
            pointer-events: none;
            transition: color 0.3s;
        }

        .form-input {
            width: 100%;
            background: var(--white);
            border: 1.5px solid rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 13px 16px 13px 44px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            color: var(--charcoal);
            outline: none;
            transition: all 0.3s ease;
        }

        .form-input::placeholder { color: rgba(107,114,128,0.55); }

        .form-input:focus {
            border-color: var(--sage);
            box-shadow: 0 0 0 4px rgba(46, 125, 50, 0.1);
        }

        .form-input:focus ~ .input-icon,
        .input-wrap:focus-within .input-icon { color: var(--sage); }

        /* Password strength bar */
        .pass-strength {
            margin-top: 8px;
            display: none;
        }

        .pass-strength.show { display: block; }

        .strength-bar {
            height: 4px;
            border-radius: 4px;
            background: var(--sand);
            overflow: hidden;
            margin-bottom: 4px;
        }

        .strength-fill {
            height: 100%;
            border-radius: 4px;
            width: 0%;
            transition: all 0.4s ease;
        }

        .strength-label {
            font-size: 0.72rem;
            font-weight: 600;
        }

        /* Password toggle */
        .pass-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--mist);
            cursor: pointer;
            font-size: 1rem;
            padding: 4px;
            transition: color 0.3s;
        }

        .pass-toggle:hover { color: var(--sage); }

        /* Submit */
        .btn-submit {
            width: 100%;
            background: var(--forest);
            color: white;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 15px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
        }

        .btn-submit:hover {
            background: var(--sage);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(46, 125, 50, 0.3);
        }

        .btn-submit:active { transform: translateY(0); }

        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 24px 0;
            color: rgba(107,114,128,0.5);
            font-size: 0.78rem;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(0,0,0,0.08);
        }

        .login-prompt {
            text-align: center;
            font-size: 0.875rem;
            color: var(--mist);
        }

        .login-prompt a {
            color: var(--sage);
            font-weight: 700;
            text-decoration: none;
            font-family: 'Syne', sans-serif;
            transition: color 0.3s;
        }

        .login-prompt a:hover { color: var(--pine); text-decoration: underline; }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            color: var(--mist);
            text-decoration: none;
            margin-bottom: 32px;
            transition: color 0.3s;
        }

        .back-link:hover { color: var(--sage); }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(24px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            body {
                grid-template-columns: 1fr;
                overflow: auto;
                min-height: 100vh;
            }
            .left-panel { display: none; }
            .right-panel { padding: 48px 28px; min-height: 100vh; }
        }
    </style>
</head>
<body>

    <!-- ===== LEFT PANEL ===== -->
    <div class="left-panel">
        <a href="index.php" class="brand">
            <div class="brand-icon"><i class="bi bi-campground"></i></div>
            <span class="brand-name">SiapTrek</span>
        </a>

        <div class="panel-content">
            <p class="panel-eyebrow">Mulai Sekarang</p>
            <h2 class="panel-title">Buat Akun,<br><span>Jelajahi</span><br>Alam Bebas.</h2>
            <p class="panel-desc">Daftar gratis dan nikmati akses ke ratusan alat camping berkualitas kapan saja.</p>

            <div class="panel-steps">
                <div class="panel-step">
                    <div class="step-num">1</div>
                    <div class="step-text">
                        <strong>Buat Akun Gratis</strong>
                        <span>Isi nama, email & password kamu</span>
                    </div>
                </div>
                <div class="panel-step">
                    <div class="step-num">2</div>
                    <div class="step-text">
                        <strong>Pilih Alat Camping</strong>
                        <span>Browse katalog lengkap kami</span>
                    </div>
                </div>
                <div class="panel-step">
                    <div class="step-num">3</div>
                    <div class="step-text">
                        <strong>Sewa & Petualangan</strong>
                        <span>Terima alat, langsung berangkat!</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-footer">&copy; 2026 SiapTrek. All rights reserved.</div>
    </div>

    <!-- ===== RIGHT PANEL ===== -->
    <div class="right-panel">
        <div class="register-box">
            <a href="index.php" class="back-link">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>

            <h1 class="register-heading">Buat Akun</h1>
            <p class="register-subheading">Daftar gratis dan mulai petualanganmu bersama SiapTrek</p>

            <?php if($error): ?>
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <form method="POST" autocomplete="off">
                <!-- Nama -->
                <div class="form-group">
                    <label class="form-label" for="nama">Nama Lengkap</label>
                    <div class="input-wrap">
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            class="form-input"
                            placeholder="Nama lengkap kamu"
                            required
                            value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>"
                        >
                        <i class="bi bi-person input-icon"></i>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-wrap">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-input"
                            placeholder="contoh@email.com"
                            required
                            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                        >
                        <i class="bi bi-envelope input-icon"></i>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Minimal 8 karakter"
                            required
                            oninput="checkStrength(this.value)"
                        >
                        <i class="bi bi-lock input-icon"></i>
                        <button type="button" class="pass-toggle" onclick="togglePassword()" id="passToggleBtn">
                            <i class="bi bi-eye" id="passIcon"></i>
                        </button>
                    </div>
                    <!-- Password strength -->
                    <div class="pass-strength" id="passStrength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <span class="strength-label" id="strengthLabel"></span>
                    </div>
                </div>

                <button type="submit" name="daftar" class="btn-submit">
                    <i class="bi bi-person-plus"></i> Daftar Sekarang
                </button>
            </form>

            <div class="divider">atau</div>

            <p class="login-prompt">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('passIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        function checkStrength(val) {
            const wrap  = document.getElementById('passStrength');
            const fill  = document.getElementById('strengthFill');
            const label = document.getElementById('strengthLabel');

            if (!val) { wrap.classList.remove('show'); return; }
            wrap.classList.add('show');

            let score = 0;
            if (val.length >= 8)               score++;
            if (/[A-Z]/.test(val))             score++;
            if (/[0-9]/.test(val))             score++;
            if (/[^A-Za-z0-9]/.test(val))      score++;

            const levels = [
                { pct: '25%', color: '#EF4444', text: 'Lemah',   textColor: '#EF4444' },
                { pct: '50%', color: '#F59E0B', text: 'Cukup',   textColor: '#F59E0B' },
                { pct: '75%', color: '#3B82F6', text: 'Kuat',    textColor: '#3B82F6' },
                { pct: '100%',color: '#22C55E', text: 'Sangat Kuat', textColor: '#22C55E' },
            ];

            const lvl = levels[score - 1] || levels[0];
            fill.style.width      = lvl.pct;
            fill.style.background = lvl.color;
            label.textContent     = lvl.text;
            label.style.color     = lvl.textColor;
        }
    </script>
</body>
</html>