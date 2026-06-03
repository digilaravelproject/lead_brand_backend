<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your OTP</title>
    <style>
      body { background: #0f1720; color: #e6eef6; font-family: 'Helvetica Neue', Arial, sans-serif; margin:0; padding:0; }
      .container { max-width:600px; margin:40px auto; padding:24px; background: #0b1220; border-radius:12px; box-shadow: 0 6px 18px rgba(0,0,0,0.6); }
      .brand { font-size:28px; font-weight:700; color:#fff; margin-bottom:8px; }
      .lead { color:#9aa8b7; margin-bottom:24px; }
      .otp-box { display:flex; align-items:center; justify-content:center; gap:14px; margin:26px 0; }
      .otp { background:#121826; border-radius:12px; padding:18px 22px; font-size:28px; letter-spacing:6px; color:#ffd166; font-weight:700; box-shadow: inset 0 -4px 12px rgba(0,0,0,0.6); }
      .footer { margin-top:28px; color:#94a3b8; font-size:13px; }
      .btn { display:inline-block; margin-top:18px; background:#f2b21b; color:#081018; padding:12px 20px; border-radius:10px; text-decoration:none; font-weight:700; }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="brand">Lead Brand Hub</div>
      <div class="lead">Use the code below to verify your email. This code expires in 10 minutes.</div>

      <div class="otp-box">
        <div class="otp">{{ $otp }}</div>
      </div>

      <div style="text-align:center">
        <a class="btn" href="#">Open App</a>
      </div>

      <div class="footer">If you did not request this code, you can safely ignore this email.</div>
    </div>
  </body>
  </html>
