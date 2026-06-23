<?= view('layouts/header') ?>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold" style="font-size: 2.5rem;">Choose Your Plan</h1>
            <p class="text-secondary" style="font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Unlock premium AI prompts and resources. Pick the plan that fits your creative needs.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($plans as $plan): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; overflow: hidden; transition: all 0.3s;">
                    <?php if ($plan['level'] === 2): ?>
                    <div style="background: linear-gradient(135deg, #8b5cf6, #6366f1); text-align: center; padding: 4px; font-size: 0.75rem; font-weight: 600; color: white; text-transform: uppercase; letter-spacing: 1px;">Most Popular</div>
                    <?php endif; ?>
                    <div class="p-4 text-center">
                        <h5 class="fw-bold mb-1" style="color: #ffffff;"><?= esc($plan['name']) ?></h5>
                        <p style="color: #cbd5e1; font-size: 0.85rem; margin-bottom: 0.75rem;"><?= esc($plan['description']) ?></p>
                        <div class="mb-3">
                            <span style="font-size: 2.5rem; font-weight: 800; color: #ffffff;">₹<?= number_format($plan['price'], 0) ?></span>
                            <?php if ($plan['validity_days'] > 0): ?>
                            <span style="color: #94a3b8;"> / <?= $plan['validity_days'] ?> days</span>
                            <?php else: ?>
                            <span style="color: #94a3b8;"> / Lifetime</span>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <span class="badge-status" style="background:rgba(139,92,246,0.35);color:#ffffff;font-weight:600;">Level <?= $plan['level'] ?> Access</span>
                        </div>
                        <ul class="list-unstyled text-start mb-4" style="font-size: 0.9rem; line-height: 2.2; color: #e0e0e0;">
                            <li><i class="bi bi-check-circle-fill me-2" style="color: #22c55e;"></i>Access to Level <?= $plan['level'] ?> prompts</li>
                            <li><i class="bi bi-check-circle-fill me-2" style="color: #22c55e;"></i>Includes <?= $plan['level'] > 0 ? 'Level ' . ($plan['level'] - 1) . ' and below' : 'all free prompts' ?></li>
                            <?php if ($plan['validity_days'] > 0): ?>
                            <li><i class="bi bi-check-circle-fill me-2" style="color: #22c55e;"></i><?= $plan['validity_days'] ?> days of access</li>
                            <?php else: ?>
                            <li><i class="bi bi-check-circle-fill me-2" style="color: #22c55e;"></i>Lifetime access</li>
                            <?php endif; ?>
                            <li><i class="bi bi-check-circle-fill me-2" style="color: #22c55e;"></i>New prompts added regularly</li>
                        </ul>
                        <?php if ((int)$plan['price'] === 0): ?>
                        <div style="padding: 12px; border-radius: 12px; background: rgba(34,197,94,0.15); color: #22c55e; font-weight: 600; text-align: center; font-size: 0.9rem;"><i class="bi bi-check-circle-fill me-1"></i>Current Plan</div>
                        <?php elseif (auth()->loggedIn()): ?>
                        <a href="<?= site_url('/subscriptions/purchase/' . $plan['id']) ?>" class="btn w-100" style="background: linear-gradient(135deg, #8b5cf6, #6366f1); color: white; border: none; padding: 12px; border-radius: 12px; font-weight: 600;">Get Started</a>
                        <?php else: ?>
                        <a href="<?= site_url('/login') ?>" class="btn w-100" style="background: linear-gradient(135deg, #8b5cf6, #6366f1); color: white; border: none; padding: 12px; border-radius: 12px; font-weight: 600;">Log in to Subscribe</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?= view('layouts/footer') ?>
