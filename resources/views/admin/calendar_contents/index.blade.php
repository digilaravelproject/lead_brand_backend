@extends('admin.layout')

@section('title', 'Manage Calendar Contents')
@section('page_title', 'Calendar Content Management')

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Calendar Contents</h1>
            <p class="text-xs text-slate-400 mt-0.5">Manage yearly calendars and upload PDFs in multiple languages (English, Hindi, Gujarati, Marathi, Bengali, Telugu, Tamil, Kannada, Panjabi).</p>
        </div>
        <div>
            <button onclick="openAddModal()" 
                    class="flex items-center justify-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white shadow-md shadow-indigo-500/10 hover:shadow-indigo-500/20 rounded-xl transition-all font-semibold text-sm transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Calendar Year</span>
            </button>
        </div>
    </div>

    <!-- Calendar Years Table Card -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-800 text-xs text-slate-400 font-semibold uppercase tracking-wider bg-slate-950/40">
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6">Year</th>
                        <th class="py-4 px-6 w-[45%]">Available Translations (PDFs)</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-slate-300">
                    @forelse($years as $year)
                        <tr class="hover:bg-slate-800/20 transition-colors" id="year-row-{{ $year->id }}">
                            <td class="py-4 px-6 font-mono text-xs text-slate-500">#{{ $year->id }}</td>
                            <td class="py-4 px-6 font-bold text-white text-base">{{ $year->year }}</td>
                            <td class="py-4 px-6 text-slate-400">
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $langs = [
                                            'en' => ['name' => 'English', 'color' => 'indigo'],
                                            'hi' => ['name' => 'Hindi', 'color' => 'red'],
                                            'gu' => ['name' => 'Gujarati', 'color' => 'amber'],
                                            'mr' => ['name' => 'Marathi', 'color' => 'emerald'],
                                            'bn' => ['name' => 'Bengali', 'color' => 'teal'],
                                            'te' => ['name' => 'Telugu', 'color' => 'cyan'],
                                            'ta' => ['name' => 'Tamil', 'color' => 'sky'],
                                            'kn' => ['name' => 'Kannada', 'color' => 'pink'],
                                            'pa' => ['name' => 'Panjabi', 'color' => 'violet']
                                        ];
                                    @endphp

                                    @foreach($langs as $code => $info)
                                        @php
                                            $content = $year->contents->firstWhere('language', $code);
                                        @endphp
                                        @if($content)
                                            <a href="{{ asset($content->pdf_path) }}" target="_blank" 
                                               class="inline-flex items-center space-x-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg bg-{{ $info['color'] }}-500/10 text-{{ $info['color'] }}-400 border border-{{ $info['color'] }}-500/20 hover:bg-{{ $info['color'] }}-500/20 transition-all"
                                               title="View {{ $info['name'] }} PDF">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ strtoupper($code) }}</span>
                                            </a>
                                        @else
                                            <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-800 text-slate-500 border border-slate-750 select-none cursor-default"
                                                  title="{{ $info['name'] }} PDF not uploaded">
                                                <span>{{ strtoupper($code) }}</span>
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <label class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" onchange="toggleYearStatus({{ $year->id }}, this)" class="sr-only peer" {{ $year->status == 1 ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-slate-850 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-500 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 peer-checked:after:bg-white border border-slate-800 peer-checked:border-emerald-400"></div>
                                </label>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <button onclick="editYear({{ $year->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-emerald-600/10 text-slate-400 hover:text-emerald-400 transition-colors cursor-pointer"
                                        title="Edit Calendar Year and PDFs">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button onclick="confirmDeleteYear({{ $year->id }}, '{{ $year->year }}')" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-red-600/10 text-slate-400 hover:text-red-400 transition-colors cursor-pointer"
                                        title="Delete Calendar Year">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-500">No Calendar Years configured.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($years->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/20">
                {{ $years->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Calendar Year Modal -->
<div id="add-year-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('add-year-modal')"></div>
    <div class="relative w-full max-w-xl bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Create Calendar Year</h3>
            <button onclick="closeModal('add-year-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form action="{{ route('admin.calendar-contents.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            <div class="p-6 space-y-5 overflow-y-auto flex-1 scrollbar">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="add-year" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Calendar Year</label>
                        <input type="number" name="year" id="add-year" required placeholder="e.g. {{ date('Y') }}" min="2000" max="2100"
                               class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                    </div>
                    <div>
                        <label for="add-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" id="add-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-350 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                            <option value="1">Active (Published)</option>
                            <option value="0">Inactive (Hidden)</option>
                        </select>
                    </div>
                </div>

                <div class="border-t border-slate-800/80 pt-4">
                    <h4 class="text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-3">Upload PDFs (Max 50MB per file)</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="add-pdf-en" class="block text-xs font-medium text-slate-400 mb-1">English PDF</label>
                            <input type="file" name="pdf_en" id="add-pdf-en" accept=".pdf"
                                   class="block w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                        </div>
                        <div>
                            <label for="add-pdf-hi" class="block text-xs font-medium text-slate-400 mb-1">Hindi PDF</label>
                            <input type="file" name="pdf_hi" id="add-pdf-hi" accept=".pdf"
                                   class="block w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                        </div>
                        <div>
                            <label for="add-pdf-gu" class="block text-xs font-medium text-slate-400 mb-1">Gujarati PDF</label>
                            <input type="file" name="pdf_gu" id="add-pdf-gu" accept=".pdf"
                                   class="block w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                        </div>
                        <div>
                            <label for="add-pdf-mr" class="block text-xs font-medium text-slate-400 mb-1">Marathi PDF</label>
                            <input type="file" name="pdf_mr" id="add-pdf-mr" accept=".pdf"
                                   class="block w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                        </div>
                        <div>
                            <label for="add-pdf-bn" class="block text-xs font-medium text-slate-400 mb-1">Bengali PDF</label>
                            <input type="file" name="pdf_bn" id="add-pdf-bn" accept=".pdf"
                                   class="block w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                        </div>
                        <div>
                            <label for="add-pdf-te" class="block text-xs font-medium text-slate-400 mb-1">Telugu PDF</label>
                            <input type="file" name="pdf_te" id="add-pdf-te" accept=".pdf"
                                   class="block w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                        </div>
                        <div>
                            <label for="add-pdf-ta" class="block text-xs font-medium text-slate-400 mb-1">Tamil PDF</label>
                            <input type="file" name="pdf_ta" id="add-pdf-ta" accept=".pdf"
                                   class="block w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                        </div>
                        <div>
                            <label for="add-pdf-kn" class="block text-xs font-medium text-slate-400 mb-1">Kannada PDF</label>
                            <input type="file" name="pdf_kn" id="add-pdf-kn" accept=".pdf"
                                   class="block w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                        </div>
                        <div>
                            <label for="add-pdf-pa" class="block text-xs font-medium text-slate-400 mb-1">Panjabi PDF</label>
                            <input type="file" name="pdf_pa" id="add-pdf-pa" accept=".pdf"
                                   class="block w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
                <button type="button" onclick="closeModal('add-year-modal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-700/60 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-sm font-semibold cursor-pointer">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold text-sm cursor-pointer shadow-md shadow-indigo-500/10">
                    Publish Year
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Calendar Year Modal -->
<div id="edit-year-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('edit-year-modal')"></div>
    <div class="relative w-full max-w-xl bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Modify Calendar Year</h3>
            <button onclick="closeModal('edit-year-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="edit-year-form" action="" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            <div class="p-6 space-y-5 overflow-y-auto flex-1 scrollbar">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit-year" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Calendar Year</label>
                        <input type="number" name="year" id="edit-year" required min="2000" max="2100"
                               class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                    </div>
                    <div>
                        <label for="edit-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" id="edit-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-350 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                            <option value="1">Active (Published)</option>
                            <option value="0">Inactive (Hidden)</option>
                        </select>
                    </div>
                </div>

                <div class="border-t border-slate-800/80 pt-4">
                    <h4 class="text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-3">Upload / Overwrite PDFs</h4>
                    
                    <div class="space-y-4">
                        <!-- English -->
                        <div class="p-3 bg-slate-950/30 rounded-xl border border-slate-850 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="space-y-0.5">
                                <label for="edit-pdf-en" class="block text-xs font-bold text-slate-200">English Language PDF</label>
                                <span id="current-pdf-en-label" class="block text-[10px] text-slate-500 italic">No file uploaded</span>
                            </div>
                            <div>
                                <input type="file" name="pdf_en" id="edit-pdf-en" accept=".pdf"
                                       class="block w-full text-xs text-slate-400 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                            </div>
                        </div>

                        <!-- Hindi -->
                        <div class="p-3 bg-slate-950/30 rounded-xl border border-slate-850 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="space-y-0.5">
                                <label for="edit-pdf-hi" class="block text-xs font-bold text-slate-200">Hindi Language PDF</label>
                                <span id="current-pdf-hi-label" class="block text-[10px] text-slate-500 italic">No file uploaded</span>
                            </div>
                            <div>
                                <input type="file" name="pdf_hi" id="edit-pdf-hi" accept=".pdf"
                                       class="block w-full text-xs text-slate-400 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                            </div>
                        </div>

                        <!-- Gujarati -->
                        <div class="p-3 bg-slate-950/30 rounded-xl border border-slate-850 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="space-y-0.5">
                                <label for="edit-pdf-gu" class="block text-xs font-bold text-slate-200">Gujarati Language PDF</label>
                                <span id="current-pdf-gu-label" class="block text-[10px] text-slate-500 italic">No file uploaded</span>
                            </div>
                            <div>
                                <input type="file" name="pdf_gu" id="edit-pdf-gu" accept=".pdf"
                                       class="block w-full text-xs text-slate-400 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                            </div>
                        </div>

                        <!-- Marathi -->
                        <div class="p-3 bg-slate-950/30 rounded-xl border border-slate-850 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="space-y-0.5">
                                <label for="edit-pdf-mr" class="block text-xs font-bold text-slate-200">Marathi Language PDF</label>
                                <span id="current-pdf-mr-label" class="block text-[10px] text-slate-500 italic">No file uploaded</span>
                            </div>
                            <div>
                                <input type="file" name="pdf_mr" id="edit-pdf-mr" accept=".pdf"
                                       class="block w-full text-xs text-slate-400 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                            </div>
                        </div>

                        <!-- Bengali -->
                        <div class="p-3 bg-slate-950/30 rounded-xl border border-slate-850 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="space-y-0.5">
                                <label for="edit-pdf-bn" class="block text-xs font-bold text-slate-200">Bengali Language PDF</label>
                                <span id="current-pdf-bn-label" class="block text-[10px] text-slate-500 italic">No file uploaded</span>
                            </div>
                            <div>
                                <input type="file" name="pdf_bn" id="edit-pdf-bn" accept=".pdf"
                                       class="block w-full text-xs text-slate-400 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                            </div>
                        </div>

                        <!-- Telugu -->
                        <div class="p-3 bg-slate-950/30 rounded-xl border border-slate-850 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="space-y-0.5">
                                <label for="edit-pdf-te" class="block text-xs font-bold text-slate-200">Telugu Language PDF</label>
                                <span id="current-pdf-te-label" class="block text-[10px] text-slate-500 italic">No file uploaded</span>
                            </div>
                            <div>
                                <input type="file" name="pdf_te" id="edit-pdf-te" accept=".pdf"
                                       class="block w-full text-xs text-slate-400 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                            </div>
                        </div>

                        <!-- Tamil -->
                        <div class="p-3 bg-slate-950/30 rounded-xl border border-slate-850 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="space-y-0.5">
                                <label for="edit-pdf-ta" class="block text-xs font-bold text-slate-200">Tamil Language PDF</label>
                                <span id="current-pdf-ta-label" class="block text-[10px] text-slate-500 italic">No file uploaded</span>
                            </div>
                            <div>
                                <input type="file" name="pdf_ta" id="edit-pdf-ta" accept=".pdf"
                                       class="block w-full text-xs text-slate-400 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                            </div>
                        </div>

                        <!-- Kannada -->
                        <div class="p-3 bg-slate-950/30 rounded-xl border border-slate-850 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="space-y-0.5">
                                <label for="edit-pdf-kn" class="block text-xs font-bold text-slate-200">Kannada Language PDF</label>
                                <span id="current-pdf-kn-label" class="block text-[10px] text-slate-500 italic">No file uploaded</span>
                            </div>
                            <div>
                                <input type="file" name="pdf_kn" id="edit-pdf-kn" accept=".pdf"
                                       class="block w-full text-xs text-slate-400 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                            </div>
                        </div>

                        <!-- Panjabi -->
                        <div class="p-3 bg-slate-950/30 rounded-xl border border-slate-850 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="space-y-0.5">
                                <label for="edit-pdf-pa" class="block text-xs font-bold text-slate-200">Panjabi Language PDF</label>
                                <span id="current-pdf-pa-label" class="block text-[10px] text-slate-500 italic">No file uploaded</span>
                            </div>
                            <div>
                                <input type="file" name="pdf_pa" id="edit-pdf-pa" accept=".pdf"
                                       class="block w-full text-xs text-slate-400 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
                <button type="button" onclick="closeModal('edit-year-modal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-700/60 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-sm font-semibold cursor-pointer">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold text-sm cursor-pointer shadow-md shadow-indigo-500/10">
                    Save Updates
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Calendar Year Confirmation Modal -->
<div id="delete-year-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('delete-year-modal')"></div>
    <div class="relative w-full max-w-md bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl z-10 animate-fade-in">
        <div class="p-6 text-center space-y-4">
            <div class="h-14 w-14 bg-red-500/10 border border-red-500/20 text-red-500 rounded-full flex items-center justify-center mx-auto mb-2">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white">Permanently Remove Calendar?</h3>
            <p class="text-sm text-slate-400">Are you sure you want to delete this calendar year and all associated language PDF translations? This action is permanent and cannot be reversed.</p>
            <div class="text-sm text-slate-350 font-bold p-3 bg-slate-950/30 border border-slate-850 rounded-xl" id="delete-year-placeholder"></div>
        </div>
        <div class="px-6 py-4 bg-slate-950/40 border-t border-slate-800 flex justify-end space-x-3">
            <button onclick="closeModal('delete-year-modal')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-750 text-slate-300 hover:text-white transition-colors text-sm font-semibold rounded-xl cursor-pointer">
                Abort
            </button>
            <form id="delete-year-form" action="" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-red-950/30 cursor-pointer">
                    Delete Calendar Year
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const baseUrl = "{{ url('admin/calendar-contents') }}";

    function openAddModal() {
        document.getElementById('add-year').value = '';
        document.getElementById('add-status').value = '1';
        document.getElementById('add-pdf-en').value = '';
        document.getElementById('add-pdf-hi').value = '';
        document.getElementById('add-pdf-gu').value = '';
        document.getElementById('add-pdf-mr').value = '';
        document.getElementById('add-pdf-bn').value = '';
        document.getElementById('add-pdf-te').value = '';
        document.getElementById('add-pdf-ta').value = '';
        document.getElementById('add-pdf-kn').value = '';
        document.getElementById('add-pdf-pa').value = '';
        openModal('add-year-modal');
    }

    function editYear(id) {
        document.getElementById('edit-year-form').action = `${baseUrl}/${id}/update`;
        document.getElementById('edit-year').value = '';
        document.getElementById('edit-status').value = '1';
        document.getElementById('edit-pdf-en').value = '';
        document.getElementById('edit-pdf-hi').value = '';
        document.getElementById('edit-pdf-gu').value = '';
        document.getElementById('edit-pdf-mr').value = '';
        document.getElementById('edit-pdf-bn').value = '';
        document.getElementById('edit-pdf-te').value = '';
        document.getElementById('edit-pdf-ta').value = '';
        document.getElementById('edit-pdf-kn').value = '';
        document.getElementById('edit-pdf-pa').value = '';

        document.getElementById('current-pdf-en-label').innerHTML = 'Loading file status...';
        document.getElementById('current-pdf-hi-label').innerHTML = 'Loading file status...';
        document.getElementById('current-pdf-gu-label').innerHTML = 'Loading file status...';
        document.getElementById('current-pdf-mr-label').innerHTML = 'Loading file status...';
        document.getElementById('current-pdf-bn-label').innerHTML = 'Loading file status...';
        document.getElementById('current-pdf-te-label').innerHTML = 'Loading file status...';
        document.getElementById('current-pdf-ta-label').innerHTML = 'Loading file status...';
        document.getElementById('current-pdf-kn-label').innerHTML = 'Loading file status...';
        document.getElementById('current-pdf-pa-label').innerHTML = 'Loading file status...';

        openModal('edit-year-modal');

        fetch(`${baseUrl}/${id}`)
            .then(res => res.json())
            .then(year => {
                document.getElementById('edit-year').value = year.year;
                document.getElementById('edit-status').value = year.status;

                const languages = ['en', 'hi', 'gu', 'mr', 'bn', 'te', 'ta', 'kn', 'pa'];
                languages.forEach(lang => {
                    const content = year.contents.find(c => c.language === lang);
                    const label = document.getElementById(`current-pdf-${lang}-label`);
                    if (content && content.pdf_path) {
                        const filename = content.pdf_path.split('/').pop();
                        label.innerHTML = `Uploaded: <a href="{{ asset('') }}${content.pdf_path}" target="_blank" class="text-indigo-400 hover:text-indigo-300 font-semibold underline">${filename}</a>`;
                    } else {
                        label.innerHTML = '<span class="text-slate-550">Not uploaded yet</span>';
                    }
                });
            })
            .catch(err => {
                console.error(err);
                alert('Failed to retrieve calendar year data.');
                closeModal('edit-year-modal');
            });
    }

    function toggleYearStatus(id, checkbox) {
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
            alert('An error occurred during communication.');
        });
    }

    function confirmDeleteYear(id, year) {
        document.getElementById('delete-year-form').action = `${baseUrl}/${id}`;
        document.getElementById('delete-year-placeholder').textContent = `Calendar Year: ${year}`;
        openModal('delete-year-modal');
    }
</script>
@endsection
