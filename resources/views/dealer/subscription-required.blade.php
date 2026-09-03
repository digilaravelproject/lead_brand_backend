<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Required - AdvisorX Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box}body{margin:0;min-height:100vh;display:grid;place-items:center;padding:20px;background:#080d20;color:#fff;font-family:'DM Sans',sans-serif}.backdrop{position:fixed;inset:0;background:radial-gradient(circle at 50% 20%,rgba(91,75,225,.25),transparent 42%)}.card{position:relative;width:100%;max-width:520px;padding:34px;border:1px solid #283149;border-radius:24px;background:#111a30;box-shadow:0 30px 90px rgba(0,0,0,.45);text-align:center}.icon{width:70px;height:70px;margin:0 auto 20px;display:grid;place-items:center;border-radius:22px;background:rgba(245,158,11,.12);color:#f59e0b;font-size:34px}.card h1{margin:0;font:800 25px 'Manrope'}.card p{margin:12px 0 24px;color:#aeb8cc;line-height:1.65}.details{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:22px;text-align:left}.detail{padding:13px;border:1px solid #25304a;border-radius:13px;background:#0b1326}.detail span{display:block;color:#78859e;font-size:10px;text-transform:uppercase}.detail strong{display:block;margin-top:5px;font-size:13px}.contacts{padding:17px;border-radius:15px;background:#19243c}.contacts span{display:block;color:#aeb8cc;font-size:12px}.contacts a{display:inline-block;margin:9px 6px 0;color:#5eead4;font-weight:700;text-decoration:none}.logout{margin-top:22px;border:1px solid #35415e;border-radius:11px;padding:11px 22px;background:transparent;color:#dbe3f2;cursor:pointer}@media(max-width:480px){.details{grid-template-columns:1fr}.card{padding:25px}}
    </style>
</head>
<body>
<div class="backdrop"></div>
<section class="card" role="dialog" aria-modal="true" aria-labelledby="subscription-title">
    <div class="icon">!</div>
    <h1 id="subscription-title">Subscription Required</h1>
    <p>Your one-year free subscription has expired. Dealer features and all user accounts under this dealer are unavailable until an administrator extends the end date.</p>
    <div class="details">
        <div class="detail"><span>Plan</span><strong>Free subscription</strong></div>
        <div class="detail"><span>Expired on</span><strong>{{ optional($dealer->subscription_ends_at)->format('d M Y') ?: 'Not available' }}</strong></div>
        <div class="detail"><span>Price</span><strong><s style="text-decoration-thickness:2px">{{ number_format($dealer->price, 2) }}</s></strong></div>
        <div class="detail"><span>Offer Price</span><strong>{{ number_format($dealer->offer_price, 2) }}</strong></div>
    </div>
    <div class="contacts">
        <span>Contact the administrator to renew access</span>
        @if($admin?->phone_number)<a href="tel:{{ $admin->phone_number }}">{{ $admin->phone_number }}</a>@endif
        @if($admin?->alternative_phone_number)<a href="tel:{{ $admin->alternative_phone_number }}">{{ $admin->alternative_phone_number }}</a>@endif
        @if(! $admin?->phone_number && ! $admin?->alternative_phone_number)<strong style="display:block;margin-top:8px">Contact details are not configured.</strong>@endif
    </div>
    <form method="POST" action="{{ route('dealer.logout') }}">@csrf<button class="logout">Log Out</button></form>
</section>
</body>
</html>
