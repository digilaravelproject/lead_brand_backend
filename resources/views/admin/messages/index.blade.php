@extends('admin.layout')

@section('title', 'Manage Messages')
@section('page_title', 'Manage Messages')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div>
        <h1 class="text-xl font-bold text-white">Manage Messages</h1>
        <p class="text-sm text-slate-400 mt-1">
            Manage messages for app users. Only active messages appear in the API.
        </p>
    </div>

    {{-- Messages List --}}
    <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-950/40 text-xs text-slate-400 uppercase">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Message</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-800 text-slate-300">

                    @forelse($messages as $message)
                        <tr>
                            {{-- ID --}}
                            <td class="px-6 py-4">
                                #{{ $message->id }}
                            </td>

                            {{-- Title --}}
                            <td class="px-6 py-4 font-semibold text-white break-words">
                                {{ $message->title }}
                            </td>

                            {{-- Message --}}
                            <td class="px-6 py-4 break-words">
                                {{ \Illuminate\Support\Str::limit($message->message, 180) }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 {{ $message->status ? 'text-emerald-400' : 'text-slate-400' }}">
                                {{ $message->status ? 'Active' : 'Inactive' }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 whitespace-nowrap">

                                {{-- Edit --}}
                                <a
                                    href="{{ route('admin.messages.index', ['edit' => $message->id]) }}#message-form"
                                    class="text-sky-400 hover:underline"
                                >
                                    Edit
                                </a>

                                {{-- Delete --}}
                                <form
                                    action="{{ route('admin.messages.destroy', $message) }}"
                                    method="POST"
                                    class="inline-block ml-4"
                                    onsubmit="return confirm('Delete this message?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="text-red-400 hover:underline"
                                    >
                                        Delete
                                    </button>
                                </form>

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="5"
                                class="px-6 py-12 text-center text-slate-500"
                            >
                                No messages found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-slate-800 text-sm text-slate-400">
            Total messages: {{ $messages->total() }}

            @if($messages->hasPages())
                {{ $messages->links() }}
            @endif
        </div>
    </div>


    {{-- Edit Message Form --}}
    @if($editing)

        <form
            id="message-form"
            method="POST"
            action="{{ route('admin.messages.update', $editing) }}"
            class="bg-slate-900 border border-slate-800 rounded-3xl p-6 space-y-4"
        >
            @csrf
            @method('PUT')

            <h2 class="text-lg font-bold text-white">
                Edit Message
            </h2>

            {{-- Title --}}
            <label class="block text-sm text-slate-300">
                Title

                <input
                    name="title"
                    required
                    maxlength="255"
                    value="{{ old('title', $editing->title) }}"
                    class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white"
                >

                @error('title')
                    <span class="text-red-400">
                        {{ $message }}
                    </span>
                @enderror
            </label>


            {{-- Message --}}
            <label class="block text-sm text-slate-300">
                Message

                <textarea
                    name="message"
                    required
                    maxlength="10000"
                    rows="5"
                    class="mt-1 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white"
                >{{ old('message', $editing->message) }}</textarea>

                @error('message')
                    <span class="text-red-400">
                        {{ $message }}
                    </span>
                @enderror
            </label>


            {{-- Status --}}
            <label class="block text-sm text-slate-300">
                Status

                <select
                    name="status"
                    class="mt-1 bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-white"
                >
                    <option
                        value="1"
                        @selected(old('status', $editing->status) == 1)
                    >
                        Active
                    </option>

                    <option
                        value="0"
                        @selected(old('status', $editing->status) == 0)
                    >
                        Inactive
                    </option>
                </select>

                @error('status')
                    <span class="text-red-400">
                        {{ $message }}
                    </span>
                @enderror
            </label>


            {{-- Buttons --}}
            <div class="flex items-center gap-4">

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-semibold"
                >
                    Save Changes
                </button>

                <a
                    href="{{ route('admin.messages.index') }}"
                    class="text-slate-400 hover:text-white"
                >
                    Cancel
                </a>

            </div>

        </form>

    @endif

</div>
@endsection