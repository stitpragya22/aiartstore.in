<?= view('layouts/header') ?>

<style>
.gallery-img {
    width: 100%;
    border-radius: 12px;
    cursor: pointer;
    transition: transform 0.3s;
    border: 1px solid var(--border-color);
    display: block;
}
.gallery-img:hover {
    transform: scale(1.02);
}
.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
}
.gallery-item .dl-overlay {
    position: absolute;
    top: 0;
    right: 0;
    width: 44px;
    height: 44px;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(6px);
    border: none;
    color: white;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    border-radius: 0 12px 0 12px;
    z-index: 2;
    text-decoration: none;
}
.gallery-item .dl-overlay:hover {
    background: rgba(139,92,246,0.85);
    width: 48px;
    height: 48px;
}
.prompt-content {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 1.5rem;
}
.prompt-content pre {
    background: var(--bg-secondary);
    color: var(--text-primary);
    padding: 1rem;
    border-radius: 10px;
    font-size: 0.85rem;
    white-space: pre-wrap;
    word-break: break-word;
    border: 1px solid var(--border-color);
}

/* Lightbox */
.lightbox {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0,0,0,0.92);
    backdrop-filter: blur(8px);
    justify-content: center;
    align-items: center;
}
.lightbox.active {
    display: flex;
}
.lightbox .lb-close {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    border: none;
    color: white;
    font-size: 1.4rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.3s;
    z-index: 10;
}
.lightbox .lb-close:hover {
    background: rgba(255,255,255,0.2);
}
.lightbox .lb-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    border: none;
    color: white;
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.3s;
    z-index: 10;
}
.lightbox .lb-nav:hover {
    background: rgba(255,255,255,0.25);
}
.lightbox .lb-prev { left: 20px; }
.lightbox .lb-next { right: 20px; }
.lightbox .lb-counter {
    position: absolute;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    color: rgba(255,255,255,0.6);
    font-size: 0.85rem;
    z-index: 10;
    background: rgba(0,0,0,0.5);
    padding: 4px 14px;
    border-radius: 20px;
}
.lightbox .lb-download {
    position: absolute;
    bottom: 24px;
    right: 24px;
    z-index: 10;
    background: rgba(139,92,246,0.85);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 10px 18px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background 0.3s;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
}
.lightbox .lb-download:hover {
    background: rgba(139,92,246,1);
}
.lightbox .lb-img {
    max-width: 90vw;
    max-height: 90vh;
    border-radius: 8px;
    object-fit: contain;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
}
</style>

<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= site_url('/prompts') ?>" style="color: #a78bfa;">Prompt Library</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= esc($prompt['title']) ?></li>
            </ol>
        </nav>

        <div class="row g-4">
            <?php if (!empty($images)): ?>
            <div class="col-lg-8">
                <div class="row g-3">
                    <?php foreach ($images as $i => $img): ?>
                    <div class="<?= count($images) === 1 ? 'col-12' : 'col-6 col-md-4' ?>">
                        <div class="gallery-item">
                            <img src="<?= base_url('uploads/prompts/' . $img['image']) ?>"
                                 alt="<?= esc($prompt['title']) ?>"
                                 class="gallery-img"
                                 loading="lazy"
                                 data-index="<?= $i ?>"
                                 onclick="openLightbox(<?= $i ?>)">
                            <a href="<?= base_url('uploads/prompts/' . $img['image']) ?>" download class="dl-overlay" title="Download"><i class="bi bi-download"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="col-lg-4">
                <div class="prompt-content">
                    <?php if (!empty($prompt['category_name'])): ?>
                    <span class="badge-status mb-2" style="background:rgba(99,102,241,0.2);color:#818cf8;"><?= esc($prompt['category_name']) ?></span>
                    <?php endif; ?>

                    <h1 class="fw-bold mb-3" style="font-size: 1.5rem;"><?= esc($prompt['title']) ?></h1>

                    <?php if (!empty($prompt['prompt'])): ?>
                    <h6 class="fw-semibold mb-2">Prompt</h6>
                    <div class="position-relative">
                        <pre><?= esc($prompt['prompt']) ?></pre>
                        <button type="button" class="btn btn-sm position-absolute" style="top: 8px; right: 8px; background: rgba(0,0,0,0.5); color: white; border: none; border-radius: 6px; padding: 4px 10px; font-size: 0.75rem;" onclick="copyDetailPrompt()">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($prompt['notes'])): ?>
                    <h6 class="fw-semibold mb-2 mt-3">Notes</h6>
                    <p class="text-muted small mb-0"><?= esc($prompt['notes']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox -->
<div class="lightbox" id="lightbox" onclick="closeLightbox(event)">
    <button class="lb-close" onclick="closeLightbox()"><i class="bi bi-x-lg"></i></button>
    <?php if (count($images) > 1): ?>
    <button class="lb-nav lb-prev" onclick="prevImage(event)"><i class="bi bi-chevron-left"></i></button>
    <button class="lb-nav lb-next" onclick="nextImage(event)"><i class="bi bi-chevron-right"></i></button>
    <?php endif; ?>
    <img class="lb-img" id="lbImg" src="" alt="">
    <span class="lb-counter" id="lbCounter"></span>
    <a class="lb-download" id="lbDownload" href="" download><i class="bi bi-download"></i> Download</a>
</div>

<script>
<?php
$imagesData = [];
foreach ($images as $img) {
    $imagesData[] = [
        'url' => base_url('uploads/prompts/' . $img['image']),
        'filename' => $img['image'],
    ];
}
?>
var lbImages = <?= json_encode($imagesData) ?>;
var lbCurrent = 0;

function openLightbox(index) {
    lbCurrent = index;
    updateLightbox();
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLightbox(e) {
    if (e && e.target !== e.currentTarget) return;
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = '';
}

function nextImage(e) {
    if (e) e.stopPropagation();
    lbCurrent = (lbCurrent + 1) % lbImages.length;
    updateLightbox();
}

function prevImage(e) {
    if (e) e.stopPropagation();
    lbCurrent = (lbCurrent - 1 + lbImages.length) % lbImages.length;
    updateLightbox();
}

function updateLightbox() {
    var img = lbImages[lbCurrent];
    document.getElementById('lbImg').src = img.url;
    document.getElementById('lbDownload').href = img.url;
    document.getElementById('lbCounter').textContent = (lbCurrent + 1) + ' / ' + lbImages.length;
}

document.addEventListener('keydown', function(e) {
    if (!document.getElementById('lightbox').classList.contains('active')) return;
    if (e.key === 'Escape') closeLightbox();
    <?php if (count($images) > 1): ?>
    if (e.key === 'ArrowRight') nextImage(e);
    if (e.key === 'ArrowLeft') prevImage(e);
    <?php endif; ?>
});

function copyDetailPrompt() {
    var text = <?= json_encode($prompt['prompt'] ?? '') ?>;
    if (!text) { showToast('Nothing to copy', 'error'); return; }
    navigator.clipboard.writeText(text).then(function() {
        showToast('Prompt copied!', 'success');
    }).catch(function() {
        showToast('Failed to copy', 'error');
    });
}
</script>

<?= view('layouts/footer') ?>
