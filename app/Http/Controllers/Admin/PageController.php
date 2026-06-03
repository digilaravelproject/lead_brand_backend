<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of pages.
     */
    public function index()
    {
        $pages = Page::latest()->paginate(10);
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Store a newly created page.
     */
    public function store(Request $request)
    {
        $request->validate([
            'page_name' => ['required', 'string', 'max:255', 'unique:pages,page_name'],
            'page_type' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ]);

        Page::create([
            'page_name' => $request->page_name,
            'page_type' => $request->page_type ?: $request->page_name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Return page details as JSON for modal.
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);
        return response()->json($page);
    }

    /**
     * Update the specified page in storage.
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $request->validate([
            'page_name' => ['required', 'string', 'max:255', 'unique:pages,page_name,' . $page->id],
            'page_type' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ]);

        $page->update([
            'page_name' => $request->page_name,
            'page_type' => $request->page_type ?: $request->page_name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Toggle Page active/inactive status via AJAX.
     */
    public function toggleStatus($id)
    {
        $page = Page::findOrFail($id);
        $page->status = $page->status == 1 ? 0 : 1;
        $page->save();

        return response()->json([
            'success' => true,
            'status' => $page->status,
            'message' => 'Page status updated successfully.'
        ]);
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }
}
