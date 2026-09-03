@extends('admin.layout')

@section('title', 'Dealer Users')
@section('page_title', 'Dealer Users')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <a href="{{ route('admin.dealers.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-amber-400 hover:text-amber-300">&larr; Back to dealers</a>
            <h1 class="mt-2 text-xl font-bold text-white">Users created by {{ $dealer->name }}</h1>
            <p class="mt-1 text-xs text-slate-400">Review only the accounts belonging to this dealer.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.dealers.users', $dealer->id) }}" class="relative w-full sm:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search dealer users..." 
                       class="w-full bg-slate-950/80 border border-slate-800 rounded-xl py-2.5 pl-10 pr-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-xs">
            </form>
            <div class="rounded-2xl border border-violet-500/20 bg-violet-500/10 px-5 py-2 text-right">
                <div class="text-[10px] font-semibold uppercase tracking-wider text-violet-300">User allowance</div>
                <div class="mt-0.5 text-base font-bold text-white">{{ $dealer->users_count }} <span class="text-xs font-normal text-slate-400">/ {{ $dealer->user_limit }}</span></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-4"><span class="text-xs uppercase text-slate-500">Dealer</span><div class="mt-1 font-semibold text-white">{{ $dealer->name }}</div></div>
        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-4"><span class="text-xs uppercase text-slate-500">Email</span><div class="mt-1 break-all font-semibold text-white">{{ $dealer->email }}</div></div>
        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-4"><span class="text-xs uppercase text-slate-500">Referral code</span><div class="mt-1 font-mono font-semibold text-amber-400">{{ $dealer->referral_code }}</div></div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-800 bg-slate-900 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-950/40 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <tr><th class="px-6 py-4">User</th><th class="px-6 py-4">Phone</th><th class="px-6 py-4">Trial period</th><th class="px-6 py-4">Approval</th><th class="px-6 py-4 text-right">Action</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse($users as $user)
                        <tr class="transition-colors hover:bg-slate-800/20">
                            <td class="px-6 py-4"><div class="font-semibold text-white">{{ $user->name }}</div><div class="text-xs text-slate-500">{{ $user->email }}</div></td>
                            <td class="px-6 py-4 text-slate-300">{{ $user->phone_number ?: 'N/A' }}</td>
                            <td class="px-6 py-4"><div class="text-slate-300">{{ optional($user->subscription_ends_at)->format('d M Y, h:i A') ?: 'N/A' }}</div><div class="text-xs {{ $user->hasExpiredTrial() ? 'text-red-400' : 'text-emerald-400' }}">{{ $user->hasExpiredTrial() ? 'Expired' : 'Free trial active' }}</div></td>
                            <td class="px-6 py-4"><span class="rounded-full px-2.5 py-1 text-xs {{ $user->approval_status === 'approved' ? 'bg-emerald-500/10 text-emerald-400' : ($user->approval_status === 'disapproved' ? 'bg-red-500/10 text-red-400' : 'bg-amber-500/10 text-amber-400') }}">{{ ucfirst($user->approval_status) }}</span></td>
                            <td class="px-6 py-4 text-right whitespace-nowrap"><button type="button" onclick="viewUser({{ $user->id }})" class="inline-flex items-center p-1.5 rounded-lg border border-violet-500/20 bg-violet-500/10 text-violet-400 hover:bg-violet-500/20" title="View user" aria-label="View user"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S3.732 16.057 2.458 12z"/></svg></button><button type="button" onclick="editUser({{ $user->id }})" class="ml-3 text-sky-400 hover:underline" aria-label="Edit user">Edit</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500">This dealer has not created any users yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())<div class="border-t border-slate-800 px-6 py-4">{{ $users->links() }}</div>@endif
    </div>
</div>

@include('admin.users.partials.modals')
@endsection

@section('scripts')
@include('admin.users.partials.scripts')
@endsection
