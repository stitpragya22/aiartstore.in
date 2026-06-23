<?= view('admin/layouts/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Subscription Plans</h4>
        <p class="text-muted mb-0">Manage prompt library access tiers</p>
    </div>
    <a href="<?= site_url('/admin/subscription-plans/create') ?>" class="btn btn-primary-custom"><i class="bi bi-plus-lg me-1"></i>Add Plan</a>
</div>

<?php if (empty($plans)): ?>
<div class="text-center py-5">
    <i class="bi bi-credit-card" style="font-size: 3rem; color: var(--text-muted);"></i>
    <p class="text-muted mt-3 mb-0">No subscription plans yet.</p>
    <a href="<?= site_url('/admin/subscription-plans/create') ?>" class="btn btn-primary-custom mt-3"><i class="bi bi-plus-lg me-1"></i>Create First Plan</a>
</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-admin">
        <thead>
            <tr>
                <th>Level</th>
                <th>Name</th>
                <th>Price</th>
                <th>Validity (Days)</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($plans as $p): ?>
            <tr>
                <td><span class="badge-status" style="background:rgba(139,92,246,0.2);color:#a78bfa;">Level <?= $p['level'] ?></span></td>
                <td class="fw-semibold"><?= esc($p['name']) ?></td>
                <td>₹<?= number_format($p['price'], 2) ?></td>
                <td><?= $p['validity_days'] > 0 ? $p['validity_days'] . ' days' : 'Lifetime' ?></td>
                <td><span class="badge-status <?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                <td>
                    <a href="<?= site_url('/admin/subscription-plans/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-custom" style="padding:2px 10px;font-size:0.75rem;" title="Edit"><i class="bi bi-pencil"></i></a>
                    <form action="<?= site_url('/admin/subscription-plans/delete/' . $p['id']) ?>" method="POST" onsubmit="return confirm('Delete this plan?')" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-custom" style="padding:2px 10px;font-size:0.75rem;border-color:rgba(239,68,68,0.3);color:var(--danger);" title="Delete"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?= view('admin/layouts/footer') ?>
