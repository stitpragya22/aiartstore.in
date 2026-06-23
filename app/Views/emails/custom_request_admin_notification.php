<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:40px 20px;">
<tr><td align="center">
<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.1);">
<tr><td style="background:linear-gradient(135deg,#667eea,#764ba2);padding:32px;text-align:center;">
<h1 style="color:#fff;margin:0;font-size:24px;">New Custom Request Received</h1>
</td></tr>
<tr><td style="padding:32px;">
<p style="font-size:16px;color:#333;line-height:1.6;">A new custom AI art request has been submitted.</p>
<table style="width:100%;border-collapse:collapse;margin:20px 0;">
<tr><td style="padding:8px 12px;border:1px solid #eee;font-weight:600;width:140px;background:#f9f9f9;">Name</td><td style="padding:8px 12px;border:1px solid #eee;"><?= esc($name) ?></td></tr>
<tr><td style="padding:8px 12px;border:1px solid #eee;font-weight:600;background:#f9f9f9;">Email</td><td style="padding:8px 12px;border:1px solid #eee;"><a href="mailto:<?= esc($email) ?>"><?= esc($email) ?></a></td></tr>
<tr><td style="padding:8px 12px;border:1px solid #eee;font-weight:600;background:#f9f9f9;">Type</td><td style="padding:8px 12px;border:1px solid #eee;"><?= esc($requestType) ?></td></tr>
<tr><td style="padding:8px 12px;border:1px solid #eee;font-weight:600;background:#f9f9f9;">Plan</td><td style="padding:8px 12px;border:1px solid #eee;">
    <?php if ($plan === 'free'): ?>Free
    <?php elseif ($plan === '99'): ?>Basic (₹99)
    <?php elseif ($plan === '249'): ?>Pro (₹249)
    <?php elseif ($plan === '499'): ?>Premium (₹499)
    <?php else: ?><?= esc($plan) ?>
    <?php endif ?>
</td></tr>
</table>
<p style="font-size:16px;color:#333;line-height:1.6;"><strong>Description:</strong></p>
<p style="font-size:15px;color:#555;line-height:1.6;background:#f9f9f9;padding:16px;border-radius:8px;"><?= nl2br(esc($description)) ?></p>
<p style="text-align:center;margin:30px 0;">
<a href="<?= site_url('/admin/custom-requests/detail/' . $requestId) ?>" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;padding:14px 32px;border-radius:8px;text-decoration:none;font-size:16px;font-weight:600;display:inline-block;">View in Admin Panel</a>
</p>
</td></tr>
<tr><td style="background:#f8f9fa;padding:20px 32px;text-align:center;border-top:1px solid #eee;">
<p style="font-size:13px;color:#6c757d;margin:0;">AI Art Store — Admin Notification</p>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>
