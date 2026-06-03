@extends('admin.layout')

@section('title', 'Business Tools')
@section('page_title', 'Business Tools')

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Business Tools Modules</h1>
            <p class="text-xs text-slate-400 mt-0.5">Manage premium business tools, create subtools under them, and upload customized marketing materials (images and videos).</p>
        </div>
        <div>
            <button onclick="openAddModal()" 
                    class="flex items-center justify-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white shadow-md shadow-indigo-500/10 hover:shadow-indigo-500/20 rounded-xl transition-all font-semibold text-sm transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Create New Tool</span>
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
                        <th class="py-4 px-6">Branding</th>
                        <th class="py-4 px-6 w-[35%]">Tool Title</th>
                        <th class="py-4 px-6">Brief Description</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-slate-300">
                    @forelse($tools as $tool)
                        <tr class="hover:bg-slate-800/20 transition-colors" id="tool-row-{{ $tool->id }}">
                            <td class="py-4 px-6 font-mono text-xs text-slate-500">#{{ $tool->id }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 bg-indigo-600/10 border border-indigo-500/20 text-indigo-400 rounded-xl flex items-center justify-center font-bold">
                                        @if($tool->icon == 'combo_plans')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                                        @elseif($tool->icon == 'combo_posters')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @elseif($tool->icon == 'concept_brochures')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        @elseif($tool->icon == 'lic_plans')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        @elseif($tool->icon == 'agent_recruitment')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                        @elseif($tool->icon == 'promotional_videos')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @elseif($tool->icon == 'create_video_ads')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        @else
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 font-semibold text-white">{{ $tool->title }}</td>
                            <td class="py-4 px-6 text-xs text-slate-400">{{ $tool->description ?: 'No description provided' }}</td>
                            <td class="py-4 px-6 text-center">
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" onchange="toggleToolStatus({{ $tool->id }}, this)" class="sr-only peer" {{ $tool->status == 1 ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-850 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-500 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 peer-checked:after:bg-white border border-slate-800 peer-checked:border-emerald-400"></div>
                                </label>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('admin.tools.manage', $tool->id) }}" 
                                   class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-indigo-600/10 text-slate-400 hover:text-indigo-400 transition-colors"
                                   title="Manage Subtools & Media">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                </a>
                                <button onclick="editTool({{ $tool->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-emerald-600/10 text-slate-400 hover:text-emerald-400 transition-colors"
                                        title="Edit Tool Info">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button onclick="confirmDeleteTool({{ $tool->id }}, '{{ addslashes($tool->title) }}')" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-red-600/10 text-slate-400 hover:text-red-400 transition-colors"
                                        title="Delete Tool Module">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-500">No Business Tools configured.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($tools->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/20">
                {{ $tools->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Modal -->
<div id="add-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('add-modal')"></div>
    <form action="{{ route('admin.tools.store') }}" method="POST" class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        @csrf
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Create Business Tool Module</h3>
            <button type="button" onclick="closeModal('add-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Form Body -->
        <div class="p-6 overflow-y-auto space-y-4 flex-1 scrollbar">
            <div>
                <label for="add-title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Tool Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="add-title" required placeholder="e.g. Combo Plans"
                       class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-655 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
            </div>

            <div>
                <label for="add-description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Description Context (Optional)</label>
                <textarea name="description" id="add-description" rows="3" placeholder="Brief outline of what this tool contains..."
                          class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-655 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm resize-none"></textarea>
            </div>

            <div>
                <label for="add-icon" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Branding Icon Symbol</label>
                <select name="icon" id="add-icon" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                    <option value="combo_plans">Combo Plans (Stacked layers)</option>
                    <option value="combo_posters">Combo Posters (Image Grid Layout)</option>
                    <option value="concept_brochures">Concept Brochures (Open Book)</option>
                    <option value="lic_plans">LIC Plans (Text Document)</option>
                    <option value="agent_recruitment">Agent Recruitment (Profile Plus User)</option>
                    <option value="promotional_videos">Promotional Videos (Play Button Circle)</option>
                    <option value="create_video_ads">Video Ads (Video Camera)</option>
                    <option value="create_pdf_calendar">PDF Calendar (Calendar Grid)</option>
                </select>
            </div>

            <div>
                <label for="add-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                <select name="status" id="add-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
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
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold text-sm">
                Create Tool
            </button>
        </div>
    </form>
</div>

<!-- Edit Modal -->
<div id="edit-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('edit-modal')"></div>
    <form id="edit-form" action="" method="POST" class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        @csrf
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Modify Business Tool</h3>
            <button type="button" onclick="closeModal('edit-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Form Body -->
        <div class="p-6 overflow-y-auto space-y-4 flex-1 scrollbar">
            <div>
                <label for="edit-title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Tool Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="edit-title" required
                       class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
            </div>

            <div>
                <label for="edit-description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Description Context</label>
                <textarea name="description" id="edit-description" rows="3"
                          class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm resize-none"></textarea>
            </div>

            <div>
                <label for="edit-icon" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Branding Icon Symbol</label>
                <select name="icon" id="edit-icon" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                    <option value="combo_plans">Combo Plans (Stacked layers)</option>
                    <option value="combo_posters">Combo Posters (Image Grid Layout)</option>
                    <option value="concept_brochures">Concept Brochures (Open Book)</option>
                    <option value="lic_plans">LIC Plans (Text Document)</option>
                    <option value="agent_recruitment">Agent Recruitment (Profile Plus User)</option>
                    <option value="promotional_videos">Promotional Videos (Play Button Circle)</option>
                    <option value="create_video_ads">Video Ads (Video Camera)</option>
                    <option value="create_pdf_calendar">PDF Calendar (Calendar Grid)</option>
                </select>
            </div>

            <div>
                <label for="edit-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                <select name="status" id="edit-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
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
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold text-sm">
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
            <h3 class="text-lg font-bold text-white">Permanently Remove Tool?</h3>
            <p class="text-sm text-slate-400">Are you sure you want to delete this tool? All subtools and uploaded files belonging to this tool will also be permanently deleted from database and server storage.</p>
            <div class="text-xs text-slate-500 italic p-3 bg-slate-950/30 border border-slate-850 rounded-xl" id="delete-title-placeholder"></div>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end space-x-3">
            <button onclick="closeModal('delete-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl">
                Abort
            </button>
            <form id="delete-form" action="" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-red-950/30">
                    Delete Tool
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const baseUrl = "{{ url('admin/tools') }}";

    function openAddModal() {
        document.getElementById('add-title').value = '';
        document.getElementById('add-description').value = '';
        document.getElementById('add-icon').value = 'combo_plans';
        document.getElementById('add-status').value = '1';
        openModal('add-modal');
    }

    function editTool(id) {
        document.getElementById('edit-form').action = `${baseUrl}/${id}/update`;
        document.getElementById('edit-title').value = '';
        document.getElementById('edit-description').value = '';
        document.getElementById('edit-icon').value = 'combo_plans';

        openModal('edit-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(tool => {
                document.getElementById('edit-title').value = tool.title;
                document.getElementById('edit-description').value = tool.description || '';
                document.getElementById('edit-icon').value = tool.icon || 'combo_plans';
                document.getElementById('edit-status').value = tool.status;
            })
            .catch(err => console.error(err));
    }

    function toggleToolStatus(id, checkbox) {
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

    function confirmDeleteTool(id, title) {
        document.getElementById('delete-form').action = `${baseUrl}/${id}`;
        document.getElementById('delete-title-placeholder').textContent = `"${title}"`;
        openModal('delete-modal');
    }
</script>
@endsection
