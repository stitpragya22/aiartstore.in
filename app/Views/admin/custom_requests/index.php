<?= view('admin/layouts/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Custom Requests</h4>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= esc($r['name']) ?></td>
                    <td><?= esc($r['email']) ?></td>
                    <td><span class="badge bg-info"><?= esc($r['request_type']) ?></span></td>
                    <td>
                        <span class="badge bg-<?= $r['plan'] === 'free' ? 'secondary' : ($r['plan'] === '99' ? 'info' : ($r['plan'] === '249' ? 'primary' : 'warning text-dark')) ?>">
                            <?php if ($r['plan'] === 'free'): ?>Free
                            <?php elseif ($r['plan'] === '99'): ?>₹99
                            <?php elseif ($r['plan'] === '249'): ?>₹249
                            <?php elseif ($r['plan'] === '499'): ?>₹499
                            <?php endif ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($r['status'] === 'pending'): ?>
                            <span class="badge bg-secondary">Pending</span>
                        <?php elseif ($r['status'] === 'in_progress'): ?>
                            <span class="badge bg-primary">In Progress</span>
                        <?php elseif ($r['status'] === 'completed'): ?>
                            <span class="badge bg-success">Completed</span>
                        <?php elseif ($r['status'] === 'rejected'): ?>
                            <span class="badge bg-danger">Rejected</span>
                        <?php endif ?>
                    </td>
                    <td><?= date('d M Y', strtotime($r['created_at'])) ?></td>
                    <td>
                        <a href="<?= site_url('/admin/custom-requests/detail/' . $r['id']) ?>" class="btn btn-sm btn-primary">View</a>
                        <form action="<?= site_url('/admin/custom-requests/delete/' . $r['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this request?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach ?>
                <?php if (empty($requests)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No requests yet.</td>
                </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('admin/layouts/footer') ?>
