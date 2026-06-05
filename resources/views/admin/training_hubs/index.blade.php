@extends('admin.layout')

@section('title', 'Training Hub')
@section('page_title', 'Training Hub')

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Training Hub Resources</h1>
            <p class="text-xs text-slate-400 mt-0.5">Upload, manage, and inspect training guides (PDFs) and instructional clips (Videos).</p>
        </div>
        <div>
            <button onclick="openAddModal()" 
                    class="flex items-center justify-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white shadow-md shadow-indigo-500/10 hover:shadow-indigo-500/20 rounded-xl transition-all font-semibold text-sm transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Upload Media</span>
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
                        <th class="py-4 px-6">Category</th>
                        <th class="py-4 px-6">Type</th>
                        <th class="py-4 px-6 w-[35%]">Title</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-slate-300">
                    @forelse($trainings as $item)
                        <tr class="hover:bg-slate-800/20 transition-colors" id="training-row-{{ $item->id }}">
                            <td class="py-4 px-6 font-mono text-xs text-slate-500">#{{ $item->id }}</td>
                            <td class="py-4 px-6 text-indigo-400 font-bold text-xs uppercase">{{ $item->category->category_name }}</td>
                            <td class="py-4 px-6">
                                @if($item->type === 'pdf')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        PDF
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-500/10 text-sky-400 border border-sky-500/20">
                                        <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        VIDEO
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-semibold text-white">{{ $item->title }}</td>
                            <td class="py-4 px-6 text-center">
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" onchange="toggleTrainingStatus({{ $item->id }}, this)" class="sr-only peer" {{ $item->status == 1 ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-850 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-500 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 peer-checked:after:bg-white border border-slate-800 peer-checked:border-emerald-400"></div>
                                </label>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <button onclick="viewTraining({{ $item->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-indigo-600/10 text-slate-400 hover:text-indigo-400 transition-colors"
                                        title="Play/Read Media">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button onclick="editTraining({{ $item->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-emerald-600/10 text-slate-400 hover:text-emerald-400 transition-colors"
                                        title="Edit Details">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button onclick="confirmDeleteTraining({{ $item->id }}, '{{ addslashes($item->title) }}')" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-red-600/10 text-slate-400 hover:text-red-400 transition-colors"
                                        title="Delete Media">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-500">No training resources uploaded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($trainings->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/20">
                {{ $trainings->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Upload / Add Modal -->
<div id="add-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('add-modal')"></div>
    <form action="{{ route('admin.training-hubs.store') }}" method="POST" enctype="multipart/form-data" class="relative w-full max-w-xl bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        @csrf
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Upload Training Resources</h3>
            <button type="button" onclick="closeModal('add-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Form Body -->
        <div class="p-6 overflow-y-auto space-y-4 flex-1 scrollbar">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Training Category -->
                <div>
                    <label for="add-category" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Category <span class="text-red-500">*</span></label>
                    <select name="training_category_id" id="add-category" required class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                        <option value="" disabled selected>Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Media Type (Toggle Selector) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Media Format <span class="text-red-500">*</span></label>
                    <div class="flex bg-slate-950/60 border border-slate-800 rounded-xl p-1 relative">
                        <label class="flex-1 text-center py-1.5 text-xs font-bold text-slate-400 rounded-lg cursor-pointer transition-all hover:text-white" id="label-type-pdf">
                            <input type="radio" name="type" value="pdf" checked class="sr-only" onchange="toggleFormFileType('pdf')">
                            <span>PDF Guides</span>
                        </label>
                        <label class="flex-1 text-center py-1.5 text-xs font-bold text-slate-400 rounded-lg cursor-pointer transition-all hover:text-white" id="label-type-video">
                            <input type="radio" name="type" value="video" class="sr-only" onchange="toggleFormFileType('video')">
                            <span>Videos</span>
                        </label>
                        <!-- Floating bg -->
                        <div id="type-pill" class="absolute top-1 bottom-1 w-[48%] bg-indigo-600 rounded-lg transition-all duration-300 z-0"></div>
                        <span class="absolute inset-0 flex justify-between z-10 pointer-events-none">
                            <span class="flex-1 text-center py-2.5 text-xs font-bold text-white" id="text-pdf">PDF Guides</span>
                            <span class="flex-1 text-center py-2.5 text-xs font-bold text-slate-400" id="text-video">Videos</span>
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <label for="add-title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Resource Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="add-title" required placeholder="e.g. Sales Portal Tutorial"
                       class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-650 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
            </div>

            <!-- Language Selection Radio Buttons -->
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Select Language <span class="text-red-500">*</span></label>
                <div class="flex flex-wrap gap-4 items-center bg-slate-950/40 border border-slate-800 rounded-xl p-3">
                    <label class="inline-flex items-center text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <input type="radio" name="language" value="en" checked class="mr-2 h-4 w-4 text-indigo-600 border-slate-800 bg-slate-900 focus:ring-indigo-500/40" id="add-lang-en">
                        <span>English</span>
                    </label>
                    <label class="inline-flex items-center text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <input type="radio" name="language" value="mr" class="mr-2 h-4 w-4 text-indigo-600 border-slate-800 bg-slate-900 focus:ring-indigo-500/40" id="add-lang-mr">
                        <span>Marathi</span>
                    </label>
                    <label class="inline-flex items-center text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <input type="radio" name="language" value="hi" class="mr-2 h-4 w-4 text-indigo-600 border-slate-800 bg-slate-900 focus:ring-indigo-500/40" id="add-lang-hi">
                        <span>Hindi</span>
                    </label>
                    <label class="inline-flex items-center text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <input type="radio" name="language" value="gu" class="mr-2 h-4 w-4 text-indigo-600 border-slate-800 bg-slate-900 focus:ring-indigo-500/40" id="add-lang-gu">
                        <span>Gujrati</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="add-description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Summary / Instructions (Optional)</label>
                <textarea name="description" id="add-description" rows="3" placeholder="Brief outline of topics covered..."
                          class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-650 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm resize-none"></textarea>
            </div>

            <!-- Upload Field (multiple select) -->
            <div>
                <label for="add-files" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5" id="upload-label-text">Select PDF File(s) <span class="text-red-500">*</span></label>
                <input type="file" name="files[]" id="add-files" required multiple accept="application/pdf"
                       class="block w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                <p class="text-xs text-slate-500 mt-1.5" id="upload-help-text">You can select multiple PDFs. Suffixes (- Part 1, Part 2...) will be added automatically to their titles.</p>
            </div>

            <div>
                <label for="add-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Publish Status</label>
                <select name="status" id="add-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                    <option value="1">Active (Published)</option>
                    <option value="0">Inactive (Draft)</option>
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
                Upload & Publish
            </button>
        </div>
    </form>
</div>

<!-- View Modal -->
<div id="view-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeMediaModal()"></div>
    <div class="relative w-full max-w-3xl bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <div>
                <h3 class="text-lg font-bold text-white" id="view-title-text">Loading Media...</h3>
                <span class="text-xs text-indigo-400 font-bold uppercase tracking-wider" id="view-category-text">Category</span>
            </div>
            <button onclick="closeMediaModal()" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto space-y-4 flex-1 scrollbar">
            
            <!-- Video Player (Hidden by default) -->
            <div id="video-player-container" class="hidden">
                <video id="view-video-player" controls class="w-full max-h-[360px] rounded-2xl bg-black border border-slate-800" src=""></video>
            </div>

            <!-- PDF Viewer (Hidden by default) -->
            <div id="pdf-viewer-container" class="hidden">
                <iframe id="view-pdf-frame" src="" class="w-full h-[450px] rounded-2xl border border-slate-800 bg-slate-955"></iframe>
                <div class="mt-2 text-center">
                    <a id="view-pdf-link" href="" target="_blank" class="inline-flex items-center space-x-1 text-xs text-indigo-400 hover:text-indigo-300 font-semibold hover:underline">
                        <span>Open PDF in new tab</span>
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Instructions & Details</span>
                <div class="text-sm text-slate-300 leading-relaxed whitespace-pre-wrap" id="view-desc-text">-</div>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end flex-shrink-0">
            <button onclick="closeMediaModal()" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl">
                Close View
            </button>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('edit-modal')"></div>
    <form id="edit-form" action="" method="POST" enctype="multipart/form-data" class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        @csrf
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Modify Training Resource</h3>
            <button type="button" onclick="closeModal('edit-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Body -->
        <div class="p-6 overflow-y-auto space-y-4 flex-1 scrollbar">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Training Category -->
                <div>
                    <label for="edit-category" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Category <span class="text-red-500">*</span></label>
                    <select name="training_category_id" id="edit-category" required class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Media Type (ReadOnly during edit to preserve file type integrity) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Media Format</label>
                    <input type="hidden" name="type" id="edit-type-hidden">
                    <div class="w-full bg-slate-950/40 border border-slate-800/60 text-slate-450 rounded-xl py-2.5 px-4 text-xs font-bold uppercase tracking-wider" id="edit-type-text">
                        PDF
                    </div>
                </div>
            </div>

            <div>
                <label for="edit-title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Resource Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="edit-title" required
                       class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
            </div>

            <div>
                <label for="edit-description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Summary / Instructions</label>
                <textarea name="description" id="edit-description" rows="3"
                          class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm resize-none"></textarea>
            </div>

            <!-- Optional File Replacement -->
            <div>
                <label for="edit-file" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5" id="edit-upload-label">Replace File (Optional)</label>
                <input type="file" name="file" id="edit-file"
                       class="block w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                <p class="text-xs text-slate-500 mt-1.5">Leave blank to keep existing file. Uploading replaces the old file.</p>
                <span class="block text-xs text-indigo-400 truncate mt-1 font-mono" id="edit-current-file-text"></span>
            </div>

            <div>
                <label for="edit-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Publish Status</label>
                <select name="status" id="edit-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                    <option value="1">Active (Published)</option>
                    <option value="0">Inactive (Draft)</option>
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
            <h3 class="text-lg font-bold text-white">Permanently Remove Media?</h3>
            <p class="text-sm text-slate-400">Are you sure you want to delete this resource? The file will be deleted permanently from the storage folder.</p>
            <div class="text-xs text-slate-500 italic p-3 bg-slate-950/30 border border-slate-850 rounded-xl" id="delete-title-placeholder"></div>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end space-x-3">
            <button onclick="closeModal('delete-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl">
                Abort
            </button>
            <form id="delete-form" action="" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-red-950/30">
                    Delete Resource
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const baseUrl = "{{ url('admin/training-hubs') }}";

    function toggleFormFileType(type) {
        const pill = document.getElementById('type-pill');
        const textPdf = document.getElementById('text-pdf');
        const textVideo = document.getElementById('text-video');
        const fileInput = document.getElementById('add-files');
        const labelText = document.getElementById('upload-label-text');
        const helpText = document.getElementById('upload-help-text');

        if (type === 'pdf') {
            pill.style.left = '4px';
            textPdf.className = 'flex-1 text-center py-2.5 text-xs font-bold text-white';
            textVideo.className = 'flex-1 text-center py-2.5 text-xs font-bold text-slate-455';
            
            fileInput.accept = 'application/pdf';
            labelText.textContent = 'Select PDF File(s) *';
            helpText.textContent = 'You can select multiple PDFs. Suffixes (- Part 1, Part 2...) will be added automatically to their titles.';
        } else {
            pill.style.left = '50%';
            textPdf.className = 'flex-1 text-center py-2.5 text-xs font-bold text-slate-455';
            textVideo.className = 'flex-1 text-center py-2.5 text-xs font-bold text-white';
            
            fileInput.accept = 'video/*';
            labelText.textContent = 'Select Video File(s) *';
            helpText.textContent = 'You can select multiple videos. Suffixes (- Part 1, Part 2...) will be added automatically to their titles.';
        }
    }

    function openAddModal() {
        document.getElementById('add-category').value = '';
        document.getElementById('add-title').value = '';
        document.getElementById('add-description').value = '';
        document.getElementById('add-files').value = '';
        document.getElementById('add-status').value = '1';
        
        // Reset toggle radios
        document.querySelector('input[name="type"][value="pdf"]').checked = true;
        toggleFormFileType('pdf');

        openModal('add-modal');
    }

    function viewTraining(id) {
        document.getElementById('view-title-text').textContent = 'Loading Media...';
        document.getElementById('view-category-text').textContent = 'Category';
        document.getElementById('view-desc-text').textContent = '-';
        
        // Hide viewers
        document.getElementById('video-player-container').classList.add('hidden');
        document.getElementById('pdf-viewer-container').classList.add('hidden');
        
        // Pause/stop video player source
        const player = document.getElementById('view-video-player');
        player.pause();
        player.src = '';

        openModal('view-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(item => {
                document.getElementById('view-title-text').textContent = item.title;
                document.getElementById('view-category-text').textContent = item.category.category_name;
                document.getElementById('view-desc-text').textContent = item.description || 'No summary description provided.';

                const assetUrl = `{{ asset('') }}${item.file_path}`;

                if (item.type === 'video') {
                    document.getElementById('video-player-container').classList.remove('hidden');
                    player.src = assetUrl;
                    player.load();
                } else {
                    document.getElementById('pdf-viewer-container').classList.remove('hidden');
                    document.getElementById('view-pdf-frame').src = assetUrl;
                    document.getElementById('view-pdf-link').href = assetUrl;
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('view-title-text').textContent = 'Error loading media data';
            });
    }

    function closeMediaModal() {
        const player = document.getElementById('view-video-player');
        player.pause();
        player.src = '';
        closeModal('view-modal');
    }

    function editTraining(id) {
        document.getElementById('edit-form').action = `${baseUrl}/${id}/update`;
        document.getElementById('edit-category').value = '';
        document.getElementById('edit-title').value = '';
        document.getElementById('edit-description').value = '';
        document.getElementById('edit-file').value = '';
        document.getElementById('edit-current-file-text').textContent = '';

        openModal('edit-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(item => {
                document.getElementById('edit-category').value = item.training_category_id;
                document.getElementById('edit-title').value = item.title;
                document.getElementById('edit-description').value = item.description || '';
                document.getElementById('edit-status').value = item.status;
                
                document.getElementById('edit-type-hidden').value = item.type;
                document.getElementById('edit-type-text').textContent = item.type.toUpperCase();

                const editUploadLabel = document.getElementById('edit-upload-label');
                const fileInput = document.getElementById('edit-file');
                
                if (item.type === 'pdf') {
                    fileInput.accept = 'application/pdf';
                    editUploadLabel.textContent = 'Replace PDF File (Optional)';
                } else {
                    fileInput.accept = 'video/*';
                    editUploadLabel.textContent = 'Replace Video File (Optional)';
                }

                document.getElementById('edit-lang-en').checked = (item.language === 'en');
                document.getElementById('edit-lang-mr').checked = (item.language === 'mr');
                document.getElementById('edit-lang-hi').checked = (item.language === 'hi');
                document.getElementById('edit-lang-gu').checked = (item.language === 'gu');

                document.getElementById('edit-current-file-text').textContent = `Current: ${item.file_path.split('/').pop()}`;
            })
            .catch(err => console.error(err));
    }

    function toggleTrainingStatus(id, checkbox) {
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

    function confirmDeleteTraining(id, title) {
        document.getElementById('delete-form').action = `${baseUrl}/${id}`;
        document.getElementById('delete-title-placeholder').textContent = `"${title}"`;
        openModal('delete-modal');
    }
</script>
@endsection
