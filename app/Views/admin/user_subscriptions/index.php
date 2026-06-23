<?= view('admin/layouts/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">User Subscriptions</h4>
        <p class="text-muted mb-0">View and manage customer subscriptions</p>
    </div>
    <a href="<?= site_url('/admin/user-subscriptions/create') ?>" class="btn btn-primary-custom"><i class="bi bi-plus-lg me-1"></i>Assign Subscription</a>
</div>

<?php if (empty($subscriptions)): ?>
<div class="text-center py-5">
    <i class="bi bi-people" style="font-size: 3rem; color: var(--text-muted);"></i>
    <p class="text-muted mt-3 mb-0">No subscriptions yet.</p>
</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-admin">
        <thead>
            <tr>
                <th>User</th>
                <th>Plan</th>
                <th>Level</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subscriptions as $s): $expired = ($s['status'] == 'active' && $s['end_date'] < date('Y-m-d H:i:s')); ?>
            <tr>
                <td>
                    <div class="fw-semibold" style="font-size:0.85rem;"><?= esc($s['user_name'] ?? $s['user_email']) ?></div>
                    <div class="text-muted" style="font-size:0.75rem;"><?= esc($s['user_email']) ?></div>
                </td>
                <td><?= esc($s['plan_name']) ?></td>
                <td><span class="badge-status" style="background:rgba(139,92,246,0.2);color:#a78bfa;">Level <?= $s['plan_level'] ?></span></td>
                <td><?= date('d M Y', strtotime($s['start_date'])) ?></td>
                <td>
                    <?php if ($s['end_date'] == '9999-12-31 23:59:59'): ?>
                        <span class="text-muted">Lifetime</span>
                    <?php else: ?>
                        <?= date('d M Y', strtotime($s['end_date'])) ?>
                        <?php if ($expired): ?><span class="badge-status cancelled ms-1">Expired</span><?php endif; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($expired): ?>
                        <span class="badge-status cancelled">Expired</span>
                    <?php else: ?>
                        <span class="badge-status <?= $s['status'] ?>"><?= ucfirst($s['status']) ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($s['status'] == 'active' && !$expired): ?>
                    <form action="<?= site_url('/admin/user-subscriptions/cancel/' . $s['id']) ?>" method="POST" onsubmit="return confirm('Cancel this subscription?')" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-custom" style="padding:2px 10px;font-size:0.75rem;border-color:rgba(239,68,68,0.3);color:var(--danger);" title="Cancel"><i class="bi bi-x-circle"></i></button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?= view('admin/layouts/footer') ?>
