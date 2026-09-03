<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);
        $messages = Message::where('status', true)->orderByDesc('id')
            ->paginate($validated['per_page'] ?? 10);

        return response()->json([
            'status' => true,
            'message' => 'Messages fetched successfully.',
            'data' => [
                'messages' => $messages->items(),
                'total' => $messages->total(),
                'current_page' => $messages->currentPage(),
                'per_page' => $messages->perPage(),
                'last_page' => $messages->lastPage(),
            ],
        ]);
    }
}
