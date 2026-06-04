<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class BannerController extends Controller
{
    /**
     * Display a listing of active banners.
     */
    public function index()
    {
        try {
            $banners = Banner::where('status', 1)->latest()->get();
            
            // Format images to full URLs
            $banners->transform(function ($banner) {
                if ($banner->image) {
                    $banner->image_url = url($banner->image);
                } else {
                    $banner->image_url = null;
                }
                return $banner;
            });

            return response()->json([
                'status' => true,
                'data' => $banners
            ], 200);
        } catch (Exception $e) {
            Log::error('API Banners listing error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch banners',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified banner.
     */
    public function show($id)
    {
        try {
            $banner = Banner::where('id', $id)->where('status', 1)->firstOrFail();
            
            if ($banner->image) {
                $banner->image_url = url($banner->image);
            } else {
                $banner->image_url = null;
            }

            return response()->json([
                'status' => true,
                'data' => $banner
            ], 200);
        } catch (Exception $e) {
            Log::error('API Banner show error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Banner not found or inactive',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
