    </main>
    <footer class="footer">
        <div class="container">
            <div class="row mb-4 pb-2 border-bottom border-secondary" style="border-color: var(--border-color) !important;">
                <div class="col-12 text-center">
                    <a href="<?= site_url('/custom-request') ?>" class="btn btn-primary-custom btn-lg px-5 py-3" style="font-size: 1.1rem;">
                        <i class="bi bi-palette-fill me-2"></i>Request Custom AI Art
                    </a>
                    <p class="text-muted mt-2 mb-0 small">Get unique AI-generated artwork tailored just for you</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-5">
                    <h5 class="fw-bold" style="font-family: 'Space Grotesk', sans-serif;">
                        <?php if ($logo = get_custom_setting('site_logo')): ?>
                            <img src="<?= base_url($logo) ?>" alt="AI Art Store" style="max-height:40px;border-radius:8px;">
                        <?php else: ?>
                            <i class="bi bi-stars" style="color: var(--accent-primary);"></i> AI Art Store
                        <?php endif ?>
                    </h5>
                    <p class="text-muted mt-2">Premium AI-generated artwork for creators, designers, and art enthusiasts. Download high-quality digital art instantly.</p>
                    <div class="d-flex gap-2">
                        <a href="https://www.instagram.com/aiartstore.in/" target="_blank" rel="noopener" class="btn btn-outline-custom btn-sm" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="https://twitter.com/aiartstore" target="_blank" rel="noopener" class="btn btn-outline-custom btn-sm" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                        <a href="https://in.pinterest.com/aiartstore/" target="_blank" rel="noopener" class="btn btn-outline-custom btn-sm" aria-label="Pinterest"><i class="bi bi-pinterest"></i></a>
                    </div>
                    <div class="mt-3">
                        <p class="text-muted small mb-1">Secure payments via</p>
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?= base_url('razorpay.png') ?>" alt="Razorpay" style="height: 30px; object-fit: contain; opacity: 0.7;">
                            <div class="d-flex gap-2 fs-4 text-muted">
                                <i class="bi bi-credit-card"></i>
                                <i class="bi bi-paypal"></i>
                                <i class="bi bi-google"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><a href="<?= site_url('/') ?>" class="text-decoration-none text-muted">Home</a></li>
                        <li class="mb-2"><a href="<?= site_url('/shop') ?>" class="text-decoration-none text-muted">Gallery</a></li>
                        <li class="mb-2"><a href="<?= site_url('/custom-request') ?>" class="text-decoration-none text-muted">Custom Art</a></li>
                        <li class="mb-2"><a href="<?= site_url('/about') ?>" class="text-decoration-none text-muted">About</a></li>
                        <li class="mb-2"><a href="<?= site_url('/blog') ?>" class="text-decoration-none text-muted">Blog</a></li>
                        <li class="mb-2"><a href="<?= site_url('/wishlist') ?>" class="text-decoration-none text-muted">Wishlist</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Support</h6>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><a href="<?= site_url('/contact') ?>" class="text-decoration-none text-muted">Contact</a></li>
                        <li class="mb-2"><a href="<?= site_url('/faq') ?>" class="text-decoration-none text-muted">FAQ</a></li>
                        <li class="mb-2"><a href="<?= site_url('/terms') ?>" class="text-decoration-none text-muted">Terms</a></li>
                        <li class="mb-2"><a href="<?= site_url('/privacy') ?>" class="text-decoration-none text-muted">Privacy</a></li>
                        <li class="mb-2"><a href="<?= site_url('/refund') ?>" class="text-decoration-none text-muted">Refund</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold mb-3">Popular Categories</h6>
                    <div class="pop-cat-grid">
                        <a href="<?= site_url('/shop') ?>">All Art</a>
                        <a href="<?= site_url('/shop') ?>">Abstract</a>
                        <a href="<?= site_url('/shop') ?>">Fantasy</a>
                        <a href="<?= site_url('/shop') ?>">Landscape</a>
                        <a href="<?= site_url('/shop') ?>">Portrait</a>
                        <a href="<?= site_url('/shop') ?>">Sci-Fi</a>
                        <a href="<?= site_url('/shop') ?>">Nature</a>
                        <a href="<?= site_url('/shop') ?>">Cyberpunk</a>
                        <a href="<?= site_url('/shop') ?>">Minimal</a>
                        <a href="<?= site_url('/shop') ?>">Surreal</a>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: var(--border-color);">
            <p class="text-center text-muted mb-0 small">&copy; <?= date('Y') ?> AI Art Store. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        function showToast(msg, type) {
            var bg = type === 'error' ? 'linear-gradient(135deg, #ef4444, #dc2626)' :
                      type === 'success' ? 'linear-gradient(135deg, #22c55e, #16a34a)' :
                      'linear-gradient(135deg, #8b5cf6, #6366f1)';
            Toastify({ text: msg, duration: 4000, gravity: 'top', position: 'right',
                stopOnFocus: true, style: { background: bg, borderRadius: '12px',
                boxShadow: '0 8px 32px rgba(0,0,0,0.3)', fontSize: '0.9rem' } }).showToast();
        }

        function updateCartCount() {
            $.get('<?= site_url('/cart/count') ?>', function(data) {
                $('#cartCount').text(data.count);
            }).fail(function() {
                console.error('Failed to update cart count');
            });
        }

        function toggleWishlist(productId, element) {
            var $btn = $(element);
            $.ajax({
                url: '<?= site_url('/wishlist/toggle') ?>',
                type: 'POST',
                data: { product_id: productId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        if (response.action === 'added') {
                            $btn.addClass('active');
                            $btn.find('i').removeClass('bi-heart').addClass('bi-heart-fill');
                            showToast('Product added to wishlist', 'success');
                        } else {
                            $btn.removeClass('active');
                            $btn.find('i').removeClass('bi-heart-fill').addClass('bi-heart');
                            showToast('Product removed from wishlist', 'success');
                            
                            // If we are on the wishlist page, fade out the item's card
                            if (window.location.pathname.indexOf('/wishlist') !== -1) {
                                $btn.closest('.col-lg-3, .col-md-4, .col-sm-6, .col-12, .card-art').fadeOut(400, function() {
                                    $(this).remove();
                                    if ($('.wishlist-grid').children(':visible').length === 0) {
                                        location.reload();
                                    }
                                });
                            }
                        }
                        $('#wishlistCount').text(response.count);
                    } else {
                        showToast(response.message, 'error');
                        if (response.message.toLowerCase().indexOf('login') !== -1) {
                            setTimeout(function() {
                                window.location.href = '<?= site_url('/login') ?>';
                            }, 1500);
                        }
                    }
                },
                error: function() {
                    showToast('Something went wrong. Please try again.', 'error');
                }
            });
        }
    </script>

    <!-- Mobile App-Style Bottom Navigation -->
    <nav class="app-bottom-nav d-md-none">
        <a href="<?= site_url('/') ?>" class="<?= current_url() == site_url('/') ? 'active' : '' ?>">
            <span class="nav-icon-wrap"><i class="bi bi-house-door-fill"></i></span>
            Home
        </a>
        <a href="<?= site_url('/shop') ?>" class="<?= strpos(current_url(), '/shop') !== false && strpos(current_url(), '/shop/') === false ? 'active' : '' ?>">
            <span class="nav-icon-wrap"><i class="bi bi-grid-fill"></i></span>
            Shop
        </a>
        <a href="<?= site_url('/custom-request') ?>" class="<?= strpos(current_url(), '/custom-request') !== false ? 'active' : '' ?>" style="color: var(--accent-primary);">
            <span class="nav-icon-wrap"><i class="bi bi-palette-fill"></i></span>
            <strong>Custom</strong>
        </a>
        <a href="<?= site_url('/cart') ?>" class="<?= strpos(current_url(), '/cart') !== false ? 'active' : '' ?>">
            <span class="nav-icon-wrap">
                <i class="bi bi-bag-fill"></i>
                <span class="nav-badge" id="mobileCartCount"><?= getCartCount() ?></span>
            </span>
            Cart
        </a>
        <?php if (auth()->loggedIn()): ?>
        <a href="<?= site_url('/orders') ?>" class="<?= strpos(current_url(), '/orders') !== false ? 'active' : '' ?>">
            <span class="nav-icon-wrap"><i class="bi bi-box-seam-fill"></i></span>
            Orders
        </a>
        <?php else: ?>
        <a href="<?= site_url('/login') ?>">
            <span class="nav-icon-wrap"><i class="bi bi-person-fill"></i></span>
            Login
        </a>
        <?php endif; ?>
    </nav>

    <script>
    // Sync mobile cart badge with desktop
    $(function() {
        var count = $('#cartCount').text();
        $('#mobileCartCount').text(count);
    });
    </script>

    <!-- Dynamic Footer Code (e.g. Analytics, JS, etc.) -->
    <?= get_custom_setting('custom_js') ?>
</body>
</html>
