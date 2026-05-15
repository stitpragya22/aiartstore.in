<?= view('layouts/header') ?>

<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="section-title mb-0">Art Gallery</h1>
                <p class="section-subtitle"><?= $search ? 'Search results for "' . esc($search) . '"' : 'Browse our collection of AI-generated art' ?></p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8">
                    <form action="<?= site_url('/shop') ?>" method="GET" class="d-flex gap-2">
                    <?php if ($selectedCategory): ?>
                        <input type="hidden" name="category" value="<?= $selectedCategory ?>">
                    <?php endif; ?>
                    <input type="text" name="search" class="form-control" placeholder="Search art, tags, description..." value="<?= esc($search) ?>">
                    <button type="submit" class="btn btn-primary-custom"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?= site_url('/shop') ?>" class="btn btn-sm <?= !$selectedCategory ? 'btn-primary-custom' : 'btn-outline-custom' ?>">All</a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?= site_url('/shop?category=' . $cat['id']) ?>" class="btn btn-sm <?= $selectedCategory == $cat['id'] ? 'btn-primary-custom' : 'btn-outline-custom' ?>"><?= esc($cat['name']) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

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
                        <div class="position-relative overflow-hidden">
                            <?php if ($product['image_watermarked']): ?>
                                <img src="<?= base_url('uploads/products/') ?><?= $product['image_watermarked'] ?>" class="art-image w-100" alt="<?= esc($product['title']) ?>">
                            <?php elseif ($product['image']): ?>
                                <img src="<?= base_url('uploads/products/') ?><?= $product['image'] ?>" class="art-image w-100" alt="<?= esc($product['title']) ?>" style="filter: blur(4px);">
                            <?php else: ?>
                                <div class="art-image w-100 d-flex align-items-center justify-content-center" style="background: var(--bg-card);"><i class="bi bi-image fs-1 text-muted"></i></div>
                            <?php endif; ?>
                            <div class="watermark-badge"><i class="bi bi-water me-1"></i>Preview</div>
                            <div class="art-overlay">
                                <a href="<?= site_url('/shop/' . $product['slug']) ?>" class="btn btn-primary-custom btn-sm w-100">View Details</a>
                            </div>
                        </div>
                        <div class="p-3">
                            <small class="text-muted"><?= esc($product['category_name'] ?? 'Uncategorized') ?></small>
                            <h6 class="mt-1 fw-semibold" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= esc($product['title']) ?></h6>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="price-tag"><?= formatPrice($product['price']) ?></span>
                                <button class="btn btn-sm btn-outline-custom add-to-cart" data-id="<?= $product['id'] ?>"><i class="bi bi-cart-plus"></i></button>
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
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        var btn = $(this);
        var id = btn.data('id');
        $.post('<?= site_url('/cart/add') ?>', { id: id, quantity: 1 }, function(res) {
            if (res.status === 'success') {
                $('#cartCount').text(res.count);
                btn.html('<i class="bi bi-check"></i>').removeClass('btn-outline-custom').addClass('btn-primary-custom');
                setTimeout(function() {
                    btn.html('<i class="bi bi-cart-plus"></i>').addClass('btn-outline-custom').removeClass('btn-primary-custom');
                }, 2000);
            }
        });
    });
});
</script>

<?= view('layouts/footer') ?>
