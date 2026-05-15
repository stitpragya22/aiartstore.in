<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #8b5cf6; }
        .details { margin-bottom: 20px; }
        .details table { width: 100%; }
        .details td { padding: 5px; }
        table.items { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table.items th { background: #8b5cf6; color: white; padding: 10px; text-align: left; }
        table.items td { padding: 10px; border-bottom: 1px solid #ddd; }
        .total { text-align: right; font-size: 16px; font-weight: bold; margin-top: 20px; }
        .footer { text-align: center; margin-top: 40px; color: #999; font-size: 11px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h1>AI Art Store</h1>
            <p>Invoice #<?= esc($order['invoice']['invoice_no'] ?? $order['order_number']) ?></p>
        </div>
        <div class="details">
            <table>
                <tr><td><strong>Order #:</strong></td><td><?= esc($order['order_number']) ?></td></tr>
                <tr><td><strong>Date:</strong></td><td><?= date('F d, Y', strtotime($order['created_at'])) ?></td></tr>
                <tr><td><strong>Payment:</strong></td><td><?= ucfirst($order['payment_method']) ?> (<?= ucfirst($order['payment_status']) ?>)</td></tr>
            </table>
        </div>
        <table class="items">
            <thead>
                <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                <tr>
                    <td><?= esc($item['product_name']) ?></td>
                    <td>₹<?= number_format($item['price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>₹<?= number_format($item['subtotal'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="total">Total: ₹<?= number_format($order['total'], 2) ?></div>
        <div class="footer">
            <p>Thank you for your purchase! | AI Art Store</p>
            <p>This is a computer-generated invoice.</p>
        </div>
    </div>
</body>
</html>
