<?= view('layouts/header') ?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-lg-8">
            <h1 class="display-6 fw-bold"><?= $current_category ? esc($current_category['name']) : 'Blog' ?></h1>
            <?php if ($current_category && $current_category['description']): ?>
                <p class="section-subtitle lead"><?= esc($current_category['description']) ?></p>
            <?php else: ?>
                <p class="section-subtitle">Insights, guides, and stories from the world of AI art and digital creativity.</p>
            <?php endif ?>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= site_url('blog') ?>" class="btn btn-sm <?= !$current_category ? 'btn-primary' : 'btn-outline-secondary' ?> rounded-pill">All</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="<?= site_url('blog?category=' . $cat['slug']) ?>" class="btn btn-sm <?= ($current_category && $current_category['id'] == $cat['id']) ? 'btn-primary' : 'btn-outline-secondary' ?> rounded-pill"><?= esc($cat['name']) ?></a>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <?php if (empty($posts)): ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <h5 class="text-muted">No posts yet. Check back soon!</h5>
                </div>
            </div>
        <?php endif ?>

        <?php foreach ($posts as $p): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm blog-card">
                    <?php if ($p['featured_image']): ?>
                        <a href="<?= site_url('blog/' . esc($p['slug'])) ?>">
                            <img src="<?= base_url($p['featured_image']) ?>" class="card-img-top" alt="<?= esc($p['title']) ?>" loading="lazy">
                        </a>
                    <?php endif ?>
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <?php if ($p['category_name']): ?>
                                <a href="<?= site_url('blog?category=' . esc($p['category_slug'])) ?>" class="badge bg-primary text-decoration-none"><?= esc($p['category_name']) ?></a>
                            <?php endif ?>
                            <?php if ($p['published_at']): ?>
                                <small class="text-muted ms-2"><?= date('M d, Y', strtotime($p['published_at'])) ?></small>
                            <?php endif ?>
                        </div>
                        <h5 class="card-title">
                            <a href="<?= site_url('blog/' . esc($p['slug'])) ?>" class="text-decoration-none text-dark stretched-link"><?= esc($p['title']) ?></a>
                        </h5>
                        <p class="card-text text-muted small flex-grow-1">
                            <?= esc($p['excerpt'] ? mb_substr(strip_tags($p['excerpt']), 0, 150) : mb_substr(strip_tags($p['content']), 0, 150)) ?>...
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <small class="text-muted">
                                <?php if ($p['tags']): ?>
                                    <?php $tags = explode(',', $p['tags']); ?>
                                    <?php foreach (array_slice($tags, 0, 2) as $tag): ?>
                                        <span class="badge bg-light text-dark me-1">#<?= esc(trim($tag)) ?></span>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </small>
                            <small><a href="<?= site_url('blog/' . esc($p['slug'])) ?>" class="fw-bold text-decoration-none">Read More &rarr;</a></small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>

<style>
.blog-card { transition: transform .2s ease, box-shadow .2s ease; }
.blog-card:hover { transform: translateY(-4px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important; }
.blog-card .card-img-top { height: 200px; object-fit: cover; }
</style>

<?= view('layouts/footer') ?>
