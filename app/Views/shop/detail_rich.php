<?php
$details = [];
if (!empty($product['details_json'])) {
    $d = json_decode($product['details_json'], true);
    if (is_array($d)) $details = $d;
}
$highlights = array_filter(array_map('trim', explode("\n", $product['highlights'] ?? '')));
$features = [];
if (!empty($product['features'])) {
    $f = json_decode($product['features'], true);
    if (is_array($f)) $features = $f;
}
$typeLabels = ['ebook' => 'E-Book', 'audio' => 'Audio', 'bundle' => 'Bundle', 'art' => 'Art Print'];
$typeIcons = ['ebook' => 'bi-book', 'audio' => 'bi-headphones', 'bundle' => 'bi-box-seam', 'art' => 'bi-image'];
$typeColors = ['ebook' => '#6366f1', 'audio' => '#f59e0b', 'bundle' => '#10b981', 'art' => '#8b5cf6'];
$type = $product['product_type'] ?? 'art';
$tColor = $typeColors[$type] ?? '#8b5cf6';
$img = null;
if ($product['image_watermarked'] || $product['image']) {
    $img = base_url('uploads/products/' . ($product['image_watermarked'] ?? $product['image']));
}

?>
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
<style>
.rich-hero {
    position: relative;
    min-height: 520px;
    display: flex;
    align-items: center;
    overflow: hidden;
    border-radius: 32px;
    margin-bottom: 2rem;
}
.rich-hero-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    filter: blur(40px) brightness(0.2);
    transform: scale(1.2);
}
.rich-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(10,10,15,0.85) 0%, rgba(10,10,15,0.4) 50%, rgba(10,10,15,0.85) 100%);
}
.rich-hero-content {
    position: relative;
    z-index: 2;
    width: 100%;
}
.glass-card {
    background: rgba(26,26,46,0.7);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 20px;
    transition: all 0.3s;
}
.glass-card:hover {
    border-color: rgba(255,255,255,0.15);
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.3);
}
.spec-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.9rem 1.5rem;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
.spec-row:last-child { border-bottom: none; }
.spec-label {
    color: rgba(255,255,255,0.5);
    font-size: 0.85rem;
    font-weight: 500;
    letter-spacing: 0.02em;
    text-transform: uppercase;
}
.spec-value {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.95rem;
    text-align: right;
}
.highlight-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 12px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.05);
    transition: all 0.3s;
}
.highlight-item:hover {
    background: rgba(139,92,246,0.1);
    border-color: rgba(139,92,246,0.2);
    transform: translateX(4px);
}
.feature-icon-box {
    text-align: center;
    padding: 1.5rem 1rem;
    border-radius: 16px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    transition: all 0.3s;
    height: 100%;
}
.feature-icon-box:hover {
    background: rgba(139,92,246,0.1);
    border-color: rgba(139,92,246,0.2);
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(139,92,246,0.15);
}
.feature-icon-box i { font-size: 1.8rem; }
.feature-icon-box h6 { margin-top: 0.75rem; font-weight: 700; }
.feature-icon-box p { font-size: 0.8rem; color: var(--text-white); margin-bottom: 0; }
.price-hero {
    font-size: 2.8rem;
    font-weight: 800;
    background: linear-gradient(135deg, <?= $tColor ?>, <?= $tColor ?>88);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.1;
}
.old-price-hero {
    font-size: 1.4rem;
    color: var(--text-white);
    text-decoration: line-through;
    font-weight: 500;
}
.content-body h2 { font-size: 1.8rem; font-weight: 700; margin-top: 2.5rem; margin-bottom: 1rem; }
.content-body h3 { font-size: 1.3rem; font-weight: 600; margin-top: 2rem; margin-bottom: 0.75rem; color: var(--accent-secondary); }
.content-body p { color: var(--text-secondary); line-height: 1.8; margin-bottom: 1.25rem; font-size: 1.05rem; }
.content-body ul, .content-body ol { color: var(--text-secondary); line-height: 1.8; padding-left: 1.5rem; }
.content-body li { margin-bottom: 0.5rem; }
.content-body blockquote {
    border-left: 3px solid <?= $tColor ?>;
    padding: 1rem 1.5rem;
    margin: 1.5rem 0;
    background: rgba(255,255,255,0.03);
    border-radius: 0 12px 12px 0;
    font-style: italic;
    color: var(--text-secondary);
}
.pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.8); }
}
.fade-in-up { animation: fadeUp 0.6s ease-out forwards; opacity: 0; }
.fade-in-up:nth-child(1) { animation-delay: 0.05s; }
.fade-in-up:nth-child(2) { animation-delay: 0.1s; }
.fade-in-up:nth-child(3) { animation-delay: 0.15s; }
.fade-in-up:nth-child(4) { animation-delay: 0.2s; }
.fade-in-up:nth-child(5) { animation-delay: 0.25s; }
.fade-in-up:nth-child(6) { animation-delay: 0.3s; }
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.floating-price {
    position: sticky;
    top: 90px;
    z-index: 10;
}
@media (max-width: 991px) {
    .rich-hero { min-height: 320px; }
    .price-hero { font-size: 2rem; }
    .floating-price { position: static; }
}
</style>

