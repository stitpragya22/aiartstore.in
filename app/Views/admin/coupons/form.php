<?= view('admin/layouts/header') ?>

<h4 class="mb-4"><?= isset($coupon) ? 'Edit' : 'Add' ?> Coupon</h4>

<form method="post">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Coupon Code</label>
                    <input type="text" name="code" class="form-control" value="<?= old('code', $coupon['code'] ?? '') ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="percentage" <?= (old('type', $coupon['type'] ?? '') === 'percentage') ? 'selected' : '' ?>>Percentage (%)</option>
                        <option value="fixed" <?= (old('type', $coupon['type'] ?? '') === 'fixed') ? 'selected' : '' ?>>Fixed (₹)</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Value</label>
                    <input type="number" name="value" class="form-control" step="0.01" value="<?= old('value', $coupon['value'] ?? '') ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Min Order Amount</label>
                    <input type="number" name="min_amount" class="form-control" step="0.01" value="<?= old('min_amount', $coupon['min_amount'] ?? 0) ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Max Uses (0 = unlimited)</label>
                    <input type="number" name="max_uses" class="form-control" value="<?= old('max_uses', $coupon['max_uses'] ?? 0) ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= (old('status', $coupon['status'] ?? '') === 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= (old('status', $coupon['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Valid From</label>
                    <input type="datetime-local" name="starts_at" class="form-control" value="<?= isset($coupon['starts_at']) ? date('Y-m-d\TH:i', strtotime($coupon['starts_at'])) : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Expires At</label>
                    <input type="datetime-local" name="expires_at" class="form-control" value="<?= isset($coupon['expires_at']) ? date('Y-m-d\TH:i', strtotime($coupon['expires_at'])) : '' ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="<?= site_url('/admin/coupons') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</form>

<?= view('admin/layouts/footer') ?>
