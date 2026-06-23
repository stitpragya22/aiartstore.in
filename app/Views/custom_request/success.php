<?= view('layouts/header') ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-6 mx-auto text-center">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <i class="bi bi-check-circle" style="font-size: 4rem; color: var(--accent-primary);"></i>
                    <h2 class="fw-bold mt-3">Request Submitted!</h2>
                    <p class="text-muted lead">Thank you! We've received your custom AI art request. We'll review it and get back to you at the email address you provided.</p>
                    <p class="text-muted">For paid plans, we'll contact you with payment instructions shortly.</p>
                    <a href="<?= site_url('/') ?>" class="btn btn-primary mt-3">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= view('layouts/footer') ?>
