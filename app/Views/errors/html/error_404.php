<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | AI Art Store</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #12121a;
            --accent-primary: #8b5cf6;
            --text-primary: #f1f1f6;
            --text-secondary: #a0a0b8;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container { text-align: center; padding: 2rem; max-width: 500px; }
        .error-code { font-size: 8rem; font-weight: 800; background: linear-gradient(135deg, #8b5cf6, #6366f1); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; line-height: 1; }
        .error-title { font-size: 1.5rem; font-weight: 700; margin: 1rem 0 0.5rem; }
        .error-text { color: var(--text-secondary); margin-bottom: 2rem; line-height: 1.6; }
        .btn-home {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139,92,246,0.3);
            color: #fff;
        }
        .glow-orb {
            position: fixed;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139,92,246,0.15), transparent 70%);
            top: -100px;
            right: -100px;
            pointer-events: none;
        }
        .glow-orb-2 {
            position: fixed;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,0.1), transparent 70%);
            bottom: -150px;
            left: -150px;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="glow-orb"></div>
    <div class="glow-orb-2"></div>
    <div class="error-container">
        <div class="error-code">404</div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-text">Looks like you've wandered somewhere the AI hasn't generated yet. The page you're looking for doesn't exist or has been moved.</p>
        <a href="<?= site_url('/') ?>" class="btn-home">Back to Home</a>
        <div style="margin-top: 2rem;">
            <a href="<?= site_url('/shop') ?>" style="color: var(--text-secondary); text-decoration: underline; font-size: 0.9rem;">Browse Gallery</a>
            <span style="color: var(--text-secondary); margin: 0 0.5rem;">|</span>
            <a href="<?= site_url('/blog') ?>" style="color: var(--text-secondary); text-decoration: underline; font-size: 0.9rem;">Read Blog</a>
        </div>
    </div>
</body>
</html>
