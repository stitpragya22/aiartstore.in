<?= view('layouts/header') ?>

<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <h1 class="fw-bold">My Subscription</h1>
            <p class="text-secondary">Manage your AI Art Store subscription</p>
        </div>

        <?php if (empty($subscriptions)): ?>
        <div class="text-center py-5">
            <i class="bi bi-credit-card" style="font-size: 3rem; color: var(--text-muted);"></i>
            <p class="text-muted mt-3 mb-1">You don't have any subscriptions yet.</p>
            <p class="text-muted mb-3">Subscribe to a plan and unlock premium prompts.</p>
            <a href="<?= site_url('/subscriptions/plans') ?>" class="btn" style="background: linear-gradient(135deg, #8b5cf6, #6366f1); color: white; border: none; padding: 12px 30px; border-radius: 12px; font-weight: 600;">View Plans</a>
        </div>
        <?php else: ?>
        <div class="mb-4">
            <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 1.5rem;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <p class="text-muted small mb-1">Current Access Level</p>
                        <h4 class="fw-bold mb-0">
                            <?php $levelLabels = ['Free', 'Pro', 'Premium']; ?>
                            <?= $levelLabels[$highestLevel] ?? 'Free' ?>
                            <span class="badge-status ms-2" style="background:rgba(139,92,246,0.2);color:#a78bfa;">Level <?= $highestLevel ?></span>
                        </h4>
                    </div>
                    <a href="<?= site_url('/subscriptions/plans') ?>" class="btn btn-outline-custom">Upgrade Plan</a>
                </div>
            </div>
        </div>

        <h5 class="fw-semibold mb-3">Subscription History</h5>
        <div class="table-responsive">
            <table class="table" style="color: var(--text-primary); border-color: var(--border-color);">
                <thead>
                    <tr>
                        <th style="border-color: var(--border-color);">Plan</th>
                        <th style="border-color: var(--border-color);">Level</th>
                        <th style="border-color: var(--border-color);">Start Date</th>
                        <th style="border-color: var(--border-color);">End Date</th>
                        <th style="border-color: var(--border-color);">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subscriptions as $s): $expired = ($s['status'] == 'active' && $s['end_date'] < date('Y-m-d H:i:s')); ?>
                    <tr>
                        <td style="border-color: var(--border-color);"><?= esc($s['plan_name']) ?></td>
                        <td style="border-color: var(--border-color);"><span class="badge-status" style="background:rgba(139,92,246,0.2);color:#a78bfa;">Level <?= $s['plan_level'] ?></span></td>
                        <td style="border-color: var(--border-color);"><?= date('d M Y', strtotime($s['start_date'])) ?></td>
                        <td style="border-color: var(--border-color);">
                            <?php if ($s['end_date'] == '9999-12-31 23:59:59'): ?>
                                Lifetime
                            <?php else: ?>
                                <?= date('d M Y', strtotime($s['end_date'])) ?>
                            <?php endif; ?>
                        </td>
                        <td style="border-color: var(--border-color);">
                            <?php if ($expired): ?>
                                <span class="badge-status cancelled">Expired</span>
                            <?php else: ?>
                                <span class="badge-status <?= $s['status'] ?>"><?= ucfirst($s['status']) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</section>

<?= view('layouts/footer') ?>
