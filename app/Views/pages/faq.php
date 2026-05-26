<?= view('layouts/header') ?>
<section class="py-5">
    <div class="container">
        <h1 class="section-title mb-4">Frequently Asked Questions</h1>
        <div class="stat-card p-4">
            <div class="mb-4">
                <h5 class="fw-bold mb-2">What is AI Art Store?</h5>
                <p class="text-secondary mb-0">AI Art Store is a marketplace for premium AI-generated digital artworks. Browse, purchase, and download high-resolution art for your creative projects.</p>
            </div>
            <div class="mb-4">
                <h5 class="fw-bold mb-2">How do I purchase an artwork?</h5>
                <p class="text-secondary mb-0">Browse our gallery, add artworks to your cart, and proceed to checkout. Payment is processed securely via Razorpay. Once payment is confirmed, the artwork is available for immediate download.</p>
            </div>
            <div class="mb-4">
                <h5 class="fw-bold mb-2">What payment methods are accepted?</h5>
                <p class="text-secondary mb-0">We accept all major credit and debit cards, UPI, net banking, and wallets through Razorpay, India's leading payment gateway.</p>
            </div>
            <div class="mb-4">
                <h5 class="fw-bold mb-2">How do I download my purchase?</h5>
                <p class="text-secondary mb-0">After successful payment, you will be redirected to your order page where you can download each artwork. You can also access your downloads anytime from the Downloads section in your account.</p>
            </div>
            <div class="mb-4">
                <h5 class="fw-bold mb-2">Can I use the artwork for commercial projects?</h5>
                <p class="text-secondary mb-0">Each purchase includes a license for personal and commercial use (up to a reasonable limit). Redistribution or resale of the digital files is not permitted.</p>
            </div>
            <div class="mb-4">
                <h5 class="fw-bold mb-2">What is a watermarked preview?</h5>
                <p class="text-secondary mb-0">Product images on the site display a watermarked preview to protect the artist's work. The downloaded high-resolution file does not contain any watermark.</p>
            </div>
            <div class="mb-4">
                <h5 class="fw-bold mb-2">I didn't receive my download link. What should I do?</h5>
                <p class="text-secondary mb-0">Check your Orders page in your account. If the order shows as completed, you can download from there. If you still face issues, contact us at <a href="mailto:support@aiartstore.in" class="text-decoration-none" style="color: var(--accent-primary);">support@aiartstore.in</a>.</p>
            </div>
            <div class="mb-4">
                <h5 class="fw-bold mb-2">Can I get a refund?</h5>
                <p class="text-secondary mb-0">Due to the digital nature of our products, all sales are final. Exceptions are made for duplicate purchases or technical errors. See our <a href="<?= site_url('/refund') ?>" class="text-decoration-none" style="color: var(--accent-primary);">Refund Policy</a> for details.</p>
            </div>
            <div class="mb-4">
                <h5 class="fw-bold mb-2">How do I change my account details?</h5>
                <p class="text-secondary mb-0">You can update your profile information from your account settings after logging in. For email changes, please contact support.</p>
            </div>
            <div>
                <h5 class="fw-bold mb-2">Is my payment information secure?</h5>
                <p class="text-secondary mb-0">Yes. All payments are processed through Razorpay's secure platform. We do not store any payment card details on our servers.</p>
            </div>
        </div>
    </div>
</section>
<?= view('layouts/footer') ?>
