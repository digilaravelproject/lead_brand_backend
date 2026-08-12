@extends('admin.layout')

@section('title', 'Training Categories')
@section('page_title', 'Training Categories')

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Training Categories</h1>
            <p class="text-xs text-slate-400 mt-0.5">Define subject categories to group training resources in the Training Hub.</p>
        </div>
        <div>
            <button onclick="openAddModal()" 
                    class="flex items-center justify-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-white shadow-md shadow-amber-500/10 hover:shadow-amber-500/20 rounded-xl transition-all font-semibold text-sm transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Category</span>
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-800 text-xs text-slate-400 font-semibold uppercase tracking-wider bg-slate-950/40">
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6 w-[35%]">Category Name</th>
                        <th class="py-4 px-6 w-[45%]">Description</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-slate-300">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-800/20 transition-colors" id="category-row-{{ $category->id }}">
                            <td class="py-4 px-6 font-mono text-xs text-slate-500">#{{ $category->id }}</td>
                            <td class="py-4 px-6 font-semibold text-white">{{ $category->category_name }}</td>
                            <td class="py-4 px-6 text-xs text-slate-400">{{ $category->description ?: 'No description provided' }}</td>
                            <td class="py-4 px-6 text-center">
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" onchange="toggleCategoryStatus({{ $category->id }}, this)" class="sr-only peer" {{ $category->status == 1 ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-850 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-500 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 peer-checked:after:bg-white border border-slate-800 peer-checked:border-emerald-400"></div>
                                </label>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                <button onclick="viewCategory({{ $category->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-amber-600/10 text-slate-400 hover:text-amber-400 transition-colors"
                                        title="View Details">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button onclick="editCategory({{ $category->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-emerald-600/10 text-slate-400 hover:text-emerald-400 transition-colors"
                                        title="Edit Category">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button onclick="confirmDeleteCategory({{ $category->id }}, '{{ addslashes($category->category_name) }}')" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-red-600/10 text-slate-400 hover:text-red-400 transition-colors"
                                        title="Delete Category">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-500">No training categories defined.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/20">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Modal -->
<div id="add-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('add-modal')"></div>
    <form action="{{ route('admin.training-categories.store') }}" method="POST" class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        @csrf
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Create Training Category</h3>
            <button type="button" onclick="closeModal('add-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Form Body -->
        <div class="p-6 overflow-y-auto space-y-4 flex-1 scrollbar">
            <div>
                <label for="add-name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Category Name <span class="text-red-500">*</span></label>
                <input type="text" name="category_name" id="add-name" required placeholder="e.g. System Onboarding"
                       class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-650 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
            </div>

            <div>
                <label for="add-description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Description (Optional)</label>
                <textarea name="description" id="add-description" rows="4" placeholder="Brief explanation of this training group..."
                          class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-650 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm resize-none"></textarea>
            </div>

            <div>
                <label for="add-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                <select name="status" id="add-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                    <option value="1">Active (Enabled)</option>
                    <option value="0">Inactive (Disabled)</option>
                </select>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
            <button type="button" onclick="closeModal('add-modal')"
                    class="px-5 py-2.5 rounded-xl border border-slate-700/60 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-sm font-semibold">
                Cancel
            </button>
            <button type="submit" 
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-white font-semibold text-sm">
                Create Category
            </button>
        </div>
    </form>
</div>

