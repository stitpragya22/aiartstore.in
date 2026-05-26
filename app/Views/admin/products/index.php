<?= view('admin/layouts/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div><p class="text-muted mb-0">Manage your art products</p></div>
    <a href="<?= site_url('/admin/products/create') ?>" class="btn btn-primary-custom"><i class="bi bi-plus-lg me-1"></i>Add Product</a>
</div>

<div class="card-admin">
    <?php if (empty($products)): ?>
        <p class="text-muted mb-0">No products yet. <a href="<?= site_url('/admin/products/create') ?>" style="color: var(--accent-primary);">Add your first product</a></p>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-admin">
            <thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Price</th><th>Featured</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td>
                        <?php if ($p['image']): ?>
                            <img src="<?= base_url('uploads/products/' . $p['image']) ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        <?php else: ?>
                            <div style="width: 50px; height: 50px; background: var(--bg-card); border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-image text-muted"></i></div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= esc($p['title']) ?></strong></td>
                    <td style="color:var(--text-secondary);font-weight:500;"><?= esc($p['category_name'] ?? 'Uncategorized') ?></td>
                    <td class="price-tag"><?= formatPrice($p['price']) ?></td>
                    <td><?= $p['is_featured'] ? '<span class="badge-status completed">Yes</span>' : '<span class="text-muted">No</span>' ?></td>
                    <td><span class="badge-status <?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="<?= site_url('/shop/' . $p['slug']) ?>" class="btn btn-sm btn-outline-custom" target="_blank"><i class="bi bi-eye"></i></a>
                            <a href="<?= site_url('/admin/products/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-custom"><i class="bi bi-pencil"></i></a>
                            <form action="<?= site_url('/admin/products/delete/' . $p['id']) ?>" method="POST" onsubmit="return confirm('Delete this product?')">
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
