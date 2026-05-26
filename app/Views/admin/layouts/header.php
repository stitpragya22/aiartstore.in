<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= esc($title ?? 'Dashboard') ?> | AI Art Store Admin</title>
    <link rel="icon" type="image/png" href="<?= base_url('/favicon.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #12121a;
            --bg-card: #1a1a2e;
            --bg-card-hover: #222240;
            --accent-primary: #8b5cf6;
            --accent-secondary: #a78bfa;
            --text-primary: #f1f1f6;
            --text-secondary: #a0a0b8;
            --text-muted: #6b6b80;
            --border-color: #2a2a40;
            --sidebar-width: 250px;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            overflow-x: hidden;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-secondary); }
        ::-webkit-scrollbar-thumb { background: var(--accent-primary); border-radius: 3px; }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-color);
            padding: 1.5rem;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-brand {
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            display: block;
            margin-bottom: 2rem;
        }

        .sidebar .nav-item { margin-bottom: 0.25rem; }

        .sidebar .nav-link {
            color: var(--text-secondary);
            padding: 0.7rem 1rem;
            border-radius: 10px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: var(--bg-card);
            color: var(--text-primary);
        }

        .sidebar .nav-link.active {
            border-left: 3px solid var(--accent-primary);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }

        .top-bar {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-admin {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s;
        }

        .card-admin:hover {
            border-color: var(--accent-primary);
        }

        .stat-card-admin {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
        }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            #sidebarToggle.active { left: 262px; transition: left 0.3s ease; }
        }
        .stat-card-admin .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .form-control, .form-select {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 10px;
            padding: 0.6rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            background: var(--bg-card);
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(139,92,246,0.15);
            color: var(--text-primary);
        }

        .form-control::placeholder { color: var(--text-muted); }

        .btn-primary-custom {
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            border: none;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139,92,246,0.3);
            color: white;
        }

        .btn-outline-custom {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-outline-custom:hover {
            border-color: var(--accent-primary);
            background: var(--bg-card);
            color: var(--text-primary);
        }

        .table-admin {
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .table-admin th {
            border-color: var(--border-color);
            color: var(--text-secondary);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .table-admin td { border-color: var(--border-color); vertical-align: middle; }
        .table-admin > :not(caption) > * > * { background: transparent; color: var(--text-primary); }

        .badge-status {
            padding: 0.3rem 0.7rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.7rem;
        }

        .badge-status.active, .badge-status.completed { background: rgba(16,185,129,0.2); color: var(--success); }
        .badge-status.inactive, .badge-status.pending { background: rgba(245,158,11,0.2); color: var(--warning); }
        .badge-status.processing { background: rgba(99,102,241,0.2); color: #818cf8; }
        .badge-status.cancelled { background: rgba(239,68,68,0.2); color: var(--danger); }

        .alert-custom {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
        }

        .alert-custom.alert-success { border-left: 4px solid var(--success); }
        .alert-custom.alert-danger { border-left: 4px solid var(--danger); }

        .page-title { font-size: 1.5rem; font-weight: 700; }
    </style>
</head>
<body>
    <button id="sidebarToggle" class="btn d-lg-none" style="position:fixed;top:12px;left:12px;z-index:1100;background:var(--bg-card);color:var(--text-primary);border:1px solid var(--border-color);border-radius:10px;padding:6px 10px;" onclick="$('.sidebar').toggleClass('open');$(this).toggleClass('active')">
        <i class="bi bi-list fs-5"></i>
    </button>
    <aside class="sidebar">
        <a href="<?= site_url('/admin') ?>" class="sidebar-brand"><i class="bi bi-stars me-2"></i>AI Art Store</a>
        <nav>
            <div class="nav-item">
                <a href="<?= site_url('/admin') ?>" class="nav-link <?= current_url() == site_url('/admin') ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i>Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= site_url('/admin/products') ?>" class="nav-link <?= strpos(current_url(), '/admin/products') !== false ? 'active' : '' ?>">
                    <i class="bi bi-image"></i>Products
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= site_url('/admin/categories') ?>" class="nav-link <?= strpos(current_url(), '/admin/categories') !== false ? 'active' : '' ?>">
                    <i class="bi bi-tags"></i>Categories
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= site_url('/admin/orders') ?>" class="nav-link <?= strpos(current_url(), '/admin/orders') !== false ? 'active' : '' ?>">
                    <i class="bi bi-box"></i>Orders
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= site_url('/admin/users') ?>" class="nav-link <?= strpos(current_url(), '/admin/users') !== false ? 'active' : '' ?>">
                    <i class="bi bi-people"></i>Users
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= site_url('/admin/settings') ?>" class="nav-link <?= strpos(current_url(), '/admin/settings') !== false ? 'active' : '' ?>">
                    <i class="bi bi-gear"></i>Settings
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= site_url('/admin/blog/posts') ?>" class="nav-link <?= strpos(current_url(), '/admin/blog/posts') !== false ? 'active' : '' ?>">
                    <i class="bi bi-pencil-square"></i>Blog Posts
                </a>
            </div>
            <div class="nav-item" style="padding-left: 2.3rem;">
                <a href="<?= site_url('/admin/blog/categories') ?>" class="nav-link <?= strpos(current_url(), '/admin/blog/categories') !== false ? 'active' : '' ?>" style="font-size:0.85rem;">
                    <i class="bi bi-tag"></i>Categories
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= site_url('/admin/landing-pages') ?>" class="nav-link <?= strpos(current_url(), '/admin/landing-pages') !== false ? 'active' : '' ?>">
                    <i class="bi bi-megaphone"></i>Landing Pages
                </a>
            </div>
            <div class="nav-item">
                <a href="<?= site_url('/admin/coupons') ?>" class="nav-link <?= strpos(current_url(), '/admin/coupons') !== false ? 'active' : '' ?>">
                    <i class="bi bi-percent"></i>Coupons
                </a>
            </div>
            <hr style="border-color: var(--border-color);">
            <div class="nav-item">
                <a href="<?= site_url('/') ?>" class="nav-link"><i class="bi bi-house"></i>View Site</a>
            </div>
            <div class="nav-item">
                <a href="<?= site_url('/logout') ?>" class="nav-link"><i class="bi bi-box-arrow-right"></i>Logout</a>
            </div>
        </nav>
    </aside>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title mb-0"><?= esc($title ?? 'Dashboard') ?></h1>
            <div>
                <a href="<?= site_url('/') ?>" class="btn btn-outline-custom btn-sm me-2"><i class="bi bi-house"></i> View Site</a>
                <a href="<?= site_url('/logout') ?>" class="btn btn-outline-custom btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>

        <?php if (session()->has('message')): ?>
            <div class="alert alert-custom alert-success d-flex align-items-center mb-4"><i class="bi bi-check-circle-fill me-2"></i><?= esc(session('message')) ?></div>
        <?php endif; ?>
        <?php if (session()->has('error')): ?>
            <div class="alert alert-custom alert-danger d-flex align-items-center mb-4"><i class="bi bi-exclamation-circle-fill me-2"></i><?= esc(session('error')) ?></div>
        <?php endif; ?>
        <?php if (session()->has('errors')): ?>
            <div class="alert alert-custom alert-danger mb-4"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= implode('<br>', array_map('esc', session('errors') ?? [])) ?></div>
        <?php endif; ?>
