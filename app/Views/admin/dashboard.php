<?= view('admin/layouts/header') ?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card-admin">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: rgba(139,92,246,0.2); color: var(--accent-primary);"><i class="bi bi-image"></i></div>
                <div>
                    <small class="text-muted">Total Products</small>
                    <h3 class="mb-0 fw-bold"><?= $totalProducts ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: rgba(16,185,129,0.2); color: var(--success);"><i class="bi bi-box"></i></div>
                <div>
                    <small class="text-muted">Total Orders</small>
                    <h3 class="mb-0 fw-bold"><?= $totalOrders ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: rgba(245,158,11,0.2); color: var(--warning);"><i class="bi bi-currency-rupee"></i></div>
                <div>
                    <small class="text-muted">Revenue</small>
                    <h3 class="mb-0 fw-bold"><?= formatPrice($totalRevenue) ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: rgba(99,102,241,0.2); color: #818cf8;"><i class="bi bi-people"></i></div>
                <div>
                    <small class="text-muted">Total Users</small>
                    <h3 class="mb-0 fw-bold"><?= $totalUsers ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-admin">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: rgba(236,72,153,0.2); color: #ec4899;"><i class="bi bi-pencil-square"></i></div>
                <div>
                    <small class="text-muted">Blog Posts</small>
                    <h3 class="mb-0 fw-bold"><?= $totalBlogPosts ?? 0 ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-admin">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Recent Orders</h5>
        <a href="<?= site_url('/admin/orders') ?>" class="btn btn-sm btn-outline-custom">View All</a>
    </div>
    <?php if (empty($recentOrders)): ?>
        <p class="text-muted mb-0">No orders yet</p>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-admin">
            <thead><tr><th>Order #</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($recentOrders as $order): ?>
                <tr>
                    <td><strong><?= esc($order['order_number']) ?></strong></td>
                    <td><?= formatPrice($order['total']) ?></td>
                    <td><span class="badge-status <?= $order['payment_status'] === 'completed' ? 'completed' : 'pending' ?>"><?= ucfirst($order['payment_status']) ?></span></td>
                    <td><span class="badge-status <?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                    <td class="text-muted"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                    <td><a href="<?= site_url('/admin/orders/' . $order['id']) ?>" class="btn btn-sm btn-primary-custom">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<div class="card-admin">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Recent Blog Posts</h5>
        <a href="<?= site_url('/admin/blog/posts') ?>" class="btn btn-sm btn-outline-custom">View All</a>
    </div>
    <?php if (empty($recentBlogPosts)): ?>
        <p class="text-muted mb-0">No blog posts yet</p>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-admin">
            <thead><tr><th>Title</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($recentBlogPosts as $bp): ?>
                <tr>
                    <td><strong><?= esc($bp['title']) ?></strong></td>
                    <td><span class="badge-status <?= $bp['status'] ?>"><?= ucfirst($bp['status']) ?></span></td>
                    <td class="text-muted"><?= $bp['published_at'] ? date('d M Y', strtotime($bp['published_at'])) : '-' ?></td>
                    <td><a href="<?= site_url('/admin/blog/posts/edit/' . $bp['id']) ?>" class="btn btn-sm btn-primary-custom">Edit</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?= view('admin/layouts/footer') ?>
