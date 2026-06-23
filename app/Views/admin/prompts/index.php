<?= view('admin/layouts/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Prompt Library</h4>
        <p class="text-muted mb-0">Your saved AI prompts and reference images</p>
    </div>
    <a href="<?= site_url('/admin/prompts/create') ?>" class="btn btn-primary-custom"><i class="bi bi-plus-lg me-1"></i>Add Prompt</a>
</div>

<?php if (empty($prompts)): ?>
<div class="text-center py-5">
    <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: var(--text-muted);"></i>
    <p class="text-muted mt-3 mb-0">No prompts saved yet.</p>
    <a href="<?= site_url('/admin/prompts/create') ?>" class="btn btn-primary-custom mt-3"><i class="bi bi-plus-lg me-1"></i>Add Your First Prompt</a>
</div>
<?php else: ?>
<div class="row g-3 row-cols-1 row-cols-md-2 row-cols-lg-4">
    <?php $cardIndex = 0; foreach ($prompts as $p): $cardIndex++; $images = $groupedImages[$p['id']] ?? []; $carouselId = 'promptCarousel' . $p['id']; ?>
    <div class="col">
        <div class="card-art h-100 position-relative" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden;">
            <!-- Image Carousel -->
            <div style="position: relative; height: 180px; background: var(--bg-secondary); overflow: hidden;">
                <?php if (!empty($images)): ?>
                <div id="<?= $carouselId ?>" class="carousel slide h-100" data-bs-ride="false" data-bs-interval="false">
                    <div class="carousel-inner h-100">
                        <?php $imgIdx = 0; foreach ($images as $img): $src = base_url('uploads/prompts/' . $img['image']); ?>
                        <div class="carousel-item h-100 <?= $imgIdx === 0 ? 'active' : '' ?>" style="background: var(--bg-secondary);">
                            <img src="<?= $src ?>" aria-hidden="true" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; filter: blur(24px); opacity: 0.6;">
                            <img src="<?= $src ?>" alt="Reference <?= $imgIdx + 1 ?>" style="position: relative; width: 100%; height: 100%; object-fit: contain; z-index: 1;">
                            <a href="<?= $src ?>" download class="position-absolute" style="top: 6px; right: 6px; width: 28px; height: 28px; border-radius: 50%; background: rgba(0,0,0,0.55); color: white; border: none; display: flex; align-items: center; justify-content: center; text-decoration: none; z-index: 3;" title="Download image">
                                <i class="bi bi-download" style="font-size: 0.75rem;"></i>
                            </a>
                        </div>
                        <?php $imgIdx++; endforeach; ?>
                    </div>
                    <?php if (count($images) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev" style="width: 28px; height: 28px; top: 50%; transform: translateY(-50%); border-radius: 50%; background: rgba(0,0,0,0.5); border: none; margin: 0 6px;">
                        <i class="bi bi-chevron-left" style="font-size: 0.75rem; color: white;"></i>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next" style="width: 28px; height: 28px; top: 50%; transform: translateY(-50%); border-radius: 50%; background: rgba(0,0,0,0.5); border: none; margin: 0 6px;">
                        <i class="bi bi-chevron-right" style="font-size: 0.75rem; color: white;"></i>
                    </button>
                    <?php endif; ?>
                </div>
                <div style="position: absolute; bottom: 6px; right: 8px; background: rgba(0,0,0,0.6); color: white; font-size: 0.65rem; padding: 2px 8px; border-radius: 10px; z-index: 2;">
                    <i class="bi bi-image me-1"></i><?= count($images) ?>
                </div>
                <?php else: ?>
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                    <i class="bi bi-image" style="font-size: 2.5rem;"></i>
                </div>
                <?php endif; ?>
            </div>

            <!-- Card Body -->
            <div class="p-3 d-flex flex-column">
                <h6 class="fw-semibold mb-1" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;"><?= esc($p['title']) ?></h6>

                <?php if (!empty($p['category_id']) && isset($catMap[$p['category_id']])): ?>
                <div class="mb-1">
                    <span class="badge-status" style="background:rgba(99,102,241,0.2);color:#818cf8;font-size:0.6rem;"><?= esc($catMap[$p['category_id']]) ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($p['prompt'])): ?>
                <p class="text-muted small mb-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.4em;"><?= esc($p['prompt']) ?></p>
                <?php endif; ?>

                <?php if (!empty($p['notes'])): ?>
                <p class="text-muted small mb-2" style="font-size: 0.7rem; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; opacity: 0.7;"><?= esc($p['notes']) ?></p>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mt-auto pt-2" style="gap:4px;flex-wrap:wrap;">
                    <div class="d-flex gap-1 align-items-center">
                        <?php $levelLabels = ['Free', 'Pro', 'Premium']; $levelColors = ['#10b981', '#818cf8', '#f59e0b']; ?>
                        <span class="badge-status" style="background:rgba(139,92,246,0.15);color:<?= $levelColors[$p['min_subscription_level']] ?? '#10b981' ?>;font-size:0.6rem;">
                            <?= $levelLabels[$p['min_subscription_level']] ?? 'Free' ?>
                        </span>
                        <span class="badge-status <?= $p['status'] ?>" style="font-size: 0.6rem;"><?= ucfirst($p['status']) ?></span>
                    </div>
                    <div class="d-flex gap-1">
                        <div class="dropdown d-inline">
                            <button type="button" class="btn btn-sm btn-outline-custom dropdown-toggle share-fb" data-id="<?= $p['id'] ?>" style="padding: 2px 10px; font-size: 0.75rem; border-color: rgba(59,89,152,0.3); color: #3b5998;" title="Share to Facebook" data-bs-toggle="dropdown"><i class="bi bi-facebook"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end" style="min-width: 140px; font-size: 0.8rem;">
                                <li><a class="dropdown-item share-fb-link" href="#" data-id="<?= $p['id'] ?>"><i class="bi bi-link-45deg me-2"></i>Share Link</a></li>
                                <li><a class="dropdown-item share-fb-photo" href="#" data-id="<?= $p['id'] ?>"><i class="bi bi-image me-2"></i>Share Photo</a></li>
                                <li><a class="dropdown-item share-fb-gallery" href="#" data-id="<?= $p['id'] ?>"><i class="bi bi-images me-2"></i>Share Gallery</a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-custom share-ig" data-id="<?= $p['id'] ?>" style="padding: 2px 10px; font-size: 0.75rem; border-color: rgba(228,64,95,0.3); color: #e4405f;" title="Share to Instagram"><i class="bi bi-instagram"></i></button>
                        <a href="<?= site_url('/admin/prompts/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-custom" style="padding: 2px 10px; font-size: 0.75rem;" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="<?= site_url('/admin/prompts/delete/' . $p['id']) ?>" method="POST" onsubmit="return confirm('Delete this prompt and all its images?')" class="d-inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-custom" style="padding: 2px 10px; font-size: 0.75rem; border-color: rgba(239,68,68,0.3); color: var(--danger);" title="Delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
$(document).ready(function() {
    function sharePrompt(button, platform) {
        var id = button.data('id');
        var url = platform === 'fb'
            ? '<?= site_url('/admin/prompts/share-facebook/') ?>' + id
            : '<?= site_url('/admin/prompts/share-instagram/') ?>' + id;

        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span>');

        $.post(url, {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(res) {
            if (res.success) {
                showToast('Posted to ' + (platform === 'fb' ? 'Facebook' : 'Instagram') + ' successfully!', 'success');
            } else {
                showToast(res.message || 'Failed to post', 'error');
            }
        }).fail(function(jqXHR) {
            var msg = 'Request failed (HTTP ' + jqXHR.status + ')';
            if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                msg += ': ' + jqXHR.responseJSON.message;
            }
            showToast(msg, 'error');
        }).always(function() {
            button.prop('disabled', false).html('<i class="bi bi-' + (platform === 'fb' ? 'facebook' : 'instagram') + '"></i>');
        });
    }

    function shareFacebook(button, type) {
        var id = button.data('id');
        var urlMap = {
            'link': '<?= site_url('/admin/prompts/share-facebook/') ?>' + id,
            'photo': '<?= site_url('/admin/prompts/share-facebook-photo/') ?>' + id,
            'gallery': '<?= site_url('/admin/prompts/share-facebook-gallery/') ?>' + id,
        };
        var url = urlMap[type];
        if (!url) return;

        var label = type.charAt(0).toUpperCase() + type.slice(1);
        button.closest('.dropdown').find('.share-fb').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span>');

        $.post(url, {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(res) {
            if (res.success) {
                showToast('Facebook ' + label + ' posted successfully!', 'success');
            } else {
                showToast(res.message || 'Failed to post', 'error');
            }
        }).fail(function(jqXHR) {
            var msg = 'Request failed (HTTP ' + jqXHR.status + ')';
            if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                msg += ': ' + jqXHR.responseJSON.message;
            }
            showToast(msg, 'error');
        }).always(function() {
            var btn = button.closest('.dropdown').find('.share-fb');
            btn.prop('disabled', false).html('<i class="bi bi-facebook"></i>');
        });
    }

    $(document).on('click', '.share-fb-link', function(e) {
        e.preventDefault();
        shareFacebook($(this), 'link');
    });

    $(document).on('click', '.share-fb-photo', function(e) {
        e.preventDefault();
        shareFacebook($(this), 'photo');
    });

    $(document).on('click', '.share-fb-gallery', function(e) {
        e.preventDefault();
        shareFacebook($(this), 'gallery');
    });

    $(document).on('click', '.share-ig', function() {
        sharePrompt($(this), 'ig');
    });
});
</script>

<?= view('admin/layouts/footer') ?>
