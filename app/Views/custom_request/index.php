<?= view('layouts/header') ?>

<style>
.plan-card { border: 2px solid var(--border-color); border-radius: 16px; padding: 2rem; text-align: center; transition: all 0.3s; background: var(--bg-card); }
.plan-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,0.15); }
.plan-card.popular { border-color: var(--accent-primary); position: relative; }
.plan-card .plan-badge { position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--accent-primary); color: #fff; padding: 4px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
.plan-card .price { font-size: 2.5rem; font-weight: 800; color: var(--text-primary); }
.plan-card .price small { font-size: 1rem; font-weight: 400; color: var(--text-muted); }
.plan-card ul { list-style: none; padding: 0; text-align: left; }
.plan-card ul li { padding: 6px 0; color: var(--text-muted); font-size: 0.9rem; }
.plan-card ul li i { margin-right: 8px; color: var(--accent-primary); }
#plan-features { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 1rem; margin-top: 0.5rem; }
#plan-features ul { list-style: none; padding: 0; margin: 0.5rem 0 0; }
#plan-features ul li { padding: 4px 0; color: var(--text-muted); font-size: 0.9rem; }
#plan-features ul li i { margin-right: 8px; }
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto text-center mb-5">
            <h1 class="display-5 fw-bold">Request Custom AI Art</h1>
            <p class="lead text-muted">Need something unique? Tell us what you envision and we'll create it using cutting-edge AI tools. Choose from our flexible plans below.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Submit Your Request</h4>

                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif ?>

                    <?php if (!auth()->loggedIn()): ?>
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-2"></i>Please <a href="<?= site_url('/login') ?>" class="alert-link">login</a> to submit a custom request.
                        </div>
                    <?php endif ?>

                    <form method="POST" action="<?= site_url('/custom-request/submit') ?>" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Your Name</label>
                                <input type="text" name="name" class="form-control" value="<?= auth()->loggedIn() ? (auth()->user()->username ?: auth()->user()->name ?: '') : old('name') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address</label>
                                <div class="form-control-plaintext border rounded px-3 py-2 bg-light" style="color: #1e293b;">
                                    <?= auth()->loggedIn() ? auth()->user()->email : '' ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Request Type</label>
                                <select name="request_type" class="form-select">
                                    <option value="ai_art" <?= old('request_type') === 'ai_art' ? 'selected' : '' ?>>AI Art</option>
                                    <option value="ai_audio" <?= old('request_type') === 'ai_audio' ? 'selected' : '' ?>>AI Audio / Song</option>
                                    <option value="other" <?= old('request_type') === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Plan</label>
                                <select name="plan" class="form-select" id="plan-select" onchange="updatePlanFeatures()">
                                    <option value="free" <?= old('plan') === 'free' ? 'selected' : '' ?>>Free (₹0)</option>
                                    <option value="99" <?= old('plan') === '99' ? 'selected' : '' ?>>Basic (₹99)</option>
                                    <option value="249" <?= old('plan') === '249' ? 'selected' : '' ?>>Pro (₹249)</option>
                                    <option value="499" <?= old('plan') === '499' ? 'selected' : '' ?>>Premium (₹499)</option>
                                </select>
                                <div id="plan-features"></div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Describe What You Need</label>
                                <textarea name="description" class="form-control" rows="5" placeholder="Describe the art style, subject, colors, mood, or song genre, lyrics theme etc. The more detail the better!" required><?= old('description') ?></textarea>
                                <span class="d-block mt-1 p-2 rounded" style="background: #fef2f2; color: #991b1b; font-size: 0.85rem; border: 1px solid #fecaca;">
                                    <i class="bi bi-exclamation-triangle"></i> We do not accept NSFW requests. / हम NSFW अनुरोध स्वीकार नहीं करते हैं।
                                </span>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Reference Image (optional)</label>
                                <input type="file" name="reference_image" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif">
                                <span class="d-block mt-1" style="color: #475569; font-size: 0.85rem;"><i class="bi bi-info-circle"></i> Upload a reference image for style inspiration.</span>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100" <?= !auth()->loggedIn() ? 'disabled' : '' ?>>Submit Request</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-5 justify-content-center">
        <div class="col-12 text-center mb-3">
            <span class="d-inline-block p-2 px-3 rounded" style="background: #f0fdf4; color: #166534; font-size: 0.85rem; border: 1px solid #bbf7d0;">
                <i class="bi bi-award"></i> Free plan completed art may get featured in our <a href="<?= site_url('/prompts') ?>" style="color: #166534; text-decoration: underline;">Prompts Library</a>.
            </span>
        </div>
        <div class="col-md-3">
            <div class="plan-card">
                <h5 class="fw-bold mb-3">Free</h5>
                <div class="price mb-2">₹0</div>
                <p class="text-muted mb-3">Best for trying out</p>
                <ul>
                    <li><i class="bi bi-check-circle"></i>1 custom request</li>
                    <li><i class="bi bi-check-circle"></i>Standard turnaround (3-5 days)</li>
                    <li><i class="bi bi-check-circle"></i>AI art or AI audio</li>
                    <li><i class="bi bi-check-circle"></i>Delivered via email</li>
                    <li><i class="bi bi-x-circle" style="color: var(--text-muted);"></i>Priority support</li>
                    <li><i class="bi bi-award"></i>May be featured in Prompts Library</li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <div class="plan-card">
                <h5 class="fw-bold mb-3">Basic</h5>
                <div class="price mb-2">₹99</div>
                <p class="text-muted mb-3">Quick & affordable</p>
                <ul>
                    <li><i class="bi bi-check-circle"></i>Up to 2 revisions</li>
                    <li><i class="bi bi-check-circle"></i>Faster turnaround (2-4 days)</li>
                    <li><i class="bi bi-check-circle"></i>AI art, AI audio, or custom</li>
                    <li><i class="bi bi-check-circle"></i>Standard resolution</li>
                    <li><i class="bi bi-check-circle"></i>Email support</li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <div class="plan-card">
                <h5 class="fw-bold mb-3">Pro</h5>
                <div class="price mb-2">₹249</div>
                <p class="text-muted mb-3">For serious creators</p>
                <ul>
                    <li><i class="bi bi-check-circle"></i>Up to 3 revisions</li>
                    <li><i class="bi bi-check-circle"></i>Priority turnaround (2-3 days)</li>
                    <li><i class="bi bi-check-circle"></i>AI art, AI audio, or custom</li>
                    <li><i class="bi bi-check-circle"></i>High-resolution output</li>
                    <li><i class="bi bi-check-circle"></i>Priority support</li>
                </ul>
            </div>
        </div>
        <div class="col-md-3">
            <div class="plan-card popular">
                <span class="plan-badge">Popular</span>
                <h5 class="fw-bold mb-3">Premium</h5>
                <div class="price mb-2">₹499</div>
                <p class="text-muted mb-3">For serious projects</p>
                <ul>
                    <li><i class="bi bi-check-circle"></i>Up to 5 revisions</li>
                    <li><i class="bi bi-check-circle"></i>Express turnaround (1-2 days)</li>
                    <li><i class="bi bi-check-circle"></i>AI art, AI audio, or custom</li>
                    <li><i class="bi bi-check-circle"></i>Ultra-HD resolution output</li>
                    <li><i class="bi bi-check-circle"></i>Dedicated support</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
