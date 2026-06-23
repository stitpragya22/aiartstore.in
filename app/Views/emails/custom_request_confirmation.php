<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Request Received</title></head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #4f46e5;">Thank You, <?= esc($name) ?>!</h2>
    <p>We have received your custom AI art request <strong>#<?= (int) $requestId ?></strong> and it is now under review.</p>
    <p>Our team will get back to you shortly. You can track the status of your request at any time:</p>
    <p style="text-align: center; margin: 30px 0;">
        <a href="<?= base_url('custom-request/track/' . $requestId) ?>" style="display: inline-block; padding: 12px 24px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 6px;">Track Your Request</a>
    </p>
    <p style="font-size: 0.9rem; color: #666;">If the button above does not work, copy this URL: <?= base_url('custom-request/track/' . $requestId) ?></p>
    <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
    <p style="color: #999; font-size: 0.8rem;">AI Art Store</p>
</body>
</html>
