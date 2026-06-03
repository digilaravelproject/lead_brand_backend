@extends('admin.layout')

@section('title', 'Manage Users')
@section('page_title', 'User Accounts')

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Registered Users</h1>
            <p class="text-xs text-slate-400 mt-0.5">View and manage customer registrations, profile photos, and settings.</p>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-800 text-xs text-slate-400 font-semibold uppercase tracking-wider bg-slate-950/40">
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6">Profile / User</th>
                        <th class="py-4 px-6">Email Address</th>
                        <th class="py-4 px-6">Phone Number</th>
                        <th class="py-4 px-6">Destination</th>
                        <th class="py-4 px-6">Brand Logo</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-slate-300">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-800/20 transition-colors">
                            <td class="py-4 px-6 font-mono text-xs text-slate-500">#{{ $user->id }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-slate-850 rounded-xl flex items-center justify-center font-bold text-indigo-400 text-sm overflow-hidden border border-slate-800/80">
                                        @if($user->profile_photo)
                                            <img src="{{ asset($user->profile_photo) }}" alt="Avatar" class="h-full w-full object-cover">
                                        @else
                                            {{ substr($user->name, 0, 2) }}
                                        @endif
                                    </div>
                                    <div class="font-bold text-white">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-300">{{ $user->email }}</td>
                            <td class="py-4 px-6 text-slate-400">{{ $user->phone_number ?: 'N/A' }}</td>
                            <td class="py-4 px-6 text-slate-400 text-xs">{{ $user->destination ?: 'N/A' }}</td>
                            <td class="py-4 px-6">
                                @if($user->logo)
                                    <div class="h-8 w-16 bg-slate-950 rounded border border-slate-850 flex items-center justify-center overflow-hidden p-1">
                                        <img src="{{ asset($user->logo) }}" alt="Logo" class="h-full object-contain">
                                    </div>
                                @else
                                    <span class="text-xs text-slate-600">No Logo</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <button onclick="viewUser({{ $user->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-indigo-600/10 text-slate-400 hover:text-indigo-400 transition-colors"
                                        title="View Details">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button onclick="editUser({{ $user->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-emerald-600/10 text-slate-400 hover:text-emerald-400 transition-colors"
                                        title="Edit User">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-red-600/10 text-slate-400 hover:text-red-400 transition-colors"
                                        title="Delete User">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Section -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/20">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

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
            <!-- User Banner -->
            <div class="flex items-center space-x-4 bg-slate-950/50 p-4 border border-slate-800/80 rounded-2xl">
                <div class="h-16 w-16 bg-slate-800 rounded-2xl flex items-center justify-center text-white text-xl font-extrabold uppercase overflow-hidden border border-slate-700/50" id="view-avatar-box">
                    <!-- Dynamic -->
                </div>
                <div>
                    <h4 class="text-base font-bold text-white" id="view-name">Loading...</h4>
                    <span class="text-xs text-indigo-400 font-semibold" id="view-destination">Destination</span>
                </div>
            </div>

            <!-- Detail Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div class="bg-slate-950/30 p-3.5 border border-slate-850 rounded-xl">
                    <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Email Address</span>
                    <span class="text-white break-all font-semibold" id="view-email">-</span>
                </div>
                <div class="bg-slate-950/30 p-3.5 border border-slate-850 rounded-xl">
                    <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Phone Number</span>
                    <span class="text-white font-semibold" id="view-phone">-</span>
                </div>
                <div class="bg-slate-950/30 p-3.5 border border-slate-850 rounded-xl">
                    <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Register Date</span>
                    <span class="text-white font-semibold" id="view-created">-</span>
                </div>
                <div class="bg-slate-950/30 p-3.5 border border-slate-850 rounded-xl">
                    <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Brand Logo</span>
                    <div id="view-logo-container" class="mt-1">
                        <!-- Dynamic -->
                    </div>
                </div>
            </div>
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
            
            <div class="p-6 space-y-4 overflow-y-auto flex-1 scrollbar">
                <div class="grid grid-cols-2 gap-4">
                    <!-- Profile Image -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Profile Photo</label>
                        <div class="flex items-center space-x-3">
                            <div class="h-12 w-12 rounded-xl bg-slate-800 border border-slate-700/50 flex items-center justify-center text-white font-extrabold uppercase overflow-hidden" id="edit-photo-preview">
                                <!-- Dynamic -->
                            </div>
                            <input type="file" name="profile_photo" accept="image/*" onchange="previewUserFile(this, 'edit-photo-preview')" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1 file:px-2 file:rounded file:border-0 file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700 file:cursor-pointer cursor-pointer">
                        </div>
                    </div>

                    <!-- Brand Logo -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Brand Logo</label>
                        <div class="flex items-center space-x-3">
                            <div class="h-12 w-20 rounded-xl bg-slate-800 border border-slate-700/50 flex items-center justify-center text-white font-extrabold uppercase overflow-hidden p-1" id="edit-logo-preview">
                                <!-- Dynamic -->
                            </div>
                            <input type="file" name="logo" accept="image/*" onchange="previewUserFile(this, 'edit-logo-preview')" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1 file:px-2 file:rounded file:border-0 file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700 file:cursor-pointer cursor-pointer">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="edit-name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Full Name</label>
                    <input type="text" name="name" id="edit-name" required
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                </div>

                <div>
                    <label for="edit-email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Email Address</label>
                    <input type="email" name="email" id="edit-email" required
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit-phone" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Phone Number</label>
                        <input type="text" name="phone_number" id="edit-phone"
                               class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                    </div>
                    <div>
                        <label for="edit-destination" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Destination</label>
                        <input type="text" name="destination" id="edit-destination"
                               class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                    </div>
                </div>

                <div class="border-t border-slate-800 pt-3.5">
                    <label for="edit-password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Change Password (Optional)</label>
                    <input type="password" name="password" id="edit-password" placeholder="Leave blank to keep current password"
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-660 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                </div>
            </div>

            <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
                <button type="button" onclick="closeModal('edit-user-modal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-700/60 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-sm font-semibold">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold text-sm">
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
@endsection

