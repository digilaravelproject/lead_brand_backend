<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\Subtool;
use App\Models\ToolMedia;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ToolController extends Controller
{
    /**
     * Display a listing of tools.
     */
    public function index()
    {
        $tools = Tool::latest()->paginate(10);
        return view('admin.tools.index', compact('tools'));
    }

    /**
     * Store a newly created tool.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:0,1'],
        ]);

        // Default icon if none provided
        $icon = $request->icon ?: 'combo_plans';

        Tool::create([
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $icon,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tools.index')
            ->with('success', 'Business Tool created successfully.');
    }

    /**
     * Return tool details as JSON for modal.
     */
    public function show($id)
    {
        $tool = Tool::findOrFail($id);
        return response()->json($tool);
    }

    /**
     * Update the specified tool.
     */
    public function update(Request $request, $id)
    {
        $tool = Tool::findOrFail($id);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:0,1'],
        ]);

        $tool->update([
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $request->icon ?: $tool->icon,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tools.index')
            ->with('success', 'Business Tool updated successfully.');
    }

    /**
     * Toggle tool status via AJAX.
     */
    public function toggleStatus($id)
    {
        $tool = Tool::findOrFail($id);
        $tool->status = $tool->status == 1 ? 0 : 1;
        $tool->save();

        return response()->json([
            'success' => true,
            'status' => $tool->status,
            'message' => 'Tool status toggled successfully.'
        ]);
    }

    /**
     * Remove the specified tool.
     */
    public function destroy($id)
    {
        $tool = Tool::findOrFail($id);
        
        // Retrieve and delete all associated media files physically
        $mediaItems = ToolMedia::where('tool_id', $tool->id)->get();
        foreach ($mediaItems as $media) {
            if ($media->file_path && file_exists(public_path($media->file_path))) {
                @unlink(public_path($media->file_path));
            }
            if ($media->thumbnail && file_exists(public_path($media->thumbnail))) {
                @unlink(public_path($media->thumbnail));
            }
            if ($media->info_image && file_exists(public_path($media->info_image))) {
                @unlink(public_path($media->info_image));
            }
            if ($media->pdf && file_exists(public_path($media->pdf))) {
                @unlink(public_path($media->pdf));
            }
        }

        $tool->delete();

        return redirect()->route('admin.tools.index')
            ->with('success', 'Business Tool and all its subtools/media deleted successfully.');
    }

    /**
     * Display the detailed management page for subtools and media of a specific tool.
     */
    public function manage($id)
    {
        $tool = Tool::with(['subtools', 'media', 'subtools.media'])->findOrFail($id);
        return view('admin.tools.manage', compact('tool'));
    }

    /**
     * Create subtool under tool.
     */
    public function storeSubtool(Request $request, $toolId)
    {
        $tool = Tool::findOrFail($toolId);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ]);

        Subtool::create([
            'tool_id' => $tool->id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tools.manage', $tool->id)
            ->with('success', 'Subtool created successfully.');
    }

    /**
     * Remove subtool.
     */
    public function destroySubtool($subtoolId)
    {
        $subtool = Subtool::findOrFail($subtoolId);
        $toolId = $subtool->tool_id;

        // Delete associated media files physically
        foreach ($subtool->media as $media) {
            if ($media->file_path && file_exists(public_path($media->file_path))) {
                @unlink(public_path($media->file_path));
            }
            if ($media->thumbnail && file_exists(public_path($media->thumbnail))) {
                @unlink(public_path($media->thumbnail));
            }
            if ($media->info_image && file_exists(public_path($media->info_image))) {
                @unlink(public_path($media->info_image));
            }
            if ($media->pdf && file_exists(public_path($media->pdf))) {
                @unlink(public_path($media->pdf));
            }
        }

        $subtool->delete();

        return redirect()->route('admin.tools.manage', $toolId)
            ->with('success', 'Subtool and all its media deleted successfully.');
    }

    /**
     * Store uploaded media under tool or subtool.
     */
    public function storeMedia(Request $request, $toolId)
    {
        $tool = Tool::findOrFail($toolId);

        $request->validate([
            'subtool_id' => ['nullable', 'exists:subtools,id'],
            'language' => ['required', 'string', 'in:en,mr,hi,gu,bn,te,ta,kn,pa'],
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:2097152'], // Max 2GB per file
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'info_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'description' => ['nullable', 'string'],
        ]);

        $subtool = null;
        if ($request->subtool_id) {
            $subtool = Subtool::findOrFail($request->subtool_id);
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            $thumbName = time() . '_tool_thumb_' . preg_replace('/[^A-Za-z0-9\-.]/', '', $thumb->getClientOriginalName());
            $thumbDest = public_path('uploads/tools/thumbnails');
            if (!file_exists($thumbDest)) {
                File::makeDirectory($thumbDest, 0755, true);
            }
            $thumb->move($thumbDest, $thumbName);
            $thumbnailPath = 'uploads/tools/thumbnails/' . $thumbName;
        }

        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $pdfFile = $request->file('pdf');
            $pdfName = time() . '_tool_pdf_' . preg_replace('/[^A-Za-z0-9\-.]/', '', $pdfFile->getClientOriginalName());
            $pdfDest = public_path('uploads/tools/pdfs');
            if (!file_exists($pdfDest)) {
                File::makeDirectory($pdfDest, 0755, true);
            }
            $pdfFile->move($pdfDest, $pdfName);
            $pdfPath = 'uploads/tools/pdfs/' . $pdfName;
        }

        $infoImagePath = null;
        if ($request->hasFile('info_image')) {
            $infoImage = $request->file('info_image');
            $infoImageName = time() . '_tool_info_' . preg_replace('/[^A-Za-z0-9\-.]/', '', $infoImage->getClientOriginalName());
            $infoImageDest = public_path('uploads/tools/info_images');
            if (!file_exists($infoImageDest)) {
                File::makeDirectory($infoImageDest, 0755, true);
            }
            $infoImage->move($infoImageDest, $infoImageName);
            $infoImagePath = 'uploads/tools/info_images/' . $infoImageName;
        }

        $files = $request->file('files');
        $uploadedCount = 0;
        $firstFileType = 'image';

        foreach ($files as $index => $file) {
            $mime = $file->getMimeType();
            $mediaType = str_contains($mime, 'video') ? 'video' : 'image';
            if ($index === 0) {
                $firstFileType = $mediaType;
            }

            // Clean filename
            $filename = time() . '_' . $index . '_' . preg_replace('/[^A-Za-z0-9\-.]/', '', $file->getClientOriginalName());
            $destinationPath = public_path('uploads/tools');
            if (!file_exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $filePath = 'uploads/tools/' . $filename;

            // Generate clean title from original filename
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $mediaTitle = ucwords(str_replace(['-', '_'], ' ', $originalName));

            ToolMedia::create([
                'tool_id' => $tool->id,
                'subtool_id' => $subtool ? $subtool->id : null,
                'title' => $mediaTitle,
                'file_path' => $filePath,
                'media_type' => $mediaType,
                'thumbnail' => $mediaType === 'video' ? $thumbnailPath : null,
                'info_image' => $infoImagePath,
                'pdf' => $pdfPath,
                'description' => $request->description,
                'language' => $request->language,
                'status' => 1,
            ]);

            $uploadedCount++;
        }

        // Trigger Notification
        try {
            $emoji = $firstFileType === 'video' ? '🎬' : '🖼️';
            $typeLabel = $firstFileType === 'video' ? 'Video' : 'Media Image';
            $locationText = $subtool ? "Subtool \"{$subtool->title}\" in \"{$tool->title}\"" : "Tool \"{$tool->title}\"";

            $message = "Uploaded {$uploadedCount} new {$firstFileType}(s) to {$locationText}.";

            Notification::create([
                'title' => "{$emoji} New {$typeLabel} Added",
                'message' => $message,
                'type' => 'tools',
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            logger()->error('Notification creation failed for Business Tools upload: ' . $e->getMessage());
        }

        return redirect()->route('admin.tools.manage', $tool->id)
            ->with('success', 'Media file(s) uploaded successfully.');
    }

    /**
     * Delete uploaded media.
     */
    public function destroyMedia($mediaId)
    {
        $media = ToolMedia::findOrFail($mediaId);
        $toolId = $media->tool_id;

        // Delete physical file
        if ($media->file_path && file_exists(public_path($media->file_path))) {
            @unlink(public_path($media->file_path));
        }

        // Delete physical thumbnail if exists
        if ($media->thumbnail && file_exists(public_path($media->thumbnail))) {
            @unlink(public_path($media->thumbnail));
        }

        // Delete physical info image if exists
        if ($media->info_image && file_exists(public_path($media->info_image))) {
            @unlink(public_path($media->info_image));
        }

        // Delete physical PDF if exists
        if ($media->pdf && file_exists(public_path($media->pdf))) {
            @unlink(public_path($media->pdf));
        }

        $media->delete();

        return redirect()->route('admin.tools.manage', $toolId)
            ->with('success', 'Media item deleted successfully.');
    }
}
