<?= view('layouts/header') ?>

<!-- Hero Search Section - Adobe Stock Style -->
<section class="stock-hero">
    <div class="container position-relative">
        <span class="badge bg-primary bg-opacity-10 text-white mb-3 px-3 py-2" style="background: var(--accent-glow) !important; border-radius: 40px; font-weight: 500;">
            <i class="bi bi-stars me-1"></i> Premium AI Art Marketplace
        </span>
        <h1>
            Search, discover, and collect<br>
            <span>premium AI-generated art</span>
        </h1>
        <p>Browse thousands of unique AI artworks. Download high-resolution digital files for your creative projects.</p>
        <form action="<?= site_url('/shop') ?>" method="get" class="stock-search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" name="search" placeholder="Search AI art, styles, categories..." aria-label="Search">
            <button type="submit">Search</button>
        </form>
        <div class="stock-filter-pills">
            <a href="<?= site_url('/shop') ?>" class="pill active">All</a>
            <?php if (!empty($categories)): ?>
                <?php $count = 0; foreach ($categories as $cat): $count++; if ($count > 6) break; ?>
                    <a href="<?= site_url('/shop/category/' . $cat['slug']) ?>" class="pill"><?= esc($cat['name']) ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-center gap-3 gap-md-4 mt-5">
            <div><span class="stat-number">500+</span><br><span class="text-secondary small">Artworks</span></div>
            <div><span class="stat-number">50+</span><br><span class="text-secondary small">Artists</span></div>
            <div><span class="stat-number">1000+</span><br><span class="text-secondary small">Happy Customers</span></div>
        </div>
        <div class="text-center mt-4">
            <a href="<?= site_url('/custom-request') ?>" class="btn btn-outline-light btn-lg px-5" style="border-radius: 50px; font-weight: 600;">
                <i class="bi bi-palette me-2"></i>Request Custom AI Art
            </a>
        </div>
    </div>
</section>

<!-- Curated Collections - Adobe Stock Style -->
<?php $showCurated = $curated_categories ?? $categories ?? []; ?>
<?php if (!empty($showCurated)): ?>
<section class="py-5">
    <div class="container">
        <div class="stock-section-header">
            <div>
                <h2>Curated collections</h2>
                <p>Explore our hand-picked categories of premium AI art</p>
            </div>
            <a href="<?= site_url('/shop') ?>">View all collections <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
        <div class="cat-masonry">
            <?php foreach ($showCurated as $cat): $src = $cat['image'] ? base_url('uploads/categories/' . $cat['image']) : ''; ?>
            <a href="<?= site_url('/shop/category/' . $cat['slug']) ?>" class="cat-masonry-item">
                <?php if ($src): ?>
                <div style="position:relative;overflow:hidden;border-radius:16px;">
                    <img src="<?= $src ?>" aria-hidden="true" style="display:block;width:100%;height:auto;filter:blur(24px);opacity:0.6;">
                    <img src="<?= $src ?>" alt="<?= esc($cat['name']) ?>" style="position:absolute;inset:0;width:100%;height:100%;object-fit:contain;padding:4px;">
                    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.75) 0%,rgba(0,0,0,0.1) 60%,transparent 100%);display:flex;flex-direction:column;justify-content:flex-end;padding:1.25rem;">
                        <h6 style="color:white;font-weight:600;margin:0;font-size:1.1rem;"><?= esc($cat['name']) ?></h6>
                        <span style="color:rgba(255,255,255,0.7);font-size:0.75rem;"><?= $cat['product_count'] ?? 0 ?> items</span>
                    </div>
                </div>
                <?php else: ?>
                <div style="border-radius:16px;overflow:hidden;background:var(--bg-card);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2.5rem 1rem;text-align:center;">
                    <i class="bi bi-palette" style="font-size:2rem;color:var(--accent-primary);opacity:0.5;"></i>
                    <h6 class="mt-2 fw-semibold mb-0" style="font-size:1rem;"><?= esc($cat['name']) ?></h6>
                    <small style="color:var(--text-muted);"><?= $cat['product_count'] ?? 0 ?> items</small>
                </div>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Artworks -->
<?php if (!empty($featured)): ?>
<section class="py-5" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="stock-section-header">
            <div>
                <h2>Featured artworks</h2>
                <p>Curated picks for you</p>
            </div>
            <a href="<?= site_url('/shop') ?>">View all <i class="bi bi-arrow-right ms-1"></i></a>
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

<!-- Latest Additions -->
<?php if (!empty($latest)): ?>
<section class="py-5">
    <div class="container">
        <div class="stock-section-header">
            <div>
                <h2>Latest additions</h2>
                <p>Newest AI art creations</p>
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

