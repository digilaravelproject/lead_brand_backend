<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Welcome to AdvisorX Pro</title></head>
<body style="margin:0;padding:0;background:#ecfeff;font-family:Arial,Helvetica,sans-serif;color:#16303b;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#ecfeff;padding:36px 14px;">
    <tr><td align="center">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:620px;background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 18px 50px rgba(13,148,136,.13);">
            <tr><td style="padding:38px 40px;background:linear-gradient(135deg,#064e3b,#0f766e);color:#ffffff;text-align:center;">
                <div style="display:inline-block;width:54px;height:54px;line-height:54px;border-radius:16px;background:rgba(255,255,255,.14);font-size:25px;font-weight:800;">AX</div>
                <div style="margin-top:14px;font-size:12px;letter-spacing:2px;text-transform:uppercase;color:#99f6e4;">AdvisorX Pro</div>
                <h1 style="margin:18px 0 8px;font-size:30px;line-height:1.2;">Your journey starts now</h1>
                <p style="margin:0;color:#ccfbf1;font-size:15px;line-height:1.6;">Welcome, {{ $user->name }}. Your complimentary four-day access is active.</p>
            </td></tr>
            <tr><td style="padding:34px 40px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"><tr>
                    <td style="padding:18px;border-radius:14px;background:#f0fdfa;border:1px solid #ccfbf1;"><div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#0f766e;">Trial ends</div><div style="margin-top:7px;font-size:18px;font-weight:800;color:#134e4a;">{{ $user->subscription_ends_at->format('d M Y, h:i A') }}</div></td>
                </tr></table>
                <p style="margin:26px 0 16px;font-size:14px;line-height:1.7;color:#475569;">Your account was created by <strong style="color:#0f766e;">{{ $owner->name }}</strong>. Sign in using <strong>{{ $user->email }}</strong> and the email OTP sent during login.</p>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:14px;background:#f8fafc;overflow:hidden;">
                    <tr><td style="padding:14px 17px;border-bottom:1px solid #e2e8f0;font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;width:35%;">Account email</td><td style="padding:14px 17px;border-bottom:1px solid #e2e8f0;font-size:14px;font-weight:700;word-break:break-all;">{{ $user->email }}</td></tr>
                    <tr><td style="padding:14px 17px;font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;">Account owner</td><td style="padding:14px 17px;font-size:15px;font-weight:800;color:#0f766e;">{{ $owner instanceof \App\Models\Dealer ? 'Dealer · '.$owner->referral_code : 'Administrator' }}</td></tr>
                </table>
                <div style="margin-top:24px;padding:16px 18px;border-radius:12px;background:#eff6ff;color:#1e40af;font-size:13px;line-height:1.65;"><strong>What happens after four days?</strong><br>Your {{ $owner instanceof \App\Models\Dealer ? 'dealer' : 'administrator' }} can approve continued access. You will be unable to use protected features while approval is pending.</div>
            </td></tr>
            <tr><td style="padding:22px 40px;background:#f8fafc;border-top:1px solid #e2e8f0;text-align:center;color:#94a3b8;font-size:11px;line-height:1.6;">Welcome to AdvisorX Pro. We’re glad to have you with us.<br>This is an automated account notification.</td></tr>
        </table>
    </td></tr>
</table>
</body>
</html>
