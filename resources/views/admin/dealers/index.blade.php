@extends('admin.layout')

@section('title', 'Manage Dealers')
@section('page_title', 'Dealer Accounts')

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
                    <tr><th class="px-6 py-4">Dealer</th><th class="px-6 py-4">Phone</th><th class="px-6 py-4">Referral</th><th class="px-6 py-4">Users</th><th class="px-6 py-4">Status</th><th class="px-6 py-4 text-right">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse($dealers as $dealer)
                    <tr class="hover:bg-slate-800/20">
                        <td class="px-6 py-4"><div class="font-semibold text-white">{{ $dealer->name }}</div><div class="text-xs text-slate-500">{{ $dealer->email }}</div></td>
                        <td class="px-6 py-4 text-slate-300">{{ $dealer->phone_number }}<div class="text-xs text-slate-500">{{ $dealer->alternative_phone_number ?: 'No alternative' }}</div></td>
                        <td class="px-6 py-4 font-mono text-amber-400">{{ $dealer->referral_code }}</td>
                        <td class="px-6 py-4"><span class="text-white font-semibold">{{ $dealer->users_count }}</span><span class="text-slate-500"> / {{ $dealer->user_limit }}</span></td>
                        <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-full text-xs {{ $dealer->is_active ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">{{ $dealer->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.dealers.users', $dealer->id) }}" class="text-violet-400 hover:text-violet-300 text-xs font-semibold">View Users</a>
                            <button onclick="viewDealer({{ $dealer->id }})" class="text-amber-400 hover:text-amber-300 text-xs font-semibold">View</button>
                            <button onclick="editDealer({{ $dealer->id }})" class="text-sky-400 hover:text-sky-300 text-xs font-semibold">Edit</button>
                            <form action="{{ route('admin.dealers.destroy', $dealer->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this dealer? Existing users will be retained.')">@csrf @method('DELETE')<button class="text-red-400 hover:text-red-300 text-xs font-semibold">Delete</button></form>
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
            <label class="sm:col-span-2 text-xs text-slate-400">Dealer Name<input id="dealer-name" name="name" value="{{ old('name') }}" required class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white"></label>
            <label class="text-xs text-slate-400">Phone Number<input id="dealer-phone" name="phone_number" value="{{ old('phone_number') }}" required class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white"></label>
            <label class="text-xs text-slate-400">Alternative Phone<input id="dealer-alt-phone" name="alternative_phone_number" value="{{ old('alternative_phone_number') }}" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white"></label>
            <label class="sm:col-span-2 text-xs text-slate-400">Email<input id="dealer-email" type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white"></label>
            <label class="text-xs text-slate-400">User Count<input id="dealer-limit" type="number" min="0" name="user_limit" value="{{ old('user_limit', 0) }}" required class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white"></label>
            <label id="referral-wrap" class="hidden text-xs text-slate-400">Referral Code<input id="dealer-referral" name="referral_code" maxlength="8" class="mt-1 w-full uppercase bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white"></label>
            <label id="status-wrap" class="hidden text-xs text-slate-400">Status<select id="dealer-status" name="is_active" class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white"><option value="1">Active</option><option value="0">Inactive</option></select></label>
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
    ['referral-wrap','status-wrap','password-wrap'].forEach(id=>document.getElementById(id).classList.add('hidden'));
    document.getElementById('auto-help').classList.remove('hidden'); showDealerModal('dealer-form-modal');
}
async function getDealer(id){ const response=await fetch(`${dealerBase}/${id}`); if(!response.ok) throw new Error('Unable to load dealer'); return response.json(); }
async function viewDealer(id){
    showDealerModal('dealer-view-modal'); const d=await getDealer(id);
    const fields=[['Name',d.name],['Email',d.email],['Login password',d.login_password],['Phone',d.phone_number],['Alternative phone',d.alternative_phone_number||'N/A'],['Referral code',d.referral_code],['User allowance',`${d.users_count} used / ${d.user_limit}`],['Remaining slots',d.remaining_user_slots],['Status',d.is_active?'Active':'Inactive'],['Created',new Date(d.created_at).toLocaleString()]];
    document.getElementById('dealer-view-content').innerHTML=fields.map(([k,v])=>`<div class="bg-slate-950/50 rounded-xl p-3"><span class="block text-xs text-slate-500 uppercase">${k}</span><span class="text-white break-all">${v}</span></div>`).join('');
}
async function editDealer(id){
    const d=await getDealer(id); document.getElementById('dealer-form').action=`${dealerBase}/${id}/update`; document.getElementById('dealer-form-title').textContent='Update Dealer';
    document.getElementById('dealer-name').value=d.name; document.getElementById('dealer-phone').value=d.phone_number; document.getElementById('dealer-alt-phone').value=d.alternative_phone_number||''; document.getElementById('dealer-email').value=d.email; document.getElementById('dealer-limit').value=d.user_limit; document.getElementById('dealer-limit').min=d.users_count; document.getElementById('dealer-referral').value=d.referral_code; document.getElementById('dealer-status').value=d.is_active?'1':'0'; document.getElementById('dealer-password').value='';
    ['referral-wrap','status-wrap','password-wrap'].forEach(x=>document.getElementById(x).classList.remove('hidden')); document.getElementById('auto-help').classList.add('hidden'); showDealerModal('dealer-form-modal');
}
@if($errors->any()) openDealerCreate(); @endif
</script>
@endsection
