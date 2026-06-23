<?= view('layouts/header') ?>
<section class="py-5">
    <div class="container">
        <h1 class="section-title mb-4">Contact Us</h1>
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="stat-card p-4">
                    <h5 class="fw-bold mb-3">Get in Touch</h5>
                    <p class="text-secondary">Have a question, feedback, or need assistance? We'd love to hear from you. Reach out via email and we'll get back to you within 24 hours.</p>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <i class="bi bi-envelope fs-4" style="color: var(--accent-primary);"></i>
                        <div>
                            <strong>Email</strong><br>
                            <a href="mailto:support@aiartstore.in" class="text-decoration-none text-secondary">support@aiartstore.in</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <i class="bi bi-envelope fs-4" style="color: var(--accent-primary);"></i>
                        <div>
                            <strong>Secondary Email</strong><br>
                            <a href="mailto:info@stitpragya.in" class="text-decoration-none text-secondary">info@stitpragya.in</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-clock fs-4" style="color: var(--accent-primary);"></i>
                        <div>
                            <strong>Response Time</strong><br>
                            <span class="text-secondary">Within 24 hours on business days</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="stat-card p-4">
                    <h5 class="fw-bold mb-3">Frequently Asked</h5>
                    <p class="text-secondary">Before reaching out, check our <a href="<?= site_url('/faq') ?>" class="text-decoration-none" style="color: var(--accent-primary);">FAQ</a> for quick answers to common questions about purchases, downloads, and account management.</p>
                    <hr style="border-color: var(--border-color);">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-shield-check fs-4" style="color: var(--success);"></i>
                        <div>
                            <strong>Secure Support</strong><br>
                            <span class="text-secondary">For order-specific issues, include your order number for faster assistance.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="ratio" style="--bs-aspect-ratio: 50%;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2871.4776830584556!2d74.73227037520645!3d19.11928658209427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bdcbb4b23d4156b%3A0x598d5bc22d6ab14f!2sStitpragya%20Technologies!5e1!3m2!1sen!2sin!4v1782223484668!5m2!1sen!2sin" style="border:0; border-radius: 12px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="stat-card p-4 text-center">
                    <p class="text-secondary mb-0">
                        Managed by <strong style="color: var(--accent-primary);">Stitpragya Technologies</strong>,
                        Savedi, Ahilyangar, MH, IND
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<?= view('layouts/footer') ?>
