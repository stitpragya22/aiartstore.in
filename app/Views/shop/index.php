<?= view('layouts/header') ?>

<style>
.filter-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
.search-wrap { position: relative; }
.search-wrap .bi-search { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.9rem; pointer-events: none; }
.search-wrap input { padding-left: 38px; padding-right: 38px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-primary); height: 44px; font-size: 0.9rem; }
.search-wrap input:focus { border-color: var(--accent-primary); box-shadow: 0 0 0 3px rgba(139,92,246,0.15); outline: none; }
.search-wrap input::placeholder { color: var(--text-muted); }
.search-wrap .btn-clear { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px 8px; font-size: 0.85rem; display: <?= $search ? 'block' : 'none' ?>; }
.search-wrap .btn-clear:hover { color: var(--text-primary); }

.sort-select { background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-primary); height: 44px; font-size: 0.85rem; padding: 0 14px; cursor: pointer; }
.sort-select:focus { border-color: var(--accent-primary); box-shadow: 0 0 0 3px rgba(139,92,246,0.15); outline: none; }

.cat-pills { display: flex; gap: 6px; flex-wrap: wrap; }
.cat-pill { padding: 6px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; text-decoration: none; transition: all 0.2s; background: var(--bg-secondary); color: var(--text-secondary); border: 1px solid var(--border-color); white-space: nowrap; }
.cat-pill:hover { border-color: var(--accent-primary); color: var(--text-primary); }
.cat-pill.active { background: var(--accent-primary); border-color: var(--accent-primary); color: #fff; }

.price-filter-wrap { position: relative; }
.price-filter-toggle { background: none; border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-secondary); height: 44px; padding: 0 16px; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 6px; white-space: nowrap; width: 100%; }
.price-filter-toggle:hover { border-color: var(--accent-primary); color: var(--text-primary); }
.price-filter-toggle.active { border-color: var(--accent-primary); color: var(--accent-primary); }

