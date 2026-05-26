<?= view('admin/layouts/header') ?>

<?php
$post = $post ?? [];
$categories = $categories ?? [];
?>

<style>
.seo-panel { background: #f8f9fa; border-radius: 8px; padding: 1.25rem; }
.seo-score-ring { width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; margin: 0 auto 1rem; border: 4px solid #6c757d; }
.seo-score-ring.good { border-color: #28a745; color: #28a745; }
.seo-score-ring.ok { border-color: #ffc107; color: #856404; }
.seo-score-ring.bad { border-color: #dc3545; color: #dc3545; }
.seo-item { display: flex; align-items: center; gap: 8px; padding: 6px 0; border-bottom: 1px solid #eee; font-size: 0.875rem; }
.seo-item:last-child { border-bottom: none; }
.seo-item .icon { width: 20px; text-align: center; }
.seo-item .icon.pass { color: #28a745; }
.seo-item .icon.fail { color: #dc3545; }
.seo-item .icon.warn { color: #ffc107; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><?= isset($post['id']) ? 'Edit' : 'Write' ?> Blog Post</h4>
</div>

<form method="post" enctype="multipart/form-data" id="postForm">
    <?= csrf_field() ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" id="postTitle" class="form-control form-control-lg" value="<?= old('title', $post['title'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" id="postContent" rows="20" class="form-control"><?= old('content', $post['content'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Excerpt</h5>
                    <textarea name="excerpt" id="postExcerpt" class="form-control" rows="3"><?= old('excerpt', $post['excerpt'] ?? '') ?></textarea>
                    <small class="text-muted">A short summary shown in blog listings.</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Publish</h5>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" <?= (old('status', $post['status'] ?? 'draft') === 'draft') ? 'selected' : '' ?>>Draft</option>
                            <option value="published" <?= (old('status', $post['status'] ?? '') === 'published') ? 'selected' : '' ?>>Publish</option>
                            <option value="archived" <?= (old('status', $post['status'] ?? '') === 'archived') ? 'selected' : '' ?>>Archive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Published At</label>
                        <input type="datetime-local" name="published_at" class="form-control" value="<?= isset($post['published_at']) ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : '' ?>">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Save Post</button>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Categories</h5>
                    <select name="category_id" class="form-select" id="postCategory">
                        <option value="">Uncategorized</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= (old('category_id', $post['category_id'] ?? '') == $c['id']) ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Featured Image</h5>
                    <?php if (!empty($post['featured_image'])): ?>
                        <img src="<?= base_url($post['featured_image']) ?>" class="img-fluid mb-2 rounded" style="max-height:150px">
                    <?php endif ?>
                    <input type="file" name="featured_image" class="form-control" accept="image/jpeg,image/png,image/webp">
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tags</h5>
                    <input type="text" name="tags" class="form-control" placeholder="tag1, tag2, tag3" value="<?= old('tags', $post['tags'] ?? '') ?>">
                </div>
            </div>

            <!-- SEO Panel -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">SEO Analysis</h5>
                    <div class="seo-panel">
                        <div class="seo-score-ring" id="seoScoreRing">0</div>
                        <p class="text-center mb-3" id="seoLabel">Enter focus keyword to start</p>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Focus Keyword</label>
                            <input type="text" name="focus_keyword" id="focusKeyword" class="form-control" value="<?= old('focus_keyword', $post['focus_keyword'] ?? '') ?>" placeholder="e.g. AI art guide">
                        </div>

                        <div id="seoResults"></div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">SEO Meta</h5>
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" id="metaTitle" class="form-control" value="<?= old('meta_title', $post['meta_title'] ?? '') ?>">
                        <small class="text-muted"><span id="metaTitleCount">0</span> chars (ideal 50-60)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" id="metaDescription" class="form-control" rows="3"><?= old('meta_description', $post['meta_description'] ?? '') ?></textarea>
                        <small class="text-muted"><span id="metaDescCount">0</span> chars (ideal 150-160)</small>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">SEO Score (manual override)</label>
                        <input type="number" name="seo_score" id="seoScoreInput" class="form-control" value="<?= old('seo_score', $post['seo_score'] ?? 0) ?>" min="0" max="100">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- TinyMCE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#postContent',
    height: 600,
    menubar: true,
    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen media table wordcount',
    toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | removeformat fullscreen code',
    image_title: true,
    automatic_uploads: false,
    branding: false,
    promotion: false,
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 16px; line-height: 1.8; color: #333; }',
    setup: function(editor) {
        editor.on('keyup change', function() {
            runSeoAnalysis();
        });
    }
});

// ===================== SEO ANALYZER =====================
function runSeoAnalysis() {
    const keyword = document.getElementById('focusKeyword')?.value?.trim() || '';
    const title = document.getElementById('postTitle')?.value || '';
    const excerpt = document.getElementById('postExcerpt')?.value || '';
    const metaTitle = document.getElementById('metaTitle')?.value || '';
    const metaDesc = document.getElementById('metaDescription')?.value || '';

    const content = tinymce.get('postContent') ? (tinymce.get('postContent').getContent({format: 'text'}) || '') : '';

    let score = 0;
    let maxScore = 10;
    const results = [];

    // 1. Focus keyword in title
    if (keyword) {
        const inTitle = title.toLowerCase().includes(keyword.toLowerCase());
        results.push({ pass: inTitle, label: 'Keyword in title', detail: inTitle ? 'Found in title' : 'Add keyword to title' });
        if (inTitle) score++;

        // 2. Keyword in content
        const inContent = content.toLowerCase().includes(keyword.toLowerCase());
        results.push({ pass: inContent, label: 'Keyword in content', detail: inContent ? 'Found in content' : 'Add keyword to content' });
        if (inContent) score++;

        // 3. Keyword in excerpt
        const inExcerpt = excerpt.toLowerCase().includes(keyword.toLowerCase());
        results.push({ pass: inExcerpt, label: 'Keyword in excerpt', detail: inExcerpt ? 'Found in excerpt' : 'Add keyword to excerpt' });
        if (inExcerpt) score++;

        // 4. Keyword in slug (simulated from title)
        const slugOk = urlify(title).includes(keyword.toLowerCase().replace(/\s+/g, '-'));
        results.push({ pass: slugOk, label: 'Keyword in slug', detail: slugOk ? 'Will be in URL' : 'Keyword not in URL slug' });
        if (slugOk) score++;

        // 5. Keyword at start of title
        const kwAtStart = title.toLowerCase().startsWith(keyword.toLowerCase());
        results.push({ pass: kwAtStart, label: 'Keyword at start of title', detail: kwAtStart ? 'Good position' : 'Move keyword earlier in title' });
        if (kwAtStart) score++;

        // 6. Keyword in meta description
        const inMetaDesc = metaDesc.toLowerCase().includes(keyword.toLowerCase());
        results.push({ pass: inMetaDesc, label: 'Keyword in meta description', detail: inMetaDesc ? 'Found in meta description' : 'Add keyword to meta description' });
        if (inMetaDesc) score++;

        maxScore = 6;
    }

    // Content length (always check)
    const wordCount = content.split(/\s+/).filter(w => w.length > 0).length;
    if (wordCount >= 300) {
        results.push({ pass: true, label: 'Content length', detail: wordCount + ' words (great)' });
        score++;
    } else if (wordCount >= 100) {
        results.push({ pass: false, label: 'Content length', detail: wordCount + ' words (aim for 300+)', warn: true });
        score += 0.5;
    } else {
        results.push({ pass: false, label: 'Content length', detail: wordCount + ' words (aim for 300+)' });
    }
    maxScore++;

    // Meta title length
    if (metaTitle.length > 0) {
        if (metaTitle.length >= 50 && metaTitle.length <= 60) {
            results.push({ pass: true, label: 'Meta title length', detail: metaTitle.length + ' chars (perfect)' });
            score++;
        } else if (metaTitle.length >= 30 && metaTitle.length <= 70) {
            results.push({ pass: false, label: 'Meta title length', detail: metaTitle.length + ' chars (aim for 50-60)', warn: true });
            score += 0.5;
        } else {
            results.push({ pass: false, label: 'Meta title length', detail: metaTitle.length + ' chars (aim for 50-60)' });
        }
        maxScore++;
    }

    // Meta description length
    if (metaDesc.length > 0) {
        if (metaDesc.length >= 150 && metaDesc.length <= 160) {
            results.push({ pass: true, label: 'Meta description length', detail: metaDesc.length + ' chars (perfect)' });
            score++;
        } else if (metaDesc.length >= 120 && metaDesc.length <= 180) {
            results.push({ pass: false, label: 'Meta description length', detail: metaDesc.length + ' chars (aim for 150-160)', warn: true });
            score += 0.5;
        } else {
            results.push({ pass: false, label: 'Meta description length', detail: metaDesc.length + ' chars (aim for 150-160)' });
        }
        maxScore++;
    }

    // Heading presence
    const hasHeadings = /<h[2-6]/i.test(tinymce.get('postContent')?.getContent() || '');
    results.push({ pass: hasHeadings, label: 'Headings used', detail: hasHeadings ? 'Good structure' : 'Add headings (H2, H3)' });
    if (hasHeadings) score++;
    maxScore++;

    // Image alt check
    const contentHtml = tinymce.get('postContent')?.getContent() || '';
    const imgCount = (contentHtml.match(/<img /gi) || []).length;
    const altCount = (contentHtml.match(/alt="[^"]+"/gi) || []).length;
    const allHaveAlt = imgCount > 0 && imgCount === altCount;
    if (imgCount > 0) {
        results.push({ pass: allHaveAlt, label: 'Image alt attributes', detail: allHaveAlt ? 'All images have alt text' : altCount + '/' + imgCount + ' images have alt text' });
        if (allHaveAlt) score++;
        maxScore++;
    }

    // Final percentage
    const pct = maxScore > 0 ? Math.round((score / maxScore) * 100) : 0;
    const finalScore = Math.min(100, pct);

    // Update UI
    const ring = document.getElementById('seoScoreRing');
    const label = document.getElementById('seoLabel');
    const resultsDiv = document.getElementById('seoResults');
    const scoreInput = document.getElementById('seoScoreInput');

    ring.textContent = finalScore;
    ring.className = 'seo-score-ring';
    if (finalScore >= 80) ring.classList.add('good');
    else if (finalScore >= 50) ring.classList.add('ok');
    else ring.classList.add('bad');

    if (!keyword) {
        label.textContent = 'Enter focus keyword to start';
    } else if (finalScore >= 80) {
        label.innerHTML = '<strong class="text-success">Good!</strong> Ready to publish';
    } else if (finalScore >= 50) {
        label.innerHTML = '<strong class="text-warning">Okay</strong> Needs improvement';
    } else {
        label.innerHTML = '<strong class="text-danger">Poor</strong> Major improvements needed';
    }

    resultsDiv.innerHTML = results.map(r => `
        <div class="seo-item">
            <span class="icon ${r.pass ? 'pass' : (r.warn ? 'warn' : 'fail')}">
                ${r.pass ? '&#10003;' : (r.warn ? '&#9888;' : '&#10007;')}
            </span>
            <span><strong>${r.label}</strong><br><small class="text-muted">${r.detail}</small></span>
        </div>
    `).join('');

    if (scoreInput) scoreInput.value = finalScore;
}

// Run on keyup for title, excerpt, meta fields
document.addEventListener('DOMContentLoaded', function() {
    ['postTitle', 'postExcerpt', 'focusKeyword', 'metaTitle', 'metaDescription'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', runSeoAnalysis);
    });

    // Character counters
    document.getElementById('metaTitle')?.addEventListener('input', function() {
        document.getElementById('metaTitleCount').textContent = this.value.length;
    });
    document.getElementById('metaDescription')?.addEventListener('input', function() {
        document.getElementById('metaDescCount').textContent = this.value.length;
    });

    // Initial counts
    if (document.getElementById('metaTitle')) {
        document.getElementById('metaTitleCount').textContent = document.getElementById('metaTitle').value.length;
    }
    if (document.getElementById('metaDescription')) {
        document.getElementById('metaDescCount').textContent = document.getElementById('metaDescription').value.length;
    }

    setTimeout(runSeoAnalysis, 500);
});

function urlify(text) {
    return text.toString().toLowerCase().trim()
        .replace(/&/g, '-and-')
        .replace(/[\s\W-]+/g, '-')
        .replace(/^-+|-+$/g, '');
}
</script>

<?= view('admin/layouts/footer') ?>