const planData = {
    free: {
        features: [
            '<i class="bi bi-check-circle text-success"></i> 1 custom request',
            '<i class="bi bi-check-circle text-success"></i> Standard turnaround (3-5 days)',
            '<i class="bi bi-check-circle text-success"></i> AI art or AI audio',
            '<i class="bi bi-check-circle text-success"></i> Delivered via email',
            '<i class="bi bi-x-circle text-muted"></i> Priority support',
            '<i class="bi bi-award text-success"></i> May be featured in Prompts Library',
        ]
    },
    99: {
        features: [
            '<i class="bi bi-check-circle text-success"></i> Up to 2 revisions',
            '<i class="bi bi-check-circle text-success"></i> Faster turnaround (2-4 days)',
            '<i class="bi bi-check-circle text-success"></i> AI art, AI audio, or custom',
            '<i class="bi bi-check-circle text-success"></i> Standard resolution',
            '<i class="bi bi-check-circle text-success"></i> Email support',
        ]
    },
    249: {
        features: [
            '<i class="bi bi-check-circle text-success"></i> Up to 3 revisions',
            '<i class="bi bi-check-circle text-success"></i> Priority turnaround (2-3 days)',
            '<i class="bi bi-check-circle text-success"></i> AI art, AI audio, or custom',
            '<i class="bi bi-check-circle text-success"></i> High-resolution output',
            '<i class="bi bi-check-circle text-success"></i> Priority support',
        ]
    },
    499: {
        features: [
            '<i class="bi bi-check-circle text-success"></i> Up to 5 revisions',
            '<i class="bi bi-check-circle text-success"></i> Express turnaround (1-2 days)',
            '<i class="bi bi-check-circle text-success"></i> AI art, AI audio, or custom',
            '<i class="bi bi-check-circle text-success"></i> Ultra-HD resolution output',
            '<i class="bi bi-check-circle text-success"></i> Dedicated support',
        ]
    }
};

function updatePlanFeatures() {
    const select = document.getElementById('plan-select');
    const container = document.getElementById('plan-features');
    const plan = select.value;
    const data = planData[plan];
    if (!data) { container.innerHTML = ''; return; }
    let html = '<ul>';
    data.features.forEach(f => { html += '<li>' + f + '</li>'; });
    html += '</ul>';
    container.innerHTML = html;
}

document.addEventListener('DOMContentLoaded', updatePlanFeatures);
</script>

<?= view('layouts/footer') ?>
