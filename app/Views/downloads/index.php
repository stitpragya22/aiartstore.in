<?= view('layouts/header') ?>

<section class="py-5">
    <div class="container">
        <h1 class="section-title mb-4">My Downloads</h1>

        <?php if (empty($downloads)): ?>
            <div class="empty-state">
                <i class="bi bi-download"></i>
                <h4>No downloads yet</h4>
                <p class="text-muted">Purchased artworks will appear here for download</p>
                <a href="<?= site_url('/shop') ?>" class="btn btn-primary-custom"><i class="bi bi-grid me-2"></i>Browse Gallery</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($downloads as $dl): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="stat-card h-100">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <?php if ($dl['image']): ?>
                                <img src="<?= base_url('uploads/products/' . $dl['image']) ?>" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            <?php endif; ?>
                            <div>
                                <h6 class="fw-bold mb-0"><?= esc($dl['title']) ?></h6>
                                <small class="text-muted">Downloaded <?= $dl['download_count'] ?> times</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?= site_url('/download/file/' . $dl['product_id'] . '/' . $dl['order_id']) ?>" class="btn btn-primary-custom flex-grow-1">
                                <i class="bi bi-download me-1"></i>Download
                            </a>
                        </div>
                        <?php if ($dl['file_size']): ?>
                        <small class="text-muted mt-2 d-block">File size: <?= esc($dl['file_size']) ?></small>
                        <?php endif; ?>
                        <?php if ($dl['dimensions']): ?>
                        <small class="text-muted d-block">Dimensions: <?= esc($dl['dimensions']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= view('layouts/footer') ?>
