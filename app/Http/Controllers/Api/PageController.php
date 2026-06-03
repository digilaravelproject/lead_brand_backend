<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{
    public function show($page_name)
    {
        $page = Page::where('page_name', $page_name)->where('status', 1)->first();
        if (! $page) {
            return response()->json(['status' => false, 'message' => 'Page not found'], 404);
        }
        return response()->json(['status' => true, 'data' => ['page' => $page]], 200);
    }
}
