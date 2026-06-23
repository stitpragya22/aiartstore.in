<?= view('admin/layouts/header') ?>

<div class="card-admin" style="max-width: 600px;">
    <form action="<?= isset($plan) ? site_url('/admin/subscription-plans/edit/' . $plan['id']) : site_url('/admin/subscription-plans/create') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">Plan Name</label>
            <input type="text" name="name" class="form-control" value="<?= old('name', $plan['name'] ?? '') ?>" required placeholder="e.g. Pro Plan">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Slug</label>
            <input type="text" name="slug" class="form-control" value="<?= old('slug', $plan['slug'] ?? '') ?>" required placeholder="e.g. pro-plan">
            <small class="text-muted">URL-friendly identifier (unique)</small>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="What this plan includes..."><?= old('description', $plan['description'] ?? '') ?></textarea>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Price (₹)</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?= old('price', $plan['price'] ?? 0) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Validity (Days)</label>
                <input type="number" name="validity_days" class="form-control" value="<?= old('validity_days', $plan['validity_days'] ?? 30) ?>" required>
                <small class="text-muted">0 = Lifetime</small>
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Access Level</label>
                <select name="level" class="form-select">
                    <option value="0" <?= (old('level', $plan['level'] ?? '') == 0) ? 'selected' : '' ?>>Level 0 - Free</option>
                    <option value="1" <?= (old('level', $plan['level'] ?? '') == 1) ? 'selected' : '' ?>>Level 1 - Pro</option>
                    <option value="2" <?= (old('level', $plan['level'] ?? '') == 2) ? 'selected' : '' ?>>Level 2 - Premium</option>
                </select>
                <small class="text-muted">Higher level includes access to lower levels</small>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="active" <?= (old('status', $plan['status'] ?? 'active') == 'active') ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= (old('status', $plan['status'] ?? '') == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary-custom"><?= isset($plan) ? 'Update' : 'Create' ?> Plan</button>
        <a href="<?= site_url('/admin/subscription-plans') ?>" class="btn btn-outline-custom">Cancel</a>
    </form>
</div>

<?= view('admin/layouts/footer') ?>
