<?= view('admin/layouts/header') ?>

<div class="card-admin" style="max-width: 600px;">
    <form action="<?= isset($category) ? site_url('/admin/categories/edit/' . $category['id']) : site_url('/admin/categories/create') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" name="name" class="form-control" value="<?= old('name', $category['name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" class="form-control" rows="3"><?= old('description', $category['description'] ?? '') ?></textarea>
        </div>

        <hr style="border-color: var(--border-color);">

        <div class="mb-3">
            <label class="form-label fw-semibold">Category Image</label>
            <?php if (isset($category) && !empty($category['image'])): ?>
            <div class="mb-2">
                <img src="<?= base_url('uploads/categories/' . $category['image']) ?>" alt="<?= esc($category['name']) ?>" style="width: 100%; max-width: 200px; border-radius: 12px; border: 1px solid var(--border-color); object-fit: cover; aspect-ratio: 16/10;">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="remove_image" id="removeImage" value="1" style="border-color: var(--border-color);">
                    <label class="form-check-label text-muted small" for="removeImage">Remove current image</label>
                </div>
            </div>
            <?php endif; ?>
            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
            <small class="text-muted">Allowed: JPG, PNG, WebP. Max 5MB.</small>
        </div>

        <hr style="border-color: var(--border-color);">

        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_curated" id="isCurated" value="1" style="border-color: var(--border-color);" <?= old('is_curated', $category['is_curated'] ?? 0) ? 'checked' : '' ?>>
                <label class="form-check-label fw-semibold" for="isCurated">Show in Curated Collections on homepage</label>
            </div>
            <small class="text-muted">When enabled, this category appears in the "Curated Collections" section on the landing page.</small>
        </div>

        <hr style="border-color: var(--border-color);">
        <div class="mb-3">
            <label class="form-label fw-semibold">SEO Meta Title</label>
            <input type="text" name="meta_title" class="form-control" value="<?= old('meta_title', $category['meta_title'] ?? '') ?>" maxlength="255" placeholder="Leave blank to use category name">
            <small class="text-muted">Appears in search engine results (recommended: 50-60 characters)</small>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">SEO Meta Description</label>
            <textarea name="meta_description" class="form-control" rows="3" placeholder="Brief description for search engines"><?= old('meta_description', $category['meta_description'] ?? '') ?></textarea>
            <small class="text-muted">Appears below the title in search results (recommended: 150-160 characters)</small>
        </div>
        <hr style="border-color: var(--border-color);">
        <div class="mb-3">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select">
                <option value="active" <?= (old('status', $category['status'] ?? 'active') == 'active') ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= (old('status', $category['status'] ?? '') == 'inactive') ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary-custom"><?= isset($category) ? 'Update' : 'Create' ?> Category</button>
        <a href="<?= site_url('/admin/categories') ?>" class="btn btn-outline-custom">Cancel</a>
    </form>
</div>

<?= view('admin/layouts/footer') ?>
