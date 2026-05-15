<?= view('layouts/header') ?>

<section class="py-5">
    <div class="container">
        <h1 class="section-title mb-4">Checkout</h1>

        <?php if (empty($cart)): ?>
            <div class="empty-state">
                <i class="bi bi-bag-x"></i>
                <h4>Your cart is empty</h4>
                <a href="<?= site_url('/shop') ?>" class="btn btn-primary-custom">Browse Gallery</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="stat-card">
                        <h5 class="fw-bold mb-3">Order Summary</h5>
                        <?php foreach ($cart as $item): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <?php if ($item['image']): ?>
                                    <img src="<?= base_url('uploads/products/' . $item['image']) ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; filter: blur(2px);">
                                <?php endif; ?>
                                <div>
                                    <small class="fw-semibold"><?= esc($item['title']) ?></small>
                                    <small class="d-block text-muted">Qty: <?= $item['quantity'] ?></small>
                                </div>
                            </div>
                            <span class="price-tag"><?= formatPrice($item['price'] * $item['quantity']) ?></span>
                        </div>
                        <?php endforeach; ?>
                        <hr style="border-color: var(--border-color);">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="price-tag fs-4"><?= formatPrice($total) ?></span>
                        </div>
                    </div>

                    <div class="stat-card mt-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check fs-4" style="color: var(--success);"></i>
                            <div>
                                <strong>Secure Payment</strong>
                                <p class="text-muted mb-0 small">Your payment is processed securely via Razorpay</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="stat-card">
                        <h5 class="fw-bold mb-3">Payment Details</h5>
                        <p class="text-muted small mb-4">You'll be redirected to Razorpay to complete your payment securely.</p>

                        <div class="d-grid gap-3">
                            <button id="payBtn" class="btn btn-primary-custom btn-lg">
                                <i class="bi bi-credit-card me-2"></i>Pay <?= formatPrice($total) ?>
                            </button>
                            <a href="<?= site_url('/cart') ?>" class="btn btn-outline-custom">Back to Cart</a>
                        </div>

                        <div class="d-flex justify-content-center gap-3 mt-4 text-muted fs-3">
                            <i class="bi bi-credit-card"></i>
                            <i class="bi bi-paypal"></i>
                            <i class="bi bi-google"></i>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
$('#payBtn').click(function() {
    var btn = $(this);
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');

    $.post('<?= site_url('/checkout/createOrder') ?>', function(res) {
        if (res.status === 'error') {
            alert(res.message);
            btn.prop('disabled', false).html('<i class="bi bi-credit-card me-2"></i>Pay <?= formatPrice($total) ?>');
            return;
        }

        var options = {
            key: res.keyId,
            amount: res.amount,
            currency: 'INR',
            name: 'AI Art Store',
            description: 'Art Purchase',
            order_id: res.orderId,
            prefill: {
                name: res.name,
                email: res.email,
            },
            theme: { color: '#8b5cf6' },
            handler: function(response) {
                $('<form>').attr({ method: 'POST', action: '<?= site_url('/checkout/verify') ?>' })
                    .append($('<input>').attr({ name: 'razorpay_order_id', value: response.razorpay_order_id }))
                    .append($('<input>').attr({ name: 'razorpay_payment_id', value: response.razorpay_payment_id }))
                    .append($('<input>').attr({ name: 'razorpay_signature', value: response.razorpay_signature }))
                    .appendTo('body').submit();
            },
            modal: {
                ondismiss: function() {
                    btn.prop('disabled', false).html('<i class="bi bi-credit-card me-2"></i>Pay <?= formatPrice($total) ?>');
                }
            }
        };

        var rzp = new Razorpay(options);
        rzp.open();
    }).fail(function() {
        alert('Something went wrong. Please try again.');
        btn.prop('disabled', false).html('<i class="bi bi-credit-card me-2"></i>Pay <?= formatPrice($total) ?>');
    });
});
</script>

<?= view('layouts/footer') ?>
