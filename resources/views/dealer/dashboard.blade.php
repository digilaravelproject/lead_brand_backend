@extends('dealer.layout')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'A quick look at your users and subscription activity')

@section('styles')
<style>
    .welcome-panel{display:flex;align-items:center;justify-content:space-between;gap:25px;padding:28px 30px;border-radius:22px;background:linear-gradient(125deg,#4f43c7,#6356e8 55%,#20bdb5);color:#fff;box-shadow:0 18px 40px rgba(76,66,192,.18);overflow:hidden;position:relative}.welcome-panel:after{content:'';position:absolute;width:220px;height:220px;border:35px solid rgba(255,255,255,.06);border-radius:50%;right:-70px;top:-90px}.welcome-panel h2{position:relative;z-index:1;margin:0;font:800 26px 'Manrope'}.welcome-panel p{position:relative;z-index:1;margin:8px 0 0;color:#e4e1ff;font-size:13px}.welcome-referral{position:relative;z-index:1;min-width:180px;padding:15px 18px;border:1px solid rgba(255,255,255,.2);border-radius:15px;background:rgba(20,17,70,.18);backdrop-filter:blur(7px)}.welcome-referral small{display:block;color:#c9fff9;font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase}.welcome-referral strong{display:block;margin-top:7px;font-family:monospace;font-size:19px;letter-spacing:2px}
    .dealer-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:23px}.dealer-stat{display:flex;align-items:center;gap:16px;padding:22px;border:1px solid var(--dealer-border);border-radius:18px;background:#fff;box-shadow:0 7px 25px rgba(40,43,78,.04)}.dealer-stat-icon{width:49px;height:49px;display:grid;place-items:center;border-radius:15px}.dealer-stat-icon svg{width:22px}.dealer-stat.purple .dealer-stat-icon{background:#eeecff;color:#5b4be1}.dealer-stat.teal .dealer-stat-icon{background:#e2fbf8;color:#0e9f98}.dealer-stat.rose .dealer-stat-icon{background:#fff0f3;color:#e84b73}.dealer-stat small{display:block;color:#8a90a6;font-size:10px;font-weight:700;letter-spacing:.8px;text-transform:uppercase}.dealer-stat strong{display:block;margin-top:3px;font:800 26px 'Manrope';color:#262943}.dealer-stat span{font-size:11px;color:#a0a5b6}
    .recent-card{margin-top:23px;border:1px solid var(--dealer-border);border-radius:20px;background:#fff;overflow:hidden}.recent-card-head{display:flex;justify-content:space-between;align-items:center;padding:21px 23px;border-bottom:1px solid #edf0f5}.recent-card-head h3{margin:0;font:800 16px 'Manrope'}.recent-card-head a{color:#5b4be1;text-decoration:none;font-size:12px;font-weight:700}.recent-user{display:grid;grid-template-columns:minmax(220px,1fr) 180px 130px;align-items:center;gap:20px;padding:16px 23px;border-bottom:1px solid #f0f2f6}.recent-user:last-child{border-bottom:0}.recent-identity{display:flex;align-items:center;gap:12px}.recent-avatar{width:39px;height:39px;display:grid;place-items:center;border-radius:12px;background:#efedff;color:#5b4be1;font-size:12px;font-weight:800}.recent-identity strong{display:block;font-size:13px}.recent-identity span,.recent-date{display:block;margin-top:2px;color:#9297a9;font-size:11px}.trial-chip{display:inline-block;padding:6px 9px;border-radius:999px;background:#e8faf7;color:#078b83;font-size:10px;font-weight:700;text-align:center}.recent-empty{padding:38px;text-align:center;color:#9ba0b2;font-size:13px}
    @media(max-width:850px){.dealer-stats{grid-template-columns:1fr}.recent-user{grid-template-columns:1fr 150px}.recent-user .trial-chip{display:none}}@media(max-width:600px){.welcome-panel{align-items:flex-start;flex-direction:column}.welcome-referral{width:100%}.recent-user{grid-template-columns:1fr}.recent-date{padding-left:51px}}
</style>
@endsection

@section('content')
<section class="welcome-panel">
    <div><h2>Welcome back, {{ $dealer->name }}</h2><p>Keep your network moving. Your latest account activity is ready below.</p></div>
    <div class="welcome-referral"><small>Your referral code</small><strong>{{ $dealer->referral_code }}</strong></div>
</section>

<section class="dealer-stats">
    <div class="dealer-stat purple"><div class="dealer-stat-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-10a4 4 0 100-8 4 4 0 000 8zm13 10v-2a4 4 0 00-3-3.87"/></svg></div><div><small>Users created</small><strong>{{ $userCount }}</strong><span>Total active records</span></div></div>
    <div class="dealer-stat teal"><div class="dealer-stat-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/></svg></div><div><small>Remaining allowance</small><strong>{{ max(0, $dealer->user_limit - $userCount) }}</strong><span>of {{ $dealer->user_limit }} assigned slots</span></div></div>
    <div class="dealer-stat rose"><div class="dealer-stat-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><div><small>Awaiting decision</small><strong>{{ $expiredCount }}</strong><span>Expired trials pending review</span></div></div>
</section>

<section class="recent-card">
    <div class="recent-card-head"><h3>Recently created users</h3><a href="{{ route('dealer.users.index') }}">Manage all &rarr;</a></div>
    @forelse($recentUsers as $user)
        <div class="recent-user"><div class="recent-identity"><div class="recent-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div><div><strong>{{ $user->name }}</strong><span>{{ $user->email }}</span></div></div><div class="recent-date">Trial ends<strong style="display:block;margin-top:3px;color:#4e5268;font-size:12px">{{ optional($user->subscription_ends_at)->format('d M Y, h:i A') }}</strong></div><span class="trial-chip">{{ $user->hasExpiredTrial() ? 'Trial expired' : 'Trial active' }}</span></div>
    @empty
        <div class="recent-empty">No users yet. Create your first user from Manage Users.</div>
    @endforelse
</section>
@endsection
