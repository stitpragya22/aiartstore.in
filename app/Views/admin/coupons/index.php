<?= view('admin/layouts/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Coupons</h4>
    <a href="<?= site_url('/admin/coupons/create') ?>" class="btn btn-primary">+ Add Coupon</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr><th>Code</th><th>Type</th><th>Value</th><th>Min Order</th><th>Uses</th><th>Expires</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php foreach ($coupons as $c): ?>
                <tr>
                    <td><strong><?= esc($c['code']) ?></strong></td>
                    <td><?= $c['type'] === 'percentage' ? '%' : 'Fixed' ?></td>
                    <td><?= $c['type'] === 'percentage' ? $c['value'] . '%' : formatPrice($c['value']) ?></td>
                    <td><?= formatPrice($c['min_amount']) ?></td>
                    <td><?= $c['used_count'] . ($c['max_uses'] > 0 ? ' / ' . $c['max_uses'] : '') ?></td>
                    <td><?= $c['expires_at'] ? date('d M Y', strtotime($c['expires_at'])) : 'Never' ?></td>
                    <td><span class="badge bg-<?= $c['status'] === 'active' ? 'success' : 'secondary' ?>"><?= $c['status'] ?></span></td>
                    <td>
                        <a href="<?= site_url('/admin/coupons/edit/' . $c['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                        <form action="<?= site_url('/admin/coupons/delete/' . $c['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('admin/layouts/footer') ?>