<!-- Why AI Art Store -->
<section class="py-5" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Everything you need for creative projects</h2>
            <p class="section-subtitle">A massive content library with fresh AI art added daily</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card-art p-4 h-100 text-center border-0" style="background: var(--bg-card);">
                    <div class="d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: 16px; background: var(--accent-glow);">
                        <i class="bi bi-shield-check fs-3" style="color: var(--accent-primary);"></i>
                    </div>
                    <h5 class="mt-3 fw-bold">Watermarked Preview</h5>
                    <p class="text-muted mb-0">All preview images are protected with watermark. Download high-res files after purchase.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-art p-4 h-100 text-center border-0" style="background: var(--bg-card);">
                    <div class="d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: 16px; background: var(--accent-glow);">
                        <i class="bi bi-lightning-charge fs-3" style="color: var(--accent-primary);"></i>
                    </div>
                    <h5 class="mt-3 fw-bold">Instant Download</h5>
                    <p class="text-muted mb-0">Get immediate access to your purchased files. Download anytime from your account.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-art p-4 h-100 text-center border-0" style="background: var(--bg-card);">
                    <div class="d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: 16px; background: var(--accent-glow);">
                        <i class="bi bi-shield-lock fs-3" style="color: var(--accent-primary);"></i>
                    </div>
                    <h5 class="mt-3 fw-bold">Secure Payments</h5>
                    <p class="text-muted mb-0">Pay securely via Razorpay. Your payment information is always protected.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Categories -->
<?php if (!empty($categories)): ?>
<section class="py-5">
    <div class="container">
        <div class="stock-section-header">
            <div>
                <h2>Popular categories</h2>
                <p>Browse art by your favorite themes</p>
            </div>
        </div>
        <div class="cat-masonry">
            <?php foreach ($categories as $cat): $src = $cat['image'] ? base_url('uploads/categories/' . $cat['image']) : ''; ?>
            <a href="<?= site_url('/shop/category/' . $cat['slug']) ?>" class="cat-masonry-item">
                <?php if ($src): ?>
                <div style="position:relative;overflow:hidden;border-radius:16px;">
                    <img src="<?= $src ?>" aria-hidden="true" style="display:block;width:100%;height:auto;filter:blur(24px);opacity:0.6;">
                    <img src="<?= $src ?>" alt="<?= esc($cat['name']) ?>" style="position:absolute;inset:0;width:100%;height:100%;object-fit:contain;padding:8px;">
                    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.75) 0%,rgba(0,0,0,0.1) 60%,transparent 100%);display:flex;flex-direction:column;justify-content:flex-end;padding:1.25rem;">
                        <h6 style="color:white;font-weight:600;margin:0;font-size:1.1rem;"><?= esc($cat['name']) ?></h6>
                        <span style="color:rgba(255,255,255,0.7);font-size:0.75rem;"><?= $cat['product_count'] ?? 0 ?> items</span>
                    </div>
                </div>
                <?php else: ?>
                <div style="border-radius:16px;overflow:hidden;background:var(--bg-card);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2.5rem 1rem;text-align:center;">
                    <i class="bi bi-palette" style="font-size:2rem;color:var(--accent-primary);opacity:0.5;"></i>
                    <h6 class="mt-2 fw-semibold mb-0" style="font-size:1rem;"><?= esc($cat['name']) ?></h6>
                    <small style="color:var(--text-muted);"><?= $cat['product_count'] ?? 0 ?> items</small>
                </div>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- FAQ Section - Adobe Stock Style -->
<section class="py-5" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h2 class="section-title">Frequently asked questions</h2>
                    <p class="section-subtitle">Everything you need to know about AI Art Store</p>
                </div>
                <div class="accordion stock-faq" id="faqAccordion">
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                What is AI Art Store?
                            </button>
                        </h3>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                AI Art Store is a marketplace for premium AI-generated artwork. We curate and showcase unique digital art created using advanced AI tools. Browse, purchase, and download high-resolution art for your creative projects.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                How do I purchase an artwork?
                            </button>
                        </h3>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Simply browse our gallery, find an artwork you love, and click "Add to Cart." Once you're ready, proceed to checkout where you can pay securely via Razorpay. After payment, you'll get instant access to download the high-resolution file.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                What file format do I get?
                            </button>
                        </h3>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                All artworks are available as high-resolution PNG or JPG files (depending on the artwork). You can download them instantly after purchase from your account's Downloads section.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Can I use the art for commercial projects?
                            </button>
                        </h3>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes! When you purchase an artwork, you receive a commercial license that allows you to use it in your projects, including websites, marketing materials, merchandise, and more. See our Terms page for full details.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                How does the watermark work?
                            </button>
                        </h3>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                All preview images displayed on our site are protected with a visible watermark to prevent unauthorized use. When you purchase an artwork, you receive the clean, high-resolution version without any watermark.
                            </div>
                        </div>
                    </div>
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
