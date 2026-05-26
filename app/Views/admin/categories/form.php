<?= view('admin/layouts/header') ?>

<div class="card-admin" style="max-width: 600px;">
    <form action="<?= isset($category) ? site_url('/admin/categories/edit/' . $category['id']) : site_url('/admin/categories/create') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" name="name" class="form-control" value="<?= old('name', $category['name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" class="form-control" rows="3"><?= old('description', $category['description'] ?? '') ?></textarea>
        </div>
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
