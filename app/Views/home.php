<?= view('layouts/header') ?>

<section class="hero-section">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge bg-primary bg-opacity-10 text-white mb-3 px-3 py-2" style="background: var(--accent-glow) !important; border-radius: 20px;">
                    <i class="bi bi-sparkles me-1"></i> AI-Powered Art
                </span>
                <h1 class="section-title display-4 fw-bold">
                    Discover Premium<br>
                    <span style="background: var(--gradient-1); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">AI Generated Art</span>
                </h1>
                <p class="text-secondary fs-5 mt-3">Explore thousands of unique AI-generated artworks. Download high-resolution digital files for your creative projects.</p>
                <div class="d-flex gap-3 mt-4">
                    <a href="<?= site_url('/shop') ?>" class="btn btn-primary-custom btn-lg"><i class="bi bi-grid me-2"></i>Browse Gallery</a>
                    <a href="#featured" class="btn btn-outline-custom btn-lg"><i class="bi bi-star me-2"></i>Featured Art</a>
                </div>
                <div class="d-flex gap-3 gap-md-4 mt-4 mt-md-5">
                    <div><span class="stat-number fs-5 fs-md-3 fw-bold" style="background: var(--gradient-1); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">500+</span><br><span class="text-secondary small">Artworks</span></div>
                    <div><span class="stat-number fs-5 fs-md-3 fw-bold" style="background: var(--gradient-1); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">50+</span><br><span class="text-secondary small">Artists</span></div>
                    <div><span class="stat-number fs-5 fs-md-3 fw-bold" style="background: var(--gradient-1); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">1000+</span><br><span class="text-secondary small">Happy Customers</span></div>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="position-relative">
                    <div style="background: var(--gradient-1); border-radius: 30px; padding: 4px;">
                        <img src="https://picsum.photos/seed/aiart/600/400" alt="AI Art" class="img-fluid rounded-4" style="border-radius: 28px !important;">
                    </div>
                    <div class="position-absolute bottom-0 start-0 translate-middle-y ms-4" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 1rem; backdrop-filter: blur(10px);">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check" style="color: var(--accent-primary); font-size: 1.5rem;"></i>
                            <div><small class="text-muted">Secure</small><br><strong>Watermarked Preview</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($categories)): ?>
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Browse Categories</h2>
            <p class="section-subtitle">Explore art by category</p>
        </div>
        <div class="row g-3">
            <?php foreach ($categories as $cat): ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="<?= site_url('/shop/category/' . $cat['slug']) ?>" class="text-decoration-none">
                    <div class="stat-card">
                        <i class="bi bi-palette fs-2" style="color: var(--accent-primary);"></i>
                        <h6 class="mt-2 mb-0"><?= esc($cat['name']) ?></h6>
                        <small class="text-muted"><?= $cat['product_count'] ?? 0 ?> items</small>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($featured)): ?>
