<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>New Message</title></head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #4f46e5;">New Message on Request #<?= (int) $requestId ?></h2>
    <p>Hi <?= esc($name) ?>,</p>
    <p>You have received a new message from our team regarding your custom request <strong>#<?= (int) $requestId ?></strong>.</p>
    <p style="text-align: center; margin: 30px 0;">
        <a href="<?= base_url('custom-request/track/' . $requestId) ?>" style="display: inline-block; padding: 12px 24px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 6px;">View Message</a>
    </p>
    <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
    <p style="color: #999; font-size: 0.8rem;">AI Art Store</p>
</body>
</html>
