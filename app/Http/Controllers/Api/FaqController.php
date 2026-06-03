<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::where('status', 1)->orderBy('id')->get();
        return response()->json(['status' => true, 'data' => ['faqs' => $faqs]], 200);
    }
}
