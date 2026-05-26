<?= view('admin/layouts/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div><p class="text-muted mb-0">Manage landing pages for Facebook ads</p></div>
    <a href="<?= site_url('/admin/landing-pages/create') ?>" class="btn btn-primary-custom"><i class="bi bi-plus-lg me-1"></i>Add Landing Page</a>
</div>

<div class="card-admin">
    <?php if (empty($landingPages)): ?>
        <p class="text-muted mb-0">No landing pages yet.</p>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Headline</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($landingPages as $lp): ?>
                <tr>
                    <td><?= $lp['id'] ?></td>
                    <td><a href="<?= site_url('/lp/' . $lp['slug']) ?>" target="_blank" class="text-decoration-none"><?= esc($lp['title']) ?> <i class="bi bi-box-arrow-up-right" style="font-size:0.7rem;"></i></a></td>
                    <td><?= esc($lp['slug']) ?></td>
                    <td><?= esc(mb_substr($lp['headline'] ?? '', 0, 40)) ?></td>
                    <td><span class="badge-status <?= $lp['status'] ?>"><?= $lp['status'] ?></span></td>
                    <td><?= date('d M Y', strtotime($lp['created_at'])) ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="<?= site_url('/admin/landing-pages/edit/' . $lp['id']) ?>" class="btn btn-sm btn-outline-custom"><i class="bi bi-pencil"></i></a>
                            <form action="<?= site_url('/admin/landing-pages/delete/' . $lp['id']) ?>" method="POST" onsubmit="return confirm('Delete this landing page?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-custom" style="border-color: rgba(239,68,68,0.3); color: var(--danger);"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?= view('admin/layouts/footer') ?>