<section class="py-4">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb" style="background:transparent;">
                <li class="breadcrumb-item"><a href="<?= site_url('/') ?>" class="text-decoration-none" style="color:var(--text-white)">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('/shop') ?>" class="text-decoration-none" style="color:var(--text-white)">Shop</a></li>
                <?php if (isset($product['category_name'])): ?>
                <li class="breadcrumb-item"><a href="<?= site_url('/shop/category/' . $product['category_slug']) ?>" class="text-decoration-none" style="color:var(--text-white)"><?= esc($product['category_name']) ?></a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active text-white"><?= esc($product['title']) ?></li>
            </ol>
        </nav>

        <div class="rich-hero" style="<?= $img ? "background-image:url('$img')" : '' ?>;background-size:cover;background-position:center;">
            <?php if ($img): ?>
            <div class="rich-hero-bg" style="background-image:url('<?= $img ?>')"></div>
            <?php endif; ?>
            <div class="rich-hero-overlay"></div>
            <div class="rich-hero-content">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <?php if ($img): ?>
                        <div style="border-radius:24px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);box-shadow:0 24px 80px rgba(0,0,0,0.5);">
                            <img src="<?= $img ?>" alt="<?= esc($product['title']) ?>" style="width:100%;display:block;">
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-6">
                        <div class="fade-in-up">
                            <span class="badge px-3 py-2 mb-2" style="background:<?= $tColor ?>;border-radius:20px;color:#fff;font-weight:600;">
                                <i class="<?= $typeIcons[$type] ?> me-1"></i> <?= $typeLabels[$type] ?>
                            </span>
                            <h1 class="display-4 fw-bold mt-2" style="font-family:'Space Grotesk',sans-serif;line-height:1.1;"><?= esc($product['title']) ?></h1>
                            <?php if ($product['subtitle']): ?>
                            <p class="fs-5 mt-2" style="color:<?= $tColor ?>;font-weight:500;"><?= esc($product['subtitle']) ?></p>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($highlights)): ?>
                        <div class="d-flex flex-column gap-2 mt-4">
                            <?php foreach ($highlights as $i => $h): ?>
                            <div class="highlight-item fade-in-up">
                                <span style="width:20px;height:20px;border-radius:50%;background:<?= $tColor ?>22;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi bi-check-lg" style="color:<?= $tColor ?>;font-size:0.75rem;"></i>
                                </span>
                                <span style="color:rgba(255,255,255,0.85);font-size:0.95rem;"><?= esc($h) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <?php if ($product['description']): ?>
                <div class="glass-card p-4 mb-4 fade-in-up">
                    <p style="color:var(--text-secondary);font-size:1.1rem;line-height:1.7;margin:0;"><?= nl2br(esc($product['description'])) ?></p>
                </div>
                <?php endif; ?>

                <?php if (!empty($features)): ?>
                <div class="glass-card p-4 mb-4 fade-in-up">
                    <h5 class="fw-bold mb-3" style="font-family:'Space Grotesk',sans-serif;">
                        <i class="bi bi-sliders2 me-2" style="color:<?= $tColor ?>"></i> Specifications
                    </h5>
                    <div style="border-radius:12px;overflow:hidden;background:rgba(0,0,0,0.2);">
                        <?php $si = 0; foreach ($features as $key => $val): ?>
                        <div class="spec-row" style="<?= $si % 2 === 0 ? 'background:rgba(255,255,255,0.02)' : '' ?>">
                            <span class="spec-label"><?= esc($key) ?></span>
                            <span class="spec-value"><?= esc($val) ?></span>
                        </div>
                        <?php $si++; endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($details)): ?>
                <?php
                $detailLabels = [
                    'ebook' => ['author' => 'Author', 'pages' => 'Pages', 'language' => 'Language', 'isbn' => 'ISBN'],
                    'audio' => ['duration' => 'Duration', 'narrator' => 'Narrator', 'bitrate' => 'Bitrate'],
                ];
                $allowedDetailLabels = $detailLabels[$type] ?? [];
                $printDetails = array_intersect_key($details, $allowedDetailLabels);
                $printDetails = array_filter($printDetails, static fn($val) => trim((string) $val) !== '');
                ?>
                <?php if (!empty($printDetails)): ?>
                <div class="glass-card p-4 mb-4 fade-in-up">
                    <h5 class="fw-bold mb-3" style="font-family:'Space Grotesk',sans-serif;">
                        <i class="bi bi-info-circle me-2" style="color:<?= $tColor ?>"></i> Product Details
                    </h5>
                    <div style="border-radius:12px;overflow:hidden;background:rgba(0,0,0,0.2);">
                        <?php $di = 0; foreach ($printDetails as $key => $val): ?>
                        <div class="spec-row" style="<?= $di % 2 === 0 ? 'background:rgba(255,255,255,0.02)' : '' ?>">
                            <span class="spec-label"><?= $allowedDetailLabels[$key] ?? ucfirst($key) ?></span>
                            <span class="spec-value"><?= esc($val) ?></span>
                        </div>
                        <?php $di++; endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <?php if ($product['content']): ?>
                <div class="glass-card p-4 p-md-5 mb-4 fade-in-up content-body">
                    <?php
                    $productContent = $product['content'];
                    // var_dump($productContent);
                    for ($i = 0; $i < 10; $i++) {
                        $decodedContent = html_entity_decode($productContent, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        if ($decodedContent === $productContent) {
                            break;
                        }
                        $productContent = $decodedContent;
                    }
                    echo $productContent;
                    ?>
                </div>
                <?php endif; ?>

                <?php if (1==0 && $type === 'bundle' && !empty($details['bundle_items'])): ?>
                <div class="glass-card p-4 p-md-5 mb-4 fade-in-up">
                    <h4 class="fw-bold mb-4" style="font-family:'Space Grotesk',sans-serif;">
                        <i class="bi bi-box-seam me-2" style="color:<?= $tColor ?>"></i>What's Included
                    </h4>
                    <div style="white-space:pre-line;color:var(--text-secondary);line-height:2;font-size:1.05rem;"><?= esc($details['bundle_items']) ?></div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <div class="floating-price">
                    <div class="glass-card p-4 mb-3 fade-in-up">
                        <?php if (!empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
                        <div class="mb-2">
                            <span class="old-price-hero"><?= formatPrice($product['compare_price']) ?></span>
                            <span class="badge ms-2" style="background:rgba(239,68,68,0.2);color:var(--danger);font-weight:600;">Save <?= formatPrice($product['compare_price'] - $product['price']) ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="price-hero" id="productPrice"><?= formatPrice($product['price']) ?></div>

                        <div class="mt-3 d-flex align-items-center gap-2">
                            <span class="pulse-dot" style="background:var(--success);"></span>
                            <small style="color:var(--text-white);font-weight:500;">In stock & ready to download</small>
                        </div>

                        <?php if (isProductPurchased($product['id'])): ?>
                            <?php $dlAvailRich = isDownloadAvailable($product['id']); ?>
                            <div class="mt-4 p-3" style="background:<?= $dlAvailRich ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)' ?>;border:1px solid <?= $dlAvailRich ? 'rgba(16,185,129,0.3)' : 'rgba(239,68,68,0.3)' ?>;border-radius:16px;">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-<?= $dlAvailRich ? 'check-circle-fill' : 'x-circle-fill' ?>" style="color:<?= $dlAvailRich ? 'var(--success)' : 'var(--danger)' ?>;font-size:1.2rem;"></i>
                                    <strong style="color:<?= $dlAvailRich ? 'var(--success)' : 'var(--danger)' ?>;font-size:1.1rem;"><?= $dlAvailRich ? 'Purchased' : 'Access Expired' ?></strong>
                                </div>
                                <small style="color:var(--text-white);">Purchased on <?= date('d M Y', strtotime(getPurchaseDate($product['id']))) ?></small>
                                <div class="mt-3">
                                    <?php if ($dlAvailRich): ?>
                                    <a href="<?= getPurchaseDownloadUrl($product['id']) ?>" class="btn w-100 py-2" style="background:var(--success);color:#fff;border-radius:12px;font-weight:600;">
                                        <i class="bi bi-download me-2"></i>Download Now
                                    </a>
                                    <?php else: ?>
                                    <span class="btn w-100 py-2 disabled" style="background:var(--text-white);color:#fff;border-radius:12px;font-weight:600;opacity:0.6;">
                                        <i class="bi bi-x-circle me-2"></i>Download Expired
                                    </span>
                                    <small style="color:var(--danger);" class="mt-2 d-block text-center"><i class="bi bi-exclamation-triangle me-1"></i>This download has expired. <a href="<?= site_url('/contact') ?>">Contact support</a>.</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                        <div class="d-flex gap-3 mt-4">
                            <button class="btn flex-grow-1 py-3" id="addToCartBtn" style="background:<?= $tColor ?>;color:#fff;border-radius:14px;font-weight:700;font-size:1.05rem;transition:all 0.3s;border:none;" onclick="addToCart(<?= $product['id'] ?>)" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 30px <?= $tColor ?>44'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                                <i class="bi bi-cart-plus me-2"></i>Add to Cart
                            </button>
                            <form method="post" action="<?= site_url('/cart/buy-now') ?>" style="display: contents;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                <button type="submit" class="btn flex-grow-1 py-3" style="background:linear-gradient(135deg,#8b5cf6,#6366f1);color:#fff;border-radius:14px;font-weight:700;font-size:1.05rem;transition:all 0.3s;border:none;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 30px rgba(139,92,246,0.4)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                                    <i class="bi bi-lightning-charge me-2"></i>Buy Now
                                </button>
                            </form>
                        </div>

                        <div class="d-flex align-items-center justify-content-center gap-2 mt-3">
                            <i class="bi bi-shield-check" style="color:var(--success);"></i>
                            <small style="color:var(--text-white);">Secure checkout via Razorpay</small>
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-2 mt-1">
                            <i class="bi bi-lightning-charge" style="color:<?= $tColor ?>;"></i>
                            <small style="color:var(--text-white);">Instant download after purchase</small>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="glass-card p-4 fade-in-up">
                        <h6 class="fw-bold mb-3" style="font-size:0.9rem;color:var(--text-white);text-transform:uppercase;letter-spacing:0.05em;">Why Shop Here</h6>
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:38px;height:38px;border-radius:10px;background:<?= $tColor ?>18;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi bi-download" style="color:<?= $tColor ?>;font-size:1rem;"></i>
                                </div>
                                <div>
                                    <small class="fw-semibold d-block" style="color:rgba(255,255,255,0.9);">Instant Delivery</small>
                                    <small style="color:var(--text-white);">Download immediately</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:38px;height:38px;border-radius:10px;background:<?= $tColor ?>18;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi bi-arrow-repeat" style="color:<?= $tColor ?>;font-size:1rem;"></i>
                                </div>
                                <div>
                                    <small class="fw-semibold d-block" style="color:rgba(255,255,255,0.9);">Lifetime Access</small>
                                    <small style="color:var(--text-white);">Re-download anytime</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:38px;height:38px;border-radius:10px;background:<?= $tColor ?>18;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi bi-shield-check" style="color:<?= $tColor ?>;font-size:1rem;"></i>
                                </div>
                                <div>
                                    <small class="fw-semibold d-block" style="color:rgba(255,255,255,0.9);">Secure Checkout</small>
                                    <small style="color:var(--text-white);">Razorpay protected</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mobile Sticky Bottom Action Bar -->
<?php if (!isProductPurchased($product['id'])): ?>
<div class="d-lg-none product-bottom-bar">
    <div class="d-flex align-items-center gap-2 px-3 py-2">
        <div class="flex-shrink-0">
            <div class="fw-bold fs-5" style="color:<?= $tColor ?>;"><?= formatPrice($product['price']) ?></div>
            <?php if (!empty($product['compare_price']) && $product['compare_price'] > $product['price']): ?>
            <small style="color:var(--text-white);text-decoration:line-through;"><?= formatPrice($product['compare_price']) ?></small>
            <?php endif; ?>
        </div>
        <div class="flex-grow-1 d-flex gap-2">
            <button class="btn flex-grow-1 py-2" id="addToCartBtnMobile" style="background:<?= $tColor ?>;color:#fff;border-radius:12px;font-weight:700;font-size:0.95rem;border:none;" onclick="addToCartMobile(<?= $product['id'] ?>)">
                <i class="bi bi-cart-plus me-1"></i>Cart
            </button>
            <form method="post" action="<?= site_url('/cart/buy-now') ?>" style="flex:1;">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <button type="submit" class="btn w-100 py-2" style="background:linear-gradient(135deg,#8b5cf6,#6366f1);color:#fff;border-radius:12px;font-weight:700;font-size:0.95rem;border:none;">
                    <i class="bi bi-lightning-charge me-1"></i>Buy
                </button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
.product-bottom-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(10,10,15,0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-top: 1px solid var(--border-color);
    z-index: 9998;
    padding-bottom: env(safe-area-inset-bottom, 0px);
    box-shadow: 0 -4px 30px rgba(0,0,0,0.5);
}
@media (min-width: 768px) {
    .product-bottom-bar { display: none !important; }
}
</style>

<script>
function addToCartMobile(id) {
    const btn = document.getElementById('addToCartBtnMobile');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>';
    $.post('<?= site_url('/cart/add') ?>', { id: id, quantity: 1 }, function(res) {
        if (res.status === 'success') {
            $('#cartCount').text(res.count);
            $('#mobileCartCount').text(res.count);
            showToast('Added to cart!', 'success');
            btn.outerHTML = '<a href="<?= site_url('/cart') ?>" class="btn flex-grow-1 py-2" style="background:var(--success);color:#fff;border-radius:12px;font-weight:700;font-size:0.95rem;border:none;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:0.3rem;"><i class="bi bi-cart-check"></i>Cart</a>';
        } else {
            showToast(res.message || 'Failed', 'error');
            btn.innerHTML = '<i class="bi bi-cart-plus me-1"></i>Cart';
            btn.disabled = false;
        }
    }).fail(function() {
        showToast('Something went wrong', 'error');
        btn.innerHTML = '<i class="bi bi-cart-plus me-1"></i>Cart';
        btn.disabled = false;
    });
}
</script>

<!-- Reviews Section -->
<section class="py-5" style="background: var(--bg-secondary);">
    <div class="container">
        <h2 class="section-title mb-4">Customer Reviews</h2>

        <?php if (empty($reviews) && !$can_review): ?>
            <p class="text-white">No reviews yet.</p>
        <?php endif; ?>

        <?php if ($can_review && !$has_reviewed): ?>
        <div class="glass-card p-4 mb-4">
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
                <button type="submit" class="btn" style="background:<?= $tColor ?>;color:#fff;border-radius:12px;font-weight:600;border:none;">Submit Review</button>
            </form>
        </div>
        <?php elseif ($has_reviewed): ?>
            <p class="text-white mb-4">You have already reviewed this product.</p>
        <?php endif; ?>

        <?php if (!empty($reviews)): ?>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="glass-card p-4 text-center">
                    <div class="display-4 fw-bold" style="color: <?= $tColor ?>;"><?= $avg_rating ?></div>
                    <div class="mb-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="bi bi-star<?= $i <= round($avg_rating) ? '-fill' : '' ?>" style="color: #f59e0b;"></i>
                        <?php endfor; ?>
                    </div>
                    <small class="text-white"><?= count($reviews) ?> review<?= count($reviews) !== 1 ? 's' : '' ?></small>
                </div>
            </div>
            <div class="col-md-8">
                <?php foreach ($reviews as $review): ?>
                <div class="glass-card p-4 mb-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <strong><?= esc($review['username'] ?? 'Anonymous') ?></strong>
                            <div>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star<?= $i <= $review['rating'] ? '-fill' : '' ?>" style="color: #f59e0b; font-size: 0.8rem;"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <small class="text-white"><?= date('d M Y', strtotime($review['created_at'])) ?></small>
                    </div>
                    <?php if ($review['title']): ?>
                    <h6 class="fw-semibold mb-1"><?= esc($review['title']) ?></h6>
                    <?php endif; ?>
                    <?php if ($review['review']): ?>
                    <p class="mb-0 small text-white"><?= nl2br(esc($review['review'])) ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($related)): ?>
<section class="py-5" style="background: var(--bg-primary);">
    <div class="container">
        <h2 class="section-title mb-4">Related <span style="background:linear-gradient(135deg,#8b5cf6,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Products</span></h2>
        <div class="row g-4">
            <?php foreach ($related as $rp): ?>
            <div class="col-6 col-md-3">
                <a href="<?= site_url('/shop/' . $rp['slug']) ?>" class="text-decoration-none">
                    <div class="card h-100 border-0" style="background: var(--bg-card); border-radius: 16px; overflow: hidden; transition: all 0.3s;">
                        <div class="position-relative" style="padding-top: 100%; background: var(--bg-secondary); overflow: hidden;">
                            <?php if ($rp['image']): ?>
                            <img src="<?= base_url('uploads/products/' . ($rp['image_watermarked'] ?: $rp['image'])) ?>" alt="<?= esc($rp['title']) ?>" class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: contain; padding: 0.5rem;">
                            <?php endif; ?>
                            <?php if ($rp['product_type'] && $rp['product_type'] !== 'art'): ?>
                            <span class="position-absolute top-0 end-0 badge m-2" style="background:linear-gradient(135deg,#8b5cf6,#6366f1);"><?= esc(ucfirst($rp['product_type'])) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="mb-1" style="color: var(--text-primary); font-size: 0.85rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= esc($rp['title']) ?></h6>
                            <span class="price-tag" style="font-size: 0.9rem;"><?= formatPrice($rp['price']) ?></span>
                        </div>
                    </div>
                </a>
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
            btn.outerHTML = '<a href="<?= site_url('/cart') ?>" class="btn flex-grow-1 py-3" style="background:var(--success);color:#fff;border-radius:14px;font-weight:700;font-size:1.05rem;border:none;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;"><i class="bi bi-cart-check me-2"></i>Go to Cart</a>';
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
