<!DOCTYPE html>
<html lang="<?= esc($p['language'] ?? 'en') ?>">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BY67JPBVPG"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-BY67JPBVPG');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($p['title']) ?></title>
    <meta name="description" content="<?= esc($p['meta_description'] ?? '') ?>">
    <meta name="keywords" content="<?= esc($p['keywords'] ?? '') ?>">
    <meta property="og:title" content="<?= esc($p['title']) ?>">
    <meta property="og:description" content="<?= esc($p['meta_description'] ?? '') ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= site_url('/lp/' . $p['slug']) ?>">
    <?php if ($p['hero_image_backgroun']): ?>
    <meta property="og:image" content="<?= base_url('uploads/landing_pages/' . $p['hero_image_backgroun']) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="<?= base_url('uploads/landing_pages/' . $p['hero_image_backgroun']) ?>">
    <?php endif; ?>
    <link rel="canonical" href="<?= site_url('/lp/' . $p['slug']) ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0a0a0f; color: #f1f1f6; overflow-x: hidden; }
        .hero {
            min-height: 100vh; display: flex; align-items: center; justify-content: center; text-align: center;
            position: relative; padding: 2rem;
            background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 50%, #0a0a0f 100%);
        }
        <?php if ($p['hero_image_backgroun']): ?>
        .hero {
            background: linear-gradient(135deg, rgba(10,10,15,0.85), rgba(26,26,46,0.85)),
                        url('<?= base_url('uploads/landing_pages/' . $p['hero_image_backgroun']) ?>') center/cover no-repeat;
        }
        <?php endif; ?>
        <?php if ($p['video_link_youtube']): ?>
        .hero-video-bg {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; overflow: hidden;
            background-size: cover; background-position: center;
        }
        .hero-play-btn {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); z-index: 2;
            color: #fff; font-size: 4rem; line-height: 1; transition: all 0.3s; opacity: 0.9; text-decoration: none;
        }
        .hero-play-btn:hover { color: #8b5cf6; transform: translate(-50%,-50%) scale(1.1); opacity: 1; }
        .hero-video-overlay { position: absolute; inset: 0; background: rgba(10,10,15,0.55); z-index: 1; }
        <?php endif; ?>
        .hero-content { position: relative; z-index: 1; max-width: 800px; }
        .hero h1 { font-size: clamp(2rem, 6vw, 4.5rem); font-weight: 900; line-height: 1.1; margin-bottom: 1rem; }
        .hero h1 span { background: linear-gradient(135deg, #8b5cf6, #6366f1); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hero p { font-size: clamp(1rem, 2vw, 1.3rem); color: #a0a0b8; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto; }
        .price-display { margin-bottom: 2rem; }
        .price-display .old-price { font-size: 1.5rem; color: #6b6b80; text-decoration: line-through; margin-right: 1rem; }
        .price-display .new-price { font-size: clamp(2rem, 4vw, 3.5rem); font-weight: 800; color: #8b5cf6; }
        .btn-cta {
            display: inline-block; padding: 1rem 2.5rem; font-size: 1.1rem; font-weight: 700;
            background: linear-gradient(135deg, #8b5cf6, #6366f1); color: #fff; border-radius: 50px;
            text-decoration: none; transition: all 0.3s; border: none; cursor: pointer;
        }
        .btn-cta:hover { transform: translateY(-3px); box-shadow: 0 10px 40px rgba(139,92,246,0.4); color: #fff; }
        .timer-bar { background: rgba(139,92,246,0.15); border: 1px solid rgba(139,92,246,0.3); border-radius: 12px; padding: 1rem 2rem; display: inline-block; margin-bottom: 1.5rem; }
        .timer-bar span { font-weight: 700; color: #8b5cf6; }
        .section { padding: 4rem 1rem; }
        .section-title { font-size: clamp(1.5rem, 3vw, 2.5rem); font-weight: 800; text-align: center; margin-bottom: 3rem; }
        .feature-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1.5rem; max-width: 900px; margin: 0 auto; }
        .feature-item { background: #1a1a2e; border: 1px solid #2a2a40; border-radius: 16px; padding: 1.5rem; text-align: center; transition: all 0.3s; }
        .feature-item:hover { border-color: #8b5cf6; transform: translateY(-4px); }
        .feature-item img { width: 100%; height: 120px; object-fit: cover; border-radius: 10px; margin-bottom: 0.75rem; }
        .feature-item h5 { font-size: 0.9rem; font-weight: 600; }
        .intro-section { background: #12121a; }
        .intro-wrapper { display: flex; align-items: center; gap: 3rem; max-width: 1000px; margin: 0 auto; flex-wrap: wrap; }
        .intro-wrapper img, .intro-wrapper .video-placeholder { flex: 1; min-width: 280px; border-radius: 16px; }
        .intro-wrapper .intro-text { flex: 1; min-width: 280px; }
        .intro-wrapper .intro-text h2 { font-size: clamp(1.3rem, 2.5vw, 2rem); font-weight: 700; margin-bottom: 1rem; }
        .intro-wrapper .intro-text p { color: #a0a0b8; line-height: 1.7; }
        .offerings-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; max-width: 1000px; margin: 0 auto; }
        .offering-card { background: #1a1a2e; border: 1px solid #2a2a40; border-radius: 16px; padding: 1.5rem; transition: all 0.3s; }
        .offering-card:hover { border-color: #8b5cf6; }
        .offering-card img { width: 100%; height: 180px; object-fit: cover; border-radius: 12px; margin-bottom: 1rem; }
        .offering-card h4 { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; }
        .offering-card p { color: #a0a0b8; font-size: 0.9rem; line-height: 1.6; }
        .testimonial-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; max-width: 900px; margin: 0 auto; }
        .testimonial-card { background: #1a1a2e; border: 1px solid #2a2a40; border-radius: 16px; padding: 1.5rem; text-align: center; }
        .testimonial-card img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 1rem; }
        .testimonial-card iframe { width: 100%; height: 200px; border-radius: 12px; border: none; }
        .footer-section { background: #12121a; border-top: 1px solid #2a2a40; text-align: center; }
        .footer-section h2 { font-size: clamp(1.5rem, 3vw, 2rem); font-weight: 800; margin-bottom: 0.5rem; }
        .footer-section p { color: #a0a0b8; margin-bottom: 2rem; }
        .footer-links { display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; max-width: 600px; margin: 0 auto; }
        .footer-links a { color: #8b5cf6; text-decoration: none; font-size: 0.9rem; }
        .footer-links a:hover { text-decoration: underline; }
        .reserve-msg { color: #f59e0b; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem; }
        .seats-left { color: #ef4444; font-weight: 700; }
        @media (max-width: 768px) {
            .hero { padding: 1rem; }
            .feature-grid { grid-template-columns: repeat(2, 1fr); }
            .intro-wrapper { flex-direction: column; text-align: center; }
        }
    </style>
    <?= $p['custom_js'] ?? '' ?>
</head>
<body>

<!-- HERO -->
<section class="hero">
    <?php
    $raw = $p['video_link_youtube'] ?? '';
    $ytId = '';
    $ytWatch = '';
    if ($raw) {
        if (preg_match('/src="([^"]+)"/', $raw, $m)) {
            $raw = $m[1];
        }
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $raw, $m)) {
            $ytId = $m[1];
            $ytWatch = 'https://www.youtube.com/watch?v=' . $ytId;
        }
    }
    if ($ytId):
    $thumb = 'https://img.youtube.com/vi/' . $ytId . '/maxresdefault.jpg';
    ?>
    <div class="hero-video-bg" style="background-image: url('<?= esc($thumb) ?>');">
        <a href="<?= esc($ytWatch) ?>" target="_blank" class="hero-play-btn" aria-label="Watch on YouTube"><i class="bi bi-play-circle-fill"></i></a>
        <div class="hero-video-overlay"></div>
    </div>
    <?php endif; ?>
    <div class="hero-content">
        <?php if ($p['reserv_seat_messsage']): ?>
        <p class="reserve-msg"><?= esc($p['reserv_seat_messsage']) ?></p>
        <?php endif; ?>
        <h1><?= nl2br($p['headline'] ?? esc($p['title'])) ?></h1>
        <?php if ($p['subheadline']): ?>
        <p><?= esc($p['subheadline']) ?></p>
        <?php endif; ?>
        <?php if ($p['old_price_of_seminar'] || $p['new_price_of_seminar']): ?>
        <div class="price-display">
            <?php if ($p['old_price_of_seminar']): ?>
            <span class="old-price"><?= esc($p['old_price_of_seminar']) ?></span>
            <?php endif; ?>
            <?php if ($p['new_price_of_seminar']): ?>
            <span class="new-price"><?= esc($p['new_price_of_seminar']) ?></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php if ($p['timer_time_in_minutes']): ?>
        <div class="timer-bar" id="countdown">⏳ <span id="timerDisplay"></span></div>
        <?php endif; ?>
        <a href="<?= esc($p['redirection_link'] ?? site_url('/shop')) ?>" class="btn-cta" target="_blank">
            <?= esc($p['_intro_join_button_text'] ?? 'Get It Now') ?>
        </a>
    </div>
</section>

<!-- FEATURE IMAGES -->
<?php
$features = [];
for ($i = 1; $i <= 6; $i++) {
    if (!empty($p['feature_image_' . $i])) {
        $features[] = $p['feature_image_' . $i];
    }
}
if (!empty($features)): ?>
<section class="section">
    <div class="feature-grid">
        <?php foreach ($features as $img): ?>
        <div class="feature-item">
            <img src="<?= base_url('uploads/landing_pages/' . $img) ?>" alt="" loading="lazy">
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- INTRO -->
<?php if ($p['intro_title'] || $p['intro_content'] || $p['intro_image'] || $p['intro_video_link']): ?>
<section class="section intro-section">
    <div class="intro-wrapper">
        <?php if ($p['intro_image']): ?>
        <img src="<?= base_url('uploads/landing_pages/' . $p['intro_image']) ?>" alt="<?= esc($p['intro_title']) ?>" loading="lazy">
        <?php elseif ($p['intro_video_link']): ?>
        <div class="video-placeholder">
            <iframe src="<?= esc($p['intro_video_link']) ?>" frameborder="0" allowfullscreen style="width:100%;height:300px;border-radius:16px;"></iframe>
        </div>
        <?php endif; ?>
        <div class="intro-text">
            <?php if ($p['intro_title']): ?>
            <h2><?= esc($p['intro_title']) ?></h2>
            <?php endif; ?>
            <?php if ($p['intro_content']): ?>
            <p><?= nl2br(esc($p['intro_content'])) ?></p>
            <?php endif; ?>
            <a href="<?= esc($p['redirection_link'] ?? site_url('/shop')) ?>" class="btn-cta" style="margin-top:1rem;" target="_blank">
                <?= esc($p['_intro_join_button_text'] ?? 'Get It Now') ?>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- OFFERINGS -->
<?php
$offerings = [];
for ($i = 1; $i <= 6; $i++) {
    if (!empty($p['workshop_title_' . $i]) || !empty($p['workshop_image_' . $i])) {
        $offerings[] = [
            'image'   => $p['workshop_image_' . $i] ?? '',
            'title'   => $p['workshop_title_' . $i] ?? '',
            'details' => $p['workshop_details_' . $i] ?? '',
        ];
    }
}
if (!empty($offerings) || !empty($p['workshop_section_title'])): ?>
<section class="section">
    <?php if ($p['workshop_section_title']): ?>
    <h2 class="section-title"><?= esc($p['workshop_section_title']) ?></h2>
    <?php endif; ?>
    <div class="offerings-grid">
        <?php foreach ($offerings as $off): ?>
        <div class="offering-card">
            <?php if ($off['image']): ?>
            <img src="<?= base_url('uploads/landing_pages/' . $off['image']) ?>" alt="<?= esc($off['title']) ?>" loading="lazy">
            <?php endif; ?>
            <h4><?= esc($off['title']) ?></h4>
            <p><?= nl2br(esc($off['details'])) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- TESTIMONIALS -->
<?php
$testimonials = [];
for ($i = 1; $i <= 3; $i++) {
    if (!empty($p['testimonial_image_' . $i]) || !empty($p['testimonial_video_link_' . $i])) {
        $testimonials[] = [
            'image' => $p['testimonial_image_' . $i] ?? '',
            'video' => $p['testimonial_video_link_' . $i] ?? '',
        ];
    }
}
if (!empty($testimonials) || !empty($p['testimonial_section_title'])): ?>
<section class="section intro-section">
    <?php if ($p['testimonial_section_title']): ?>
    <h2 class="section-title"><?= esc($p['testimonial_section_title']) ?></h2>
    <?php endif; ?>
    <div class="testimonial-grid">
        <?php foreach ($testimonials as $t):
            $tRaw = $t['video'] ?? '';
            $tId = '';
            if ($tRaw) {
                if (preg_match('/src="([^"]+)"/', $tRaw, $m)) $tRaw = $m[1];
                if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $tRaw, $m)) $tId = $m[1];
            }
        ?>
        <div class="testimonial-card">
            <?php if ($t['image']): ?>
            <img src="<?= base_url('uploads/landing_pages/' . $t['image']) ?>" alt="" loading="lazy">
            <?php endif; ?>
            <?php if ($tId): ?>
            <a href="https://www.youtube.com/watch?v=<?= urlencode($tId) ?>" target="_blank" class="testimonial-video-link" style="display:block;position:relative;border-radius:12px;overflow:hidden;">
                <img src="https://img.youtube.com/vi/<?= urlencode($tId) ?>/hqdefault.jpg" alt="" loading="lazy" style="width:100%;display:block;">
                <span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:#fff;font-size:2.5rem;opacity:0.9;text-shadow:0 2px 10px rgba(0,0,0,0.5);"><i class="bi bi-play-circle-fill"></i></span>
            </a>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- FOOTER -->
<section class="section footer-section">
    <?php if ($p['footer_section_title']): ?>
    <h2><?= esc($p['footer_section_title']) ?></h2>
    <?php endif; ?>
    <?php if ($p['footer_section_subtitle']): ?>
    <p><?= esc($p['footer_section_subtitle']) ?></p>
    <?php endif; ?>
    <a href="<?= esc($p['redirection_link'] ?? site_url('/shop')) ?>" class="btn-cta" target="_blank">
        <?= esc($p['_intro_join_button_text'] ?? 'Get It Now') ?>
    </a>
    <?php
    $footerLinks = [];
    for ($i = 1; $i <= 4; $i++) {
        if (!empty($p['footer_link_title_' . $i]) && !empty($p['footer_link_' . $i])) {
            $footerLinks[] = ['title' => $p['footer_link_title_' . $i], 'link' => $p['footer_link_' . $i]];
        }
    }
    if (!empty($footerLinks)): ?>
    <div class="footer-links" style="margin-top:2rem;">
        <?php foreach ($footerLinks as $fl): ?>
        <a href="<?= esc($fl['link']) ?>" target="_blank"><?= esc($fl['title']) ?></a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <p style="margin-top:2rem;font-size:0.8rem;color:#6b6b80;">&copy; <?= date('Y') ?> AI Art Store</p>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php if ($p['timer_time_in_minutes']): ?>
<script>
(function() {
    var minutes = <?= (int) $p['timer_time_in_minutes'] ?>;
    var seconds = minutes * 60;
    var display = document.getElementById('timerDisplay');
    function updateTimer() {
        var m = Math.floor(seconds / 60);
        var s = seconds % 60;
        display.textContent = m.toString().padStart(2, '0') + ':' + s.toString().padStart(2, '0');
        if (seconds > 0) { seconds--; }
    }
    updateTimer();
    setInterval(updateTimer, 1000);
})();
</script>
<?php endif; ?>
</body>
</html>
