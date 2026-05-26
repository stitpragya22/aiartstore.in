<?= view('layouts/header') ?>
<style>
.checkout-item {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 0.9rem 1rem;
    margin-bottom: 0.6rem;
}
.checkout-item:last-child { margin-bottom: 0; }
.checkout-pay-bar {
    position: sticky;
    bottom: 0;
    background: rgba(10,10,15,0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-top: 1px solid var(--border-color);
    padding: 0.9rem 1rem;
    padding-bottom: max(0.9rem, env(safe-area-inset-bottom, 0.9rem));
    z-index: 100;
    margin: 0 -1rem;
}
@media (min-width: 768px) {
    .checkout-pay-bar { position: static; border-radius: 16px; margin: 0; background: var(--bg-card); backdrop-filter: none; border: 1px solid var(--border-color); padding: 1.5rem; }
}
</style>

<section class="py-4">
    <div class="container">
        <h1 class="section-title mb-4" style="font-size:1.6rem;">Checkout</h1>

        <?php if (empty($cart)): ?>
            <div class="empty-state">
                <i class="bi bi-bag-x"></i>
                <h4>Your cart is empty</h4>
                <a href="<?= site_url('/shop') ?>" class="btn btn-primary-custom">Browse Gallery</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="stat-card p-0 p-md-4" style="background:transparent;border:none;">
                        <h5 class="fw-bold mb-3 px-3 px-md-0">Order Summary</h5>
                        <?php foreach ($cart as $item): ?>
                        <div class="checkout-item d-flex align-items-center gap-3">
                            <?php if ($item['image']): ?>
                                <img src="<?= base_url('uploads/products/' . $item['image']) ?>" alt="<?= esc($item['title']) ?>" style="width:48px;height:48px;object-fit:cover;border-radius:10px;filter:blur(2px);flex-shrink:0;">
                            <?php endif; ?>
                            <div class="flex-grow-1 min-w-0">
                                <small class="fw-semibold d-block" style="font-size:0.9rem;"><?= esc($item['title']) ?></small>
                            </div>
                            <span class="price-tag flex-shrink-0"><?= formatPrice($item['price'] * $item['quantity']) ?></span>
                        </div>
                        <?php endforeach; ?>
                        <hr style="border-color:var(--border-color);" class="mx-3 mx-md-0">
                        <div class="d-flex justify-content-between px-3 px-md-0">
                            <span class="fw-bold fs-5">Subtotal</span>
                            <span class="price-tag fs-5" id="subtotalDisplay"><?= formatPrice($total) ?></span>
                        </div>
                        <?php if ($coupon_discount > 0): ?>
                        <div class="d-flex justify-content-between text-success mt-1 px-3 px-md-0">
                            <span class="small fw-semibold">Discount (<?= esc($coupon_code) ?>)</span>
                            <span class="small fw-semibold" id="discountDisplay">-<?= formatPrice($coupon_discount) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="stat-card mt-3">
                        <h6 class="fw-semibold mb-2">Have a coupon?</h6>
                        <div class="input-group">
                            <input type="text" id="couponCode" class="form-control" placeholder="Enter code" value="<?= esc($coupon_code ?? '') ?>">
                            <button id="applyCouponBtn" class="btn btn-primary-custom">Apply</button>
                            <?php if ($coupon_code): ?>
                            <button id="removeCouponBtn" class="btn btn-outline-custom">Remove</button>
                            <?php endif; ?>
                        </div>
                        <div id="couponMessage" class="mt-2 small"></div>
                    </div>

                    <div class="stat-card mt-3 d-none d-md-flex">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check fs-4" style="color: var(--success);"></i>
                            <div>
                                <strong>Secure Payment</strong>
                                <p class="mb-0 small" style="color: #a0a0b8;">Your payment is processed securely via Razorpay</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="checkout-pay-bar">
                        <h5 class="fw-bold mb-3 d-none d-md-block">Payment Details</h5>
                        <p class="text-muted small mb-3 d-none d-md-block">You'll be redirected to Razorpay to complete your payment securely.</p>

                        <div class="d-flex d-md-block align-items-center gap-3">
                            <div class="flex-grow-1 d-md-none">
                                <span class="text-muted small">Total</span>
                                <div class="price-tag fs-5 fw-bold" id="grandTotalDisplayMobile"><?= formatPrice($grand_total) ?></div>
                            </div>
                            <button id="payBtn" class="btn btn-primary-custom btn-lg w-100" style="border-radius:14px;font-size:1.05rem;">
                                <i class="bi bi-credit-card me-2"></i>Pay <span id="payAmount"><?= formatPrice($grand_total) ?></span>
                            </button>
                        </div>
                        <a href="<?= site_url('/cart') ?>" class="btn btn-outline-custom w-100 mt-2 d-none d-md-block">Back to Cart</a>

                        <div class="d-flex justify-content-center gap-3 mt-3 text-muted fs-3">
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
var grandTotal = <?= $grand_total ?>;

// Refresh CSRF token from cookie after each AJAX call (token regenerates on POST)
$(document).ajaxComplete(function() {
    var match = document.cookie.match(/(?:^|;\s*)csrf_cookie_name=([^;]+)/);
    if (match) {
        $('meta[name="csrf-token"]').attr('content', decodeURIComponent(match[1]));
    }
});

$('#payBtn').click(function() {
    var btn = $(this);
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');

    $.post('<?= site_url('/checkout/createOrder') ?>', { '<?= csrf_token() ?>': $('meta[name="csrf-token"]').attr('content') }, function(res) {
        if (res.status === 'error') {
            alert(res.message);
            btn.prop('disabled', false).html('<i class="bi bi-credit-card me-2"></i>Pay ' + formatPrice(grandTotal));
            return;
        }

        var options = {
            key: res.keyId,
            amount: res.amount,
            currency: res.currency,
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
                    .append($('<input>').attr({ name: '<?= csrf_token() ?>', value: $('meta[name="csrf-token"]').attr('content') }))
                    .append($('<input>').attr({ name: 'razorpay_order_id', value: response.razorpay_order_id }))
                    .append($('<input>').attr({ name: 'razorpay_payment_id', value: response.razorpay_payment_id }))
                    .append($('<input>').attr({ name: 'razorpay_signature', value: response.razorpay_signature }))
                    .appendTo('body').submit();
            },
            modal: {
                ondismiss: function() {
                    btn.prop('disabled', false).html('<i class="bi bi-credit-card me-2"></i>Pay ' + formatPrice(grandTotal));
                }
            }
        };

        var rzp = new Razorpay(options);
        rzp.open();
    }).fail(function() {
        alert('Something went wrong. Please try again.');
        btn.prop('disabled', false).html('<i class="bi bi-credit-card me-2"></i>Pay ' + formatPrice(grandTotal));
    });
});

