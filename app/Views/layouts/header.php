<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'AI Art Store' ?> | AI Art Store</title>
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
            --gradient-2: linear-gradient(135deg, #1a1a2e, #16213e);
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-secondary); }
        ::-webkit-scrollbar-thumb { background: var(--accent-primary); border-radius: 4px; }

        .navbar {
            background: rgba(10, 10, 15, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-brand i { -webkit-text-fill-color: initial; color: var(--accent-primary); margin-right: 8px; }

        .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500;
            transition: all 0.3s;
            position: relative;
            padding: 0.5rem 1rem !important;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--text-primary) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--gradient-1);
            transition: width 0.3s;
        }

        .nav-link:hover::after, .nav-link.active::after { width: 60%; }

        .btn-cart {
            position: relative;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 0.5rem 1rem;
            border-radius: 12px;
            transition: all 0.3s;
        }

        .btn-cart:hover {
            background: var(--bg-card-hover);
            border-color: var(--accent-primary);
            box-shadow: 0 0 20px var(--accent-glow);
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--gradient-1);
            color: white;
            font-size: 0.65rem;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .btn-primary-custom {
            background: var(--gradient-1);
            border: none;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--accent-glow);
            color: white;
        }

        .btn-outline-custom {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-outline-custom:hover {
            border-color: var(--accent-primary);
            background: var(--bg-card);
            color: var(--text-primary);
        }

        .card-art {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s;
        }

        .card-art:hover {
            transform: translateY(-8px);
            border-color: var(--accent-primary);
            box-shadow: 0 20px 60px rgba(0,0,0,0.4), 0 0 40px var(--accent-glow);
        }

        .card-art .art-image {
            height: 280px;
            object-fit: cover;
            transition: transform 0.6s;
        }

        .card-art:hover .art-image { transform: scale(1.05); }

        .card-art .art-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.4s;
            display: flex;
            align-items: flex-end;
            padding: 1rem;
        }

        .card-art:hover .art-overlay { opacity: 1; }

        .watermark-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(139, 92, 246, 0.8);
            color: white;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
            font-weight: 500;
        }

        .section-title {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .section-subtitle { color: var(--text-secondary); font-size: 1.1rem; }

        .hero-section {
            background: var(--bg-secondary);
            padding: 6rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, var(--accent-glow) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            border-color: var(--accent-primary);
            transform: translateY(-4px);
        }

        .stat-card .stat-number {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
            padding: 3rem 0;
            margin-top: auto;
        }

        .price-tag {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            color: var(--accent-secondary);
        }

        .old-price {
            text-decoration: line-through;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .form-control, .form-select {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 12px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            background: var(--bg-card);
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px var(--accent-glow);
            color: var(--text-primary);
        }

        .form-control::placeholder { color: var(--text-muted); }

        .table-art {
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .table-art th {
            border-color: var(--border-color);
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .table-art td { border-color: var(--border-color); vertical-align: middle; }

        .table-art > :not(caption) > * > * { background: transparent; color: var(--text-primary); }

        .alert-custom {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
        }

        .alert-custom.alert-success { border-left: 4px solid var(--success); }
        .alert-custom.alert-danger { border-left: 4px solid var(--danger); }
        .alert-custom.alert-info { border-left: 4px solid var(--accent-primary); }

        .page-link {
            background: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .page-link:hover {
            background: var(--bg-card-hover);
            border-color: var(--accent-primary);
            color: var(--text-primary);
        }

        .page-item.active .page-link {
            background: var(--gradient-1);
            border-color: var(--accent-primary);
        }

        .toast-container { z-index: 9999; }

        .badge-status {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .badge-status.completed { background: rgba(16, 185, 129, 0.2); color: var(--success); }
        .badge-status.pending { background: rgba(245, 158, 11, 0.2); color: var(--warning); }
        .badge-status.processing { background: rgba(99, 102, 241, 0.2); color: #818cf8; }
        .badge-status.cancelled { background: rgba(239, 68, 68, 0.2); color: var(--danger); }

        .product-detail-image {
            border-radius: 20px;
            max-height: 500px;
            width: 100%;
            object-fit: cover;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .section-title { font-size: 1.8rem; }
            .hero-section { padding: 3rem 0; }
            .card-art .art-image { height: 200px; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= site_url('/') ?>">
                <i class="bi bi-stars"></i>AI Art Store
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link <?= current_url() == site_url('/') ? 'active' : '' ?>" href="<?= site_url('/') ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link <?= strpos(current_url(), '/shop') !== false ? 'active' : '' ?>" href="<?= site_url('/shop') ?>">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link <?= strpos(current_url(), '/orders') !== false ? 'active' : '' ?>" href="<?= site_url('/orders') ?>">Orders</a></li>
                    <li class="nav-item"><a class="nav-link <?= strpos(current_url(), '/downloads') !== false ? 'active' : '' ?>" href="<?= site_url('/downloads') ?>">Downloads</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    <a href="<?= site_url('/cart') ?>" class="btn btn-cart">
                        <i class="bi bi-bag"></i>
                        <span class="cart-badge" id="cartCount"><?= getCartCount() ?></span>
                    </a>
                    <?php if (auth()->loggedIn()): ?>
                        <?php $user = auth()->user(); ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-custom dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i><?= esc($user->username ?? $user->email) ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" style="background: var(--bg-card); border-color: var(--border-color);">
                                <?php if ($user->can('admin.access')): ?>
                                    <li><a class="dropdown-item" href="<?= site_url('/admin') ?>"><i class="bi bi-speedometer2 me-2"></i>Admin Panel</a></li>
                                    <li><hr class="dropdown-divider" style="border-color: var(--border-color);"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="<?= site_url('/orders') ?>"><i class="bi bi-box me-2"></i>My Orders</a></li>
                                <li><a class="dropdown-item" href="<?= site_url('/downloads') ?>"><i class="bi bi-download me-2"></i>Downloads</a></li>
                                <li><hr class="dropdown-divider" style="border-color: var(--border-color);"></li>
                                <li><a class="dropdown-item" href="<?= site_url('/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= site_url('/login') ?>" class="btn btn-outline-custom">Login</a>
                        <a href="<?= site_url('/register') ?>" class="btn btn-primary-custom">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <main>
        <?php if (session()->has('message')): ?>
            <div class="container mt-3"><div class="alert alert-custom alert-success d-flex align-items-center"><i class="bi bi-check-circle-fill me-2"></i><?= session('message') ?></div></div>
        <?php endif; ?>
        <?php if (session()->has('error')): ?>
            <div class="container mt-3"><div class="alert alert-custom alert-danger d-flex align-items-center"><i class="bi bi-exclamation-circle-fill me-2"></i><?= session('error') ?></div></div>
        <?php endif; ?>
        <?php if (session()->has('errors')): ?>
            <div class="container mt-3"><div class="alert alert-custom alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= implode('<br>', session('errors')) ?></div></div>
        <?php endif; ?>
