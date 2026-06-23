<?= view('layouts/header') ?>

<style>
.request-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 1.5rem; transition: all 0.3s; }
.request-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.12); }
.request-card .status-badge { font-size: 0.8rem; padding: 4px 12px; border-radius: 20px; font-weight: 500; }
</style>

<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold mb-0">My Custom Requests</h1>
            <a href="<?= site_url('/custom-request') ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Request</a>
        </div>

        <?php if (empty($requests)): ?>
            <div class="text-center py-5">
                <i class="bi bi-palette" style="font-size: 3rem; color: var(--text-muted);"></i>
                <h4 class="mt-3">No requests yet</h4>
                <p class="text-muted">Submit a custom AI art request to get started.</p>
                <a href="<?= site_url('/custom-request') ?>" class="btn btn-primary-custom">Submit Your First Request</a>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($requests as $r): ?>
                <div class="col-md-6">
                    <a href="<?= site_url('/custom-request/track/' . $r['id']) ?>" class="text-decoration-none text-reset">
                        <div class="request-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong>#<?= $r['id'] ?></strong>
                                    <span class="badge bg-info ms-2"><?= esc($r['request_type']) ?></span>
                                    <span class="badge bg-secondary ms-1">
    <?php if ($r['plan'] === 'free'): ?>Free
    <?php elseif ($r['plan'] === '99'): ?>₹99
    <?php elseif ($r['plan'] === '249'): ?>₹249
    <?php elseif ($r['plan'] === '499'): ?>₹499
    <?php endif ?>
</span>
                                </div>
                                <?php if ($r['status'] === 'pending'): ?>
                                    <span class="status-badge" style="background: rgba(108,117,125,0.2); color: var(--text-muted);">Pending</span>
                                <?php elseif ($r['status'] === 'in_progress'): ?>
                                    <span class="status-badge" style="background: rgba(13,110,253,0.2); color: #0d6efd;">In Progress</span>
                                <?php elseif ($r['status'] === 'completed'): ?>
                                    <span class="status-badge" style="background: rgba(25,135,84,0.2); color: #198754;">Completed</span>
                                <?php elseif ($r['status'] === 'rejected'): ?>
                                    <span class="status-badge" style="background: rgba(220,53,69,0.2); color: #dc3545;">Rejected</span>
                                <?php endif ?>
                            </div>
                            <p class="text-muted small mb-2"><?= esc(mb_substr($r['description'], 0, 120)) ?><?= mb_strlen($r['description']) > 120 ? '...' : '' ?></p>
                            <small class="text-muted">Submitted: <?= date('d M Y', strtotime($r['created_at'])) ?></small>
                        </div>
                    </a>
                </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</section>

<?= view('layouts/footer') ?>
