<?= view('layouts/header') ?>

<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="section-title mb-0">Order #<?= esc($order['order_number']) ?></h1>
                <p class="section-subtitle">Placed on <?= date('F d, Y \a\t h:i A', strtotime($order['created_at'])) ?></p>
            </div>
            <a href="<?= site_url('/orders') ?>" class="btn btn-outline-custom"><i class="bi bi-arrow-left me-1"></i>Back to Orders</a>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="stat-card">
                    <h5 class="fw-bold mb-3">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-art">
                            <thead>
                                <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item): ?>
                                <tr>
                                    <td><?= esc($item['product_name']) ?></td>
                                    <td><?= formatPrice($item['price']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td class="price-tag"><?= formatPrice($item['subtotal']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="stat-card">
                    <h5 class="fw-bold mb-3">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal</span><span><?= formatPrice($order['subtotal']) ?></span></div>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Tax</span><span><?= formatPrice($order['tax']) ?></span></div>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Discount</span><span><?= formatPrice($order['discount']) ?></span></div>
                    <hr style="border-color: var(--border-color);">
                    <div class="d-flex justify-content-between fs-5"><strong>Total</strong><span class="price-tag"><?= formatPrice($order['total']) ?></span></div>
                    <hr style="border-color: var(--border-color);">
                    <div class="mb-2"><small class="text-muted">Payment</small><br><span class="badge-status <?= $order['payment_status'] === 'completed' ? 'completed' : 'pending' ?>"><?= ucfirst($order['payment_status']) ?></span></div>
                    <div class="mb-2"><small class="text-muted">Order Status</small><br><span class="badge-status <?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></div>
                    <?php if ($order['invoice_no']): ?>
                    <div class="mb-2"><small class="text-muted">Invoice</small><br><strong><?= esc($order['invoice_no']) ?></strong></div>
                    <?php endif; ?>
                </div>

                <?php if ($order['status'] === 'completed'): ?>
                <div class="stat-card mt-3">
                    <h6 class="fw-bold mb-3"><i class="bi bi-download me-2" style="color: var(--success);"></i>Downloads</h6>
                    <?php foreach ($order['items'] as $item): ?>
                    <a href="<?= site_url('/downloads') ?>" class="btn btn-outline-custom btn-sm w-100 mb-2">
                        <i class="bi bi-download me-1"></i><?= esc($item['product_name']) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?= view('layouts/footer') ?>
