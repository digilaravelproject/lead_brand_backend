<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingHub;
use App\Models\TrainingCategory;
use Illuminate\Http\Request;

class TrainingHubController extends Controller
{
    /**
     * Display a listing of training hub resources.
     */
    public function index()
    {
        $trainings = TrainingHub::with('category')->latest()->paginate(10);
        $categories = TrainingCategory::where('status', 1)->get();
        return view('admin.training_hubs.index', compact('trainings', 'categories'));
    }

    /**
     * Store newly created training resources (supports multiple file uploads).
     */
    public function store(Request $request)
    {
        $request->validate([
            'training_category_id' => ['required', 'exists:training_categories,id'],
            'type' => ['required', 'in:pdf,video'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'files' => ['required', 'array'],
            'files.*' => $request->type === 'pdf' 
                ? ['file', 'mimes:pdf', 'max:20480'] // Max 20MB for PDFs
                : ['file', 'mimes:mp4,mov,avi,mkv,webm', 'max:102400'], // Max 100MB for Videos
            'status' => ['required', 'in:0,1'],
        ]);

        $files = $request->file('files');
        $totalFiles = count($files);

        foreach ($files as $index => $file) {
            // If multiple files are uploaded, append a suffix to the title
            $title = $request->title;
            if ($totalFiles > 1) {
                $title = $title . ' - Part ' . ($index + 1);
            }

            // Move file
            $filename = time() . '_' . $index . '_' . preg_replace('/[^A-Za-z0-9\-.]/', '', $file->getClientOriginalName());
            $destinationPath = public_path('uploads/trainings');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $filePath = 'uploads/trainings/' . $filename;

            TrainingHub::create([
                'training_category_id' => $request->training_category_id,
                'type' => $request->type,
                'title' => $title,
                'description' => $request->description,
                'file_path' => $filePath,
                'status' => $request->status,
            ]);
        }

        return redirect()->route('admin.training-hubs.index')
            ->with('success', 'Training resource(s) uploaded successfully.');
    }

    /**
     * Return training details as JSON for modal.
     */
    public function show($id)
    {
        $training = TrainingHub::with('category')->findOrFail($id);
        return response()->json($training);
    }

    /**
     * Update the specified training resource.
     */
    public function update(Request $request, $id)
    {
        $training = TrainingHub::findOrFail($id);

        $request->validate([
            'training_category_id' => ['required', 'exists:training_categories,id'],
            'type' => ['required', 'in:pdf,video'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => $request->type === 'pdf' 
                ? ['nullable', 'file', 'mimes:pdf', 'max:20480'] 
                : ['nullable', 'file', 'mimes:mp4,mov,avi,mkv,webm', 'max:102400'],
            'status' => ['required', 'in:0,1'],
        ]);

        $training->training_category_id = $request->training_category_id;
        $training->type = $request->type;
        $training->title = $request->title;
        $training->description = $request->description;
        $training->status = $request->status;

        // If replacing the file
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($training->file_path && file_exists(public_path($training->file_path))) {
                @unlink(public_path($training->file_path));
            }

            $file = $request->file('file');
            $filename = time() . '_replace_' . preg_replace('/[^A-Za-z0-9\-.]/', '', $file->getClientOriginalName());
            $destinationPath = public_path('uploads/trainings');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $training->file_path = 'uploads/trainings/' . $filename;
        }

        $training->save();

        return redirect()->route('admin.training-hubs.index')
            ->with('success', 'Training resource updated successfully.');
    }

    /**
     * Toggle training resource status via AJAX.
     */
    public function toggleStatus($id)
    {
        $training = TrainingHub::findOrFail($id);
        $training->status = $training->status == 1 ? 0 : 1;
        $training->save();

        return response()->json([
            'success' => true,
            'status' => $training->status,
            'message' => 'Training resource status toggled.'
        ]);
    }

    /**
     * Remove the specified training resource.
     */
    public function destroy($id)
    {
        $training = TrainingHub::findOrFail($id);

        // Delete file
        if ($training->file_path && file_exists(public_path($training->file_path))) {
            @unlink(public_path($training->file_path));
        }

        $training->delete();

        return redirect()->route('admin.training-hubs.index')
            ->with('success', 'Training resource deleted successfully.');
    }
}
