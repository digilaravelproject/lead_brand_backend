@extends('admin.layout')

@section('title', 'Manage Dealers')
@section('page_title', 'Dealer Accounts')

@section('styles')
<style>
    #dealer-subscription-start::-webkit-calendar-picker-indicator,
    #dealer-subscription-end::-webkit-calendar-picker-indicator {
        filter: invert(1);
        opacity: .85;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Manage Dealers</h1>
            <p class="text-xs text-slate-400 mt-1">Create dealer accounts, assign user allowances, and manage access.</p>
        </div>
        <button onclick="openDealerCreate()" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-sm font-semibold">Create Dealer</button>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-950/40 text-xs text-slate-400 uppercase">
                    <tr><th class="px-6 py-4">Dealer</th><th class="px-6 py-4">Phone</th><th class="px-6 py-4">Referral</th><th class="px-6 py-4">Users</th><th class="px-6 py-4">Status</th><th class="px-6 py-4 text-right min-w-[210px]">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse($dealers as $dealer)
                    <tr class="hover:bg-slate-800/20">
                        <td class="px-6 py-4"><div class="font-semibold text-white">{{ $dealer->name }}</div><div class="text-xs text-slate-500">{{ $dealer->email }}</div></td>
                        <td class="px-6 py-4 text-slate-300">{{ $dealer->phone_number }}<div class="text-xs text-slate-500">{{ $dealer->alternative_phone_number ?: 'No alternative' }}</div></td>
                        <td class="px-6 py-4 font-mono text-amber-400">{{ $dealer->referral_code }}</td>
                        <td class="px-6 py-4"><span class="text-white font-semibold">{{ $dealer->users_count }}</span><span class="text-slate-500"> / {{ $dealer->user_limit }}</span></td>
                        <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-full text-xs {{ $dealer->is_active ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">{{ $dealer->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="px-6 py-4 text-right whitespace-nowrap"><div class="inline-flex items-center justify-end gap-2 flex-nowrap">
                            <a href="{{ route('admin.dealers.users', $dealer->id) }}" class="inline-flex items-center p-1.5 rounded-lg border border-violet-500/20 bg-violet-500/10 text-violet-400 hover:bg-violet-500/20" title="View dealer users" aria-label="View dealer users"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></a>
                            <button onclick="viewDealer({{ $dealer->id }})" class="inline-flex items-center p-1.5 rounded-lg border border-amber-500/20 bg-amber-500/10 text-amber-400 hover:bg-amber-500/20" title="View dealer" aria-label="View dealer"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S3.732 16.057 2.458 12z"/></svg></button>
                            <button onclick="editDealer({{ $dealer->id }})" class="inline-flex items-center p-1.5 rounded-lg border border-sky-500/20 bg-sky-500/10 text-sky-400 hover:bg-sky-500/20" title="Edit dealer" aria-label="Edit dealer"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                            <form action="{{ route('admin.dealers.destroy', $dealer->id) }}" method="POST" class="inline-flex" onsubmit="return confirm('Delete this dealer? Existing users will be retained.')">@csrf @method('DELETE')<button class="inline-flex items-center p-1.5 rounded-lg border border-red-500/20 bg-red-500/10 text-red-400 hover:bg-red-500/20" title="Delete dealer" aria-label="Delete dealer"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                        </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-12 text-slate-500">No dealers created yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($dealers->hasPages())<div class="px-6 py-4 border-t border-slate-800">{{ $dealers->links() }}</div>@endif
    </div>
</div>

<div id="dealer-form-modal" class="hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80" onclick="closeDealerModal('dealer-form-modal')"></div>
    <div class="relative w-full max-w-xl bg-slate-900 border border-slate-800 rounded-3xl p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between mb-5"><h3 id="dealer-form-title" class="text-lg font-bold text-white">Create Dealer</h3><button type="button" onclick="closeDealerModal('dealer-form-modal')" class="text-slate-400">✕</button></div>
        <form id="dealer-form" method="POST" action="{{ route('admin.dealers.store') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">@csrf
            <label class="sm:col-span-2 text-xs text-slate-400">Dealer Name<input id="dealer-name" name="name" value="{{ old('name') }}" required class="mt-1 w-full bg-slate-950 border @error('name') border-red-500 @else border-slate-700 @enderror rounded-xl px-4 py-2.5 text-white">@error('name')<span role="alert" class="mt-1.5 block text-xs text-red-400">{{ $message }}</span>@enderror</label>
            <label class="text-xs text-slate-400">Phone Number<input id="dealer-phone" name="phone_number" value="{{ old('phone_number') }}" required class="mt-1 w-full bg-slate-950 border @error('phone_number') border-red-500 @else border-slate-700 @enderror rounded-xl px-4 py-2.5 text-white">@error('phone_number')<span role="alert" class="mt-1.5 block text-xs text-red-400">{{ $message }}</span>@enderror</label>
            <label class="text-xs text-slate-400">Alternative Phone<input id="dealer-alt-phone" name="alternative_phone_number" value="{{ old('alternative_phone_number') }}" class="mt-1 w-full bg-slate-950 border @error('alternative_phone_number') border-red-500 @else border-slate-700 @enderror rounded-xl px-4 py-2.5 text-white">@error('alternative_phone_number')<span role="alert" class="mt-1.5 block text-xs text-red-400">{{ $message }}</span>@enderror</label>
            <label class="sm:col-span-2 text-xs text-slate-400">Email<input id="dealer-email" type="email" name="email" value="{{ old('email') }}" required aria-describedby="dealer-email-error" class="mt-1 w-full bg-slate-950 border @error('email') border-red-500 @else border-slate-700 @enderror rounded-xl px-4 py-2.5 text-white">@error('email')<span id="dealer-email-error" role="alert" class="mt-1.5 block text-xs text-red-400">{{ $message }}</span>@enderror</label>
            <label class="text-xs text-slate-400">User Count<input id="dealer-limit" type="number" min="0" name="user_limit" value="{{ old('user_limit', 0) }}" required class="mt-1 w-full bg-slate-950 border @error('user_limit') border-red-500 @else border-slate-700 @enderror rounded-xl px-4 py-2.5 text-white">@error('user_limit')<span role="alert" class="mt-1.5 block text-xs text-red-400">{{ $message }}</span>@enderror</label>
            <label class="text-xs text-slate-400">Price<input id="dealer-price" name="price" type="number" min="0" max="99999999.99" step="0.01" required value="{{ old('price', 1000) }}" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white">@error('price')<span class="block text-red-400">{{ $message }}</span>@enderror</label>
            <label class="text-xs text-slate-400">Offer Price<input id="dealer-offer-price" name="offer_price" type="number" min="0" max="99999999.99" step="0.01" required value="{{ old('offer_price', 800) }}" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white">@error('offer_price')<span class="block text-red-400">{{ $message }}</span>@enderror</label>
            <label id="referral-wrap" class="hidden text-xs text-slate-400">Referral Code<input id="dealer-referral" name="referral_code" maxlength="8" class="mt-1 w-full uppercase bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white"></label>
            <label id="status-wrap" class="hidden text-xs text-slate-400">Status<select id="dealer-status" name="is_active" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white"><option value="1">Active</option><option value="0">Inactive</option></select></label>
            <div id="subscription-wrap" class="hidden sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="text-xs text-slate-400">Subscription Start Date<input id="dealer-subscription-start" type="date" readonly aria-readonly="true" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white"></label>
                <label class="text-xs text-slate-400">Subscription End Date<input id="dealer-subscription-end" type="date" name="subscription_ends_at" class="mt-1 w-full bg-slate-950 border @error('subscription_ends_at') border-red-500 @else border-slate-700 @enderror rounded-xl px-4 py-2.5 text-white">@error('subscription_ends_at')<span role="alert" class="mt-1.5 block text-xs text-red-400">{{ $message }}</span>@enderror</label>
                <span class="sm:col-span-2 text-xs text-slate-500">Extend the end date to renew dealer and dealer-user access.</span>
            </div>
            <label id="password-wrap" class="hidden sm:col-span-2 text-xs text-slate-400">New Password (optional)<input id="dealer-password" type="password" name="password" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white"></label>
            <div class="sm:col-span-2 text-xs text-slate-500" id="auto-help">An 8-character referral code and password based on the dealer name will be generated and emailed immediately.</div>
            <div class="sm:col-span-2 flex justify-end gap-3 mt-2"><button type="button" onclick="closeDealerModal('dealer-form-modal')" class="px-4 py-2 text-slate-300">Cancel</button><button class="px-5 py-2.5 bg-amber-600 rounded-xl text-white font-semibold">Save Dealer</button></div>
        </form>
    </div>
</div>

<div id="dealer-view-modal" class="hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80" onclick="closeDealerModal('dealer-view-modal')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl p-6"><div class="flex justify-between"><h3 class="text-lg font-bold text-white">Dealer Details</h3><button onclick="closeDealerModal('dealer-view-modal')" class="text-slate-400">✕</button></div><div id="dealer-view-content" class="mt-5 grid grid-cols-2 gap-4 text-sm"></div></div>
</div>
@endsection

@section('scripts')
<script>
const dealerBase = @json(url('admin/dealers'));
function showDealerModal(id){ const el=document.getElementById(id); el.classList.remove('hidden'); el.classList.add('flex'); }
function closeDealerModal(id){ const el=document.getElementById(id); el.classList.add('hidden'); el.classList.remove('flex'); }
function openDealerCreate(){
    document.getElementById('dealer-form').reset(); document.getElementById('dealer-form').action=dealerBase;
    document.getElementById('dealer-form-title').textContent='Create Dealer';
    ['referral-wrap','status-wrap','subscription-wrap','password-wrap'].forEach(id=>document.getElementById(id).classList.add('hidden'));
    document.getElementById('auto-help').classList.remove('hidden'); showDealerModal('dealer-form-modal');
}
async function getDealer(id){ const response=await fetch(`${dealerBase}/${id}`); if(!response.ok) throw new Error('Unable to load dealer'); return response.json(); }
async function viewDealer(id){
    showDealerModal('dealer-view-modal'); const d=await getDealer(id);
    const fields=[['Name',d.name],['Email',d.email],['Login password',d.login_password],['Phone',d.phone_number],['Alternative phone',d.alternative_phone_number||'N/A'],['Referral code',d.referral_code],['Price',d.price],['Offer Price',d.offer_price],['Plan','Free subscription'],['Subscription status',d.subscription_status],['Subscription starts',new Date(d.subscription_started_at).toLocaleDateString()],['Subscription ends',new Date(d.subscription_ends_at).toLocaleDateString()],['User allowance',`${d.users_count} used / ${d.user_limit}`],['Remaining slots',d.remaining_user_slots],['Account status',d.is_active?'Active':'Inactive'],['Created',new Date(d.created_at).toLocaleString()]];
    document.getElementById('dealer-view-content').innerHTML=fields.map(([k,v])=>`<div class="bg-slate-950/50 rounded-xl p-3"><span class="block text-xs text-slate-500 uppercase">${k}</span><span class="text-white break-all">${v}</span></div>`).join('');
}
async function editDealer(id){
    const d=await getDealer(id);
    document.getElementById('dealer-price').value=d.price ?? 1000;
    document.getElementById('dealer-offer-price').value=d.offer_price ?? 800;
    document.getElementById('dealer-form').action=`${dealerBase}/${id}/update`; document.getElementById('dealer-form-title').textContent='Update Dealer';
    document.getElementById('dealer-name').value=d.name; document.getElementById('dealer-phone').value=d.phone_number; document.getElementById('dealer-alt-phone').value=d.alternative_phone_number||''; document.getElementById('dealer-email').value=d.email; document.getElementById('dealer-limit').value=d.user_limit; document.getElementById('dealer-limit').min=d.users_count; document.getElementById('dealer-referral').value=d.referral_code; document.getElementById('dealer-status').value=d.is_active?'1':'0'; document.getElementById('dealer-subscription-start').value=d.subscription_started_at ? d.subscription_started_at.slice(0,10) : ''; document.getElementById('dealer-subscription-end').value=d.subscription_ends_at ? d.subscription_ends_at.slice(0,10) : ''; document.getElementById('dealer-password').value='';
    ['referral-wrap','status-wrap','subscription-wrap','password-wrap'].forEach(x=>document.getElementById(x).classList.remove('hidden')); document.getElementById('auto-help').classList.add('hidden'); showDealerModal('dealer-form-modal');
}
@if($errors->any()) openDealerCreate(); @endif
</script>
@endsection
