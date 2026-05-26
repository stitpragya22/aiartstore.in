<?= view('admin/layouts/header') ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-admin">
            <h5 class="fw-bold mb-3">Order Items</h5>
            <div class="table-responsive">
                <table class="table table-admin">
                    <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td><?= esc($item['product_name']) ?></td>
                            <td><?= formatPrice($item['price']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= formatPrice($item['subtotal']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card-admin">
            <h5 class="fw-bold mb-3">Download Access</h5>
            <?php if (empty($order['downloads'])): ?>
                <p class="text-muted mb-0">No download records for this order.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-admin">
                    <thead><tr><th>Product</th><th>Used</th><th>Expires</th><th>Status</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($order['downloads'] as $download): ?>
                        <?php
                            $isRevoked = !empty($download['revoked_at']);
                            $isExpired = !empty($download['expires_at']) && strtotime($download['expires_at']) < time();
                        ?>
                        <tr>
                            <td><?= esc($download['product_title']) ?></td>
                            <td><?= (int)$download['download_count'] ?> / <?= (int)$download['max_downloads'] ?: 'Unlimited' ?></td>
                            <td><?= $download['expires_at'] ? date('d M Y', strtotime($download['expires_at'])) : 'Never' ?></td>
                            <td>
                                <?php if ($isRevoked): ?>
                                    <span class="badge-status cancelled">Revoked</span>
                                <?php elseif ($isExpired): ?>
                                    <span class="badge-status pending">Expired</span>
                                <?php else: ?>
                                    <span class="badge-status completed">Active</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form action="<?= site_url('/admin/orders/reissue-download/' . $download['id']) ?>" method="POST">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-primary-custom" type="submit">Reissue</button>
                                    </form>
                                    <?php if (!$isRevoked): ?>
                                    <form action="<?= site_url('/admin/orders/revoke-download/' . $download['id']) ?>" method="POST">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Revoke</button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-admin">
            <h5 class="fw-bold mb-3">Order Details</h5>
            <div class="mb-2"><small class="text-muted">Order #</small><br><strong><?= esc($order['order_number']) ?></strong></div>
            <div class="mb-2"><small class="text-muted">Customer</small><br><strong><?= esc($order['user']['username'] ?? $order['user']['email'] ?? 'N/A') ?></strong></div>
            <div class="mb-2"><small class="text-muted">Email</small><br><?= esc($order['user']['email'] ?? 'N/A') ?></div>
            <div class="mb-2"><small class="text-muted">Date</small><br><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></div>
            <hr style="border-color: var(--border-color);">
            <div class="d-flex justify-content-between mb-1"><span class="text-muted">Subtotal</span><span><?= formatPrice($order['subtotal']) ?></span></div>
            <div class="d-flex justify-content-between mb-1"><span class="text-muted">Total</span><span class="price-tag fw-bold"><?= formatPrice($order['total']) ?></span></div>
            <hr style="border-color: var(--border-color);">
            <div class="mb-2"><small class="text-muted">Payment</small><br><span class="badge-status <?= $order['payment_status'] === 'completed' ? 'completed' : 'pending' ?>"><?= ucfirst($order['payment_status']) ?></span></div>
            <div class="mb-2"><small class="text-muted">Payment ID</small><br><small class="text-muted"><?= esc($order['payment_id'] ?? 'N/A') ?></small></div>
            <?php if ($order['invoice']): ?>
            <div class="mb-2"><small class="text-muted">Invoice</small><br><strong><?= esc($order['invoice']['invoice_no']) ?></strong></div>
            <?php endif; ?>
            <hr style="border-color: var(--border-color);">
            <form action="<?= site_url('/admin/orders/update-status/' . $order['id']) ?>" method="POST">
                <?= csrf_field() ?>
                <label class="form-label fw-semibold">Update Status</label>
                <div class="d-flex gap-2">
                    <select name="status" class="form-select">
                        <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                    <button type="submit" class="btn btn-primary-custom">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= view('admin/layouts/footer') ?>