@section('scripts')
<script>
    const baseUrl = "{{ url('admin/users') }}";

    function viewUser(id) {
        // Clear old contents
        document.getElementById('view-name').textContent = 'Loading...';
        document.getElementById('view-destination').textContent = '';
        document.getElementById('view-email').textContent = '-';
        document.getElementById('view-phone').textContent = '-';
        document.getElementById('view-created').textContent = '-';
        document.getElementById('view-avatar-box').innerHTML = '';
        document.getElementById('view-logo-container').innerHTML = '';

        openModal('view-user-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(user => {
                document.getElementById('view-name').textContent = user.name;
                document.getElementById('view-destination').textContent = user.destination || 'No destination specified';
                document.getElementById('view-email').textContent = user.email;
                document.getElementById('view-phone').textContent = user.phone_number || 'N/A';
                
                const createdDate = new Date(user.created_at);
                document.getElementById('view-created').textContent = createdDate.toLocaleDateString('en-US', {
                    year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
                });

                // Set profile picture
                const avatarBox = document.getElementById('view-avatar-box');
                if (user.profile_photo) {
                    avatarBox.innerHTML = `<img src="{{ asset('') }}${user.profile_photo}" alt="Avatar" class="h-full w-full object-cover">`;
                } else {
                    avatarBox.innerHTML = `<span>${user.name.substring(0,2).toUpperCase()}</span>`;
                }

                // Set brand logo
                const logoContainer = document.getElementById('view-logo-container');
                if (user.logo) {
                    logoContainer.innerHTML = `
                        <div class="h-10 w-24 bg-slate-950 border border-slate-800 rounded flex items-center justify-center p-1.5 overflow-hidden mt-1">
                            <img src="{{ asset('') }}${user.logo}" alt="Logo" class="h-full object-contain">
                        </div>`;
                } else {
                    logoContainer.innerHTML = `<span class="text-xs text-slate-500">No logo uploaded</span>`;
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('view-name').textContent = 'Error loading user data';
            });
    }

    function editUser(id) {
        document.getElementById('edit-user-form').action = `${baseUrl}/${id}/update`;
        document.getElementById('edit-name').value = '';
        document.getElementById('edit-email').value = '';
        document.getElementById('edit-phone').value = '';
        document.getElementById('edit-destination').value = '';
        document.getElementById('edit-password').value = '';
        document.getElementById('edit-photo-preview').innerHTML = '';
        document.getElementById('edit-logo-preview').innerHTML = '';

        openModal('edit-user-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(user => {
                document.getElementById('edit-name').value = user.name;
                document.getElementById('edit-email').value = user.email;
                document.getElementById('edit-phone').value = user.phone_number || '';
                document.getElementById('edit-destination').value = user.destination || '';

                const photoPreview = document.getElementById('edit-photo-preview');
                if (user.profile_photo) {
                    photoPreview.innerHTML = `<img src="{{ asset('') }}${user.profile_photo}" class="h-full w-full object-cover">`;
                } else {
                    photoPreview.innerHTML = `<span>${user.name.substring(0,2).toUpperCase()}</span>`;
                }

                const logoPreview = document.getElementById('edit-logo-preview');
                if (user.logo) {
                    logoPreview.innerHTML = `<img src="{{ asset('') }}${user.logo}" class="h-full object-contain">`;
                } else {
                    logoPreview.innerHTML = `<span class="text-[10px] text-slate-500">NONE</span>`;
                }
            })
            .catch(err => console.error(err));
    }

    function confirmDeleteUser(id, name) {
        document.getElementById('delete-user-form').action = `${baseUrl}/${id}`;
        document.getElementById('delete-username-placeholder').textContent = name;
        openModal('delete-user-modal');
    }

    function previewUserFile(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                preview.innerHTML = `<img src="${e.target.result}" class="h-full w-full object-cover">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
