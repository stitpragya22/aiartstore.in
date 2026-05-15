<?= view('admin/layouts/header') ?>

<div class="card-admin">
    <?php if (empty($orders)): ?>
        <p class="text-muted mb-0">No orders yet.</p>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-admin">
            <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><strong><?= esc($order['order_number']) ?></strong></td>
                    <td><?= esc($order['username'] ?? $order['email'] ?? 'N/A') ?></td>
                    <td class="price-tag"><?= formatPrice($order['total']) ?></td>
                    <td><span class="badge-status <?= $order['payment_status'] === 'completed' ? 'completed' : 'pending' ?>"><?= ucfirst($order['payment_status']) ?></span></td>
                    <td><span class="badge-status <?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                    <td class="text-muted"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                    <td><a href="<?= site_url('/admin/orders/' . $order['id']) ?>" class="btn btn-sm btn-primary-custom">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?= view('admin/layouts/footer') ?>
