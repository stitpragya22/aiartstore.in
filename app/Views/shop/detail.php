<?= view('layouts/header') ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "<?= esc($product['title'], 'js') ?>",
  "description": "<?= esc(strip_tags($product['description'] ?? ''), 'js') ?>",
  "image": "<?= base_url('uploads/products/' . ($product['image_watermarked'] ?: $product['image'])) ?>",
  "offers": {
    "@type": "Offer",
    "price": "<?= $product['price'] ?>",
    "priceCurrency": "INR",
    "availability": "https://schema.org/InStock"
  }
}
</script>
<style>
.star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 4px; }
.star-rating input { display: none; }
.star-rating .star-label { cursor: pointer; font-size: 1.5rem; color: #4a4a5e; transition: color 0.2s; }
.star-rating .star-label:hover,
.star-rating .star-label:hover ~ .star-label,
.star-rating input:checked ~ .star-label { color: #f59e0b; }
</style>

<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background: transparent;">
                <li class="breadcrumb-item"><a href="<?= site_url('/') ?>" class="text-muted text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('/shop') ?>" class="text-muted text-decoration-none">Gallery</a></li>
                <?php if (isset($product['category_name'])): ?>
                <li class="breadcrumb-item"><a href="<?= site_url('/shop/category/' . $product['category_slug']) ?>" class="text-muted text-decoration-none"><?= esc($product['category_name']) ?></a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active text-white" aria-current="page"><?= esc($product['title']) ?></li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-lg-7">
                <div class="product-detail-wrapper">
                    <?php if ($product['image_watermarked'] || $product['image']): ?>
                        <?php $detailImg = base_url('uploads/products/' . ($product['image_watermarked'] ?? $product['image'])); ?>
                        <div class="product-detail-bg" style="background-image: url('<?= str_replace("'", "%27", $detailImg) ?>')"></div>
                        <img src="<?= $detailImg ?>" class="product-detail-image" alt="<?= esc($product['title']) ?>">
                    <?php else: ?>
                        <div class="product-detail-placeholder"><i class="bi bi-image"></i></div>
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

                <?php if (isProductPurchased($product['id'])): ?>
                    <?php $dlAvailable = isDownloadAvailable($product['id']); ?>
                    <div class="mt-4 p-3" style="background: rgba(34, 197, 94, 0.1); border: 1px solid <?= $dlAvailable ? 'rgba(34, 197, 94, 0.3)' : 'rgba(239, 68, 68, 0.3)' ?>; border-radius: 16px;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-<?= $dlAvailable ? 'check-circle-fill' : 'x-circle-fill' ?>" style="color: <?= $dlAvailable ? 'var(--success)' : 'var(--danger)' ?>;"></i>
                            <strong style="color: <?= $dlAvailable ? 'var(--success)' : 'var(--danger)' ?>;"><?= $dlAvailable ? 'Purchased' : 'Access Expired' ?></strong>
                        </div>
                        <small class="text-muted">Purchased on <?= date('d M Y', strtotime(getPurchaseDate($product['id']))) ?></small>
                        <div class="mt-3">
                            <?php if ($dlAvailable): ?>
                            <a href="<?= getPurchaseDownloadUrl($product['id']) ?>" class="btn btn-success w-100">
                                <i class="bi bi-download me-2"></i>Download Now
                            </a>
                            <?php else: ?>
                            <span class="btn btn-secondary w-100 disabled">
                                <i class="bi bi-x-circle me-2"></i>Download Expired
                            </span>
                            <small class="text-danger mt-2 d-block text-center"><i class="bi bi-exclamation-triangle me-1"></i>This download link has expired. <a href="<?= site_url('/contact') ?>">Contact support</a> for assistance.</small>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                <div class="d-flex gap-3 mt-4">
                    <button class="btn btn-primary-custom flex-grow-1 btn-lg" id="addToCartBtn" onclick="addToCart(<?= $product['id'] ?>)">
                        <i class="bi bi-cart-plus me-2"></i>Add to Cart
                    </button>
                    <form method="post" action="<?= site_url('/cart/buy-now') ?>" style="display: contents;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <button type="submit" class="btn btn-primary-custom flex-grow-1 btn-lg" style="background:var(--gradient-1);border:none;">
                            <i class="bi bi-lightning-charge me-2"></i>Buy Now
                        </button>
                    </form>
                    <button class="btn btn-outline-custom btn-wishlist-detail <?= isProductWishlisted($product['id']) ? 'active' : '' ?>" onclick="toggleWishlist(<?= $product['id'] ?>, this)" title="Add to Wishlist">
                        <i class="bi <?= isProductWishlisted($product['id']) ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                    </button>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <i class="bi bi-shield-check" style="color: var(--success);"></i>
                    <small class="text-muted">Secure checkout via Razorpay</small>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Section -->
<section class="py-5" style="background: var(--bg-primary);">
    <div class="container">
        <h2 class="section-title mb-4">Customer Reviews</h2>

        <?php if (empty($reviews) && !$can_review): ?>
            <p class="text-muted">No reviews yet.</p>
        <?php endif; ?>

        <?php if ($can_review && !$has_reviewed): ?>
        <div class="stat-card mb-4">
            <h5 class="fw-semibold mb-3">Write a Review</h5>
            <form method="post" action="<?= site_url('/shop/review/' . $product['id']) ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Rating</label>
                    <div class="star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" <?= $i === 5 ? 'checked' : '' ?>>
                        <label for="star<?= $i ?>" class="star-label"><i class="bi bi-star-fill"></i></label>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Review Title (optional)</label>
                    <input type="text" name="title" class="form-control" maxlength="255">
                </div>
                <div class="mb-3">
                    <label class="form-label">Your Review</label>
                    <textarea name="review" class="form-control" rows="4" minlength="10"></textarea>
                </div>
                <button type="submit" class="btn btn-primary-custom">Submit Review</button>
            </form>
        </div>
        <?php elseif ($has_reviewed): ?>
            <p class="text-muted mb-4">You have already reviewed this product.</p>
        <?php endif; ?>

        <?php if (!empty($reviews)): ?>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div class="display-4 fw-bold" style="color: var(--accent-primary);"><?= $avg_rating ?></div>
                    <div class="mb-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="bi bi-star<?= $i <= round($avg_rating) ? '-fill' : '' ?>" style="color: #f59e0b;"></i>
                        <?php endfor; ?>
                    </div>
                    <small class="text-muted"><?= count($reviews) ?> review<?= count($reviews) !== 1 ? 's' : '' ?></small>
                </div>
            </div>
            <div class="col-md-8">
                <?php foreach ($reviews as $review): ?>
                <div class="stat-card mb-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <strong><?= esc($review['username'] ?? 'Anonymous') ?></strong>
                            <div>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star<?= $i <= $review['rating'] ? '-fill' : '' ?>" style="color: #f59e0b; font-size: 0.8rem;"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <small class="text-muted"><?= date('d M Y', strtotime($review['created_at'])) ?></small>
                    </div>
                    <?php if ($review['title']): ?>
                    <h6 class="fw-semibold mb-1"><?= esc($review['title']) ?></h6>
                    <?php endif; ?>
                    <?php if ($review['review']): ?>
                    <p class="mb-0 small text-muted"><?= nl2br(esc($review['review'])) ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
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
                    <button class="btn-wishlist <?= isProductWishlisted($product['id']) ? 'active' : '' ?>" onclick="toggleWishlist(<?= $product['id'] ?>, this)" title="Add to Wishlist">
                        <i class="bi <?= isProductWishlisted($product['id']) ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                    </button>
                    <div class="art-image-wrapper">
                        <?php if ($product['image_watermarked']): ?>
                            <?php $img = base_url('uploads/products/' . $product['image_watermarked']); ?>
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
function addToCart(id) {
    const btn = document.getElementById('addToCartBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Adding...';
    $.post('<?= site_url('/cart/add') ?>', { id: id, quantity: 1 }, function(res) {
        if (res.status === 'success') {
            $('#cartCount').text(res.count);
            showToast('Added to cart!', 'success');
            btn.outerHTML = '<a href="<?= site_url('/cart') ?>" class="btn flex-grow-1 btn-lg" style="background:var(--success);color:#fff;border-radius:12px;font-weight:700;border:none;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;"><i class="bi bi-cart-check me-2"></i>Go to Cart</a>';
        } else {
            showToast(res.message || 'Failed to add', 'error');
            btn.innerHTML = '<i class="bi bi-cart-plus me-2"></i>Add to Cart';
            btn.disabled = false;
        }
    }).fail(function() {
        showToast('Something went wrong', 'error');
        btn.innerHTML = '<i class="bi bi-cart-plus me-2"></i>Add to Cart';
        btn.disabled = false;
    });
}
</script>

<?= view('layouts/footer') ?>
