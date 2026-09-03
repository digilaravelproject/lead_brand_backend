<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dealer Panel') - AdvisorX Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--dealer-navy:#18133f;--dealer-purple:#5b4be1;--dealer-purple-dark:#352b91;--dealer-teal:#18b9b1;--dealer-bg:#f3f6fb;--dealer-text:#20233a;--dealer-muted:#7a8099;--dealer-border:#e5e9f2}
        *{box-sizing:border-box}body{margin:0;background:var(--dealer-bg);color:var(--dealer-text);font-family:'DM Sans',sans-serif}.hidden{display:none!important}
        .dealer-shell{display:flex;min-height:100vh}.dealer-sidebar{position:fixed;inset:0 auto 0 0;z-index:50;width:275px;padding:26px 20px 22px;background:linear-gradient(175deg,#161136 0%,#241967 100%);color:#fff;display:flex;flex-direction:column;box-shadow:12px 0 35px rgba(37,28,99,.12)}
        .dealer-brand{display:flex;align-items:center;gap:12px;padding:3px 8px 27px;border-bottom:1px solid rgba(255,255,255,.1)}.dealer-brand-mark{width:47px;height:47px;display:grid;place-items:center;border-radius:15px;background:linear-gradient(135deg,#2ed8cf,#6858ec);font-family:'Manrope';font-weight:800;box-shadow:0 10px 22px rgba(24,185,177,.28)}.dealer-brand h2{margin:0;font:800 17px/1.25 'Manrope';letter-spacing:-.3px}.dealer-brand span{display:block;margin-top:4px;color:#8de4df;font-size:10px;font-weight:700;letter-spacing:1.8px;text-transform:uppercase}
        .dealer-nav{display:flex;flex-direction:column;gap:9px;padding-top:28px;flex:1}.dealer-nav-label{padding:0 13px 7px;color:#827cab;font-size:10px;font-weight:700;letter-spacing:1.6px;text-transform:uppercase}.dealer-nav a{display:flex;align-items:center;gap:13px;padding:14px 15px;border-radius:14px;color:#bcb9d7;text-decoration:none;font-size:14px;font-weight:600;transition:.2s}.dealer-nav a svg{width:20px;height:20px}.dealer-nav a:hover{background:rgba(255,255,255,.07);color:#fff}.dealer-nav a.active{background:linear-gradient(135deg,rgba(24,185,177,.23),rgba(91,75,225,.3));color:#fff;box-shadow:inset 3px 0 #35d4cc}.dealer-nav a.active svg{color:#48ded6}
        .dealer-quota{margin:18px 4px;padding:16px;border:1px solid rgba(113,224,217,.18);border-radius:15px;background:rgba(7,8,34,.22)}.dealer-quota-head{display:flex;justify-content:space-between;font-size:11px;color:#a8a3cc}.dealer-quota-head strong{color:#fff}.dealer-progress{height:6px;margin-top:10px;border-radius:10px;background:rgba(255,255,255,.1);overflow:hidden}.dealer-progress span{display:block;height:100%;border-radius:10px;background:linear-gradient(90deg,#21c7bf,#7567f0)}
        .dealer-logout{width:100%;display:flex;align-items:center;justify-content:center;gap:9px;padding:12px;border:1px solid rgba(255,255,255,.12);border-radius:13px;background:rgba(255,255,255,.06);color:#d7d4e8;font:600 13px 'DM Sans';cursor:pointer}.dealer-logout:hover{border-color:rgba(248,113,113,.35);background:rgba(248,113,113,.1);color:#fecaca}
        .dealer-main{width:calc(100% - 275px);min-height:100vh;margin-left:275px}.dealer-navbar{height:82px;padding:0 34px;display:flex;align-items:center;justify-content:space-between;background:#fff;border-bottom:1px solid var(--dealer-border);position:sticky;top:0;z-index:30}.dealer-navbar-left{display:flex;align-items:center;gap:14px}.dealer-menu-button{display:none;border:0;background:#f0f2f8;border-radius:11px;padding:9px;color:#34345c;cursor:pointer}.dealer-navbar h1{margin:0;font:800 19px 'Manrope';letter-spacing:-.35px}.dealer-navbar p{margin:4px 0 0;color:var(--dealer-muted);font-size:12px}.dealer-profile-button{display:flex;align-items:center;gap:11px;padding:7px 9px 7px 7px;border:1px solid var(--dealer-border);border-radius:15px;background:#fff;cursor:pointer;text-align:left}.dealer-avatar{width:40px;height:40px;display:grid;place-items:center;border-radius:12px;background:linear-gradient(135deg,#5b4be1,#18b9b1);color:#fff;font-weight:800}.dealer-profile-button strong{display:block;font-size:13px;color:#262942}.dealer-profile-button small{display:block;margin-top:2px;color:#18a49d;font-size:10px;font-weight:700;letter-spacing:.5px}.dealer-content{padding:32px 34px 45px;max-width:1600px;margin:0 auto}.dealer-alert{margin-bottom:22px;padding:14px 17px;border-radius:13px;font-size:13px}.dealer-alert.success{border:1px solid #a7f3d0;background:#ecfdf5;color:#047857}.dealer-alert.error{border:1px solid #fecaca;background:#fef2f2;color:#b91c1c}.dealer-alert ul{margin:0;padding-left:18px}
        .dealer-modal{position:fixed;inset:0;z-index:70;display:flex;align-items:center;justify-content:center;padding:18px}.dealer-modal-backdrop{position:absolute;inset:0;background:rgba(20,18,55,.62);backdrop-filter:blur(5px)}.dealer-modal-card{position:relative;width:100%;max-width:570px;max-height:90vh;overflow-y:auto;padding:27px;border-radius:22px;background:#fff;box-shadow:0 25px 80px rgba(20,18,55,.28)}.dealer-modal-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:23px}.dealer-modal-head h2{margin:0;font:800 19px 'Manrope'}.dealer-close{border:0;background:#f1f3f8;width:34px;height:34px;border-radius:10px;color:#6f748b;font-size:21px;cursor:pointer}.dealer-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}.dealer-field{display:block;color:#676d85;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px}.dealer-field.full{grid-column:1/-1}.dealer-field input{width:100%;margin-top:7px;padding:12px 13px;border:1px solid #dfe3ed;border-radius:11px;background:#f9fafc;color:#22263e;font:500 14px 'DM Sans';outline:none}.dealer-field input:focus{border-color:#6858e9;box-shadow:0 0 0 3px rgba(104,88,233,.1)}.dealer-button{border:0;border-radius:11px;padding:12px 20px;background:linear-gradient(135deg,#5b4be1,#7566ed);color:#fff;font:700 13px 'DM Sans';cursor:pointer;box-shadow:0 8px 18px rgba(91,75,225,.2)}.dealer-modal-actions{grid-column:1/-1;display:flex;justify-content:flex-end;margin-top:4px}
        .dealer-backdrop{display:none;position:fixed;inset:0;z-index:45;background:rgba(18,16,45,.55)}
        @media(max-width:900px){.dealer-sidebar{transform:translateX(-105%);transition:transform .25s}.dealer-sidebar.open{transform:translateX(0)}.dealer-backdrop.open{display:block}.dealer-main{width:100%;margin-left:0}.dealer-menu-button{display:inline-grid;place-items:center}.dealer-content{padding:24px 18px}.dealer-navbar{padding:0 18px}.dealer-profile-button div:nth-child(2){display:none}}
        @media(max-width:600px){.dealer-form-grid{grid-template-columns:1fr}.dealer-field.full,.dealer-modal-actions{grid-column:auto}.dealer-navbar p{display:none}}
    </style>
    @yield('styles')
</head>
<body>
@php
    $layoutDealer = Auth::guard('dealer')->user();
    $layoutUsed = $layoutDealer->users()->count();
    $layoutPercent = $layoutDealer->user_limit > 0 ? min(100, ($layoutUsed / $layoutDealer->user_limit) * 100) : 0;
@endphp
<div id="dealer-backdrop" class="dealer-backdrop" onclick="toggleDealerSidebar()"></div>
<div class="dealer-shell">
    <aside id="dealer-sidebar" class="dealer-sidebar">
        <div class="dealer-brand"><div class="dealer-brand-mark">AX</div><div><h2>AdvisorX Pro</h2><span>Dealer Workspace</span></div></div>
        <nav class="dealer-nav">
            <div class="dealer-nav-label">Workspace</div>
            <a href="{{ route('dealer.dashboard') }}" class="{{ request()->routeIs('dealer.dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 13h6V4H4v9zm0 7h6v-4H4v4zm10 0h6v-9h-6v9zm0-16v4h6V4h-6z"/></svg><span>Dashboard</span>
            </a>
            <a href="{{ route('dealer.users.index') }}" class="{{ request()->routeIs('dealer.users.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-10a4 4 0 100-8 4 4 0 000 8zm13 10v-2a4 4 0 00-3-3.87m-1-11.96a4 4 0 010 7.75"/></svg><span>Manage Users</span>
            </a>
        </nav>
        <div class="dealer-quota"><div class="dealer-quota-head"><span>User capacity</span><strong>{{ $layoutUsed }}/{{ $layoutDealer->user_limit }}</strong></div><div class="dealer-progress"><span style="width:{{ $layoutPercent }}%"></span></div></div>
        <form method="POST" action="{{ route('dealer.logout') }}">@csrf<button class="dealer-logout"><svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Log Out</button></form>
    </aside>

    <main class="dealer-main">
        <header class="dealer-navbar">
            <div class="dealer-navbar-left"><button type="button" class="dealer-menu-button" onclick="toggleDealerSidebar()"><svg width="21" height="21" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg></button><div><h1>@yield('page_title', 'Dealer Dashboard')</h1><p>@yield('page_subtitle', 'Manage your AdvisorX Pro workspace')</p></div></div>
            <button type="button" class="dealer-profile-button" onclick="document.getElementById('profile-modal').classList.remove('hidden')"><div class="dealer-avatar">{{ strtoupper(substr($layoutDealer->name, 0, 2)) }}</div><div><strong>{{ $layoutDealer->name }}</strong><small>{{ $layoutDealer->referral_code }}</small></div></button>
        </header>
        <div class="dealer-content">
            @if(session('success'))<div class="dealer-alert success">{{ session('success') }}</div>@endif
            @if($errors->any())<div class="dealer-alert error"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @yield('content')
        </div>
    </main>
</div>

<div id="profile-modal" class="dealer-modal hidden">
    <div class="dealer-modal-backdrop" onclick="document.getElementById('profile-modal').classList.add('hidden')"></div>
    <div class="dealer-modal-card">
        <div class="dealer-modal-head"><div><h2>Dealer Profile</h2><div style="margin-top:4px;color:#858aa0;font-size:12px">Update your contact and login information</div></div><button type="button" class="dealer-close" onclick="document.getElementById('profile-modal').classList.add('hidden')">&times;</button></div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:20px;padding:15px;border:1px solid #e5e9f2;border-radius:14px;background:#f8fafc">
            <div><span style="display:block;color:#858aa0;font-size:10px;text-transform:uppercase">Plan</span><strong style="font-size:13px">Free subscription</strong></div>
            <div><span style="display:block;color:#858aa0;font-size:10px;text-transform:uppercase">Status</span><strong style="font-size:13px;color:{{ $layoutDealer->hasSubscriptionAccess() ? '#059669' : '#dc2626' }}">{{ ucfirst($layoutDealer->subscriptionStatus()) }}</strong></div>
            <div><span style="display:block;color:#858aa0;font-size:10px;text-transform:uppercase">Valid until</span><strong style="font-size:13px">{{ optional($layoutDealer->subscription_ends_at)->format('d M Y') ?: 'Not set' }}</strong></div>
        </div>
        <form method="POST" action="{{ route('dealer.profile.update') }}" class="dealer-form-grid">@csrf
            <label class="dealer-field full">Dealer Name<input name="name" value="{{ old('name', $layoutDealer->name) }}" required></label>
            <label class="dealer-field">Phone<input name="phone_number" value="{{ old('phone_number', $layoutDealer->phone_number) }}" required></label>
            <label class="dealer-field">Alternative Phone<input name="alternative_phone_number" value="{{ old('alternative_phone_number', $layoutDealer->alternative_phone_number) }}"></label>
            <label class="dealer-field full">Email<input type="email" name="email" value="{{ old('email', $layoutDealer->email) }}" required></label>
            <label class="dealer-field full">Referral Code<input name="referral_code" maxlength="8" value="{{ old('referral_code', $layoutDealer->referral_code) }}" required style="text-transform:uppercase"></label>
            <label class="dealer-field">Price<input name="price" type="number" min="0" max="99999999.99" step="0.01" required value="{{ old('price', $layoutDealer->price ?? 1000) }}"></label>
            <label class="dealer-field">Offer Price<input name="offer_price" type="number" min="0" max="99999999.99" step="0.01" required value="{{ old('offer_price', $layoutDealer->offer_price ?? 800) }}"></label>
            <label class="dealer-field">New Password<input type="password" name="password"></label>
            <label class="dealer-field">Confirm Password<input type="password" name="password_confirmation"></label>
            <div class="dealer-modal-actions"><button class="dealer-button">Save Profile</button></div>
        </form>
    </div>
</div>
<script>
function toggleDealerSidebar(){document.getElementById('dealer-sidebar').classList.toggle('open');document.getElementById('dealer-backdrop').classList.toggle('open')}
</script>
@yield('scripts')
</body>
</html>