<!-- View Modal -->
<div id="view-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('view-modal')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Category Details</h3>
            <button onclick="closeModal('view-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <!-- Body -->
        <div class="p-6 overflow-y-auto space-y-4 flex-1 scrollbar">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Category Title</span>
                <h4 class="text-base font-bold text-white" id="view-name-text">Loading...</h4>
            </div>

            <div class="space-y-1 border-t border-slate-800/80 pt-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Description Context</span>
                <div class="text-sm text-slate-300 leading-relaxed bg-slate-950/30 p-4 border border-slate-850 rounded-2xl whitespace-pre-wrap" id="view-desc-text">-</div>
            </div>

            <div class="flex items-center justify-between border-t border-slate-800/80 pt-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Status State</span>
                <span id="view-status-badge" class="px-2.5 py-1 text-xs font-bold rounded-full">Active</span>
            </div>
        </div>
        <!-- Footer -->
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end flex-shrink-0">
            <button onclick="closeModal('view-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl">
                Close View
            </button>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('edit-modal')"></div>
    <form id="edit-form" action="" method="POST" class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        @csrf
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Modify Training Category</h3>
            <button type="button" onclick="closeModal('edit-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Form Body -->
        <div class="p-6 overflow-y-auto space-y-4 flex-1 scrollbar">
            <div>
                <label for="edit-name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Category Name <span class="text-red-500">*</span></label>
                <input type="text" name="category_name" id="edit-name-input" required
                       class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
            </div>

            <div>
                <label for="edit-description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Description (Optional)</label>
                <textarea name="description" id="edit-desc-input" rows="4"
                          class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm resize-none"></textarea>
            </div>

            <div>
                <label for="edit-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                <select name="status" id="edit-status-select" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                    <option value="1">Active (Enabled)</option>
                    <option value="0">Inactive (Disabled)</option>
                </select>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
            <button type="button" onclick="closeModal('edit-modal')"
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

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('delete-modal')"></div>
    <div class="relative w-full max-w-md bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl z-10 animate-fade-in">
        <div class="p-6 text-center space-y-4">
            <div class="h-14 w-14 bg-red-500/10 border border-red-500/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-2">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white">Permanently Remove Category?</h3>
            <p class="text-sm text-slate-400">Are you sure you want to delete this category? All associated training hub videos and PDFs belonging to this category will also be deleted.</p>
            <div class="text-xs text-slate-500 italic p-3 bg-slate-950/30 border border-slate-850 rounded-xl" id="delete-name-placeholder"></div>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end space-x-3">
            <button onclick="closeModal('delete-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl">
                Abort
            </button>
            <form id="delete-form" action="" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-red-950/30">
                    Delete Category
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const baseUrl = "{{ url('admin/training-categories') }}";

    function openAddModal() {
        document.getElementById('add-name').value = '';
        document.getElementById('add-description').value = '';
        document.getElementById('add-status').value = '1';
        openModal('add-modal');
    }

    function viewCategory(id) {
        document.getElementById('view-name-text').textContent = 'Loading...';
        document.getElementById('view-desc-text').textContent = 'Loading...';
        document.getElementById('view-status-badge').className = 'hidden';

        openModal('view-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(category => {
                document.getElementById('view-name-text').textContent = category.category_name;
                document.getElementById('view-desc-text').textContent = category.description || 'No description provided';
                
                const statusBadge = document.getElementById('view-status-badge');
                statusBadge.classList.remove('hidden');
                if (category.status == 1) {
                    statusBadge.textContent = 'Active / Enabled';
                    statusBadge.className = 'px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
                } else {
                    statusBadge.textContent = 'Inactive / Disabled';
                    statusBadge.className = 'px-2.5 py-1 text-xs font-bold rounded-full bg-slate-800 text-slate-400 border border-slate-750';
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('view-name-text').textContent = 'Error loading category data';
            });
    }

    function editCategory(id) {
        document.getElementById('edit-form').action = `${baseUrl}/${id}/update`;
        document.getElementById('edit-name-input').value = '';
        document.getElementById('edit-desc-input').value = '';
        document.getElementById('edit-status-select').value = '1';

        openModal('edit-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(category => {
                document.getElementById('edit-name-input').value = category.category_name;
                document.getElementById('edit-desc-input').value = category.description || '';
                document.getElementById('edit-status-select').value = category.status;
            })
            .catch(err => console.error(err));
    }

    function toggleCategoryStatus(id, checkbox) {
        fetch(`${baseUrl}/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
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

    function confirmDeleteCategory(id, name) {
        document.getElementById('delete-form').action = `${baseUrl}/${id}`;
        document.getElementById('delete-name-placeholder').textContent = `"${name}"`;
        openModal('delete-modal');
    }
</script>
@endsection
