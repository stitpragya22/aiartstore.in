<?= view('admin/layouts/header') ?>

<div class="card-admin" style="max-width: 600px;">
    <form action="<?= isset($category) ? site_url('/admin/categories/edit/' . $category['id']) : site_url('/admin/categories/create') ?>" method="POST">
        <div class="mb-3">
            <label class="form-label fw-semibold">Name</label>
            <input type="text" name="name" class="form-control" value="<?= old('name', $category['name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" class="form-control" rows="3"><?= old('description', $category['description'] ?? '') ?></textarea>
        </div>
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
