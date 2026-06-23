<?= view('admin/layouts/header') ?>

<div class="card-admin" style="max-width: 720px;">
    <form action="<?= isset($prompt) ? site_url('/admin/prompts/edit/' . $prompt['id']) : site_url('/admin/prompts/create') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">Title</label>
            <input type="text" name="title" class="form-control" value="<?= old('title', $prompt['title'] ?? '') ?>" required placeholder="e.g. Cyberpunk Cityscape">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Slug</label>
            <input type="text" name="slug" class="form-control" value="<?= old('slug', $prompt['slug'] ?? '') ?>" placeholder="Auto-generated from title if blank">
            <small class="text-muted">URL-friendly version of the title. Leave blank to auto-generate.</small>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Prompt</label>
            <div class="d-flex gap-2 align-items-start">
                <textarea name="prompt" class="form-control" rows="5" placeholder="Paste your AI prompt here..." id="promptText"><?= old('prompt', $prompt['prompt'] ?? '') ?></textarea>
                <button type="button" class="btn btn-outline-custom" style="padding: 8px 12px; flex-shrink: 0;" onclick="copyPrompt()" title="Copy prompt">
                    <i class="bi bi-clipboard" id="copyIcon"></i>
                </button>
            </div>
        </div>
        <script>
        function copyPrompt() {
            var text = document.getElementById('promptText').value;
            if (!text) { showToast('Nothing to copy', 'error'); return; }
            navigator.clipboard.writeText(text).then(function() {
                var icon = document.getElementById('copyIcon');
                icon.classList.remove('bi-clipboard');
                icon.classList.add('bi-clipboard-check');
                showToast('Prompt copied!', 'success');
                setTimeout(function() {
                    icon.classList.remove('bi-clipboard-check');
                    icon.classList.add('bi-clipboard');
                }, 2000);
            }).catch(function() {
                showToast('Failed to copy', 'error');
            });
        }
        </script>
        <div class="mb-3">
            <label class="form-label fw-semibold">Notes <small class="text-muted">(HTML supported)</small></label>
            <textarea name="notes" id="notesEditor" class="form-control" rows="6" placeholder="HTML content for user messages, links, tips..."><?= old('notes', $prompt['notes'] ?? '') ?></textarea>
            <small class="text-muted">Rich text content shown to users. You can include links, images, and formatting.</small>
        </div>

        <hr style="border-color: var(--border-color);">

        <div class="mb-3">
            <label class="form-label fw-semibold">Reference Images</label>
            <?php if (isset($images) && !empty($images)): ?>
            <div class="d-flex flex-wrap gap-2 mb-3">
                <?php foreach ($images as $img): ?>
                <div class="position-relative" style="width: 120px;">
                    <img src="<?= base_url('uploads/prompts/' . $img['image']) ?>" alt="Reference" style="width: 120px; height: 90px; border-radius: 10px; object-fit: cover; border: 1px solid var(--border-color);">
                    <div class="position-absolute d-flex gap-1" style="top: 4px; right: 4px;">
                        <a href="<?= base_url('uploads/prompts/' . $img['image']) ?>" download class="btn btn-sm p-0" style="width: 22px; height: 22px; border-radius: 50%; background: rgba(0,0,0,0.55); color: white; border: none; font-size: 0.65rem; display: flex; align-items: center; justify-content: center; text-decoration: none;" title="Download"><i class="bi bi-download"></i></a>
                        <button type="button" class="btn btn-sm p-0" style="width: 22px; height: 22px; border-radius: 50%; background: rgba(239,68,68,0.85); color: white; border: none; font-size: 0.7rem; display: flex; align-items: center; justify-content: center;" onclick="deletePromptImage(<?= $img['id'] ?>)" title="Remove"><i class="bi bi-x"></i></button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <input type="file" name="images[]" class="form-control" accept="image/jpeg,image/png,image/webp" multiple>
            <small class="text-muted">You can select multiple images. Allowed: JPG, PNG, WebP. Max 5MB each.</small>
        </div>

        <hr style="border-color: var(--border-color);">

        <div class="mb-3">
            <label class="form-label fw-semibold">Category</label>
            <select name="category_id" class="form-select">
                <option value="">-- No Category --</option>
                <?php if (isset($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= (old('category_id', $prompt['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Subscription Access Level</label>
            <select name="min_subscription_level" class="form-select">
                <option value="0" <?= (old('min_subscription_level', $prompt['min_subscription_level'] ?? 0) == 0) ? 'selected' : '' ?>>Free (Level 0)</option>
                <option value="1" <?= (old('min_subscription_level', $prompt['min_subscription_level'] ?? '') == 1) ? 'selected' : '' ?>>Pro (Level 1)</option>
                <option value="2" <?= (old('min_subscription_level', $prompt['min_subscription_level'] ?? '') == 2) ? 'selected' : '' ?>>Premium (Level 2)</option>
            </select>
            <small class="text-muted">Users need at least this subscription level to access this prompt</small>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select">
                <option value="active" <?= (old('status', $prompt['status'] ?? 'active') == 'active') ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= (old('status', $prompt['status'] ?? '') == 'inactive') ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <hr style="border-color: var(--border-color);">
        <h6 class="fw-bold mb-3">SEO Settings</h6>

        <div class="mb-3">
            <label class="form-label fw-semibold">SEO Title</label>
            <input type="text" name="seo_title" class="form-control" value="<?= old('seo_title', $prompt['seo_title'] ?? '') ?>" placeholder="Leave blank to use prompt title">
            <small class="text-muted">Custom title for search engines and social sharing</small>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Meta Description</label>
            <textarea name="seo_description" class="form-control" rows="3" placeholder="Brief description for search results..."><?= old('seo_description', $prompt['seo_description'] ?? '') ?></textarea>
            <small class="text-muted">Recommended: 150-160 characters</small>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Meta Keywords</label>
            <input type="text" name="seo_keywords" class="form-control" value="<?= old('seo_keywords', $prompt['seo_keywords'] ?? '') ?>" placeholder="ai prompt, cyberpunk, cityscape, ...">
            <small class="text-muted">Comma-separated keywords</small>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">SEO Thumbnail</label>
            <?php if (isset($images) && !empty($images)): ?>
            <div class="d-flex flex-wrap gap-2">
                <?php foreach ($images as $img): ?>
                <label class="position-relative" style="cursor: pointer; width: 100px;">
                    <input type="radio" name="seo_thumbnail" value="<?= esc($img['image']) ?>" <?= (old('seo_thumbnail', $prompt['seo_thumbnail'] ?? '') == $img['image']) ? 'checked' : '' ?> style="position: absolute; top: 4px; left: 4px; z-index: 2;">
                    <img src="<?= base_url('uploads/prompts/' . $img['image']) ?>" alt="" style="width: 100px; height: 75px; border-radius: 8px; object-fit: cover; border: 2px solid <?= (old('seo_thumbnail', $prompt['seo_thumbnail'] ?? '') == $img['image']) ? '#8b5cf6' : 'var(--border-color)' ?>;">
                </label>
                <?php endforeach; ?>
            </div>
            <small class="text-muted">Select the image used for social sharing previews</small>
            <?php else: ?>
            <p class="text-muted small mb-0">Upload reference images above to set a thumbnail</p>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary-custom"><?= isset($prompt) ? 'Update' : 'Create' ?> Prompt</button>
        <a href="<?= site_url('/admin/prompts') ?>" class="btn btn-outline-custom">Cancel</a>
    </form>
</div>

<style>
.note-editor .note-editing-area .note-editable {
    background: #fff !important;
    color: #1a1a2e !important;
}
.note-editor .note-toolbar {
    background: #f5f5f5 !important;
}

</style>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    $('#notesEditor').summernote({
        height: 250,
        dialogsInBody: true,
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']],
            ['view', ['codeview']],
        ],
        callbacks: {
            onChange: function() {
                $('#notesEditor').val($('#notesEditor').summernote('code'));
            }
        }
    });
});

function deletePromptImage(id) {
    if (!confirm('Remove this image?')) return;
    $.ajax({
        url: '<?= site_url('/admin/prompts/delete-image/') ?>' + id,
        type: 'POST',
        data: {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        },
        success: function() { location.reload(); },
        error: function() { showToast('Failed to remove image', 'error'); }
    });
}
</script>

<?= view('admin/layouts/footer') ?>
