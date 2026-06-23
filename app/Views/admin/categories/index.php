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
            <thead><tr><th>Image</th><th>Name</th><th>Slug</th><th>Description</th><th>Curated</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td>
                        <?php if (!empty($cat['image'])): ?>
                            <img src="<?= base_url('uploads/categories/' . $cat['image']) ?>" alt="<?= esc($cat['name']) ?>" style="width: 48px; height: 48px; border-radius: 10px; object-fit: cover; border: 1px solid var(--border-color);">
                        <?php else: ?>
                            <div style="width: 48px; height: 48px; border-radius: 10px; background: var(--bg-card); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                                <i class="bi bi-image" style="font-size: 1.1rem;"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= esc($cat['name']) ?></strong></td>
                    <td class="text-muted"><?= esc($cat['slug']) ?></td>
                    <td class="text-muted"><?= esc(substr($cat['description'] ?? '', 0, 60)) ?></td>
                    <td>
                        <div class="form-check form-switch mb-0" style="padding-left: 2.5em;">
                            <input class="form-check-input toggle-switch" type="checkbox" data-id="<?= $cat['id'] ?>" data-field="is_curated" <?= ($cat['is_curated'] ?? 0) ? 'checked' : '' ?>>
                        </div>
                    </td>
                    <td>
                        <div class="form-check form-switch mb-0" style="padding-left: 2.5em;">
                            <input class="form-check-input toggle-switch" type="checkbox" data-id="<?= $cat['id'] ?>" data-field="status" <?= ($cat['status'] ?? 'active') === 'active' ? 'checked' : '' ?>>
                        </div>
                    </td>
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
    <script>
    $(function() {
        $('.toggle-switch').change(function() {
            var field = $(this).data('field');
            var value = field === 'status' ? ($(this).is(':checked') ? 'active' : 'inactive') : ($(this).is(':checked') ? 1 : 0);
            var data = { field: field, value: value };
            data['<?= csrf_token() ?>'] = $('meta[name="csrf-token"]').attr('content');
            $.post('<?= site_url('/admin/categories/toggle/') ?>' + $(this).data('id'), data)
            .done(function(res) {
                if (res.csrf_hash) $('meta[name="csrf-token"]').attr('content', res.csrf_hash);
                showToast('Updated', 'success');
            })
            .fail(function() { showToast('Toggle failed', 'error'); location.reload(); });
        });
    });
    </script>
    <?php endif; ?>
</div>

<?= view('admin/layouts/footer') ?>
