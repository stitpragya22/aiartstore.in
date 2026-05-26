<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f5f5f5;margin:0;padding:0}
.container{max-width:600px;margin:0 auto;padding:20px}
.header{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;padding:30px;text-align:center;border-radius:12px 12px 0 0}
.header h1{margin:0;font-size:24px}
.body{background:#fff;padding:30px;border-radius:0 0 12px 12px}
.footer{text-align:center;padding:20px;color:#9ca3af;font-size:12px}
</style></head>
<body>
<div class="container">
<div class="header"><h1>Invoice</h1><p>#<?= esc($order['order_number']) ?></p></div>
<div class="body">
<p>Your invoice is attached to this email as a PDF.</p>
<p>Order #: <?= esc($order['order_number']) ?><br>
Date: <?= date('d M Y', strtotime($order['created_at'])) ?><br>
Total: ₹<?= number_format($order['total'], 2) ?></p>
<p>You can also download your invoice anytime from your account dashboard.</p>
</div>
<div class="footer"><p>&copy; <?= date('Y') ?> AI Art Store. All rights reserved.</p></div>
</div>
</body>
</html>
