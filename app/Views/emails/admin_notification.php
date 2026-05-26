<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f5f5f5;margin:0;padding:0}
.container{max-width:600px;margin:0 auto;padding:20px}
.header{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:30px;text-align:center;border-radius:12px 12px 0 0}
.header h1{margin:0;font-size:24px}
.body{background:#fff;padding:30px;border-radius:0 0 12px 12px}
.footer{text-align:center;padding:20px;color:#9ca3af;font-size:12px}
</style></head>
<body>
<div class="container">
<div class="header"><h1>New Order Received!</h1><p>#<?= esc($order['order_number']) ?></p></div>
<div class="body">
<p>A new order has been placed on your store.</p>
<p><strong>Order:</strong> #<?= esc($order['order_number']) ?><br>
<strong>Customer:</strong> <?= esc($order['customer_email'] ?? 'N/A') ?><br>
<strong>Total:</strong> ₹<?= number_format($order['total'], 2) ?><br>
<strong>Date:</strong> <?= date('d M Y h:i A', strtotime($order['created_at'])) ?></p>
<p><a href="<?= site_url('/admin/orders/' . $order['id']) ?>">View Order in Admin</a></p>
</div>
<div class="footer"><p>&copy; <?= date('Y') ?> AI Art Store.</p></div>
</div>
</body>
</html>
