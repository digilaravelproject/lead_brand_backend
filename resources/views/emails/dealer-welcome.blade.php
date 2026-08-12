<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your AdvisorX Pro dealer account</title>
</head>
<body style="margin:0;padding:0;background:#eef2ff;font-family:Arial,Helvetica,sans-serif;color:#172033;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef2ff;padding:36px 14px;">
    <tr><td align="center">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:620px;background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 18px 50px rgba(55,48,163,.14);">
            <tr><td style="padding:36px 40px;background:linear-gradient(135deg,#312e81,#6d28d9);color:#ffffff;">
                <table role="presentation" width="100%"><tr>
                    <td><div style="font-size:24px;font-weight:800;letter-spacing:-.5px;">AdvisorX Pro</div><div style="margin-top:5px;font-size:12px;letter-spacing:2px;text-transform:uppercase;color:#ddd6fe;">Dealer Partnership</div></td>
                    <td align="right"><span style="display:inline-block;padding:8px 13px;border:1px solid rgba(255,255,255,.3);border-radius:999px;font-size:11px;font-weight:700;letter-spacing:1px;">ACCOUNT READY</span></td>
                </tr></table>
                <h1 style="margin:34px 0 8px;font-size:30px;line-height:1.2;">Welcome, {{ $dealer->name }}!</h1>
                <p style="margin:0;color:#e9d5ff;font-size:15px;line-height:1.6;">Your dealer workspace is ready. Sign in to manage your assigned users and grow your AdvisorX Pro network.</p>
            </td></tr>
            <tr><td style="padding:34px 40px;">
                <p style="margin:0 0 18px;font-size:14px;color:#64748b;">Use these credentials for your first sign-in:</p>
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:16px;background:#f8fafc;overflow:hidden;">
                    <tr><td style="padding:15px 18px;border-bottom:1px solid #e2e8f0;font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;width:34%;">Email</td><td style="padding:15px 18px;border-bottom:1px solid #e2e8f0;font-size:14px;font-weight:700;color:#172033;word-break:break-all;">{{ $dealer->email }}</td></tr>
                    <tr><td style="padding:15px 18px;border-bottom:1px solid #e2e8f0;font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;">Password</td><td style="padding:15px 18px;border-bottom:1px solid #e2e8f0;font-family:monospace;font-size:15px;font-weight:700;color:#5b21b6;">{{ $plainPassword }}</td></tr>
                    <tr><td style="padding:15px 18px;border-bottom:1px solid #e2e8f0;font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;">Referral code</td><td style="padding:15px 18px;border-bottom:1px solid #e2e8f0;font-family:monospace;font-size:16px;font-weight:800;letter-spacing:2px;color:#5b21b6;">{{ $dealer->referral_code }}</td></tr>
                    <tr><td style="padding:15px 18px;font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;">User allowance</td><td style="padding:15px 18px;font-size:14px;font-weight:700;color:#172033;">{{ $dealer->user_limit }} users</td></tr>
                </table>
                <table role="presentation" width="100%" style="margin-top:28px;"><tr><td align="center">
                    <a href="{{ route('dealer.login') }}" style="display:inline-block;background:#6d28d9;color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;padding:14px 30px;border-radius:12px;box-shadow:0 8px 20px rgba(109,40,217,.24);">Open Dealer Portal &rarr;</a>
                </td></tr></table>
                <div style="margin-top:28px;padding:15px 17px;border-radius:12px;background:#fff7ed;border-left:4px solid #f97316;color:#9a3412;font-size:13px;line-height:1.6;"><strong>Security tip:</strong> Change your temporary password from Profile after your first sign-in. Never share your credentials.</div>
            </td></tr>
            <tr><td style="padding:22px 40px;background:#f8fafc;border-top:1px solid #e2e8f0;text-align:center;color:#94a3b8;font-size:11px;line-height:1.6;">This account was created by the AdvisorX Pro administrator.<br>If you were not expecting this email, please contact support.</td></tr>
        </table>
    </td></tr>
</table>
</body>
</html>
