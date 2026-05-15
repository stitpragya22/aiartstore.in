<?= view('admin/layouts/header') ?>

<div class="card-admin">
    <?php if (empty($users)): ?>
        <p class="text-muted mb-0">No users found.</p>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-admin">
            <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Groups</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td>#<?= $user->id ?></td>
                    <td><strong><?= esc($user->username ?? 'N/A') ?></strong></td>
                    <td><?= esc($user->email) ?></td>
                    <td>
                        <?php $groups = $user->getGroups(); ?>
                        <?php if (empty($groups)): ?>
                            <span class="text-muted">None</span>
                        <?php else: ?>
                            <?php foreach ($groups as $g): ?>
                                <span class="badge-status active"><?= esc($g) ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-custom dropdown-toggle" data-bs-toggle="dropdown">Manage</button>
                            <ul class="dropdown-menu dropdown-menu-dark" style="background: var(--bg-card); border-color: var(--border-color);">
                                <li>
                                    <form action="<?= site_url('/admin/users/toggle-group/' . $user->id) ?>" method="POST" class="px-3 py-2">
                                        <small class="text-muted d-block mb-2">Toggle Groups:</small>
                                        <?php foreach (['user', 'admin', 'superadmin', 'beta'] as $group): ?>
                                            <div class="form-check mb-1">
                                                <input type="radio" name="group" value="<?= $group ?>" class="form-check-input" id="g<?= $user->id . $group ?>" <?= in_array($group, $groups) ? 'checked' : '' ?>>
                                                <label class="form-check-label small" for="g<?= $user->id . $group ?>"><?= ucfirst($group) ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                        <button type="submit" class="btn btn-sm btn-primary-custom mt-2 w-100">Update</button>
                                    </form>
                                </li>
                            </ul>
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
