<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of Banners.
     */
    public function index()
    {
        $banners = Banner::latest()->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Store a newly created Banner.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'heading' => ['nullable', 'string', 'max:255'],
            'services' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5120'], // Max 5MB
            'status' => ['required', 'in:0,1'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $imagePath = Storage::url($path);
        }

        Banner::create([
            'title' => $request->title,
            'heading' => $request->heading ?? 'Doctors Save Lives, We Save Lifestyle',
            'services' => $request->services ?? 'Premium Payment, Maturity Claim, Policy Revival, Policy Loan, Change in Address, Change in Nomination, Policy Branch Transfer, Change in Premium Mode',
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    /**
     * Return Banner details as JSON.
     */
    public function show($id)
    {
        $banner = Banner::findOrFail($id);
        return response()->json($banner);
    }

    /**
     * Update the specified Banner.
     */
    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'heading' => ['nullable', 'string', 'max:255'],
            'services' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'status' => ['required', 'in:0,1'],
        ]);

        $imagePath = $banner->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($banner->image) {
                $oldPath = str_replace('/storage/', '', $banner->image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('banners', 'public');
            $imagePath = Storage::url($path);
        }

        $banner->update([
            'title' => $request->title,
            'heading' => $request->heading ?? 'Doctors Save Lives, We Save Lifestyle',
            'services' => $request->services ?? 'Premium Payment, Maturity Claim, Policy Revival, Policy Loan, Change in Address, Change in Nomination, Policy Branch Transfer, Change in Premium Mode',
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->status = $banner->status == 1 ? 0 : 1;
        $banner->save();

        return response()->json([
            'success' => true,
            'status' => $banner->status,
            'message' => 'Banner status updated successfully.'
        ]);
    }

    /**
     * Remove the specified Banner.
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        if ($banner->image) {
            $oldPath = str_replace('/storage/', '', $banner->image);
            Storage::disk('public')->delete($oldPath);
        }
        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }
}
