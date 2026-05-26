<?= view('admin/layouts/header') ?>

<h4 class="mb-4"><?= isset($category) ? 'Edit' : 'Add' ?> Blog Category</h4>

<form method="post">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= old('name', $category['name'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= old('description', $category['description'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Meta Title</label>
                <input type="text" name="meta_title" class="form-control" value="<?= old('meta_title', $category['meta_title'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Meta Description</label>
                <textarea name="meta_description" class="form-control" rows="2"><?= old('meta_description', $category['meta_description'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" <?= (old('status', $category['status'] ?? '') === 'active') ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= (old('status', $category['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="<?= site_url('/admin/blog/categories') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</form>

<?= view('admin/layouts/footer') ?>
