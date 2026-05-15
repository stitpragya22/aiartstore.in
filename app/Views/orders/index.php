<?= view('layouts/header') ?>

<section class="py-5">
    <div class="container">
        <h1 class="section-title mb-4">My Orders</h1>

        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <i class="bi bi-box"></i>
                <h4>No orders yet</h4>
                <p class="text-muted">Start shopping to see your orders here</p>
                <a href="<?= site_url('/shop') ?>" class="btn btn-primary-custom"><i class="bi bi-grid me-2"></i>Browse Gallery</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-art">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong class="text-white"><?= esc($order['order_number']) ?></strong></td>
                            <td class="text-muted"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
                            <td class="price-tag"><?= formatPrice($order['total']) ?></td>
                            <td>
                                <span class="badge-status <?= $order['payment_status'] === 'completed' ? 'completed' : 'pending' ?>">
                                    <?= ucfirst($order['payment_status']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-status <?= $order['status'] ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td><a href="<?= site_url('/orders/' . $order['order_number']) ?>" class="btn btn-sm btn-primary-custom">View</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= view('layouts/footer') ?>
