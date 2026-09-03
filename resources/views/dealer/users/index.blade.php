@extends('dealer.layout')
@section('title', 'Manage Users')
@section('page_title', 'Manage Users')
@section('page_subtitle', 'Create accounts and manage four-day trial access')

@section('styles')
<style>
.dealer-field textarea,.dealer-field select{width:100%;margin-top:7px;padding:12px 13px;border:1px solid #dfe3ed;border-radius:11px;background:#f9fafc;color:#22263e;font:500 14px "DM Sans";}

    .users-toolbar{display:flex;align-items:center;justify-content:space-between;gap:20px;margin-bottom:22px}.users-toolbar h2{margin:0;font:800 21px 'Manrope'}.users-toolbar p{margin:6px 0 0;color:#858ba1;font-size:12px}.create-user-button{display:inline-flex;align-items:center;gap:8px;border:0;border-radius:12px;padding:12px 18px;background:linear-gradient(135deg,#5b4be1,#7566ed);color:#fff;font:700 13px 'DM Sans';cursor:pointer;box-shadow:0 9px 22px rgba(91,75,225,.23)}.create-user-button:disabled{background:#c5c8d4;box-shadow:none;cursor:not-allowed}.limit-notice{margin-bottom:20px;padding:14px 17px;border:1px solid #fde68a;border-radius:13px;background:#fffbeb;color:#92400e;font-size:13px}.users-card{border:1px solid var(--dealer-border);border-radius:20px;background:#fff;overflow:hidden;box-shadow:0 8px 30px rgba(42,45,79,.04)}.users-scroll{overflow-x:auto}.dealer-users-table{width:100%;min-width:950px;border-collapse:collapse;text-align:left}.dealer-users-table th{padding:15px 19px;background:#fafbfe;color:#8a90a5;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase}.dealer-users-table td{padding:17px 19px;border-top:1px solid #edf0f5;color:#555a70;font-size:13px}.user-cell{display:flex;align-items:center;gap:11px}.user-cell-avatar{width:38px;height:38px;display:grid;place-items:center;border-radius:11px;background:#eeecff;color:#5b4be1;font-size:11px;font-weight:800}.user-cell strong{display:block;color:#292d46;font-size:13px}.user-cell span{display:block;margin-top:2px;color:#969bad;font-size:11px}.trial-state{margin-top:4px;font-size:10px;font-weight:700}.trial-state.active{color:#07958d}.trial-state.expired{color:#dc4663}.status-pill{display:inline-block;padding:6px 10px;border-radius:999px;font-size:10px;font-weight:700;text-transform:capitalize}.status-pill.pending{background:#fff5db;color:#a96700}.status-pill.approved{background:#e7faf5;color:#078565}.status-pill.disapproved{background:#ffeaee;color:#c52e4c}.user-actions{display:flex;align-items:center;justify-content:flex-end;gap:8px;white-space:nowrap;flex-wrap:nowrap;min-width:max-content}.user-actions button{width:34px;height:34px;display:inline-grid;place-items:center;border:1px solid currentColor;border-radius:9px;background:#fff;padding:0;cursor:pointer}.user-actions svg{width:16px;height:16px}.action-view{color:#5b4be1}.action-edit{color:#078f89}.action-delete{color:#df3f60}.action-approve{color:#078565}.action-disapprove{color:#d27808}.user-actions form{display:inline-flex;margin:0}.empty-users{padding:46px!important;text-align:center;color:#9a9fb1!important}.user-detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:13px}.user-detail-box{padding:13px;border:1px solid #eaedf3;border-radius:12px;background:#fafbfe}.user-detail-box small{display:block;color:#9398aa;font-size:9px;font-weight:700;letter-spacing:.7px;text-transform:uppercase}.user-detail-box strong{display:block;margin-top:5px;color:#292d46;font-size:12px;word-break:break-all}.form-helper{grid-column:1/-1;margin:-2px 0 0;color:#969bad;font-size:11px}.cancel-button{margin-right:10px;border:0;background:#f1f3f8;color:#656b81;border-radius:11px;padding:12px 18px;font:700 13px 'DM Sans';cursor:pointer}.dealer-field input[type="datetime-local"]::-webkit-calendar-picker-indicator{opacity:.75;cursor:pointer}
    .toolbar-actions{display:flex;align-items:center;gap:12px}
    .search-form{position:relative}
    .search-form input{padding:10px 14px 10px 36px;border:1px solid var(--dealer-border);border-radius:12px;outline:none;font:500 13px 'DM Sans';width:220px;background:#fff;color:var(--dealer-text);transition:all .2s}
    .search-form input:focus{border-color:#5b4be1;box-shadow:0 0 0 3px rgba(91,75,225,.1)}
    .search-icon{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#858ba1;pointer-events:none;display:flex;align-items:center}
    .search-icon svg{width:14px;height:14px}
    .custom-pagination{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:#fafbfe;border-top:1px solid #edf0f5;font-size:12px;color:#7a8099}
    .pagination-info strong{color:var(--dealer-text)}
    .pagination-links{display:flex;align-items:center;gap:6px}
    .pagination-btn,.pagination-link{display:inline-flex;align-items:center;justify-content:center;height:32px;padding:0 12px;border:1px solid #edf0f5;border-radius:8px;background:#fff;color:#5b4be1;text-decoration:none;font-weight:600;transition:all .2s}
    .pagination-link{width:32px;padding:0}
    .pagination-btn:hover:not(.disabled),.pagination-link:hover:not(.active){background:#eeecff;border-color:#5b4be1;color:#5b4be1}
    .pagination-btn.disabled{color:#a8a9b4;background:#fcfcfd;cursor:not-allowed}
    .pagination-link.active{background:#5b4be1;border-color:#5b4be1;color:#fff;cursor:default}
    @media(max-width:600px){
        .users-toolbar{align-items:flex-start;flex-direction:column}
        .toolbar-actions{width:100%;flex-direction:column;align-items:stretch}
        .search-form,.search-form input{width:100%}
        .create-user-button{width:100%;justify-content:center}
        .user-detail-grid{grid-template-columns:1fr}
        .custom-pagination{flex-direction:column;gap:12px;text-align:center}
    }
</style>
@endsection

@section('content')
<div class="users-toolbar">
    <div>
        <h2>Your user network</h2>
        <p>{{ $usedSlots }} of {{ $dealer->user_limit }} slots used &middot; {{ max(0, $dealer->user_limit - $usedSlots) }} remaining</p>
    </div>
    <div class="toolbar-actions">
        <!-- Search Form -->
        <form method="GET" action="{{ route('dealer.users.index') }}" class="search-form">
            <span class="search-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users...">
        </form>
        <button type="button" onclick="openUserCreate()" @disabled($usedSlots >= $dealer->user_limit) class="create-user-button"><span style="font-size:18px;line-height:1">+</span> Create User</button>
    </div>
</div>
@if($usedSlots >= $dealer->user_limit)<div class="limit-notice">Your assigned user allowance has been reached. Ask the administrator to increase it before creating another user.</div>@endif

<div class="users-card">
    <div class="users-scroll">
        <table class="dealer-users-table">
            <thead>
                <tr>
                    <th style="width:70px">Sr. No.</th>
                    <th>User</th>
                    <th>Phone</th>
                    <th>Trial ends</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td style="font-weight:600;color:#8a90a5">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                    <td><div class="user-cell"><div class="user-cell-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div><div><strong>{{ $user->name }}</strong><span>{{ $user->email }}</span></div></div></td>
                    <td>{{ $user->phone_number ?: 'N/A' }}</td>
                    <td>{{ optional($user->subscription_ends_at)->format('d M Y, h:i A') ?: 'N/A' }}<div class="trial-state {{ $user->hasExpiredTrial() ? 'expired' : 'active' }}">{{ $user->hasExpiredTrial() ? 'Trial expired' : 'Free trial active' }}</div></td>
                    <td><span class="status-pill {{ $user->approval_status }}">{{ $user->approval_status }}</span></td>
                    <td>
                        <div class="user-actions">
                            <button type="button" onclick="viewUser({{ $user->id }})" class="action-view" title="View user" aria-label="View user"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S3.732 16.057 2.458 12z"/></svg></button>
                            <button type="button" onclick="editUser({{ $user->id }})" class="action-edit" title="Edit user" aria-label="Edit user"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                            @if($user->hasExpiredTrial())
                                <form method="POST" action="{{ route('dealer.users.approval', $user->id) }}">@csrf<input type="hidden" name="approval_status" value="approved"><button class="action-approve" title="Approve user" aria-label="Approve user"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></button></form>
                                <form method="POST" action="{{ route('dealer.users.approval', $user->id) }}">@csrf<input type="hidden" name="approval_status" value="disapproved"><button class="action-disapprove" title="Disapprove user" aria-label="Disapprove user"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></form>
                            @endif
                            <form method="POST" action="{{ route('dealer.users.destroy', $user->id) }}" onsubmit="return confirm('Delete this user?')">@csrf @method('DELETE')<button class="action-delete" title="Delete user" aria-label="Delete user"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty-users">No users created yet. Use Create User to add the first account.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->links('partials.pagination') }}
</div>

<div id="user-form-modal" class="dealer-modal hidden">
    <div class="dealer-modal-backdrop" onclick="closeUserModal('user-form-modal')"></div>
    <div class="dealer-modal-card">
        <div class="dealer-modal-head"><div><h2 id="user-form-title">Create User</h2><div style="margin-top:4px;color:#858aa0;font-size:12px">The user receives a welcome email automatically</div></div><button type="button" class="dealer-close" onclick="closeUserModal('user-form-modal')">&times;</button></div>
        <form id="user-form" action="{{ route('dealer.users.store') }}" method="POST" enctype="multipart/form-data" class="dealer-form-grid">@csrf
            <input type="hidden" name="editing_user_id" id="editing-user-id">
            <label class="dealer-field full">Full Name<input id="user-name" name="name" required></label>
            <label class="dealer-field full">Email<input id="user-email" type="email" name="email" required></label>
            <label class="dealer-field full">Phone Number<input id="user-phone" name="phone_number"></label>
            <label id="subscription-start-wrap" class="dealer-field hidden">Start Date<input id="user-subscription-start" type="datetime-local" name="subscription_started_at"></label>
            <label id="subscription-end-wrap" class="dealer-field hidden">End Date<input id="user-subscription-end" type="datetime-local" name="subscription_ends_at"></label>
<div id="edit-profile-fields" class="hidden" style="grid-column:1/-1"><div class="dealer-form-grid">
<label class="dealer-field">WhatsApp Number<input type="text" name="whatsapp_number" id="user-whatsapp_number" maxlength="30"></label>
<label class="dealer-field">Destination<input type="text" name="destination" id="user-destination" maxlength="255"></label>
<label class="dealer-field full">Address<textarea name="address" id="user-address" rows="3" maxlength="5000"></textarea></label>
<label class="dealer-field">Language<select name="language" id="user-language"><option value="en">English</option><option value="mr">Marathi</option><option value="hi">Hindi</option><option value="gu">Gujarati</option><option value="bn">Bengali</option><option value="te">Telugu</option><option value="ta">Tamil</option><option value="kn">Kannada</option><option value="pa">Punjabi</option></select></label>
<label class="dealer-field">Approval Status<select name="approval_status" id="user-approval_status"><option value="pending">Pending</option><option value="approved">Approved</option><option value="disapproved">Disapproved</option></select></label>
<label class="dealer-field">Profile Photo<div id="user-profile_photo-preview" style="margin-top:8px"></div><input type="file" name="profile_photo" id="user-profile_photo" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewDealerImage(this, 'user-profile_photo-preview')"></label>
<label class="dealer-field"><input type="checkbox" name="remove_profile_photo" value="1" style="width:auto"> Remove profile photo</label>
<label class="dealer-field">Brand Logo<div id="user-logo-preview" style="margin-top:8px"></div><input type="file" name="logo" id="user-logo" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewDealerImage(this, 'user-logo-preview')"></label>
<label class="dealer-field"><input type="checkbox" name="remove_logo" value="1" style="width:auto"> Remove brand logo</label>
<label class="dealer-field">New Password (optional)<input type="password" name="password" id="user-password" minlength="8" autocomplete="new-password" placeholder="Leave blank to keep current password"></label>
<p class="form-helper">Images: JPG, PNG, GIF, or WebP up to 5 MB. Approval can be changed after the subscription ends.</p></div></div>
            <div id="create-user-helper" class="form-helper">The user signs in through email OTP and receives four days of free access.</div>
            <div class="dealer-modal-actions"><button type="button" class="cancel-button" onclick="closeUserModal('user-form-modal')">Cancel</button><button class="dealer-button">Save User</button></div>
        </form>
    </div>
</div>

<div id="user-view-modal" class="dealer-modal hidden"><div class="dealer-modal-backdrop" onclick="closeUserModal('user-view-modal')"></div><div class="dealer-modal-card"><div class="dealer-modal-head"><h2>User Details</h2><button type="button" class="dealer-close" onclick="closeUserModal('user-view-modal')">&times;</button></div><div id="user-view-content" class="user-detail-grid"></div></div></div>
@endsection

@section('scripts')
@include('partials.user-profile-script')
<script>
const userBase = @json(url('dealer/users'));
function closeUserModal(id){document.getElementById(id).classList.add('hidden')}
function openUserCreate(){document.getElementById('user-form').reset();document.getElementById('user-form').action=userBase;document.getElementById('user-form-title').textContent='Create User';document.getElementById('create-user-helper').classList.remove('hidden');toggleSubscriptionFields(false);document.getElementById('edit-profile-fields').classList.add('hidden');document.querySelectorAll('#edit-profile-fields input, #edit-profile-fields select, #edit-profile-fields textarea').forEach(el=>el.disabled=true);document.getElementById('user-form-modal').classList.remove('hidden')}
async function fetchUser(id){const response=await fetch(`${userBase}/${id}`);if(!response.ok)throw new Error('Unable to load user');return response.json()}
function formatDateTimeLocal(value){if(!value)return '';const match=String(value).match(/^(\d{4}-\d{2}-\d{2})[T ](\d{2}:\d{2})/);return match?`${match[1]}T${match[2]}`:''}
function oneYearAfterDateTimeLocal(value){const formatted=formatDateTimeLocal(value);if(!formatted)return '';const [date,time]=formatted.split('T');const [year,month,day]=date.split('-').map(Number);const [hour,minute]=time.split(':').map(Number);const maximum=new Date(year+1,month-1,day,hour,minute);const pad=number=>String(number).padStart(2,'0');return `${maximum.getFullYear()}-${pad(maximum.getMonth()+1)}-${pad(maximum.getDate())}T${pad(maximum.getHours())}:${pad(maximum.getMinutes())}`}
function toggleSubscriptionFields(show){['subscription-start-wrap','subscription-end-wrap'].forEach(id=>document.getElementById(id).classList.toggle('hidden',!show));document.getElementById('user-subscription-start').required=show;document.getElementById('user-subscription-end').required=show}
function renderUserFields(user){renderUserDetails(document.getElementById('user-view-content'), user)}
async function viewUser(id){
    const content=document.getElementById('user-view-content');
    content.textContent='Loading...';
    document.getElementById('user-view-modal').classList.remove('hidden');
    try { renderUserFields(await fetchUser(id)); } catch(error) { content.textContent=error.message; }
}
async function editUser(id, previous=null){
    try {
        const user=await fetchUser(id);
        const form=document.getElementById('user-form');
        form.reset();
        form.action=`${userBase}/${id}/update`;
        document.getElementById('editing-user-id').value=id;
        document.getElementById('user-form-title').textContent='Update User';
        document.getElementById('user-name').value=user.name;
        document.getElementById('user-email').value=user.email;
        document.getElementById('user-phone').value=user.phone_number||'';
        for (const key of ['whatsapp_number','destination','address','language']) document.getElementById(`user-${key}`).value=user[key]??(key==='language'?'en':'');
        document.getElementById('user-subscription-start').value=formatDateTimeLocal(user.subscription_started_at);
        document.getElementById('user-subscription-end').value=formatDateTimeLocal(user.subscription_ends_at);
        document.getElementById('user-subscription-end').max=oneYearAfterDateTimeLocal(user.created_at);
        setApprovalOptions(document.getElementById('user-approval_status'),user);
        for (const field of ['profile_photo','logo']) showProfileImage(document.getElementById(`user-${field}-preview`),user[field],field==='logo'?'Brand Logo':'Profile Photo');
        document.getElementById('create-user-helper').classList.add('hidden');
        toggleSubscriptionFields(true);
        document.getElementById('edit-profile-fields').classList.remove('hidden');
        document.querySelectorAll('#edit-profile-fields input, #edit-profile-fields select, #edit-profile-fields textarea').forEach(el=>el.disabled=false);
        if(previous) for(const field of ['name','email','phone_number','whatsapp_number','destination','address','language','approval_status','subscription_started_at','subscription_ends_at']) {
            if(Object.hasOwn(previous,field)) form.elements[field].value=previous[field]??'';
        }
        document.getElementById('user-form-modal').classList.remove('hidden');
    } catch(error) { alert(error.message); }
}
function previewDealerImage(input,id){
    if(!input.files?.[0])return;
    const reader=new FileReader();
    reader.onload=event=>{const img=document.createElement('img');img.src=event.target.result;img.style.cssText='max-width:100%;max-height:100px;object-fit:contain';document.getElementById(id).replaceChildren(img);};
    reader.readAsDataURL(input.files[0]);
}
@if($errors->any() && old('editing_user_id'))
editUser(@json(old('editing_user_id')), @json(session()->getOldInput()));
@endif
</script>
@endsection
