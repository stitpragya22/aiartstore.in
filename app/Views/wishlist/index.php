<?= view('layouts/header') ?>

<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="section-title mb-0">My Wishlist</h1>
                <p class="section-subtitle">Manage your saved digital artworks</p>
            </div>
            <a href="<?= site_url('/shop') ?>" class="btn btn-outline-custom">
                <i class="bi bi-arrow-left me-1"></i> Continue Shopping
            </a>
        </div>

        <?php if (empty($wishlist)): ?>
            <div class="empty-state py-5 text-center" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 24px; backdrop-filter: blur(10px);">
                <div class="mb-4">
                    <i class="bi bi-heart" style="font-size: 5rem; color: rgba(255, 71, 87, 0.4); text-shadow: 0 0 30px rgba(255, 71, 87, 0.2);"></i>
                </div>
                <h3 class="fw-bold mb-2">Your wishlist is empty</h3>
                <p class="text-secondary col-md-6 mx-auto mb-4">Explore our gallery of unique, high-resolution AI-generated digital art and click the heart icon to save your favorites here.</p>
                <a href="<?= site_url('/shop') ?>" class="btn btn-primary-custom btn-lg px-5">
                    <i class="bi bi-grid me-2"></i> Browse Gallery
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4 wishlist-grid">
                <?php foreach ($wishlist as $item): ?>
                <div class="col-md-6 col-lg-3 wishlist-item-card">
                    <div class="card-art position-relative">
                        <!-- Floating Heart to Remove from Wishlist -->
                        <button class="btn-wishlist active" onclick="toggleWishlist(<?= $item['product_id'] ?>, this)" title="Remove from Wishlist">
                            <i class="bi bi-heart-fill"></i>
                        </button>
                        
                        <div class="art-image-wrapper">
                            <?php $img = $item['image_watermarked'] ? base_url('uploads/products/' . $item['image_watermarked']) : ($item['image'] ? base_url('uploads/products/' . $item['image']) : null); ?>
                            <?php if ($img): ?>
                                <div class="art-image-bg" style="background-image: url('<?= str_replace("'", "%27", $img) ?>')"></div>
                                <img src="<?= $img ?>" class="art-image" alt="<?= esc($item['title']) ?>">
                            <?php else: ?>
                                <div class="art-image-placeholder"><i class="bi bi-image"></i></div>
                            <?php endif; ?>
                            <div class="watermark-badge"><i class="bi bi-water me-1"></i>Preview</div>
                            <div class="art-overlay">
                                <a href="<?= site_url('/shop/' . $item['slug']) ?>" class="btn btn-primary-custom btn-sm w-100">View Details</a>
                            </div>
                        </div>
                        
                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><?= esc($item['category_name'] ?? 'Uncategorized') ?></small>
                                <?php if (isset($item['product_type']) && $item['product_type'] !== 'art'): ?>
                                <span class="badge" style="background:var(--accent-glow);color:#fff;font-size:0.65rem;border-radius:20px;"><?= ucfirst($item['product_type']) ?></span>
                                <?php endif; ?>
                            </div>
                            <h6 class="mt-1 fw-semibold" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= esc($item['title']) ?></h6>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="price-tag"><?= formatPrice($item['price']) ?></span>
                                <?php if (isProductPurchased($item['product_id'])): ?>
                                    <span class="badge" style="background: rgba(34,197,94,0.2); color: var(--success); border-radius: 20px;"><i class="bi bi-check-circle me-1"></i>Owned</span>
                                <?php else: ?>
                                <button class="btn btn-outline-custom add-to-cart" data-id="<?= $item['product_id'] ?>" style="border-radius:10px;padding:6px 12px;min-width:40px;min-height:40px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-cart-plus" style="font-size:1rem;"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        var btn = $(this);
        var id = btn.data('id');
        btn.prop('disabled', true);
        btn.html('<span class="spinner-border spinner-border-sm"></span>');
        $.post('<?= site_url('/cart/add') ?>', { id: id, quantity: 1 }, function(res) {
            if (res.status === 'success') {
                $('#cartCount').text(res.count);
                if (typeof mobileCartCount !== 'undefined') $('#mobileCartCount').text(res.count);
                showToast('Added to cart!', 'success');
                btn.html('<i class="bi bi-check-lg"></i>');
                setTimeout(function() { btn.html('<i class="bi bi-cart-plus"></i>'); btn.prop('disabled', false); }, 1500);
            } else {
                showToast(res.message || 'Failed to add', 'error');
                btn.html('<i class="bi bi-cart-plus"></i>');
                btn.prop('disabled', false);
            }
        }).fail(function() {
            showToast('Something went wrong', 'error');
            btn.html('<i class="bi bi-cart-plus"></i>');
            btn.prop('disabled', false);
        });
    });
});
</script>

<?= view('layouts/footer') ?>
