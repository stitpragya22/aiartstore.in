<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f5f5f5;margin:0;padding:0}
.container{max-width:600px;margin:0 auto;padding:20px}
.header{background:linear-gradient(135deg,#10b981,#059669);color:#fff;padding:30px;text-align:center;border-radius:12px 12px 0 0}
.header h1{margin:0;font-size:24px}
.body{background:#fff;padding:30px;border-radius:0 0 12px 12px}
.item{border:1px solid #e5e7eb;border-radius:8px;padding:16px;margin:12px 0}
.item h3{margin:0 0 8px 0}
.btn{display:inline-block;padding:12px 24px;background:linear-gradient(135deg,#8b5cf6,#6366f1);color:#fff;text-decoration:none;border-radius:8px;font-weight:600;margin:4px}
.footer{text-align:center;padding:20px;color:#9ca3af;font-size:12px}
</style></head>
<body>
<div class="container">
<div class="header"><h1>Your Downloads Are Ready!</h1><p>#<?= esc($order['order_number']) ?></p></div>
<div class="body">
<p>Your purchased items are now available for download. Click each item below to download:</p>

<?php foreach ($items as $item): ?>
<div class="item">
<h3><?= esc($item['product_name'] ?? $item['title']) ?></h3>
<?php if (!empty($item['download_url'])): ?>
<a href="<?= esc($item['download_url']) ?>" class="btn">Download File</a>
<?php endif; ?>
<a href="<?= site_url('/downloads') ?>" class="btn">View All Downloads</a>
</div>
<?php endforeach ?>

<p>All files are available in your <a href="<?= site_url('/downloads') ?>">downloads dashboard</a> for future access.</p>
</div>
<div class="footer"><p>&copy; <?= date('Y') ?> AI Art Store. All rights reserved.</p></div>
</div>
</body>
</html>
