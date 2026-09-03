<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = Message::orderByDesc('id')->paginate(10);
        $editing = $request->filled('edit') ? Message::findOrFail($request->input('edit')) : null;

        return view('admin.messages.index', compact('messages', 'editing'));
    }

    public function store(Request $request)
    {
        Message::create($this->validated($request));

        return redirect()->route('admin.messages.index')->with('success', 'Message created successfully.');
    }

    public function update(Request $request, Message $message)
    {
        $message->update($this->validated($request));

        return redirect()->route('admin.messages.index')->with('success', 'Message updated successfully.');
    }

    public function destroy(Message $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:10000'],
            'status' => ['required', 'boolean'],
        ]);
    }
}
