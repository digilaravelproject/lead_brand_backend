@include('partials.user-profile-script')
<script>
const baseUrl = @json(url('admin/users'));
async function fetchProfile(id) {
    const response = await fetch(`${baseUrl}/${id}`, {headers: {'Accept':'application/json'}});
    if (!response.ok) throw new Error('Unable to load user details.');
    return response.json();
}
async function viewUser(id) {
    const container = document.getElementById('user-details-content');
    container.textContent = 'Loading...';
    openModal('view-user-modal');
    try { renderUserDetails(container, await fetchProfile(id), true); }
    catch (error) { container.textContent = error.message; }
}
function formatDateTimeLocal(value) {
    if (!value) return '';
    const match = String(value).match(/^(\d{4}-\d{2}-\d{2})[T ](\d{2}:\d{2})/);
    return match ? `${match[1]}T${match[2]}` : '';
}
function oneYearAfterDateTimeLocal(value) {
    const formatted = formatDateTimeLocal(value);
    if (!formatted) return '';
    const [date, time] = formatted.split('T');
    const [year, month, day] = date.split('-').map(Number);
    const [hour, minute] = time.split(':').map(Number);
    const maximum = new Date(year + 1, month - 1, day, hour, minute);
    const pad = number => String(number).padStart(2, '0');
    return `${maximum.getFullYear()}-${pad(maximum.getMonth()+1)}-${pad(maximum.getDate())}T${pad(maximum.getHours())}:${pad(maximum.getMinutes())}`;
}
async function editUser(id, previous = null) {
    const form = document.getElementById('edit-user-form');
    form.reset();
    try {
        const user = await fetchProfile(id);
        form.action = `${baseUrl}/${id}/update`;
        document.getElementById('editing-user-id').value = id;
        for (const [field, element] of Object.entries({name:'edit-name',email:'edit-email',phone_number:'edit-phone',destination:'edit-destination',whatsapp_number:'edit-whatsapp_number',address:'edit-address',language:'edit-language'})) {
            document.getElementById(element).value = user[field] ?? (field === 'language' ? 'en' : '');
        }
        document.getElementById('edit-subscription-start').value = formatDateTimeLocal(user.subscription_started_at);
        document.getElementById('edit-subscription-end').value = formatDateTimeLocal(user.subscription_ends_at);
        document.getElementById('edit-subscription-end').max = oneYearAfterDateTimeLocal(user.created_at);
        setApprovalOptions(document.getElementById('edit-approval_status'), user);
        showProfileImage(document.getElementById('edit-photo-preview'), user.profile_photo, 'Profile Photo');
        showProfileImage(document.getElementById('edit-logo-preview'), user.logo, 'Brand Logo');
        if (previous) {
            for (const field of ['name','email','phone_number','destination','whatsapp_number','address','language','approval_status','subscription_started_at','subscription_ends_at']) {
                if (Object.hasOwn(previous, field)) form.elements[field].value = previous[field] ?? '';
            }
        }
        openModal('edit-user-modal');
    } catch (error) { alert(error.message); }
}
function confirmDeleteUser(id, name) {
    document.getElementById('delete-user-form').action = `${baseUrl}/${id}`;
    document.getElementById('delete-username-placeholder').textContent = name;
    openModal('delete-user-modal');
}
function previewUserFile(input, previewId) {
    if (!input.files?.[0]) return;
    const reader = new FileReader();
    reader.onload = event => {
        const img = document.createElement('img');
        img.src = event.target.result;
        img.style.cssText = 'max-width:100%;max-height:100px;object-fit:contain';
        document.getElementById(previewId).replaceChildren(img);
    };
    reader.readAsDataURL(input.files[0]);
}
@if($errors->any() && old('editing_user_id'))
editUser(@json(old('editing_user_id')), @json(session()->getOldInput()));
@endif
</script>
