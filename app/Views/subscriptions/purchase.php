<?= view('layouts/header') ?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; overflow: hidden;">
                    <div class="p-4 text-center" style="background: linear-gradient(135deg, rgba(139,92,246,0.1), rgba(99,102,241,0.1));">
                        <h3 class="fw-bold mb-1" style="color: #ffffff;"><?= esc($plan['name']) ?></h3>
                        <div class="my-3">
                            <span style="font-size: 3rem; font-weight: 800; color: #ffffff;">₹<?= number_format($plan['price'], 0) ?></span>
                            <?php if ($plan['validity_days'] > 0): ?>
                            <span style="color: #94a3b8;"> / <?= $plan['validity_days'] ?> days</span>
                            <?php else: ?>
                            <span style="color: #94a3b8;"> / Lifetime</span>
                            <?php endif; ?>
                        </div>
                        <p style="color: #cbd5e1;"><?= esc($plan['description']) ?></p>
                    </div>
                    <div class="p-4">
                        <button id="payBtn" class="btn w-100" style="background: linear-gradient(135deg, #8b5cf6, #6366f1); color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 600; font-size: 1.1rem;">
                            <i class="bi bi-lock me-2"></i>Pay ₹<?= number_format($plan['price'], 0) ?>
                        </button>
                        <p class="text-center small mt-2" style="color: #94a3b8;">Secure payment via Razorpay</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('payBtn').addEventListener('click', function() {
    var btn = this;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

    fetch('<?= site_url('/subscriptions/purchase/' . $plan['id']) ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: '<?= csrf_token() ?>=<?= csrf_hash() ?>'
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.error) { alert(data.error); btn.disabled = false; btn.innerHTML = 'Pay ₹<?= number_format($plan['price'], 0) ?>'; return; }

        var options = {
            key: data.key_id,
            amount: data.amount,
            currency: data.currency,
            name: 'AI Art Store',
            description: data.plan_name + ' Subscription',
            order_id: data.order_id,
            prefill: {
                email: '<?= auth()->user()->email ?? '' ?>',
            },
            handler: function(response) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= site_url('/subscriptions/verify') ?>';

                var fields = {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_signature: response.razorpay_signature,
                    plan_id: data.plan_id,
                    db_order_id: data.db_order_id,
                };

                for (var key in fields) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = fields[key];
                    form.appendChild(input);
                }

                document.body.appendChild(form);
                form.submit();
            },
            modal: {
                ondismiss: function() {
                    btn.disabled = false;
                    btn.innerHTML = 'Pay ₹<?= number_format($plan['price'], 0) ?>';
                }
            },
            theme: { color: '#8b5cf6' }
        };

        var rzp = new Razorpay(options);
        rzp.open();
    })
    .catch(function(err) {
        console.error(err);
        alert('Something went wrong. Please try again.');
        btn.disabled = false;
        btn.innerHTML = 'Pay ₹<?= number_format($plan['price'], 0) ?>';
    });
});
</script>

<?= view('layouts/footer') ?>
