<script>
const profileAssetBase = @json(rtrim(asset(''), '/') . '/');
const profileLanguages = {en:'English',mr:'Marathi',hi:'Hindi',gu:'Gujarati',bn:'Bengali',te:'Telugu',ta:'Tamil',kn:'Kannada',pa:'Punjabi'};
function profileImageUrl(value) {
    if (!value) return null;
    try {
        const url = new URL(value, profileAssetBase);
        return ['http:', 'https:'].includes(url.protocol) ? url.href : null;
    } catch { return null; }
}
function showProfileImage(container, value, label) {
    container.replaceChildren();
    const url = profileImageUrl(value);
    if (!url) { container.textContent = 'No image uploaded'; return; }
    const img = document.createElement('img');
    img.src = url;
    img.alt = label;
    img.style.cssText = 'max-width:100%;max-height:100px;object-fit:contain;border-radius:8px';
    img.onerror = () => { container.textContent = 'Image unavailable'; };
    container.append(img);
}
function userDetailFields(user) {
    const date = value => value ? new Date(value).toLocaleString() : 'Not set';
    return [
        ['User ID', user.id], ['Full Name', user.name], ['Email Address', user.email],
        ['Phone Number', user.phone_number], ['WhatsApp Number', user.whatsapp_number],
        ['Address', user.address], ['Destination', user.destination],
        ['Language', profileLanguages[user.language] || user.language],
        ['Managed By', user.dealer ? `${user.dealer.name} (${user.dealer.referral_code})` : 'Admin'],
        ['Subscription Start', date(user.subscription_started_at)], ['Subscription End', date(user.subscription_ends_at)],
        ['Approval Status', user.approval_status], ['Email Verified At', date(user.email_verified_at)],
        ['Google Account', user.google_id ? 'Linked' : 'Not linked'],
        ['Registered At', date(user.created_at)], ['Last Updated', date(user.updated_at)],
    ];
}
function renderUserDetails(container, user, dark = false) {
    const fields = userDetailFields(user);
    container.replaceChildren();
    for (const [label, value] of [...fields, ['Profile Photo', user.profile_photo], ['Brand Logo', user.logo]]) {
        const box = document.createElement('div');
        box.style.cssText = `padding:14px;border:1px solid ${dark ? '#263248' : '#e5e9f2'};border-radius:12px;background:${dark ? '#0b1326' : '#fafbfe'};min-width:0`;
        const key = document.createElement('div');
        key.textContent = label;
        key.style.cssText = 'font-size:11px;text-transform:uppercase;color:#8894a8;margin-bottom:6px';
        const text = document.createElement('div');
        text.style.cssText = `font-size:13px;white-space:pre-wrap;overflow-wrap:anywhere;color:${dark ? '#fff' : '#292d46'}`;
        if (['Profile Photo', 'Brand Logo'].includes(label)) showProfileImage(text, value, label);
        else text.textContent = value ?? 'Not set';
        box.append(key, text);
        container.append(box);
    }
}
function setApprovalOptions(select, user) {
    const expired = user.subscription_ends_at && new Date(user.subscription_ends_at) < new Date();
    for (const option of select.options) option.disabled = !expired && option.value !== user.approval_status;
    select.value = user.approval_status;
}
</script>
