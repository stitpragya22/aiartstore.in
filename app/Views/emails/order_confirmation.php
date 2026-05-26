<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f5f5f5;margin:0;padding:0}
.container{max-width:600px;margin:0 auto;padding:20px}
.header{background:linear-gradient(135deg,#8b5cf6,#6366f1);color:#fff;padding:30px;text-align:center;border-radius:12px 12px 0 0}
.header h1{margin:0;font-size:24px}
.body{background:#fff;padding:30px;border-radius:0 0 12px 12px}
.order-details{border:1px solid #e5e7eb;border-radius:8px;padding:16px;margin:16px 0}
.order-details table{width:100%;border-collapse:collapse}
.order-details th,.order-details td{padding:8px;text-align:left;border-bottom:1px solid #f3f4f6}
.order-details th{font-size:12px;text-transform:uppercase;color:#6b7280}
.btn{display:inline-block;padding:12px 24px;background:linear-gradient(135deg,#8b5cf6,#6366f1);color:#fff;text-decoration:none;border-radius:8px;font-weight:600;margin:8px 0}
.footer{text-align:center;padding:20px;color:#9ca3af;font-size:12px}
</style></head>
<body>
<div class="container">
<div class="header"><h1>Order Confirmed!</h1><p>#<?= esc($order['order_number']) ?></p></div>
<div class="body">
<p>Thank you for your purchase! Your order has been confirmed and your downloads are ready.</p>

<div class="order-details">
<table>
<tr><th>Product</th><th>Amount</th></tr>
<?php foreach ($items as $item): ?>
<tr><td><?= esc($item['product_name'] ?? $item['title']) ?></td><td>₹<?= number_format($item['price'], 2) ?></td></tr>
<?php endforeach ?>
<tr style="font-weight:700"><td>Total Paid</td><td>₹<?= number_format($order['total'], 2) ?></td></tr>
</table>
</div>

<p style="text-align:center"><a href="<?= site_url('/downloads') ?>" class="btn">Access Your Downloads</a></p>

<p>You can also download your invoice from your account dashboard.</p>
<p>If you have any questions, reply to this email or contact us.</p>
</div>
<div class="footer"><p>&copy; <?= date('Y') ?> AI Art Store. All rights reserved.</p></div>
</div>
</body>
</html>
