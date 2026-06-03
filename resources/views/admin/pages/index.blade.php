@extends('admin.layout')

@section('title', 'Manage Static Pages')
@section('page_title', 'Static Content Pages')

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Static Pages</h1>
            <p class="text-xs text-slate-400 mt-0.5">Manage details and text content for Privacy Policy, About Us, Terms, and custom pages.</p>
        </div>
        <div>
            <button onclick="openAddModal()" 
                    class="flex items-center justify-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white shadow-md shadow-indigo-500/10 hover:shadow-indigo-500/20 rounded-xl transition-all font-semibold text-sm transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Page</span>
            </button>
        </div>
    </div>

    <!-- Pages Table Card -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-800 text-xs text-slate-400 font-semibold uppercase tracking-wider bg-slate-950/40">
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6">Page Name</th>
                        <th class="py-4 px-6">Page Type</th>
                        <th class="py-4 px-6">Content Preview</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-slate-300">
                    @forelse($pages as $page)
                        <tr class="hover:bg-slate-800/20 transition-colors" id="page-row-{{ $page->id }}">
                            <td class="py-4 px-6 font-mono text-xs text-slate-500">#{{ $page->id }}</td>
                            <td class="py-4 px-6 font-semibold text-white">
                                <span class="capitalize">{{ str_replace('_', ' ', $page->page_name) }}</span>
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-400 font-mono">{{ $page->page_type }}</td>
                            <td class="py-4 px-6 text-slate-400">
                                <div class="line-clamp-2 text-xs max-w-xs">{{ strip_tags($page->description) }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" onchange="togglePageStatus({{ $page->id }}, this)" class="sr-only peer" {{ $page->status == 1 ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-850 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-500 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 peer-checked:after:bg-white border border-slate-800 peer-checked:border-emerald-400"></div>
                                </label>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <button onclick="viewPage({{ $page->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-indigo-600/10 text-slate-400 hover:text-indigo-400 transition-colors"
                                        title="View Page Content">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button onclick="editPage({{ $page->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-emerald-600/10 text-slate-400 hover:text-emerald-400 transition-colors"
                                        title="Edit Page">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button onclick="confirmDeletePage({{ $page->id }}, '{{ addslashes($page->page_name) }}')" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-red-600/10 text-slate-400 hover:text-red-400 transition-colors"
                                        title="Delete Page">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-500">No static pages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($pages->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/20">
                {{ $pages->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Page Modal -->
<div id="add-page-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('add-page-modal')"></div>
    <div class="relative w-full max-w-2xl bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Create Static Page</h3>
            <button onclick="closeModal('add-page-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form action="{{ route('admin.pages.store') }}" method="POST" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            <div class="p-6 space-y-4 overflow-y-auto flex-1 scrollbar">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="add-page_name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Page Name (URL Key)</label>
                        <input type="text" name="page_name" id="add-page_name" required placeholder="e.g. privacy_policy"
                               class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-655 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                    </div>

                    <div>
                        <label for="add-page_type" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Page Type (Optional)</label>
                        <input type="text" name="page_type" id="add-page_type" placeholder="e.g. text_content"
                               class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-655 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                    </div>
                </div>

                <div>
                    <label for="add-description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Page HTML Content</label>
                    <textarea name="description" id="add-description" rows="8" required placeholder="<h2>Privacy Policy</h2><p>Our guidelines details here...</p>"
                              class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-655 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm font-mono"></textarea>
                </div>

                <div>
                    <label for="add-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" id="add-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                        <option value="1">Active (Published)</option>
                        <option value="0">Inactive (Draft)</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
                <button type="button" onclick="closeModal('add-page-modal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-700/60 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-sm font-semibold">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold text-sm">
                    Create Page
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Page Modal -->
<div id="view-page-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('view-page-modal')"></div>
    <div class="relative w-full max-w-2xl bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <div>
                <h3 class="text-lg font-bold text-white capitalize" id="view-title">Loading Page...</h3>
                <span class="text-xs text-indigo-400 font-mono" id="view-type">type: loading</span>
            </div>
            <button onclick="closeModal('view-page-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4 overflow-y-auto flex-1 scrollbar">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Page Content Render Preview</span>
            <div class="h-[300px] overflow-y-auto bg-slate-955 p-5 border border-slate-850 rounded-2xl text-sm text-slate-300 leading-relaxed prose prose-invert max-w-none scrollbar" id="view-content">
                <!-- HTML rendered dynamically -->
            </div>

            <div class="flex items-center justify-between border-t border-slate-800/80 pt-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Published Status</span>
                <span id="view-status" class="px-2.5 py-1 text-xs font-bold rounded-full">Active</span>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end flex-shrink-0">
            <button onclick="closeModal('view-page-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl">
                Close View
            </button>
        </div>
    </div>
</div>

<!-- Edit Page Modal -->
<div id="edit-page-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('edit-page-modal')"></div>
    <div class="relative w-full max-w-2xl bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Modify Page Settings & Content</h3>
            <button onclick="closeModal('edit-page-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="edit-page-form" action="" method="POST" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            <div class="p-6 space-y-4 overflow-y-auto flex-1 scrollbar">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit-page_name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Page Name (URL Key)</label>
                        <input type="text" name="page_name" id="edit-page_name" required
                               class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                    </div>

                    <div>
                        <label for="edit-page_type" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Page Type</label>
                        <input type="text" name="page_type" id="edit-page_type" required
                               class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                    </div>
                </div>

                <div>
                    <label for="edit-description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Page HTML Content</label>
                    <textarea name="description" id="edit-description" rows="8" required
                              class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm font-mono"></textarea>
                </div>

                <div>
                    <label for="edit-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" id="edit-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                        <option value="1">Active (Published)</option>
                        <option value="0">Inactive (Draft)</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
                <button type="button" onclick="closeModal('edit-page-modal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-700/60 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-sm font-semibold">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold text-sm">
                    Apply Content Updates
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Delete Page Confirmation Modal -->
<div id="delete-page-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('delete-page-modal')"></div>
    <div class="relative w-full max-w-md bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl z-10 animate-fade-in">
        <div class="p-6 text-center space-y-4">
            <div class="h-14 w-14 bg-red-500/10 border border-red-500/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-2">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white">Permanently Remove Static Page?</h3>
            <p class="text-sm text-slate-400">Are you sure you want to delete the page <span class="text-white font-semibold" id="delete-page-name-placeholder"></span>? Any public URL serving this page will experience a 404.</p>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end space-x-3">
            <button onclick="closeModal('delete-page-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl">
                Abort
            </button>
            <form id="delete-page-form" action="" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-red-950/30">
                    Delete Page
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const baseUrl = "{{ url('admin/pages') }}";

    function openAddModal() {
        document.getElementById('add-page_name').value = '';
        document.getElementById('add-page_type').value = '';
        document.getElementById('add-description').value = '';
        document.getElementById('add-status').value = '1';
        openModal('add-page-modal');
    }

    function viewPage(id) {
        document.getElementById('view-title').textContent = 'Loading Page...';
        document.getElementById('view-type').textContent = 'type: loading';
        document.getElementById('view-content').innerHTML = '<p class="text-slate-500 italic">Retrieving content...</p>';
        document.getElementById('view-status').className = 'hidden';

        openModal('view-page-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(page => {
                document.getElementById('view-title').textContent = page.page_name.replace(/_/g, ' ');
                document.getElementById('view-type').textContent = `type: ${page.page_type}`;
                document.getElementById('view-content').innerHTML = page.description || '<p class="text-slate-500 italic">No content written.</p>';
                
                const statusBadge = document.getElementById('view-status');
                statusBadge.classList.remove('hidden');
                if (page.status == 1) {
                    statusBadge.textContent = 'Active / Published';
                    statusBadge.className = 'px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
                } else {
                    statusBadge.textContent = 'Inactive / Draft';
                    statusBadge.className = 'px-2.5 py-1 text-xs font-bold rounded-full bg-slate-800 text-slate-400 border border-slate-750';
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('view-title').textContent = 'Error loading page data';
            });
    }

    function editPage(id) {
        document.getElementById('edit-page-form').action = `${baseUrl}/${id}/update`;
        document.getElementById('edit-page_name').value = '';
        document.getElementById('edit-page_type').value = '';
        document.getElementById('edit-description').value = '';
        document.getElementById('edit-status').value = '1';

        openModal('edit-page-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(page => {
                document.getElementById('edit-page_name').value = page.page_name;
                document.getElementById('edit-page_type').value = page.page_type;
                document.getElementById('edit-description').value = page.description || '';
                document.getElementById('edit-status').value = page.status;
            })
            .catch(err => console.error(err));
    }

    function togglePageStatus(id, checkbox) {
        fetch(`${baseUrl}/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-csrf_token': document.querySelector('meta[name="csrf_token"]').getAttribute('content')
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
            alert('An error occurred while communicating with the server.');
        });
    }

    function confirmDeletePage(id, name) {
        document.getElementById('delete-page-form').action = `${baseUrl}/${id}`;
        document.getElementById('delete-page-name-placeholder').textContent = `"${name.replace(/_/g, ' ')}"`;
        openModal('delete-page-modal');
    }
</script>
@endsection
