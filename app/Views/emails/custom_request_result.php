<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:40px 20px;">
<tr><td align="center">
<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.1);">
<tr><td style="background:linear-gradient(135deg,#667eea,#764ba2);padding:32px;text-align:center;">
<h1 style="color:#fff;margin:0;font-size:24px;">Your Custom Request is Ready!</h1>
</td></tr>
<tr><td style="padding:32px;">
<p style="font-size:16px;color:#333;line-height:1.6;">Hi <strong><?= esc($name) ?></strong>,</p>
<p style="font-size:16px;color:#333;line-height:1.6;">Great news! Your custom AI art request <strong>#<?= $requestId ?></strong> has been completed. Your result file is attached to this email.</p>
<p style="font-size:16px;color:#333;line-height:1.6;">You can also download it directly from the link below:</p>
<p style="text-align:center;margin:30px 0;">
<a href="<?= $resultFile ?>" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;padding:14px 32px;border-radius:8px;text-decoration:none;font-size:16px;font-weight:600;display:inline-block;">Download Your File</a>
</p>
<p style="font-size:16px;color:#333;line-height:1.6;">If you have any questions or need modifications, feel free to reply to this email.</p>
<p style="font-size:16px;color:#333;line-height:1.6;">Thank you for choosing AI Art Store!</p>
</td></tr>
<tr><td style="background:#f8f9fa;padding:20px 32px;text-align:center;border-top:1px solid #eee;">
<p style="font-size:13px;color:#6c757d;margin:0;">AI Art Store — Premium AI-generated artwork</p>
<p style="font-size:13px;color:#6c757d;margin:5px 0 0;"><a href="https://aiartstore.in" style="color:#667eea;">aiartstore.in</a></p>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>
