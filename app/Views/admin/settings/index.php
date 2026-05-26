<?= view('admin/layouts/header') ?>

<div class="card-admin" style="max-width: 700px;">
    <form method="POST">
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
