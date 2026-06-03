<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingCategory;
use Illuminate\Http\Request;

class TrainingCategoryController extends Controller
{
    /**
     * Display a listing of training categories.
     */
    public function index()
    {
        $categories = TrainingCategory::latest()->paginate(10);
        return view('admin.training_categories.index', compact('categories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ]);

        TrainingCategory::create([
            'category_name' => $request->category_name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.training-categories.index')
            ->with('success', 'Training category created successfully.');
    }

    /**
     * Return category details as JSON for modal.
     */
    public function show($id)
    {
        $category = TrainingCategory::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, $id)
    {
        $category = TrainingCategory::findOrFail($id);

        $request->validate([
            'category_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.training-categories.index')
            ->with('success', 'Training category updated successfully.');
    }

    /**
     * Toggle category active/inactive status via AJAX.
     */
    public function toggleStatus($id)
    {
        $category = TrainingCategory::findOrFail($id);
        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();

        return response()->json([
            'success' => true,
            'status' => $category->status,
            'message' => 'Training category status toggled.'
        ]);
    }

    /**
     * Remove the specified category.
     */
    public function destroy($id)
    {
        $category = TrainingCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.training-categories.index')
            ->with('success', 'Training category deleted successfully.');
    }
}
