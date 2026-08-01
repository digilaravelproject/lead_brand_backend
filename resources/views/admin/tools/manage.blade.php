@extends('admin.layout')

@section('title', 'Manage Business Tool')
@section('page_title', 'Business Tool Management')

@section('content')
<div class="space-y-6">
    <!-- Header/Back Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-800/80 pb-5">
        <div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.tools.index') }}" class="p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-white">{{ $tool->title }}</h1>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $tool->description ?: 'Manage subtools and assets.' }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="openModal('add-subtool-modal')" 
                    class="flex items-center justify-center space-x-2 px-4 py-2 bg-slate-900 border border-slate-800 text-slate-300 hover:text-white rounded-xl transition-all font-semibold text-xs cursor-pointer">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Subtool</span>
            </button>
            <button onclick="openModal('upload-media-modal')" 
                    class="flex items-center justify-center space-x-2 px-4 py-2 bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-white shadow-md shadow-amber-500/10 hover:shadow-amber-500/20 rounded-xl transition-all font-semibold text-xs cursor-pointer">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span>Upload Media</span>
            </button>
        </div>
    </div>

    <!-- Quick Analytics Row -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-4 flex items-center justify-between shadow-sm">
            <div>
                <span class="block text-xs text-slate-500 font-semibold uppercase tracking-wider">Subtools count</span>
                <span class="text-xl font-bold text-white mt-1 block">{{ $tool->subtools->count() }}</span>
            </div>
            <div class="h-9 w-9 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
            </div>
        </div>
        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-4 flex items-center justify-between shadow-sm">
            <div>
                <span class="block text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Images</span>
                <span class="text-xl font-bold text-white mt-1 block">{{ $tool->allMedia->where('media_type', 'image')->count() }}</span>
            </div>
            <div class="h-9 w-9 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-4 flex items-center justify-between shadow-sm">
            <div>
                <span class="block text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Videos</span>
                <span class="text-xl font-bold text-white mt-1 block">{{ $tool->allMedia->where('media_type', 'video')->count() }}</span>
            </div>
            <div class="h-9 w-9 rounded-lg bg-rose-500/10 text-rose-400 flex items-center justify-center">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-4 flex items-center justify-between shadow-sm">
            <div>
                <span class="block text-xs text-slate-500 font-semibold uppercase tracking-wider">Tool Status</span>
                <span class="mt-1 block font-bold text-xs">
                    @if($tool->status == 1)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Active</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-800 text-slate-400 border border-slate-750">Inactive</span>
                    @endif
                </span>
            </div>
            <div class="h-9 w-9 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Media Sections & Subtools Grid -->
    <div class="space-y-6">
        
        <!-- DIRECT TOOL MEDIA (If no subtools or media directly at root) -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 shadow-sm">
            <h2 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center space-x-2">
                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                <span>Direct Media / Root Assets (No Subtool)</span>
            </h2>
            
            @if($tool->media->isEmpty())
                <div class="py-6 text-center text-xs text-slate-500 border border-dashed border-slate-800 rounded-2xl">
                    No direct assets uploaded here. Use "Upload Media" to add images or videos directly to this tool.
                </div>
            @else
                <div class="bg-slate-950/40 border border-slate-800/80 rounded-2xl overflow-hidden shadow-xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead>
                                <tr class="border-b border-slate-800 text-[10px] text-slate-400 font-bold uppercase tracking-wider bg-slate-950/80">
                                    <th class="py-3 px-4 w-[12%]">Preview</th>
                                    <th class="py-3 px-4 w-[23%]">Title</th>
                                    <th class="py-3 px-4 w-[10%]">Language</th>
                                    <th class="py-3 px-4 w-[10%]">Type</th>
                                    <th class="py-3 px-4 w-[15%]">PDF File</th>
                                    <th class="py-3 px-4 w-[15%]">Info Image</th>
                                    <th class="py-3 px-4 w-[15%]">Info Image</th>
                                    <th class="py-3 px-4 w-[15%]">Description</th>
                                    <th class="py-3 px-4 text-right w-[10%]">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/40 text-slate-300">
                                @foreach($tool->media as $media)
                                    <tr class="hover:bg-slate-800/20 transition-colors">
                                        <td class="py-3 px-4">
                                            <div class="relative h-10 w-16 bg-black rounded-lg overflow-hidden border border-slate-800 flex items-center justify-center">
                                                @if($media->media_type === 'image')
                                                    <img src="{{ asset($media->file_path) }}" alt="Preview" class="h-full w-full object-cover">
                                                @else
                                                    <video src="{{ asset($media->file_path) }}" @if($media->thumbnail) poster="{{ asset($media->thumbnail) }}" @endif class="h-full w-full object-cover"></video>
                                                    <div class="absolute inset-0 flex items-center justify-center bg-black/45">
                                                        <svg class="h-3 w-3 text-white fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 font-semibold text-white truncate max-w-[150px]" title="{{ $media->title }}">
                                            {{ $media->title ?: 'Media #'.$media->id }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-800 text-[10px] text-slate-300 font-bold uppercase">
                                                {{ $media->language }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $media->media_type === 'video' ? 'bg-rose-500/10 text-rose-455 border border-rose-500/20' : 'bg-emerald-500/10 text-emerald-455 border border-emerald-500/20' }}">
                                                {{ $media->media_type }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($media->pdf)
                                                <a href="{{ asset($media->pdf) }}" target="_blank" class="inline-flex items-center space-x-1 text-amber-400 hover:text-amber-300 font-bold">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <span>View PDF</span>
                                                </a>
                                            @else
                                                <span class="text-slate-600 font-medium">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($media->info_image)
                                                <img src="{{ asset($media->info_image) }}" alt="Info Image" class="h-12 w-20 object-cover rounded-lg border border-slate-800">
                                            @else
                                                <span class="text-slate-600 font-medium">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-slate-400 truncate max-w-[200px]" title="{{ $media->description }}">
                                            {{ $media->description ?: '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <form action="{{ route('admin.tools.media.destroy', $media->id) }}" method="POST" onsubmit="return confirm('Delete this media?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-red-650/10 hover:bg-red-650/20 text-red-400 rounded-lg hover:text-red-300 transition-colors cursor-pointer">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- SUBTOOL MEDIA GROUPS -->
        <div class="space-y-6">
            <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center space-x-2">
                <span class="h-2 w-2 rounded-full bg-yellow-500"></span>
                <span>Subtool Assets Directories</span>
            </h2>

            @forelse($tool->subtools as $subtool)
                <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 shadow-sm space-y-4">
                    <!-- Title/Action -->
                    <div class="flex items-center justify-between border-b border-slate-800/60 pb-3">
                        <div class="flex items-center space-x-2.5">
                            <div class="h-7 w-7 rounded-lg bg-yellow-500/10 text-yellow-400 flex items-center justify-center">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-white">{{ $subtool->title }}</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $subtool->description ?: 'No details provided.' }}</p>
                            </div>
                        </div>
                        <div>
                            <form action="{{ route('admin.tools.subtools.destroy', $subtool->id) }}" method="POST" onsubmit="return confirm('Delete this subtool and all its media files? This cannot be undone.');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-semibold px-2.5 py-1.5 rounded-lg border border-red-500/10 hover:bg-red-500/10 transition-all">
                                    Remove Subtool
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Subtool media files -->
                    @if($subtool->media->isEmpty())
                        <div class="py-6 text-center text-xs text-slate-500 border border-dashed border-slate-800 rounded-2xl">
                            No files uploaded in this subtool. Use "Upload Media" and target this subtool.
                        </div>
                    @else
                        <div class="bg-slate-950/40 border border-slate-800/80 rounded-2xl overflow-hidden shadow-xl">
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs text-left">
                                    <thead>
                                        <tr class="border-b border-slate-800 text-[10px] text-slate-400 font-bold uppercase tracking-wider bg-slate-950/80">
                                            <th class="py-3 px-4 w-[12%]">Preview</th>
                                            <th class="py-3 px-4 w-[23%]">Title</th>
                                            <th class="py-3 px-4 w-[10%]">Language</th>
                                            <th class="py-3 px-4 w-[10%]">Type</th>
                                            <th class="py-3 px-4 w-[15%]">PDF File</th>
                                            <th class="py-3 px-4 w-[20%]">Description</th>
                                            <th class="py-3 px-4 text-right w-[10%]">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-800/40 text-slate-300">
                                        @foreach($subtool->media as $media)
                                            <tr class="hover:bg-slate-800/20 transition-colors">
                                                <td class="py-3 px-4">
                                                    <div class="relative h-10 w-16 bg-black rounded-lg overflow-hidden border border-slate-800 flex items-center justify-center">
                                                        @if($media->media_type === 'image')
                                                            <img src="{{ asset($media->file_path) }}" alt="Preview" class="h-full w-full object-cover">
                                                        @else
                                                            <video src="{{ asset($media->file_path) }}" @if($media->thumbnail) poster="{{ asset($media->thumbnail) }}" @endif class="h-full w-full object-cover"></video>
                                                            <div class="absolute inset-0 flex items-center justify-center bg-black/45">
                                                                <svg class="h-3 w-3 text-white fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="py-3 px-4 font-semibold text-white truncate max-w-[150px]" title="{{ $media->title }}">
                                                    {{ $media->title ?: 'Media #'.$media->id }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-800 text-[10px] text-slate-300 font-bold uppercase">
                                                        {{ $media->language }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $media->media_type === 'video' ? 'bg-rose-500/10 text-rose-455 border border-rose-500/20' : 'bg-emerald-500/10 text-emerald-455 border border-emerald-500/20' }}">
                                                        {{ $media->media_type }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    @if($media->pdf)
                                                        <a href="{{ asset($media->pdf) }}" target="_blank" class="inline-flex items-center space-x-1 text-amber-400 hover:text-amber-300 font-bold">
                                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            <span>View PDF</span>
                                                        </a>
                                                    @else
                                                        <span class="text-slate-600 font-medium">-</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4">
                                                    @if($media->info_image)
                                                        <img src="{{ asset($media->info_image) }}" alt="Info Image" class="h-12 w-20 object-cover rounded-lg border border-slate-800">
                                                    @else
                                                        <span class="text-slate-600 font-medium">-</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4 text-slate-400 truncate max-w-[200px]" title="{{ $media->description }}">
                                                    {{ $media->description ?: '-' }}
                                                </td>
                                                <td class="py-3 px-4 text-right">
                                                    <form action="{{ route('admin.tools.media.destroy', $media->id) }}" method="POST" onsubmit="return confirm('Delete this media?');" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1.5 bg-red-650/10 hover:bg-red-650/20 text-red-400 rounded-lg hover:text-red-300 transition-colors cursor-pointer">
                                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="py-12 text-center text-xs text-slate-500 bg-slate-900 border border-slate-800 rounded-3xl shadow-sm">
                    No subtools defined under this tool module. Define folders like "Child Plan" using "Add Subtool".
                </div>
            @endforelse
        </div>

    </div>
</div>

<!-- Add Subtool Modal -->
<div id="add-subtool-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('add-subtool-modal')"></div>
    <form action="{{ route('admin.tools.subtools.store', $tool->id) }}" method="POST" class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        @csrf
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Add Subtool Category Folder</h3>
            <button type="button" onclick="closeModal('add-subtool-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Form Body -->
        <div class="p-6 overflow-y-auto space-y-4 flex-1 scrollbar">
            <div>
                <label for="subtool-title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Subtool Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="subtool-title" required placeholder="e.g. Child Plan"
                       class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-655 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
            </div>

            <div>
                <label for="subtool-description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Brief Overview (Optional)</label>
                <textarea name="description" id="subtool-description" rows="3" placeholder="Brief outline..."
                          class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-655 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm resize-none"></textarea>
            </div>

            <div>
                <label for="subtool-status" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Publish Status</label>
                <select name="status" id="subtool-status" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                    <option value="1">Active (Visible)</option>
                    <option value="0">Inactive (Hidden)</option>
                </select>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
            <button type="button" onclick="closeModal('add-subtool-modal')"
                    class="px-5 py-2.5 rounded-xl border border-slate-700/60 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-sm font-semibold">
                Cancel
            </button>
            <button type="submit" 
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-white font-semibold text-sm">
                Add Subtool Folder
            </button>
        </div>
    </form>
</div>

<!-- Upload Media Modal -->
<div id="upload-media-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('upload-media-modal')"></div>
    <form action="{{ route('admin.tools.media.store', $tool->id) }}" method="POST" enctype="multipart/form-data" class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
        @csrf
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-bold text-white">Upload Marketing Media</h3>
            <button type="button" onclick="closeModal('upload-media-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Form Body -->
        <div class="p-6 overflow-y-auto space-y-4 flex-1 scrollbar">
            <!-- Select target folder (direct or subtool) -->
            <div>
                <label for="media-subtool" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Target Destination <span class="text-red-500">*</span></label>
                <select name="subtool_id" id="media-subtool" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm">
                    <option value="">Directly to root (No Subtool)</option>
                    @foreach($tool->subtools as $subtool)
                        <option value="{{ $subtool->id }}">Subtool Directory: {{ $subtool->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Language Selection Radio Buttons -->
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Select Language <span class="text-red-500">*</span></label>
                <div class="flex flex-wrap gap-4 items-center bg-slate-950/40 border border-slate-800 rounded-xl p-3">
                    <label class="inline-flex items-center text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <input type="radio" name="language" value="en" checked class="mr-2 h-4 w-4 text-amber-600 border-slate-800 bg-slate-900 focus:ring-amber-500/40">
                        <span>English</span>
                    </label>
                    <label class="inline-flex items-center text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <input type="radio" name="language" value="mr" class="mr-2 h-4 w-4 text-amber-600 border-slate-800 bg-slate-900 focus:ring-amber-500/40">
                        <span>Marathi</span>
                    </label>
                    <label class="inline-flex items-center text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <input type="radio" name="language" value="hi" class="mr-2 h-4 w-4 text-amber-600 border-slate-800 bg-slate-900 focus:ring-amber-500/40">
                        <span>Hindi</span>
                    </label>
                    <label class="inline-flex items-center text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <input type="radio" name="language" value="gu" class="mr-2 h-4 w-4 text-amber-600 border-slate-800 bg-slate-900 focus:ring-amber-500/40">
                        <span>Gujrati</span>
                    </label>
                    <label class="inline-flex items-center text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <input type="radio" name="language" value="bn" class="mr-2 h-4 w-4 text-amber-600 border-slate-800 bg-slate-900 focus:ring-amber-500/40">
                        <span>Bengali</span>
                    </label>
                    <label class="inline-flex items-center text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <input type="radio" name="language" value="te" class="mr-2 h-4 w-4 text-amber-600 border-slate-800 bg-slate-900 focus:ring-amber-500/40">
                        <span>Telugu</span>
                    </label>
                    <label class="inline-flex items-center text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <input type="radio" name="language" value="ta" class="mr-2 h-4 w-4 text-amber-600 border-slate-800 bg-slate-900 focus:ring-amber-500/40">
                        <span>Tamil</span>
                    </label>
                    <label class="inline-flex items-center text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <input type="radio" name="language" value="kn" class="mr-2 h-4 w-4 text-amber-600 border-slate-800 bg-slate-900 focus:ring-amber-500/40">
                        <span>Kannada</span>
                    </label>
                    <label class="inline-flex items-center text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <input type="radio" name="language" value="pa" class="mr-2 h-4 w-4 text-amber-600 border-slate-800 bg-slate-900 focus:ring-amber-500/40">
                        <span>Panjabi</span>
                    </label>
                </div>
            </div>

            <!-- Upload Multiple Files -->
            <div>
                <label for="media-files" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Select Files <span class="text-red-500">*</span></label>
                <input type="file" name="files[]" id="media-files" required multiple accept="image/*,video/*"
                       class="block w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-600/10 file:text-amber-400 hover:file:bg-amber-600/20 file:cursor-pointer cursor-pointer">
                <p class="text-xs text-slate-500 mt-2">You can select multiple images (PNG, JPG, JPEG, GIF) or videos (MP4, WEBM) up to 100MB per file. File name will be used as media title.</p>
            </div>

            <!-- Optional Video Thumbnail (Hidden by default) -->
            <div id="tool-media-thumbnail-container" class="hidden">
                <label for="tool-media-thumbnail" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Video Thumbnail (Optional)</label>
                <input type="file" name="thumbnail" id="tool-media-thumbnail" accept="image/*"
                       class="block w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-600/10 file:text-amber-400 hover:file:bg-amber-600/20 file:cursor-pointer cursor-pointer">
                <p class="text-xs text-slate-500 mt-1.5">Accepts PNG, JPG, JPEG, WEBP. This thumbnail will represent the video resource.</p>
            </div>

            <!-- Optional Info Image Upload -->
            <div>
                <label for="media-info-image" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Info Image (Optional)</label>
                <input type="file" name="info_image" id="media-info-image" accept="image/*"
                       class="block w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-600/10 file:text-amber-400 hover:file:bg-amber-600/20 file:cursor-pointer cursor-pointer">
                <p class="text-xs text-slate-500 mt-1.5">Optional informational image to store alongside the main media upload.</p>
            </div>

            <!-- Optional PDF Upload -->
            <div>
                <label for="media-pdf" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">PDF Document (Optional)</label>
                <input type="file" name="pdf" id="media-pdf" accept="application/pdf"
                       class="block w-full text-sm text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-600/10 file:text-amber-400 hover:file:bg-amber-600/20 file:cursor-pointer cursor-pointer">
                <p class="text-xs text-slate-500 mt-1.5">Accepts PDF files up to 20MB.</p>
            </div>

            <!-- Optional Description -->
            <div>
                <label for="media-description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Description (Optional)</label>
                <textarea name="description" id="media-description" rows="3" placeholder="Enter media description..."
                          class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-2.5 px-4 text-white placeholder-slate-655 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-sm resize-none"></textarea>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-slate-800 px-6 py-4 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
            <button type="button" onclick="closeModal('upload-media-modal')"
                    class="px-5 py-2.5 rounded-xl border border-slate-700/60 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-sm font-semibold">
                Cancel
            </button>
            <button type="submit" 
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-white font-semibold text-sm">
                Start Upload
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('media-files');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const files = e.target.files;
                let hasVideo = false;
                for (let i = 0; i < files.length; i++) {
                    if (files[i].type.startsWith('video/') || files[i].name.match(/\.(mp4|mov|avi|mkv|webm)$/i)) {
                        hasVideo = true;
                        break;
                    }
                }
                const container = document.getElementById('tool-media-thumbnail-container');
                if (hasVideo) {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                    document.getElementById('tool-media-thumbnail').value = '';
                }
            });
        }
    });

    // Reset fields when opening modal
    const originalOpenModal = window.openModal;
    window.openModal = function(modalId) {
        if (modalId === 'upload-media-modal') {
            document.getElementById('media-files').value = '';
            document.getElementById('tool-media-thumbnail').value = '';
            document.getElementById('tool-media-thumbnail-container').classList.add('hidden');
            if (document.getElementById('media-pdf')) {
                document.getElementById('media-pdf').value = '';
            }
            if (document.getElementById('media-description')) {
                document.getElementById('media-description').value = '';
            }
        }
        if (originalOpenModal) {
            originalOpenModal(modalId);
        }
    };
</script>
@endsection
