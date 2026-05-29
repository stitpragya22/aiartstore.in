<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Dynamic Header Code (e.g. Analytics, CSS, etc.) -->
    <?= get_custom_setting('custom_css') ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | AI Art Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #12121a;
            --bg-card: #1a1a2e;
            --bg-card-hover: #222240;
            --accent-primary: #8b5cf6;
            --accent-secondary: #a78bfa;
            --accent-glow: rgba(139, 92, 246, 0.3);
            --text-primary: #f1f1f6;
            --text-secondary: #a0a0b8;
            --text-muted: #6b6b80;
            --border-color: #2a2a40;
            --gradient-1: linear-gradient(135deg, #8b5cf6, #6366f1);
            --success: #10b981;
            --danger: #ef4444;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 440px;
        }
        .auth-title {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.75rem;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        .auth-subtitle {
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }
        .auth-logo {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .auth-logo a {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
        }
        .auth-logo i {
            -webkit-text-fill-color: initial;
            color: var(--accent-primary);
            margin-right: 8px;
        }
        .form-control {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 12px;
            padding: 0.75rem 1rem;
        }
        .form-control:focus {
            background: var(--bg-secondary);
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px var(--accent-glow);
            color: var(--text-primary);
        }
        .form-control::placeholder { color: var(--text-muted); }
        .form-label { color: var(--text-secondary); font-weight: 500; font-size: 0.9rem; }
        .btn-primary-custom {
            background: var(--gradient-1);
            border: none;
            color: white;
            padding: 0.75rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--accent-glow);
            color: white;
        }
        .btn-google {
            background: white;
            color: #333;
            border: 1px solid #ddd;
            padding: 0.75rem;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-google:hover {
            background: #f8f9fa;
            border-color: #ccc;
        }
        .divider {
            display: flex;
            align-items: center;
            color: var(--text-muted);
            font-size: 0.85rem;
            margin: 1.5rem 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }
        .divider::before { margin-right: 1rem; }
        .divider::after { margin-left: 1rem; }
        .alert-custom {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
        }
        .alert-custom.alert-danger { border-left: 4px solid var(--danger); }
        .alert-custom.alert-success { border-left: 4px solid var(--success); }
        .auth-footer {
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-top: 1.5rem;
        }
        .auth-footer a { color: var(--accent-secondary); text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }
        .form-check-label { color: var(--text-secondary); font-size: 0.9rem; }
        .form-check-input {
            background: var(--bg-secondary);
            border-color: var(--border-color);
        }
        .form-check-input:checked {
            background: var(--accent-primary);
            border-color: var(--accent-primary);
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <a href="<?= site_url('/') ?>"><i class="bi bi-stars"></i>AI Art Store</a>
        </div>
        <div class="auth-title">Welcome Back</div>
        <div class="auth-subtitle">Sign in to access your purchases and downloads</div>

        <?php if (session('error') !== null): ?>
            <div class="alert alert-custom alert-danger" role="alert"><?= esc(session('error')) ?></div>
        <?php elseif (session('errors') !== null): ?>
            <div class="alert alert-custom alert-danger" role="alert">
                <?php if (is_array(session('errors'))): ?>
                    <?php foreach (session('errors') as $error): ?>
                        <?= esc($error) ?><br>
                    <?php endforeach ?>
                <?php else: ?>
                    <?= esc(session('errors')) ?>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if (session('message') !== null): ?>
            <div class="alert alert-custom alert-success" role="alert"><?= esc(session('message')) ?></div>
        <?php endif ?>

        <a href="<?= site_url('/auth/google/login') ?>" class="btn-google">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Continue with Google
        </a>

        <div class="divider">or sign in with email</div>

        <form action="<?= url_to('login') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="floatingEmailInput" class="form-label">Email</label>
                <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="your@email.com" value="<?= old('email') ?>" required>
            </div>

            <div class="mb-3">
                <label for="floatingPasswordInput" class="form-label">Password</label>
                <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="current-password" placeholder="Enter your password" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="rememberCheck">
                <label class="form-check-label" for="rememberCheck">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary-custom">Sign In</button>
        </form>

        <div class="auth-footer">
            <?php if (setting('Auth.allowRegistration')): ?>
                Don't have an account? <a href="<?= url_to('register') ?>">Sign up</a><br>
            <?php endif ?>
            <?php if (setting('Auth.allowMagicLinkLogins')): ?>
                <a href="<?= url_to('magic-link') ?>">Forgot your password?</a>
            <?php endif ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Dynamic Footer Code (e.g. Analytics, JS, etc.) -->
    <?= get_custom_setting('custom_js') ?>
</body>
</html>
