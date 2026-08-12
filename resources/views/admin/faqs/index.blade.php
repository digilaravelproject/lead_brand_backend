@extends('admin.layout')

@section('title', 'Manage FAQs')
@section('page_title', 'FAQ Management')

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Frequently Asked Questions</h1>
            <p class="text-xs text-slate-400 mt-0.5">Add, edit, structure, and toggle status of help questions for consumers.</p>
        </div>
        <div>
            <button onclick="openAddModal()" 
                    class="flex items-center justify-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-white shadow-md shadow-amber-500/10 hover:shadow-amber-500/20 rounded-xl transition-all font-semibold text-sm transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add FAQ</span>
            </button>
        </div>
    </div>

    <!-- FAQs Table Card -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-800 text-xs text-slate-400 font-semibold uppercase tracking-wider bg-slate-950/40">
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6 w-[35%]">Question</th>
                        <th class="py-4 px-6 w-[40%]">Answer</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-slate-300">
                    @forelse($faqs as $faq)
                        <tr class="hover:bg-slate-800/20 transition-colors" id="faq-row-{{ $faq->id }}">
                            <td class="py-4 px-6 font-mono text-xs text-slate-500">#{{ $faq->id }}</td>
                            <td class="py-4 px-6 font-semibold text-white">{{ $faq->question }}</td>
                            <td class="py-4 px-6 text-slate-400">
                                <div class="line-clamp-2 text-xs">{{ strip_tags($faq->answer) }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" onchange="toggleFaqStatus({{ $faq->id }}, this)" class="sr-only peer" {{ $faq->status == 1 ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-850 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-500 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 peer-checked:after:bg-white border border-slate-800 peer-checked:border-emerald-400"></div>
                                </label>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                <button onclick="viewFaq({{ $faq->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-amber-600/10 text-slate-400 hover:text-amber-400 transition-colors"
                                        title="View FAQ">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button onclick="editFaq({{ $faq->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-emerald-600/10 text-slate-400 hover:text-emerald-400 transition-colors"
                                        title="Edit FAQ">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button onclick="confirmDeleteFaq({{ $faq->id }}, '{{ addslashes($faq->question) }}')" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-red-600/10 text-slate-400 hover:text-red-400 transition-colors"
                                        title="Delete FAQ">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-500">No FAQ records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($faqs->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/20">
                {{ $faqs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add FAQ Modal -->
<div id="add-faq-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('add-faq-modal')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Create FAQ Record</h3>
            <button onclick="closeModal('add-faq-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form action="{{ route('admin.faqs.store') }}" method="POST" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            <div class="p-6 space-y-4 overflow-y-auto flex-1 scrollbar">
                <div>
                    <label for="add-question" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Question</label>
                    <input type="text" name="question" id="add-question" required placeholder="What is AdvisorX Pro?"
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-655 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                </div>

                <div>
                    <label for="add-answer" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Answer Description</label>
                    <textarea name="answer" id="add-answer" rows="5" required placeholder="AdvisorX Pro is a client administration platform..."
                              class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-655 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm resize-none"></textarea>
                </div>

                <div>
                    <label for="add-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Default Status</label>
                    <select name="status" id="add-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                        <option value="1">Active (Visible)</option>
                        <option value="0">Inactive (Hidden)</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
                <button type="button" onclick="closeModal('add-faq-modal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-700/60 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-sm font-semibold">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-white font-semibold text-sm">
                    Publish FAQ
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View FAQ Modal -->
<div id="view-faq-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('view-faq-modal')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">FAQ Record View</h3>
            <button onclick="closeModal('view-faq-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4 overflow-y-auto flex-1 scrollbar">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Question Inquiry</span>
                <h4 class="text-base font-bold text-white leading-snug" id="view-question">Loading...</h4>
            </div>

            <div class="space-y-1 border-t border-slate-800/80 pt-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Answer Statement</span>
                <div class="text-sm text-slate-300 leading-relaxed bg-slate-950/30 p-4 border border-slate-850 rounded-2xl whitespace-pre-wrap" id="view-answer">Loading...</div>
            </div>

            <div class="flex items-center justify-between border-t border-slate-800/80 pt-4">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Visible to Users?</span>
                <span id="view-status" class="px-2.5 py-1 text-xs font-bold rounded-full">Active</span>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end flex-shrink-0">
            <button onclick="closeModal('view-faq-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl">
                Close View
            </button>
        </div>
    </div>
</div>

<!-- Edit FAQ Modal -->
<div id="edit-faq-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('edit-faq-modal')"></div>
    <div class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Modify FAQ Record</h3>
            <button onclick="closeModal('edit-faq-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="edit-faq-form" action="" method="POST" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            <div class="p-6 space-y-4 overflow-y-auto flex-1 scrollbar">
                <div>
                    <label for="edit-question" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Question</label>
                    <input type="text" name="question" id="edit-question" required
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                </div>

                <div>
                    <label for="edit-answer" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Answer Description</label>
                    <textarea name="answer" id="edit-answer" rows="5" required
                              class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm resize-none"></textarea>
                </div>

                <div>
                    <label for="edit-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" id="edit-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                        <option value="1">Active (Visible)</option>
                        <option value="0">Inactive (Hidden)</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
                <button type="button" onclick="closeModal('edit-faq-modal')"
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


<!-- Delete FAQ Confirmation Modal -->
<div id="delete-faq-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('delete-faq-modal')"></div>
    <div class="relative w-full max-w-md bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl z-10 animate-fade-in">
        <div class="p-6 text-center space-y-4">
            <div class="h-14 w-14 bg-red-500/10 border border-red-500/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-2">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white">Permanently Remove FAQ?</h3>
            <p class="text-sm text-slate-400">Are you sure you want to delete this FAQ record? This action is permanent and cannot be reversed.</p>
            <div class="text-xs text-slate-500 italic p-3 bg-slate-950/30 border border-slate-850 rounded-xl" id="delete-faq-question-placeholder"></div>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end space-x-3">
            <button onclick="closeModal('delete-faq-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl">
                Abort
            </button>
            <form id="delete-faq-form" action="" method="POST" class="inline">
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
    const baseUrl = "{{ url('admin/faqs') }}";

    function openAddModal() {
        document.getElementById('add-question').value = '';
        document.getElementById('add-answer').value = '';
        document.getElementById('add-status').value = '1';
        openModal('add-faq-modal');
    }

    function viewFaq(id) {
        document.getElementById('view-question').textContent = 'Loading...';
        document.getElementById('view-answer').textContent = 'Loading...';
        document.getElementById('view-status').className = 'hidden';

        openModal('view-faq-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(faq => {
                document.getElementById('view-question').textContent = faq.question;
                document.getElementById('view-answer').textContent = faq.answer;
                
                const statusBadge = document.getElementById('view-status');
                statusBadge.classList.remove('hidden');
                if (faq.status == 1) {
                    statusBadge.textContent = 'Active / Visible';
                    statusBadge.className = 'px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
                } else {
                    statusBadge.textContent = 'Inactive / Hidden';
                    statusBadge.className = 'px-2.5 py-1 text-xs font-bold rounded-full bg-slate-800 text-slate-400 border border-slate-750';
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('view-question').textContent = 'Error loading FAQ data';
            });
    }

    function editFaq(id) {
        document.getElementById('edit-faq-form').action = `${baseUrl}/${id}/update`;
        document.getElementById('edit-question').value = '';
        document.getElementById('edit-answer').value = '';
        document.getElementById('edit-status').value = '1';

        openModal('edit-faq-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(faq => {
                document.getElementById('edit-question').value = faq.question;
                document.getElementById('edit-answer').value = faq.answer;
                document.getElementById('edit-status').value = faq.status;
            })
            .catch(err => console.error(err));
    }

    function toggleFaqStatus(id, checkbox) {
        // Optimistic UI state or just execute fetch
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
                // Show a mini temporary success feedback maybe?
                console.log(data.message);
            } else {
                // Revert checkbox state on failure
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

    function confirmDeleteFaq(id, question) {
        document.getElementById('delete-faq-form').action = `${baseUrl}/${id}`;
        document.getElementById('delete-faq-question-placeholder').textContent = `"${question}"`;
        openModal('delete-faq-modal');
    }
</script>
@endsection
