<?= view('layouts/header') ?>
<style>
.cart-item {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 1rem;
    transition: all 0.3s;
}
.cart-item:active { transform: scale(0.99); }
.cart-item-img {
    width: 72px;
    height: 72px;
    object-fit: cover;
    border-radius: 12px;
    filter: blur(2px);
    flex-shrink: 0;
}
.cart-checkout-bar {
    position: sticky;
    bottom: 0;
    background: rgba(10,10,15,0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-top: 1px solid var(--border-color);
    padding: 1rem;
    padding-bottom: max(1rem, env(safe-area-inset-bottom, 1rem));
    z-index: 100;
    margin: 0 -1rem;
}
@media (min-width: 768px) {
    .cart-checkout-bar { position: static; border-radius: 16px; margin: 0; background: var(--bg-card); backdrop-filter: none; }
}
@media (max-width: 767px) {
    .cart-table-head { display: none; }
    .cart-item { margin-bottom: 0.75rem; }
}
</style>

<section class="py-4">
    <div class="container">
        <h1 class="section-title mb-4" style="font-size:1.6rem;">Shopping Cart</h1>

        <?php if (empty($cart)): ?>
            <div class="empty-state">
                <i class="bi bi-bag-x"></i>
                <h4>Your cart is empty</h4>
                <p class="text-muted">Explore our gallery and add some art to your cart</p>
                <a href="<?= site_url('/shop') ?>" class="btn btn-primary-custom"><i class="bi bi-grid me-2"></i>Browse Gallery</a>
            </div>
        <?php else: ?>
            <div class="d-none d-md-block">
                <div class="table-responsive">
                    <table class="table table-art">
                        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
                        <tbody>
                            <?php foreach ($cart as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if ($item['image']): ?>
                                            <img src="<?= base_url('uploads/products/' . $item['image']) ?>" alt="<?= esc($item['title']) ?>" style="width:60px;height:60px;object-fit:cover;border-radius:8px;filter:blur(2px);">
                                        <?php endif; ?>
                                        <div>
                                            <a href="<?= site_url('/shop/' . $item['slug']) ?>" class="text-white text-decoration-none fw-semibold"><?= esc($item['title']) ?></a>
                                        </div>
                                    </div>
                                </td>
                                <td class="price-tag"><?= formatPrice($item['price']) ?></td>
                                <td><span class="fw-bold">1</span></td>
                                <td class="price-tag"><?= formatPrice($item['price']) ?></td>
                                <td>
                                    <form action="<?= site_url('/cart/remove/' . $item['id']) ?>" method="POST" onsubmit="return confirm('Remove this item?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-color:rgba(239,68,68,0.3);color:var(--danger);"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-block d-md-none">
                <?php foreach ($cart as $item): ?>
                <div class="cart-item d-flex align-items-center gap-3">
                    <?php if ($item['image']): ?>
                        <img src="<?= base_url('uploads/products/' . $item['image']) ?>" alt="<?= esc($item['title']) ?>" class="cart-item-img">
                    <?php endif; ?>
                    <div class="flex-grow-1 min-w-0">
                        <a href="<?= site_url('/shop/' . $item['slug']) ?>" class="text-white text-decoration-none fw-semibold" style="font-size:0.9rem;"><?= esc($item['title']) ?></a>
                        <div class="price-tag mt-1"><?= formatPrice($item['price']) ?></div>
                    </div>
                    <form action="<?= site_url('/cart/remove/' . $item['id']) ?>" method="POST" onsubmit="return confirm('Remove?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn" style="background:rgba(239,68,68,0.1);color:var(--danger);border-radius:12px;padding:10px 12px;border:none;"><i class="bi bi-trash3-fill"></i></button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="row justify-content-end mt-4">
                <div class="col-md-4">
                    <div class="cart-checkout-bar d-flex d-md-block align-items-center justify-content-between gap-3">
                        <div class="d-flex d-md-block justify-content-between align-items-center w-100">
                            <div>
                                <span class="text-muted small">Total</span>
                                <div class="price-tag fs-4 fw-bold"><?= formatPrice($total) ?></div>
                            </div>
                            <a href="<?= site_url('/checkout') ?>" class="btn btn-primary-custom btn-lg flex-grow-1 flex-md-grow-0 w-md-100 mt-md-3" style="border-radius:14px;font-size:1.05rem;">
                                Checkout <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <a href="<?= site_url('/shop') ?>" class="btn btn-outline-custom w-100 mt-2 d-none d-md-block">Continue Shopping</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= view('layouts/footer') ?>
