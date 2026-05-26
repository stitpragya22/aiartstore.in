<?= view('admin/layouts/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Blog Posts</h4>
    <a href="<?= site_url('/admin/blog/posts/create') ?>" class="btn btn-primary">+ Write New Post</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>SEO Score</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td>
                        <strong><?= esc($p['title']) ?></strong><br>
                        <small class="text-muted">/blog/<?= esc($p['slug']) ?></small>
                    </td>
                    <td><?= esc($p['category_name'] ?? 'Uncategorized') ?></td>
                    <td>
                        <?php if ($p['seo_score'] >= 80): ?>
                            <span class="badge bg-success"><?= $p['seo_score'] ?></span>
                        <?php elseif ($p['seo_score'] >= 50): ?>
                            <span class="badge bg-warning text-dark"><?= $p['seo_score'] ?></span>
                        <?php else: ?>
                            <span class="badge bg-danger"><?= $p['seo_score'] ?></span>
                        <?php endif ?>
                    </td>
                    <td>
                        <?php if ($p['status'] === 'published'): ?>
                            <span class="badge bg-success">Published</span>
                        <?php elseif ($p['status'] === 'draft'): ?>
                            <span class="badge bg-secondary">Draft</span>
                        <?php else: ?>
                            <span class="badge bg-dark">Archived</span>
                        <?php endif ?>
                    </td>
                    <td><?= $p['published_at'] ? date('d M Y', strtotime($p['published_at'])) : '-' ?></td>
                    <td>
                        <a href="<?= site_url('/admin/blog/posts/edit/' . $p['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="<?= site_url('/blog/' . esc($p['slug'])) ?>" class="btn btn-sm btn-info" target="_blank">View</a>
                        <form action="<?= site_url('/admin/blog/posts/delete/' . $p['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this post?')">
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
