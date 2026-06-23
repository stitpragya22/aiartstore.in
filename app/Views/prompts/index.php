<?= view('layouts/header') ?>

<style>
.prompt-masonry {
    column-count: 3;
    column-gap: 16px;
}
.prompt-masonry .prompt-card {
    display: inline-block;
    width: 100%;
    margin-bottom: 16px;
    break-inside: avoid;
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
}
.prompt-masonry .prompt-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.35);
}
.prompt-masonry .prompt-card .img-wrap {
    position: relative;
    overflow: hidden;
}
.prompt-masonry .prompt-card .img-wrap img {
    width: 100%;
    height: auto;
    display: block;
}
.prompt-masonry .prompt-card .body {
    padding: 1rem;
}
.prompt-masonry .prompt-card .body h6 {
    color: #ffffff;
    font-weight: 600;
    margin-bottom: 0.25rem;
}
.prompt-masonry .prompt-card .body .cat-badge {
    color: #a78bfa;
    font-size: 0.7rem;
    background: rgba(99,102,241,0.2);
    padding: 2px 8px;
    border-radius: 6px;
}
.prompt-masonry .prompt-card .lock-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}
.prompt-masonry .prompt-card .lock-overlay i {
    font-size: 2rem;
    color: rgba(255,255,255,0.7);
}
.level-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    font-size: 0.6rem;
    padding: 2px 8px;
    border-radius: 6px;
    font-weight: 600;
    z-index: 3;
}
@media (max-width: 991px) { .prompt-masonry { column-count: 2; } }
@media (max-width: 576px) { .prompt-masonry { column-count: 1; } }
</style>

<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h1 class="fw-bold mb-1">Prompt Library</h1>
                <p class="text-secondary mb-0">
                    <?php if ($isLoggedIn): ?>
                    <?php $levelLabels = ['Free', 'Pro', 'Premium']; ?>
                    Your access level: <span class="badge-status ms-1" style="background:rgba(139,92,246,0.2);color:#a78bfa;"><?= $levelLabels[$userLevel] ?? 'Free' ?></span>
                    <?php else: ?>
                    Browse our free AI prompts. <a href="<?= site_url('/login') ?>" style="color: #a78bfa;">Log in</a> for more.
                    <?php endif; ?>
                </p>
            </div>
            <?php if ($isLoggedIn): ?>
            <a href="<?= site_url('/subscriptions/plans') ?>" class="btn" style="background: linear-gradient(135deg, #8b5cf6, #6366f1); color: white; border: none; padding: 8px 20px; border-radius: 10px; font-weight: 600; font-size: 0.9rem;">Upgrade Plan</a>
            <?php endif; ?>
        </div>

        <?php if (!empty($categories)): ?>
        <div class="mb-4 d-flex flex-wrap gap-2">
            <a href="<?= site_url('/prompts') ?>" class="btn btn-sm <?= !service('request')->getGet('category') ? 'btn-primary-custom' : 'btn-outline-custom' ?>">All</a>
            <?php foreach ($categories as $cat): ?>
            <a href="<?= site_url('/prompts?category=' . $cat['id']) ?>" class="btn btn-sm <?= service('request')->getGet('category') == $cat['id'] ? 'btn-primary-custom' : 'btn-outline-custom' ?>"><?= esc($cat['name']) ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($prompts)): ?>
        <div class="text-center py-5">
            <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: var(--text-muted);"></i>
            <p class="text-muted mt-3 mb-0">No prompts available.</p>
        </div>
        <?php else: ?>
        <div class="prompt-masonry">
            <?php foreach ($prompts as $p): $canAccess = $p['min_subscription_level'] <= $userLevel; $images = $groupedImages[$p['id']] ?? []; ?>
            <div class="prompt-card" onclick="handlePromptClick(this, <?= $p['id'] ?>, <?= $canAccess ? 'true' : 'false' ?>)">
                <?php if (!empty($images)): $first = $images[0]; ?>
                <div class="img-wrap" style="background: var(--bg-secondary);">
                    <img src="<?= base_url('uploads/prompts/' . $first['image']) ?>" alt="<?= esc($p['title']) ?>" loading="lazy">
                    <?php if (!$canAccess): ?>
                    <div class="lock-overlay"><i class="bi bi-lock-fill"></i></div>
                    <?php endif; ?>
                    <?php $levelLabels = ['Free', 'Pro', 'Premium']; $levelColors = ['#10b981', '#818cf8', '#f59e0b']; ?>
                    <span class="level-badge" style="background:<?= $levelColors[$p['min_subscription_level']] ?? '#10b981' ?>;color:#fff;"><?= $levelLabels[$p['min_subscription_level']] ?? 'Free' ?></span>
                </div>
                <?php endif; ?>
                <div class="body">
                    <?php if (!empty($p['category_name'])): ?>
                    <span class="cat-badge"><?= esc($p['category_name']) ?></span>
                    <?php endif; ?>
                    <h6><?= esc($p['title']) ?></h6>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
var slugMap = <?= !empty($prompts) ? json_encode(array_combine(array_column($prompts, 'id'), array_map(function($p) { return $p['slug'] ?: url_title($p['title'], '-', true); }, $prompts))) : '{}' ?>;

function handlePromptClick(el, id, canAccess) {
    if (canAccess) {
        window.location.href = '<?= site_url('/prompts/') ?>' + id + '/' + slugMap[id];
    } else {
        showToast('Please subscribe to access this prompt', 'error');
    }
}
</script>

<?= view('layouts/footer') ?>
