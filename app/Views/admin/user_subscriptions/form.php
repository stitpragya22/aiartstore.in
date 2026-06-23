<?= view('admin/layouts/header') ?>

<div class="card-admin" style="max-width: 500px;">
    <form action="<?= site_url('/admin/user-subscriptions/create') ?>" method="POST">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">User</label>
            <select name="user_id" class="form-select" required>
                <option value="">-- Select User --</option>
                <?php foreach ($users as $u): ?>
                <option value="<?= $u->id ?>" <?= old('user_id') == $u->id ? 'selected' : '' ?>><?= esc($u->email) ?> (<?= esc($u->username ?? 'No username') ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Plan</label>
            <select name="plan_id" class="form-select" required>
                <option value="">-- Select Plan --</option>
                <?php foreach ($plans as $p): ?>
                <option value="<?= $p['id'] ?>" <?= old('plan_id') == $p['id'] ? 'selected' : '' ?>><?= esc($p['name']) ?> (Level <?= $p['level'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Start Date</label>
            <input type="datetime-local" name="start_date" class="form-control" value="<?= old('start_date', date('Y-m-d\TH:i')) ?>">
        </div>
        <button type="submit" class="btn btn-primary-custom">Assign Subscription</button>
        <a href="<?= site_url('/admin/user-subscriptions') ?>" class="btn btn-outline-custom">Cancel</a>
    </form>
</div>

<?= view('admin/layouts/footer') ?>