.price-popup { position: absolute; top: calc(100% + 6px); left: 0; right: 0; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 1rem; box-shadow: 0 12px 40px rgba(0,0,0,0.5); display: none; z-index: 50; min-width: 260px; }
.price-popup.open { display: block; }
.price-popup::before { content: ''; position: absolute; top: -6px; left: 24px; width: 10px; height: 10px; background: var(--bg-card); border: 1px solid var(--border-color); border-right: none; border-bottom: none; transform: rotate(45deg); }
.price-slider-wrap { position: relative; height: 32px; margin: 0; }
.price-slider-wrap input[type="range"] {
    position: absolute; width: 100%; height: 4px; top: 50%; transform: translateY(-50%);
    -webkit-appearance: none; appearance: none; background: transparent; pointer-events: none; margin: 0; z-index: 3;
}
.price-slider-wrap input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none; appearance: none; height: 20px; width: 20px; border-radius: 50%;
    background: var(--accent-primary); border: 3px solid var(--bg-card); cursor: grab;
    pointer-events: auto; box-shadow: 0 2px 8px rgba(0,0,0,0.4); transition: transform 0.15s;
}
.price-slider-wrap input[type="range"]::-webkit-slider-thumb:active { transform: scale(1.2); cursor: grabbing; }
.price-slider-wrap input[type="range"]::-moz-range-thumb {
    height: 20px; width: 20px; border-radius: 50%; background: var(--accent-primary);
    border: 3px solid var(--bg-card); cursor: grab; pointer-events: auto; box-shadow: 0 2px 8px rgba(0,0,0,0.4);
}
.price-track { position: absolute; top: 50%; transform: translateY(-50%); height: 4px; width: 100%; background: var(--border-color); border-radius: 10px; }
.price-track .range-fill { position: absolute; height: 100%; background: var(--accent-primary); border-radius: 10px; }
.price-labels { display: flex; justify-content: space-between; font-size: 0.8rem; font-weight: 600; color: var(--text-primary); margin-top: 4px; }
.price-popup-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 10px; }
.price-popup-actions button { padding: 6px 16px; border-radius: 8px; font-size: 0.8rem; cursor: pointer; border: none; font-weight: 500; transition: all 0.2s; }
</style>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="section-title mb-0"><?= $selectedCategory ? esc($title) : 'Art Gallery' ?></h1>
                <p class="section-subtitle"><?= $search ? 'Search results for "' . esc($search) . '"' : ($meta_description ?? 'Browse our collection of AI-generated art') ?></p>
            </div>
        </div>

        <form id="filterForm" action="<?= $selectedCategory ? site_url('/shop/category/' . $selectedCategory) : site_url('/shop') ?>" method="GET">
            <div class="filter-card">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <div class="search-wrap">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" placeholder="Search artworks, tags..." value="<?= esc($search) ?>">
                            <button type="button" class="btn-clear" onclick="document.getElementsByName('search')[0].value='';this.form.submit()"><i class="bi bi-x-lg"></i></button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="sort" class="sort-select w-100" onchange="this.form.submit()">
                            <option value="date_desc" <?= $sort === 'date_desc' ? 'selected' : '' ?>>Newest First</option>
                            <option value="date_asc" <?= $sort === 'date_asc' ? 'selected' : '' ?>>Oldest First</option>
                            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                            <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Name: A to Z</option>
                            <option value="name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Name: Z to A</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="price-filter-wrap">
                            <button type="button" class="price-filter-toggle" id="priceToggle" onclick="togglePricePopup()">
                                <i class="bi bi-currency-rupee"></i> Price Range
                                <?php if (($min_price !== null && $min_price !== '' && (float)$min_price > 0) || ($max_price !== null && $max_price !== '' && (float)$max_price < $max_price_default)): ?><span class="active-dot" style="width:6px;height:6px;border-radius:50%;background:var(--accent-primary);display:inline-block;margin-left:4px;"></span><?php endif; ?>
                            </button>
                            <div class="price-popup" id="pricePopup">
                                <div class="price-slider-wrap">
                                    <div class="price-track"><div class="range-fill" id="rangeFill"></div></div>
                                    <input type="range" id="priceMin" name="min_price" min="0" max="<?= $max_price_default ?>" step="50" value="<?= esc($min_price ?? 0) ?>">
                                    <input type="range" id="priceMax" name="max_price" min="0" max="<?= $max_price_default ?>" step="50" value="<?= esc($max_price ?? $max_price_default) ?>">
                                    <div class="price-labels">
                                        <span id="priceLabelMin">₹<?= $min_price ? number_format((float)$min_price) : '0' ?></span>
                                        <span id="priceLabelMax">₹<?= $max_price ? number_format((float)$max_price) : number_format($max_price_default) . '+' ?></span>
                                    </div>
                                </div>
                                <div class="price-popup-actions">
                                    <button type="button" onclick="document.getElementsByName('min_price')[0].value=0;document.getElementsByName('max_price')[0].value=5000;updatePriceRange();this.form.submit()" style="background:var(--bg-secondary);color:var(--text-secondary);">Reset</button>
                                    <button type="submit" style="background:var(--accent-primary);color:#fff;">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 text-end">
                        <?php
                        $hasPriceFilter = ($min_price !== null && $min_price !== '' && (float)$min_price > 0) || ($max_price !== null && $max_price !== '' && (float)$max_price < $max_price_default);
                        if ($search || $hasPriceFilter || $sort !== 'date_desc'): ?>
                        <a href="<?= $selectedCategory ? site_url('/shop/category/' . $selectedCategory) : site_url('/shop') ?>" class="cat-pill" style="background:transparent;border-color:var(--accent-primary);color:var(--accent-primary);"><i class="bi bi-x me-1"></i>Clear</a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <div class="cat-pills mb-4">
                <a href="<?= site_url('/shop') ?>" class="cat-pill <?= !$selectedCategory ? 'active' : '' ?>">All Artworks</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="<?= site_url('/shop/category/' . $cat['slug']) ?>" class="cat-pill <?= $selectedCategory == $cat['slug'] ? 'active' : '' ?>"><?= esc($cat['name']) ?></a>
                <?php endforeach; ?>
            </div>
        </form>

        <?php if (empty($products)): ?>
            <div class="empty-state">
                <i class="bi bi-image"></i>
                <h4>No artworks found</h4>
                <p class="text-muted">Try adjusting your search or filters</p>
                <a href="<?= site_url('/shop') ?>" class="btn btn-primary-custom">Clear Filters</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($products as $product): ?>
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
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><?= esc($product['category_name'] ?? 'Uncategorized') ?></small>
                                <?php if (isset($product['product_type']) && $product['product_type'] !== 'art'): ?>
                                <span class="badge" style="background:var(--accent-glow);color:#fff;font-size:0.65rem;border-radius:20px;"><?= ucfirst($product['product_type']) ?></span>
                                <?php endif; ?>
                            </div>
                            <h6 class="mt-1 fw-semibold" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= esc($product['title']) ?></h6>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="price-tag"><?= formatPrice($product['price']) ?></span>
                                <?php if (isProductPurchased($product['id'])): ?>
                                    <?php $dlAvail = isDownloadAvailable($product['id']); ?>
                                    <span class="badge" style="background: <?= $dlAvail ? 'rgba(34,197,94,0.2)' : 'rgba(239,68,68,0.2)' ?>; color: <?= $dlAvail ? 'var(--success)' : 'var(--danger)' ?>; border-radius: 20px;"><i class="bi bi-<?= $dlAvail ? 'check-circle' : 'x-circle' ?> me-1"></i><?= $dlAvail ? 'Owned' : 'Expired' ?></span>
                                <?php else: ?>
                                <button class="btn btn-outline-custom add-to-cart" data-id="<?= $product['id'] ?>" style="border-radius:10px;padding:6px 12px;min-width:40px;min-height:40px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-cart-plus" style="font-size:1rem;"></i></button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (isset($pager)): ?>
            <div class="mt-4"><?= $pager->links() ?></div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<script>
