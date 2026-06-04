<?php
include 'config.php';

$error = "";

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        
        if($user['role'] == 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: catalog.php");
        }
        exit;
    } else {
        $error = "Email atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiapTrek</title>
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

        /* Brand */
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

        /* Panel headline */
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
            font-size: clamp(2rem, 3.5vw, 3rem);
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

        /* Feature list */
        .panel-features {
            margin-top: 48px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .panel-feature {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 0.875rem;
            color: rgba(255,255,255,0.7);
            font-weight: 400;
        }

        .pf-icon {
            width: 34px;
            height: 34px;
            background: rgba(134, 239, 172, 0.1);
            border: 1px solid rgba(134, 239, 172, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--mint);
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        /* Panel footer */
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
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            animation: fadeInRight 0.6s ease both;
        }

        .login-heading {
            font-size: 1.9rem;
            font-weight: 800;
            color: var(--forest);
            letter-spacing: -0.03em;
            margin-bottom: 8px;
        }

        .login-subheading {
            font-size: 0.875rem;
            color: var(--mist);
            margin-bottom: 40px;
            font-weight: 400;
        }

        /* Error alert */
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

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-family: 'Syne', sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--forest);
            letter-spacing: 0.02em;
            margin-bottom: 8px;
            display: block;
        }

        .input-wrap {
            position: relative;
        }

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
            padding: 14px 16px 14px 44px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            color: var(--charcoal);
            outline: none;
            transition: all 0.3s ease;
        }

        .form-input::placeholder { color: rgba(107,114,128,0.6); }

        .form-input:focus {
            border-color: var(--sage);
            box-shadow: 0 0 0 4px rgba(46, 125, 50, 0.1);
        }

        .form-input:focus + .input-icon,
        .input-wrap:focus-within .input-icon {
            color: var(--sage);
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

        /* Submit button */
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

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 28px 0;
            color: rgba(107,114,128,0.5);
            font-size: 0.78rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(0,0,0,0.08);
        }

        /* Register link */
        .register-prompt {
            text-align: center;
            font-size: 0.875rem;
            color: var(--mist);
        }

        .register-prompt a {
            color: var(--sage);
            font-weight: 700;
            text-decoration: none;
            font-family: 'Syne', sans-serif;
            transition: color 0.3s;
        }

        .register-prompt a:hover { color: var(--pine); text-decoration: underline; }

        /* Back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            color: var(--mist);
            text-decoration: none;
            margin-bottom: 36px;
            transition: color 0.3s;
        }

        .back-link:hover { color: var(--sage); }

        /* Animation */
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

            .left-panel {
                display: none;
            }

            .right-panel {
                padding: 48px 28px;
                min-height: 100vh;
            }
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
            <p class="panel-eyebrow">Selamat Datang</p>
            <h2 class="panel-title">Petualangan<br><span>Menanti</span><br>Kamu.</h2>
            <p class="panel-desc">Masuk untuk mengakses katalog lengkap alat camping berkualitas terbaik.</p>

            <div class="panel-features">
                <div class="panel-feature">
                    <div class="pf-icon"><i class="bi bi-shield-check-fill"></i></div>
                    <span>Alat camping terjamin kualitasnya</span>
                </div>
                <div class="panel-feature">
                    <div class="pf-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                    <span>Proses pemesanan cepat & mudah</span>
                </div>
                <div class="panel-feature">
                    <div class="pf-icon"><i class="bi bi-headset"></i></div>
                    <span>Support 24/7 siap membantu kamu</span>
                </div>
            </div>
        </div>

        <div class="panel-footer">&copy; 2026 SiapTrek. All rights reserved.</div>
    </div>

    <!-- ===== RIGHT PANEL ===== -->
    <div class="right-panel">
        <div class="login-box">
            <a href="index.php" class="back-link">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>

            <h1 class="login-heading">Masuk Akun</h1>
            <p class="login-subheading">Masukkan email dan password kamu untuk melanjutkan</p>

            <?php if($error): ?>
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" autocomplete="off">
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
                            placeholder="Masukkan password"
                            required
                        >
                        <i class="bi bi-lock input-icon"></i>
                        <button type="button" class="pass-toggle" onclick="togglePassword()" id="passToggleBtn">
                            <i class="bi bi-eye" id="passIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" name="login" class="btn-submit">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
                </button>
            </form>

            <div class="divider">atau</div>

            <p class="register-prompt">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
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
    </script>
</body>
</html>