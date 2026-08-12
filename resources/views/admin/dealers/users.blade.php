@extends('admin.layout')

@section('title', 'Dealer Users')
@section('page_title', 'Dealer Users')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('admin.dealers.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-amber-400 hover:text-amber-300">&larr; Back to dealers</a>
            <h1 class="mt-2 text-xl font-bold text-white">Users created by {{ $dealer->name }}</h1>
            <p class="mt-1 text-xs text-slate-400">Review only the accounts belonging to this dealer.</p>
        </div>
        <div class="rounded-2xl border border-violet-500/20 bg-violet-500/10 px-5 py-3 text-right">
            <div class="text-xs font-semibold uppercase tracking-wider text-violet-300">User allowance</div>
            <div class="mt-1 text-xl font-bold text-white">{{ $dealer->users_count }} <span class="text-sm font-normal text-slate-400">/ {{ $dealer->user_limit }}</span></div>
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
                            <td class="px-6 py-4 text-right"><button type="button" onclick="viewDealerUser({{ $user->id }})" class="text-xs font-semibold text-violet-400 hover:text-violet-300">View User</button></td>
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

<div id="dealer-user-modal" class="hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeDealerUser()"></div>
    <div class="relative w-full max-w-lg rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-2xl">
        <div class="flex items-center justify-between"><h2 class="text-lg font-bold text-white">User Details</h2><button type="button" onclick="closeDealerUser()" class="text-slate-400 hover:text-white">&times;</button></div>
        <div id="dealer-user-content" class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const adminUserBase = @json(url('admin/users'));
function closeDealerUser() {
    const modal = document.getElementById('dealer-user-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
async function viewDealerUser(id) {
    const modal = document.getElementById('dealer-user-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    const response = await fetch(`${adminUserBase}/${id}`);
    const user = await response.json();
    const fields = [
        ['Name', user.name], ['Email', user.email], ['Phone', user.phone_number || 'N/A'],
        ['Trial starts', user.subscription_started_at ? new Date(user.subscription_started_at).toLocaleString() : 'N/A'],
        ['Trial ends', user.subscription_ends_at ? new Date(user.subscription_ends_at).toLocaleString() : 'N/A'],
        ['Approval', user.approval_status]
    ];
    const content = document.getElementById('dealer-user-content');
    content.replaceChildren(...fields.map(([label, value]) => {
        const box = document.createElement('div');
        box.className = 'rounded-xl border border-slate-800 bg-slate-950/50 p-3';
        const key = document.createElement('span');
        key.className = 'block text-xs uppercase text-slate-500';
        key.textContent = label;
        const text = document.createElement('span');
        text.className = 'break-all text-sm font-semibold text-white';
        text.textContent = value;
        box.append(key, text);
        return box;
    }));
}
</script>
@endsection
