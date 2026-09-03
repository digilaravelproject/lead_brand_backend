<!-- View User Modal -->
<div id="view-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('view-user-modal')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">User Account Details</h3>
            <button onclick="closeModal('view-user-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-6 overflow-y-auto flex-1 scrollbar">
            <div id="user-details-content" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end flex-shrink-0">
            <button onclick="closeModal('view-user-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl">
                Close View
            </button>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="edit-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('edit-user-modal')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Modify User Profile</h3>
            <button onclick="closeModal('edit-user-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="edit-user-form" action="" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            <input type="hidden" name="editing_user_id" id="editing-user-id">
            <div class="p-6 space-y-4 overflow-y-auto flex-1 scrollbar">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Profile Image -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Profile Photo</label>
                        <div class="flex flex-col items-start gap-2">
                            <div class="h-12 w-12 rounded-xl bg-slate-800 border border-slate-700/50 flex items-center justify-center text-white font-extrabold uppercase overflow-hidden" id="edit-photo-preview">
                                <!-- Dynamic -->
                            </div>
                            <input type="file" name="profile_photo" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewUserFile(this, 'edit-photo-preview')" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1 file:px-2 file:rounded file:border-0 file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700 file:cursor-pointer cursor-pointer"><label class="block text-xs text-slate-400 mt-2"><input type="checkbox" name="remove_profile_photo" value="1"> Remove image</label>
                        </div>
                    </div>

                    <!-- Brand Logo -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Brand Logo</label>
                        <div class="flex flex-col items-start gap-2">
                            <div class="h-12 w-20 rounded-xl bg-slate-800 border border-slate-700/50 flex items-center justify-center text-white font-extrabold uppercase overflow-hidden p-1" id="edit-logo-preview">
                                <!-- Dynamic -->
                            </div>
                            <input type="file" name="logo" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewUserFile(this, 'edit-logo-preview')" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1 file:px-2 file:rounded file:border-0 file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700 file:cursor-pointer cursor-pointer"><label class="block text-xs text-slate-400 mt-2"><input type="checkbox" name="remove_logo" value="1"> Remove image</label>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="edit-name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Full Name</label>
                    <input type="text" name="name" id="edit-name" required
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                </div>

                <div>
                    <label for="edit-email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Email Address</label>
                    <input type="email" name="email" id="edit-email" required
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit-phone" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Phone Number</label>
                        <input type="text" name="phone_number" id="edit-phone"
                               class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                    </div>
                    <div>
                        <label for="edit-destination" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Destination</label>
                        <input type="text" name="destination" id="edit-destination"
                               class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit-subscription-start" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Start Date</label>
                        <input type="datetime-local" name="subscription_started_at" id="edit-subscription-start" required
                               class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                    </div>
                    <div>
                        <label for="edit-subscription-end" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">End Date</label>
                        <input type="datetime-local" name="subscription_ends_at" id="edit-subscription-end" required
                               class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                    </div>
                </div>

<label class="block text-xs font-semibold text-slate-400 uppercase">WhatsApp Number
<input type="text" maxlength="30" name="whatsapp_number" id="edit-whatsapp_number" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white text-sm mt-2"></label>
<label class="block text-xs font-semibold text-slate-400 uppercase">Address
<textarea name="address" id="edit-address" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white text-sm mt-2" rows="3" maxlength="5000"></textarea></label>
<label class="block text-xs font-semibold text-slate-400 uppercase">Language
<select name="language" id="edit-language" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white text-sm mt-2"><option value="en">English</option><option value="mr">Marathi</option><option value="hi">Hindi</option><option value="gu">Gujarati</option><option value="bn">Bengali</option><option value="te">Telugu</option><option value="ta">Tamil</option><option value="kn">Kannada</option><option value="pa">Punjabi</option></select></label>
<label class="block text-xs font-semibold text-slate-400 uppercase">Approval Status
<select name="approval_status" id="edit-approval_status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white text-sm mt-2"><option value="pending">Pending</option><option value="approved">Approved</option><option value="disapproved">Disapproved</option></select></label>
<p class="text-xs text-slate-500">Approval can be changed after the subscription ends. Account ID, registration, and verification dates appear in the details view.</p>
                <div class="border-t border-slate-800 pt-3.5">
                    <label for="edit-password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Change Password (Optional)</label>
                    <input type="password" name="password" id="edit-password" minlength="8" autocomplete="new-password" placeholder="Leave blank to keep current password"
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-660 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                </div>
            </div>

            <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
                <button type="button" onclick="closeModal('edit-user-modal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-700/60 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-sm font-semibold">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-white font-semibold text-sm">
                    Apply Updates
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Delete User Confirmation Modal -->
<div id="delete-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('delete-user-modal')"></div>
    <div class="relative w-full max-w-md bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl z-10 animate-fade-in">
        <div class="p-6 text-center space-y-4">
            <div class="h-14 w-14 bg-red-500/10 border border-red-500/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-2">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white">Permanently Remove Account?</h3>
            <p class="text-sm text-slate-400">Are you sure you want to delete <span class="text-white font-semibold" id="delete-username-placeholder"></span>? This action is permanent and cannot be reversed.</p>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end space-x-3">
            <button onclick="closeModal('delete-user-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl">
                Abort
            </button>
            <form id="delete-user-form" action="" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-red-950/30">
                    Delete Account
                </button>
            </form>
        </div>
    </div>
</div>
