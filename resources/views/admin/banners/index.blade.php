@extends('admin.layout')

@section('title', 'Manage Banners')
@section('page_title', 'Banner Management')

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Banner Templates</h1>
            <p class="text-xs text-slate-400 mt-0.5">Add, edit, view, and toggle active status of promotional banners used for calendars.</p>
        </div>
        <div>
            <button onclick="openAddModal()" 
                    class="flex items-center justify-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-white shadow-md shadow-amber-500/10 hover:shadow-amber-500/20 rounded-xl transition-all font-semibold text-sm transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Banner</span>
            </button>
        </div>
    </div>

    <!-- Banners Table Card -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-800 text-xs text-slate-400 font-semibold uppercase tracking-wider bg-slate-950/40">
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6 w-[20%]">Title</th>
                        <th class="py-4 px-6 w-[25%]">Heading</th>
                        <th class="py-4 px-6">Template Image</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-slate-300">
                    @forelse($banners as $banner)
                        <tr class="hover:bg-slate-800/20 transition-colors" id="banner-row-{{ $banner->id }}">
                            <td class="py-4 px-6 font-mono text-xs text-slate-500">#{{ $banner->id }}</td>
                            <td class="py-4 px-6 font-semibold text-white">{{ $banner->title }}</td>
                            <td class="py-4 px-6 text-slate-400 text-xs truncate max-w-[200px]">{{ $banner->heading }}</td>
                            <td class="py-4 px-6">
                                @if($banner->image)
                                    <div class="h-10 w-24 rounded-lg bg-slate-800 overflow-hidden border border-slate-700/60">
                                        <img src="{{ asset($banner->image) }}" alt="Preview" class="h-full w-full object-cover">
                                    </div>
                                @else
                                    <span class="text-xs text-slate-500 italic">No image template</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" onchange="toggleBannerStatus({{ $banner->id }}, this)" class="sr-only peer" {{ $banner->status == 1 ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-850 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-500 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 peer-checked:after:bg-white border border-slate-800 peer-checked:border-emerald-400"></div>
                                </label>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                <button onclick="viewBanner({{ $banner->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-amber-600/10 text-slate-400 hover:text-amber-400 transition-colors"
                                        title="View Details">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button onclick="editBanner({{ $banner->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-emerald-600/10 text-slate-400 hover:text-emerald-400 transition-colors"
                                        title="Edit Banner">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button onclick="confirmDeleteBanner({{ $banner->id }}, '{{ addslashes($banner->title) }}')" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-red-600/10 text-slate-400 hover:text-red-400 transition-colors"
                                        title="Delete Banner">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-500">No Banner records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($banners->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/20">
                {{ $banners->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Banner Modal -->
<div id="add-banner-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('add-banner-modal')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Create Banner Record</h3>
            <button onclick="closeModal('add-banner-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            <div class="p-6 space-y-4 overflow-y-auto flex-1 scrollbar">
                <div>
                    <label for="add-title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Title Name</label>
                    <input type="text" name="title" id="add-title" required placeholder="Standard Consultancy Banner"
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                </div>

                <div>
                    <label for="add-heading" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Slogan / Heading</label>
                    <input type="text" name="heading" id="add-heading" placeholder="Doctors Save Lives, We Save Lifestyle"
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                </div>

                <div>
                    <label for="add-services" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Services List (Comma-separated)</label>
                    <textarea name="services" id="add-services" rows="3" placeholder="Premium Payment, Maturity Claim, Policy Revival, Policy Loan"
                              class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm resize-none"></textarea>
                    <p class="text-xs text-slate-500 mt-1">Leave blank to use default services from Screenshot 1.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Background Image / Pattern</label>
                    <input type="file" name="image" accept="image/*"
                           class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-600/10 file:text-amber-400 hover:file:bg-amber-600/20 file:cursor-pointer cursor-pointer">
                    <p class="text-xs text-slate-500 mt-1">Optional. Supports PNG, JPG, JPEG up to 5MB.</p>
                </div>

                <div>
                    <label for="add-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Default Status</label>
                    <select name="status" id="add-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
                <button type="button" onclick="closeModal('add-banner-modal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-700/60 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-sm font-semibold">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-white font-semibold text-sm">
                    Create Banner
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Banner Modal -->
<div id="view-banner-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('view-banner-modal')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Banner Details</h3>
            <button onclick="closeModal('view-banner-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4 overflow-y-auto flex-1 scrollbar">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Banner Title</span>
                <h4 class="text-base font-bold text-white leading-snug" id="view-title">Loading...</h4>
            </div>

            <div class="space-y-1 border-t border-slate-800/80 pt-3">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Slogan / Heading</span>
                <div class="text-sm text-slate-300" id="view-heading">Loading...</div>
            </div>

            <div class="space-y-1 border-t border-slate-800/80 pt-3">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Consultancy Services</span>
                <div class="text-xs text-slate-400 bg-slate-950/30 p-3 border border-slate-850 rounded-2xl whitespace-pre-wrap leading-relaxed" id="view-services">Loading...</div>
            </div>

            <div class="space-y-2 border-t border-slate-800/80 pt-3">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Image Template</span>
                <div id="view-image-container" class="w-full max-h-[160px] rounded-xl overflow-hidden bg-slate-950/50 border border-slate-850 flex items-center justify-center p-2">
                    <img id="view-image" src="" alt="Banner template" class="max-h-[140px] max-w-full object-contain">
                </div>
            </div>

            <div class="flex items-center justify-between border-t border-slate-800/80 pt-3">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</span>
                <span id="view-status" class="px-2.5 py-1 text-xs font-bold rounded-full">Active</span>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end flex-shrink-0">
            <button onclick="closeModal('view-banner-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Edit Banner Modal -->
<div id="edit-banner-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('edit-banner-modal')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Modify Banner Record</h3>
            <button onclick="closeModal('edit-banner-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="edit-banner-form" action="" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            <div class="p-6 space-y-4 overflow-y-auto flex-1 scrollbar">
                <div>
                    <label for="edit-title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Title Name</label>
                    <input type="text" name="title" id="edit-title" required
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                </div>

                <div>
                    <label for="edit-heading" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Slogan / Heading</label>
                    <input type="text" name="heading" id="edit-heading"
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                </div>

                <div>
                    <label for="edit-services" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Services List (Comma-separated)</label>
                    <textarea name="services" id="edit-services" rows="3"
                              class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Replace Background Image</label>
                    <div id="edit-current-image" class="mb-2 hidden">
                        <span class="block text-xs text-slate-500 mb-1">Current Image:</span>
                        <img id="edit-preview-img" src="" class="h-14 w-32 object-cover rounded-lg border border-slate-700">
                    </div>
                    <input type="file" name="image" accept="image/*"
                           class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-600/10 file:text-amber-400 hover:file:bg-amber-600/20 file:cursor-pointer cursor-pointer">
                </div>

                <div>
                    <label for="edit-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" id="edit-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
                <button type="button" onclick="closeModal('edit-banner-modal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-700/60 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-sm font-semibold">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-white font-semibold text-sm">
                    Save Updates
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Banner Confirmation Modal -->
<div id="delete-banner-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('delete-banner-modal')"></div>
    <div class="relative w-full max-w-md bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl z-10 animate-fade-in">
        <div class="p-6 text-center space-y-4">
            <div class="h-14 w-14 bg-red-500/10 border border-red-500/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-2">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white">Remove Banner Template?</h3>
            <p class="text-sm text-slate-400">Are you sure you want to delete this banner record? This action is permanent and cannot be reversed.</p>
            <div class="text-xs text-slate-500 italic p-3 bg-slate-950/30 border border-slate-850 rounded-xl" id="delete-banner-title-placeholder"></div>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end space-x-3">
            <button onclick="closeModal('delete-banner-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl">
                Cancel
            </button>
            <form id="delete-banner-form" action="" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-red-950/30">
                    Delete Record
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const baseUrl = "{{ url('admin/banners') }}";

    function openAddModal() {
        document.getElementById('add-title').value = '';
        document.getElementById('add-heading').value = '';
        document.getElementById('add-services').value = '';
        document.getElementById('add-status').value = '1';
        openModal('add-banner-modal');
    }

    function viewBanner(id) {
        document.getElementById('view-title').textContent = 'Loading...';
        document.getElementById('view-heading').textContent = 'Loading...';
        document.getElementById('view-services').textContent = 'Loading...';
        document.getElementById('view-image').src = '';
        document.getElementById('view-image-container').classList.add('hidden');
        document.getElementById('view-status').className = 'hidden';

        openModal('view-banner-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(banner => {
                document.getElementById('view-title').textContent = banner.title;
                document.getElementById('view-heading').textContent = banner.heading || 'No Slogan';
                document.getElementById('view-services').textContent = banner.services || 'Default services matching screenshot 1';
                
                if (banner.image) {
                    document.getElementById('view-image').src = "{{ asset('') }}" + banner.image.replace(/^\//, '');
                    document.getElementById('view-image-container').classList.remove('hidden');
                }
                
                const statusBadge = document.getElementById('view-status');
                statusBadge.classList.remove('hidden');
                if (banner.status == 1) {
                    statusBadge.textContent = 'Active';
                    statusBadge.className = 'px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
                } else {
                    statusBadge.textContent = 'Inactive';
                    statusBadge.className = 'px-2.5 py-1 text-xs font-bold rounded-full bg-slate-800 text-slate-400 border border-slate-750';
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('view-title').textContent = 'Error loading banner details';
            });
    }

    function editBanner(id) {
        document.getElementById('edit-banner-form').action = `${baseUrl}/${id}/update`;
        document.getElementById('edit-title').value = '';
        document.getElementById('edit-heading').value = '';
        document.getElementById('edit-services').value = '';
        document.getElementById('edit-status').value = '1';
        document.getElementById('edit-current-image').classList.add('hidden');

        openModal('edit-banner-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(banner => {
                document.getElementById('edit-title').value = banner.title;
                document.getElementById('edit-heading').value = banner.heading || '';
                document.getElementById('edit-services').value = banner.services || '';
                document.getElementById('edit-status').value = banner.status;
                
                if (banner.image) {
                    document.getElementById('edit-preview-img').src = "{{ asset('') }}" + banner.image.replace(/^\//, '');
                    document.getElementById('edit-current-image').classList.remove('hidden');
                }
            })
            .catch(err => console.error(err));
    }

    function toggleBannerStatus(id, checkbox) {
        fetch(`${baseUrl}/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                console.log(data.message);
            } else {
                checkbox.checked = !checkbox.checked;
                alert('Failed to toggle status.');
            }
        })
        .catch(err => {
            console.error(err);
            checkbox.checked = !checkbox.checked;
            alert('An error occurred.');
        });
    }

    function confirmDeleteBanner(id, title) {
        document.getElementById('delete-banner-form').action = `${baseUrl}/${id}`;
        document.getElementById('delete-banner-title-placeholder').textContent = `"${title}"`;
        openModal('delete-banner-modal');
    }
</script>
@endsection