function togglePricePopup() {
    $('#pricePopup').toggleClass('open');
    $('#priceToggle').toggleClass('active');
}

function updatePriceRange() {
    var min = parseInt($('#priceMin').val());
    var max = parseInt($('#priceMax').val());
    if (min > max) {
        $('#priceMax').val(min);
        max = min;
    }
    var maxVal = parseInt($('#priceMax').attr('max'));
    var pctMin = (min / maxVal) * 100;
    var pctMax = (max / maxVal) * 100;
    $('#rangeFill').css({ left: pctMin + '%', width: (pctMax - pctMin) + '%' });
    $('#priceLabelMin').text('₹' + Number(min).toLocaleString('en-IN'));
    $('#priceLabelMax').text('₹' + Number(max).toLocaleString('en-IN'));
}

$(document).ready(function() {
    $('#priceMin').on('input', function() {
        $('#priceMin').css('z-index', 4);
        $('#priceMax').css('z-index', 3);
        updatePriceRange();
    });
    $('#priceMax').on('input', function() {
        $('#priceMax').css('z-index', 4);
        $('#priceMin').css('z-index', 3);
        updatePriceRange();
    });

    <?php if (($min_price !== null && $min_price !== '' && (float)$min_price > 0) || ($max_price !== null && $max_price !== '' && (float)$max_price < $max_price_default)): ?>
    $('#pricePopup').addClass('open');
    $('#priceToggle').addClass('active');
    <?php endif; ?>
    updatePriceRange();

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.price-filter-wrap').length) {
            $('#pricePopup').removeClass('open');
            $('#priceToggle').removeClass('active');
        }
    });

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