// Coupon handling
$('#applyCouponBtn').click(function() {
    var code = $('#couponCode').val().trim();
    if (!code) { $('#couponMessage').html('<span class="text-danger">Enter a coupon code</span>'); return; }

    $.post('<?= site_url('/checkout/validate-coupon') ?>', {
        code: code,
        '<?= csrf_token() ?>': $('meta[name="csrf-token"]').attr('content')
    }, function(res) {
        if (res.valid) {
            $('#couponMessage').html('<span class="text-success">' + res.message + '</span>');
            $('#discountDisplay').text('-₹' + res.discount.toFixed(2));
            $('#grandTotalDisplayMobile').text('₹' + res.grand_total.toFixed(2));
            $('#payAmount').text('₹' + res.grand_total.toFixed(2));
            grandTotal = res.grand_total;
            $('#subtotalDisplay').text('₹' + (grandTotal + (res.discount || 0)).toFixed(2));
        } else {
            $('#couponMessage').html('<span class="text-danger">' + res.message + '</span>');
        }
    }).fail(function() {
        $('#couponMessage').html('<span class="text-danger">Request failed. Check your connection and try again.</span>');
    });
});

$('#removeCouponBtn').click(function() {
    $.post('<?= site_url('/checkout/validate-coupon') ?>', {
        code: '',
        '<?= csrf_token() ?>': $('meta[name="csrf-token"]').attr('content')
    }, function(res) {
        location.reload();
    }).fail(function() {
        $('#couponMessage').html('<span class="text-danger">Failed to remove coupon. Try again.</span>');
    });
});

function formatPrice(amount) {
    return '₹' + parseFloat(amount).toFixed(2);
}
</script>

<?= view('layouts/footer') ?>