<section class="py-5" id="featured" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-0">Featured Artworks</h2>
                <p class="section-subtitle">Curated picks for you</p>
            </div>
            <a href="<?= site_url('/shop') ?>" class="btn btn-outline-custom">View All <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach ($featured as $product): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card-art position-relative">
                    <button class="btn-wishlist <?= isProductWishlisted($product['id']) ? 'active' : '' ?>" onclick="toggleWishlist(<?= $product['id'] ?>, this)" title="Add to Wishlist">
                        <i class="bi <?= isProductWishlisted($product['id']) ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                    </button>
                    <div class="art-image-wrapper">
                        <?php $img = $product['image_watermarked'] ? base_url('uploads/products/' . $product['image_watermarked']) : ($product['image'] ? base_url('uploads/products/' . $product['image']) : null); ?>
                        <?php if ($img): ?>
                            <div class="art-image-bg" style="background-image: url('<?= str_replace("'", "%27", $img) ?>')"></div>
                            <img src="<?= $img ?>" class="art-image" alt="<?= esc($product['title']) ?>">
                        <?php else: ?>
                            <div class="art-image-placeholder"><i class="bi bi-image"></i></div>
                        <?php endif; ?>
                        <div class="watermark-badge"><i class="bi bi-water me-1"></i>Preview</div>
                        <div class="art-overlay">
                            <a href="<?= site_url('/shop/' . $product['slug']) ?>" class="btn btn-primary-custom btn-sm w-100">View Details</a>
                        </div>
                    </div>
                    <div class="p-3">
                        <small class="text-muted"><?= esc($product['category_name'] ?? 'Uncategorized') ?></small>
                        <h6 class="mt-1 fw-semibold"><?= esc($product['title']) ?></h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price-tag"><?= formatPrice($product['price']) ?></span>
                            <?php if (isProductPurchased($product['id'])): ?>
                                <span class="badge" style="background: rgba(34,197,94,0.2); color: var(--success); border-radius: 20px;"><i class="bi bi-check-circle me-1"></i>Owned</span>
                            <?php else: ?>
                            <button class="btn btn-sm btn-outline-custom add-to-cart" data-id="<?= $product['id'] ?>"><i class="bi bi-cart-plus"></i></button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($latest)): ?>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-0">Latest Additions</h2>
                <p class="section-subtitle">Newest AI art creations</p>
            </div>
        </div>
        <div class="row g-4">
            <?php foreach ($latest as $product): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card-art position-relative">
                    <button class="btn-wishlist <?= isProductWishlisted($product['id']) ? 'active' : '' ?>" onclick="toggleWishlist(<?= $product['id'] ?>, this)" title="Add to Wishlist">
                        <i class="bi <?= isProductWishlisted($product['id']) ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                    </button>
                    <div class="art-image-wrapper">
                        <?php $img = $product['image_watermarked'] ? base_url('uploads/products/' . $product['image_watermarked']) : ($product['image'] ? base_url('uploads/products/' . $product['image']) : null); ?>
                        <?php if ($img): ?>
                            <div class="art-image-bg" style="background-image: url('<?= str_replace("'", "%27", $img) ?>')"></div>
                            <img src="<?= $img ?>" class="art-image" alt="<?= esc($product['title']) ?>">
                        <?php else: ?>
                            <div class="art-image-placeholder"><i class="bi bi-image"></i></div>
                        <?php endif; ?>
                        <div class="watermark-badge"><i class="bi bi-water me-1"></i>Preview</div>
                        <div class="art-overlay">
                            <a href="<?= site_url('/shop/' . $product['slug']) ?>" class="btn btn-primary-custom btn-sm w-100">View Details</a>
                        </div>
                    </div>
                    <div class="p-3">
                        <small class="text-muted"><?= esc($product['category_name'] ?? 'Uncategorized') ?></small>
                        <h6 class="mt-1 fw-semibold"><?= esc($product['title']) ?></h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price-tag"><?= formatPrice($product['price']) ?></span>
                            <?php if (isProductPurchased($product['id'])): ?>
                                <span class="badge" style="background: rgba(34,197,94,0.2); color: var(--success); border-radius: 20px;"><i class="bi bi-check-circle me-1"></i>Owned</span>
                            <?php else: ?>
                            <button class="btn btn-sm btn-outline-custom add-to-cart" data-id="<?= $product['id'] ?>"><i class="bi bi-cart-plus"></i></button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="py-5" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card h-100">
                    <i class="bi bi-shield-check fs-1" style="color: var(--accent-primary);"></i>
                    <h5 class="mt-3 fw-bold">Watermarked Preview</h5>
                    <p class="text-muted mb-0">All preview images are protected with watermark. Download high-res files after purchase.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card h-100">
                    <i class="bi bi-lightning-charge fs-1" style="color: var(--accent-primary);"></i>
                    <h5 class="mt-3 fw-bold">Instant Download</h5>
                    <p class="text-muted mb-0">Get immediate access to your purchased files. Download anytime from your account.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card h-100">
                    <i class="bi bi-shield-lock fs-1" style="color: var(--accent-primary);"></i>
                    <h5 class="mt-3 fw-bold">Secure Payments</h5>
                    <p class="text-muted mb-0">Pay securely via Razorpay. Your payment information is always protected.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        var btn = $(this);
        var id = btn.data('id');
        btn.prop('disabled', true);
        $.post('<?= site_url('/cart/add') ?>', { id: id, quantity: 1 }, function(res) {
            if (res.status === 'success') {
                $('#cartCount').text(res.count);
                showToast('Added to cart!', 'success');
            } else {
                showToast(res.message || 'Failed to add', 'error');
            }
            btn.prop('disabled', false);
        }).fail(function() {
            showToast('Something went wrong', 'error');
            btn.prop('disabled', false);
        });
    });
});
</script>

<?= view('layouts/footer') ?>
