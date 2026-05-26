<?= view('admin/layouts/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div><p class="text-muted mb-0">Manage art categories</p></div>
    <a href="<?= site_url('/admin/categories/create') ?>" class="btn btn-primary-custom"><i class="bi bi-plus-lg me-1"></i>Add Category</a>
</div>

<div class="card-admin">
    <?php if (empty($categories)): ?>
        <p class="text-muted mb-0">No categories yet.</p>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-admin">
            <thead><tr><th>Name</th><th>Slug</th><th>Description</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><strong><?= esc($cat['name']) ?></strong></td>
                    <td class="text-muted"><?= esc($cat['slug']) ?></td>
                    <td class="text-muted"><?= esc(substr($cat['description'] ?? '', 0, 60)) ?></td>
                    <td><span class="badge-status <?= $cat['status'] ?>"><?= ucfirst($cat['status']) ?></span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="<?= site_url('/admin/categories/edit/' . $cat['id']) ?>" class="btn btn-sm btn-outline-custom"><i class="bi bi-pencil"></i></a>
                            <form action="<?= site_url('/admin/categories/delete/' . $cat['id']) ?>" method="POST" onsubmit="return confirm('Delete this category?')">
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
