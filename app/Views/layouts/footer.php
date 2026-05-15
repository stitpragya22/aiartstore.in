    </main>
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5 class="fw-bold" style="font-family: 'Space Grotesk', sans-serif;"><i class="bi bi-stars" style="color: var(--accent-primary);"></i> AI Art Store</h5>
                    <p class="text-muted mt-2">Premium AI-generated artwork for creators, designers, and art enthusiasts. Download high-quality digital art instantly.</p>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-custom btn-sm"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-custom btn-sm"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="btn btn-outline-custom btn-sm"><i class="bi bi-pinterest"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><a href="<?= site_url('/') ?>" class="text-decoration-none text-muted">Home</a></li>
                        <li class="mb-2"><a href="<?= site_url('/shop') ?>" class="text-decoration-none text-muted">Gallery</a></li>
                        <li class="mb-2"><a href="<?= site_url('/cart') ?>" class="text-decoration-none text-muted">Cart</a></li>
                        <li class="mb-2"><a href="<?= site_url('/orders') ?>" class="text-decoration-none text-muted">Orders</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Support</h6>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Terms</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Privacy</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold mb-3">Payment</h6>
                    <p class="text-muted small">Secure payments via Razorpay</p>
                    <div class="d-flex gap-2 fs-3 text-muted">
                        <i class="bi bi-credit-card"></i>
                        <i class="bi bi-paypal"></i>
                        <i class="bi bi-google"></i>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: var(--border-color);">
            <p class="text-center text-muted mb-0 small">&copy; <?= date('Y') ?> AI Art Store. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
            setTimeout(function() { $('.alert-custom').fadeOut(500); }, 5000);
        });

        function updateCartCount() {
            $.get('<?= site_url('/cart/count') ?>', function(data) {
                $('#cartCount').text(data.count);
            });
        }
    </script>
</body>
</html>
