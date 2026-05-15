<?= view('layouts/header') ?>

<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background: transparent;">
                <li class="breadcrumb-item"><a href="<?= site_url('/') ?>" class="text-muted text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('/shop') ?>" class="text-muted text-decoration-none">Gallery</a></li>
                <?php if (isset($product['category_name'])): ?>
                <li class="breadcrumb-item"><a href="<?= site_url('/shop?category=' . $product['category_id']) ?>" class="text-muted text-decoration-none"><?= esc($product['category_name']) ?></a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active text-white" aria-current="page"><?= esc($product['title']) ?></li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-lg-7">
                <div class="position-relative">
                    <?php if ($product['image_watermarked']): ?>
                        <img src="<?= base_url('uploads/products/') ?><?= $product['image_watermarked'] ?>" class="product-detail-image" alt="<?= esc($product['title']) ?>">
                    <?php elseif ($product['image']): ?>
                        <img src="<?= base_url('uploads/products/') ?><?= $product['image'] ?>" class="product-detail-image" alt="<?= esc($product['title']) ?>" style="filter: blur(8px);">
                    <?php else: ?>
                        <div class="product-detail-image d-flex align-items-center justify-content-center" style="background: var(--bg-card);"><i class="bi bi-image fs-1 text-muted"></i></div>
                    <?php endif; ?>
                    <div class="watermark-badge" style="top: 20px; right: 20px; font-size: 0.85rem;"><i class="bi bi-water me-1"></i>Watermarked Preview</div>
                </div>
            </div>
            <div class="col-lg-5">
                <span class="badge bg-primary bg-opacity-10 text-white mb-2 px-3 py-2" style="background: var(--accent-glow) !important; border-radius: 20px;">
                    <?= esc($product['category_name'] ?? 'Uncategorized') ?>
                </span>
                <h1 class="display-6 fw-bold mt-2"><?= esc($product['title']) ?></h1>

                <div class="d-flex align-items-center gap-3 mt-3">
                    <span class="price-tag fs-2"><?= formatPrice($product['price']) ?></span>
                    <?php if ($product['compare_price'] && $product['compare_price'] > $product['price']): ?>
                        <span class="old-price fs-5"><?= formatPrice($product['compare_price']) ?></span>
                        <span class="badge bg-danger" style="background: rgba(239, 68, 68, 0.2) !important; color: var(--danger);">Sale</span>
                    <?php endif; ?>
                </div>

                <?php if ($product['description']): ?>
                <div class="mt-4">
                    <h6 class="fw-bold">Description</h6>
                    <p class="text-secondary"><?= nl2br(esc($product['description'])) ?></p>
                </div>
                <?php endif; ?>

                <div class="d-flex gap-3 mt-4">
                    <div class="stat-card flex-grow-1">
                        <small class="text-muted">File Size</small>
                        <p class="mb-0 fw-bold"><?= esc($product['file_size'] ?? 'N/A') ?></p>
                    </div>
                    <div class="stat-card flex-grow-1">
                        <small class="text-muted">Dimensions</small>
                        <p class="mb-0 fw-bold"><?= esc($product['dimensions'] ?? 'N/A') ?></p>
                    </div>
                </div>

                <?php if ($product['tags']): ?>
                <div class="mt-4">
                    <h6 class="fw-bold">Tags</h6>
                    <div class="d-flex flex-wrap gap-1">
                        <?php foreach (explode(',', $product['tags']) as $tag): ?>
                            <a href="<?= site_url('/shop?search=' . urlencode(trim($tag))) ?>" class="btn btn-sm btn-outline-custom" style="border-radius: 20px;"><?= esc(trim($tag)) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="mt-4 p-3" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px;">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-info-circle" style="color: var(--accent-primary);"></i>
                        <small class="text-muted">You'll receive a high-resolution digital file after purchase. Preview image is watermarked.</small>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <div class="d-flex align-items-center" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 0.5rem;">
                        <button class="btn btn-link text-white text-decoration-none px-2" onclick="updateQty(-1)">-</button>
                        <input type="number" id="qty" value="1" min="1" class="form-control text-center" style="width: 60px; background: transparent; border: none; color: white; padding: 0;">
                        <button class="btn btn-link text-white text-decoration-none px-2" onclick="updateQty(1)">+</button>
                    </div>
                    <button class="btn btn-primary-custom flex-grow-1 btn-lg" onclick="addToCart(<?= $product['id'] ?>)">
                        <i class="bi bi-cart-plus me-2"></i>Add to Cart
                    </button>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <i class="bi bi-shield-check" style="color: var(--success);"></i>
                    <small class="text-muted">Secure checkout via Razorpay</small>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($related)): ?>
<section class="py-5" style="background: var(--bg-secondary);">
    <div class="container">
        <h2 class="section-title mb-5">Related Artworks</h2>
        <div class="row g-4">
            <?php foreach ($related as $product): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card-art position-relative">
                    <div class="position-relative overflow-hidden">
                        <?php if ($product['image_watermarked']): ?>
                            <img src="<?= base_url('uploads/products/') ?><?= $product['image_watermarked'] ?>" class="art-image w-100" alt="<?= esc($product['title']) ?>">
                        <?php endif; ?>
                        <div class="watermark-badge"><i class="bi bi-water me-1"></i>Preview</div>
                        <div class="art-overlay">
                            <a href="<?= site_url('/shop/' . $product['slug']) ?>" class="btn btn-primary-custom btn-sm w-100">View Details</a>
                        </div>
                    </div>
                    <div class="p-3">
                        <h6 class="fw-semibold"><?= esc($product['title']) ?></h6>
                        <span class="price-tag"><?= formatPrice($product['price']) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
function updateQty(amount) {
    var input = document.getElementById('qty');
    var val = parseInt(input.value) + amount;
    if (val < 1) val = 1;
    input.value = val;
}

function addToCart(id) {
    var qty = document.getElementById('qty').value;
    $.post('<?= site_url('/cart/add') ?>', { id: id, quantity: qty }, function(res) {
        if (res.status === 'success') {
            $('#cartCount').text(res.count);
            alert('Added to cart!');
        }
    });
}
</script>

<?= view('layouts/footer') ?>
