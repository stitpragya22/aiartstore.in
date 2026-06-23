<?= view('admin/layouts/header') ?>

<div class="card-admin" style="max-width: 700px;">
    <form method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <h5 class="fw-bold mb-3"><i class="bi bi-credit-card me-2" style="color: var(--accent-primary);"></i>Razorpay Payment Gateway</h5>
        <p class="text-muted mb-4">Configure your Razorpay API keys. Switch between test and live modes for development and production.</p>

        <div class="mb-4">
            <label class="form-label fw-semibold">Mode</label>
            <div class="d-flex gap-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="razorpay_mode" id="modeTest" value="test" <?= (old('razorpay_mode', $settings['razorpay_mode'] ?? 'test') == 'test') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="modeTest">
                        <span class="badge-status" style="background: rgba(245,158,11,0.2); color: var(--warning); padding: 4px 12px;">Test Mode</span>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="razorpay_mode" id="modeLive" value="live" <?= (old('razorpay_mode', $settings['razorpay_mode'] ?? '') == 'live') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="modeLive">
                        <span class="badge-status" style="background: rgba(16,185,129,0.2); color: var(--success); padding: 4px 12px;">Live Mode</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="mb-4 p-3" style="border: 1px solid rgba(245,158,11,0.3); border-radius: 12px; background: rgba(245,158,11,0.05);" id="testKeys">
            <h6 class="fw-bold mb-3" style="color: var(--warning);"><i class="bi bi-flask me-1"></i>Test Keys</h6>
            <div class="mb-3">
                <label class="form-label fw-semibold">Test Key ID</label>
                <input type="text" name="razorpay_test_key_id" class="form-control" value="<?= old('razorpay_test_key_id', $settings['razorpay_test_key_id'] ?? '') ?>" placeholder="rzp_test_XXXXXXXXXXXXXXXX">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Test Key Secret</label>
                <input type="password" name="razorpay_test_key_secret" class="form-control" value="<?= old('razorpay_test_key_secret', $settings['razorpay_test_key_secret'] ?? '') ?>" placeholder="Enter test secret key">
            </div>
        </div>

        <div class="mb-4 p-3" style="border: 1px solid rgba(16,185,129,0.3); border-radius: 12px; background: rgba(16,185,129,0.05);" id="liveKeys">
            <h6 class="fw-bold mb-3" style="color: var(--success);"><i class="bi bi-rocket-takeoff me-1"></i>Live Keys</h6>
            <div class="mb-3">
                <label class="form-label fw-semibold">Live Key ID</label>
                <input type="text" name="razorpay_live_key_id" class="form-control" value="<?= old('razorpay_live_key_id', $settings['razorpay_live_key_id'] ?? '') ?>" placeholder="rzp_live_XXXXXXXXXXXXXXXX">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Live Key Secret</label>
                <input type="password" name="razorpay_live_key_secret" class="form-control" value="<?= old('razorpay_live_key_secret', $settings['razorpay_live_key_secret'] ?? '') ?>" placeholder="Enter live secret key">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Webhook Secret</label>
            <input type="password" name="razorpay_webhook_secret" class="form-control" value="<?= old('razorpay_webhook_secret', $settings['razorpay_webhook_secret'] ?? '') ?>" placeholder="Webhook secret from Razorpay dashboard">
            <small class="text-muted">Razorpay webhook URL: <?= site_url('/razorpay/webhook') ?></small>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Currency</label>
            <select name="razorpay_currency" class="form-select">
                <option value="INR" <?= (old('razorpay_currency', $settings['razorpay_currency'] ?? 'INR') == 'INR') ? 'selected' : '' ?>>INR (?)</option>
                <option value="USD" <?= (old('razorpay_currency', $settings['razorpay_currency'] ?? '') == 'USD') ? 'selected' : '' ?>>USD ($)</option>
            </select>
        </div>

        <hr class="my-4" style="border-color: var(--border-color);">

        <h5 class="fw-bold mb-3"><i class="bi bi-image me-2" style="color: var(--accent-primary);"></i>Site Logo & Favicon</h5>
        <p class="text-muted mb-4">Upload your site logo (for the navigation bar) and favicon (browser tab icon).</p>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Site Logo</label>
                <?php $logo = $settings['site_logo'] ?? ''; ?>
                <?php if ($logo): ?>
                    <div class="mb-2">
                        <img src="<?= base_url($logo) ?>" alt="Current logo" style="max-height:50px;border-radius:8px;border:1px solid var(--border-color);padding:4px;">
                    </div>
                <?php endif ?>
                <input type="file" name="site_logo" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif">
                <small class="text-muted">Recommended: PNG, max 200&times;60px. Leave empty to keep current.</small>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Favicon</label>
                <?php $favicon = $settings['site_favicon'] ?? ''; ?>
                <?php if ($favicon): ?>
                    <div class="mb-2">
                        <img src="<?= base_url($favicon) ?>" alt="Current favicon" style="max-height:32px;border-radius:4px;border:1px solid var(--border-color);padding:2px;">
                    </div>
                <?php endif ?>
                <input type="file" name="site_favicon" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif,image/x-icon">
                <small class="text-muted">Recommended: PNG/ICO, 32&times;32px or 16&times;16px.</small>
            </div>
        </div>

        <hr class="my-4" style="border-color: var(--border-color);">
        
        <h5 class="fw-bold mb-3"><i class="bi bi-code-slash me-2" style="color: var(--accent-primary);"></i>Custom Header & Footer Code</h5>
        <p class="text-muted mb-4">Add custom CSS styles to the website header and custom Javascript code to the footer (e.g. tracking pixels, analytics, or custom styling overrides).</p>

        <div class="mb-3">
            <label class="form-label fw-semibold">Custom Header Code (Loaded in &lt;head&gt;)</label>
            <textarea name="custom_css" class="form-control" rows="6" style="font-family: monospace;" placeholder="<!-- Paste your Google Analytics, custom CSS (inside <style>), or meta tags here -->"><?= esc($settings['custom_css'] ?? '') ?></textarea>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Custom Footer Code (Loaded before &lt;/body&gt;)</label>
            <textarea name="custom_js" class="form-control" rows="6" style="font-family: monospace;" placeholder="<!-- Paste your tracking pixels, footer scripts, or custom Javascript (inside <script>) here -->"><?= esc($settings['custom_js'] ?? '') ?></textarea>
        </div>

        <hr class="my-4" style="border-color: var(--border-color);">

        <h5 class="fw-bold mb-3"><i class="bi bi-envelope me-2" style="color: var(--accent-primary);"></i>Email Notifications</h5>
        <p class="text-muted mb-4">Configure notification email addresses for receiving admin alerts.</p>

        <div class="mb-4">
            <label class="form-label fw-semibold">Admin Notification Email</label>
            <input type="email" name="admin_email" class="form-control" value="<?= old('admin_email', $settings['admin_email'] ?? 'info@aiartstore.in') ?>" placeholder="admin@example.com">
            <small class="text-muted">New custom AI art requests will be sent to this email.</small>
        </div>

        <hr class="my-4" style="border-color: var(--border-color);">

        <h5 class="fw-bold mb-3"><i class="bi bi-share me-2" style="color: var(--accent-primary);"></i>Social Media Sharing</h5>
        <p class="text-muted mb-4">Connect your Facebook Page and Instagram Business account to share prompts directly from the admin panel. <a href="https://developers.facebook.com/docs/facebook-login/guides/access-tokens/" target="_blank" style="color: var(--accent-primary);">Learn how to get tokens</a></p>

        <div class="mb-3">
            <label class="form-label fw-semibold">Facebook Page ID</label>
            <input type="text" name="facebook_page_id" class="form-control" value="<?= old('facebook_page_id', $settings['facebook_page_id'] ?? '') ?>" placeholder="Your Facebook Page ID">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Facebook Page Access Token</label>
            <input type="password" name="facebook_access_token" class="form-control" value="<?= old('facebook_access_token', $settings['facebook_access_token'] ?? '') ?>" placeholder="Long-lived Page Access Token">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Instagram Business Account ID</label>
            <input type="text" name="instagram_business_id" class="form-control" value="<?= old('instagram_business_id', $settings['instagram_business_id'] ?? '') ?>" placeholder="Instagram Business Account ID">
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Instagram Access Token</label>
            <input type="password" name="instagram_access_token" class="form-control" value="<?= old('instagram_access_token', $settings['instagram_access_token'] ?? '') ?>" placeholder="Instagram/ Facebook Graph Access Token">
        </div>

        <button type="submit" class="btn btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Save Settings</button>
        <a href="<?= site_url('/admin') ?>" class="btn btn-outline-custom">Cancel</a>
    </form>
</div>

<script>
$(document).ready(function() {
    function toggleKeyVisibility() {
        var mode = $('input[name="razorpay_mode"]:checked').val();
        if (mode === 'live') {
            $('#testKeys').css('opacity', '0.4');
            $('#liveKeys').css('opacity', '1');
        } else {
            $('#testKeys').css('opacity', '1');
            $('#liveKeys').css('opacity', '0.4');
        }
    }
    $('input[name="razorpay_mode"]').change(toggleKeyVisibility);
    toggleKeyVisibility();
});
</script>

<?= view('admin/layouts/footer') ?>
