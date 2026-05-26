<?= view('admin/layouts/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Blog Categories</h4>
    <a href="<?= site_url('/admin/blog/categories/create') ?>" class="btn btn-primary">+ Add Category</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Posts</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= esc($c['name']) ?></td>
                    <td><?= esc($c['slug']) ?></td>
                    <td><?= (int)($postCounts[$c['id']] ?? 0) ?></td>
                    <td><span class="badge bg-<?= $c['status'] === 'active' ? 'success' : 'secondary' ?>"><?= $c['status'] ?></span></td>
                    <td>
                        <a href="<?= site_url('/admin/blog/categories/edit/' . $c['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                        <form action="<?= site_url('/admin/blog/categories/delete/' . $c['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?')">
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
