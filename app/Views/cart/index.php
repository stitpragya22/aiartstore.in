<?= view('layouts/header') ?>

<section class="py-5">
    <div class="container">
        <h1 class="section-title mb-4">Shopping Cart</h1>

        <?php if (empty($cart)): ?>
            <div class="empty-state">
                <i class="bi bi-bag-x"></i>
                <h4>Your cart is empty</h4>
                <p class="text-muted">Explore our gallery and add some art to your cart</p>
                <a href="<?= site_url('/shop') ?>" class="btn btn-primary-custom"><i class="bi bi-grid me-2"></i>Browse Gallery</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-art">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <?php if ($item['image']): ?>
                                        <img src="<?= base_url('uploads/products/' . $item['image']) ?>" alt="<?= esc($item['title']) ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; filter: blur(2px);">
                                    <?php endif; ?>
                                    <div>
                                        <a href="<?= site_url('/shop/' . $item['slug']) ?>" class="text-white text-decoration-none fw-semibold"><?= esc($item['title']) ?></a>
                                    </div>
                                </div>
                            </td>
                            <td><?= formatPrice($item['price']) ?></td>
                            <td>
                                <form action="<?= site_url('/cart/update') ?>" method="POST" class="d-flex align-items-center gap-2">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="form-control" style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-outline-custom"><i class="bi bi-arrow-repeat"></i></button>
                                </form>
                            </td>
                            <td class="price-tag"><?= formatPrice($item['price'] * $item['quantity']) ?></td>
                            <td><a href="<?= site_url('/cart/remove/' . $item['id']) ?>" class="btn btn-sm btn-outline-danger" style="border-color: rgba(239,68,68,0.3); color: var(--danger);" onclick="return confirm('Remove this item?')"><i class="bi bi-trash"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row justify-content-end mt-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold"><?= formatPrice($total) ?></span>
                        </div>
                        <hr style="border-color: var(--border-color);">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="price-tag fs-5"><?= formatPrice($total) ?></span>
                        </div>
                        <a href="<?= site_url('/checkout') ?>" class="btn btn-primary-custom w-100 btn-lg">Proceed to Checkout <i class="bi bi-arrow-right ms-1"></i></a>
                        <a href="<?= site_url('/shop') ?>" class="btn btn-outline-custom w-100 mt-2">Continue Shopping</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= view('layouts/footer') ?>
