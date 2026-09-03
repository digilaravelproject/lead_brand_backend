@extends('admin.layout')

@section('title', 'Manage Users')
@section('page_title', 'User Accounts')

@section('styles')
<style>
    #edit-subscription-start::-webkit-calendar-picker-indicator,
    #edit-subscription-end::-webkit-calendar-picker-indicator { filter: invert(1); opacity: .8; cursor: pointer; }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white">Registered Users</h1>
            <p class="text-xs text-slate-400 mt-0.5">View and manage customer registrations, profile photos, and settings.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.users.index') }}" class="relative w-full sm:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or phone..." 
                       class="w-full bg-slate-950/80 border border-slate-800 rounded-xl py-2.5 pl-10 pr-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 transition-all text-xs">
            </form>
            <button onclick="document.getElementById('create-user-panel').classList.toggle('hidden')" class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-semibold whitespace-nowrap">Create User</button>
        </div>
    </div>

    <div id="create-user-panel" class="{{ $errors->any() ? '' : 'hidden' }} bg-slate-900 border border-slate-800 rounded-2xl p-5">
        <h2 class="font-bold text-white mb-4">Create User with 4-Day Trial</h2>
        <form method="POST" action="{{ route('admin.users.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">@csrf
            <input name="name" value="{{ old('name') }}" required placeholder="Full name" class="bg-slate-950 border border-slate-700 rounded-xl p-3 text-white">
            <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email" class="bg-slate-950 border border-slate-700 rounded-xl p-3 text-white">
            <input name="phone_number" value="{{ old('phone_number') }}" placeholder="Phone number" class="bg-slate-950 border border-slate-700 rounded-xl p-3 text-white">
            <button class="bg-amber-600 rounded-xl px-5 py-3 font-semibold text-white">Create User</button>
        </form>
    </div>

    <!-- Users Table Card -->
    <div class="bg-slate-900 border border-slate-800/80 rounded-3xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-800 text-xs text-slate-400 font-semibold uppercase tracking-wider bg-slate-950/40">
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6">Profile / User</th>
                        <th class="py-4 px-6">Email Address</th>
                        <th class="py-4 px-6">Phone Number</th>
                        <th class="py-4 px-6">Destination</th>
                        <th class="py-4 px-6">Brand Logo</th>
                        <th class="py-4 px-6">Created At</th>
                        <th class="py-4 px-6">Expired At</th>
                        <th class="py-4 px-6">Subscription</th>
                        <th class="py-4 px-6 text-right min-w-[250px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-slate-300">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-800/20 transition-colors">
                            <td class="py-4 px-6 font-mono text-xs text-slate-500">#{{ $user->id }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-slate-850 rounded-xl flex items-center justify-center font-bold text-amber-400 text-sm overflow-hidden border border-slate-800/80">
                                        @if($user->profile_photo)
                                            <img src="{{ asset($user->profile_photo) }}" alt="Avatar" class="h-full w-full object-cover">
                                        @else
                                            {{ substr($user->name, 0, 2) }}
                                        @endif
                                    </div>
                                    <div class="font-bold text-white">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-300">{{ $user->email }}</td>
                            <td class="py-4 px-6 text-slate-400">{{ $user->phone_number ?: 'N/A' }}</td>
                            <td class="py-4 px-6 text-slate-400 text-xs">{{ $user->destination ?: 'N/A' }}</td>
                            <td class="py-4 px-6">
                                @if($user->logo)
                                    <div class="h-8 w-16 bg-slate-950 rounded border border-slate-850 flex items-center justify-center overflow-hidden p-1">
                                        <img src="{{ asset($user->logo) }}" alt="Logo" class="h-full object-contain">
                                    </div>
                                @else
                                    <span class="text-xs text-slate-600">No Logo</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-400 whitespace-nowrap">{{ $user->created_at->format('d M Y') }}<div class="text-slate-600 mt-1">{{ $user->created_at->format('h:i A') }}</div></td>
                            <td class="py-4 px-6 text-xs whitespace-nowrap"><span class="{{ $user->hasExpiredTrial() ? 'text-red-400' : 'text-slate-300' }}">{{ optional($user->subscription_ends_at)->format('d M Y') ?: 'No expiry' }}</span>@if($user->subscription_ends_at)<div class="text-slate-600 mt-1">{{ $user->subscription_ends_at->format('h:i A') }}</div>@endif</td>
                            <td class="py-4 px-6 text-xs"><div class="{{ $user->hasSubscriptionAccess() ? 'text-emerald-400' : 'text-red-400' }}">{{ $user->hasSubscriptionAccess() ? 'Active subscription' : 'Expired subscription' }}</div><div class="text-slate-500">{{ optional($user->subscription_ends_at)->format('d M Y') ?: 'No expiry' }}</div><div class="mt-1 capitalize text-slate-400">{{ $user->approval_status }}</div></td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2 flex-nowrap min-w-max">
                                <button onclick="viewUser({{ $user->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-amber-600/10 text-slate-400 hover:text-amber-400 transition-colors"
                                        title="View Details">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button onclick="editUser({{ $user->id }})" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-emerald-600/10 text-slate-400 hover:text-emerald-400 transition-colors"
                                        title="Edit User">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')" 
                                        class="inline-flex items-center p-1.5 rounded-lg border border-slate-700/60 hover:bg-red-600/10 text-slate-400 hover:text-red-400 transition-colors"
                                        title="Delete User">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                @if($user->hasExpiredTrial())
                                    <form method="POST" action="{{ route('admin.users.approval', $user->id) }}" class="inline">@csrf<input type="hidden" name="approval_status" value="approved"><button class="inline-flex items-center p-1.5 rounded-lg border border-emerald-500/20 bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition-colors {{ $user->approval_status === 'approved' ? 'opacity-50' : '' }}" title="Approve user" aria-label="Approve user"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></button></form>
                                    <form method="POST" action="{{ route('admin.users.approval', $user->id) }}" class="inline">@csrf<input type="hidden" name="approval_status" value="disapproved"><button class="inline-flex items-center p-1.5 rounded-lg border border-orange-500/20 bg-orange-500/10 text-orange-400 hover:bg-orange-500/20 transition-colors {{ $user->approval_status === 'disapproved' ? 'opacity-50' : '' }}" title="Disapprove user" aria-label="Disapprove user"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button></form>
                                @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-12 text-slate-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Section -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/20">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

@include('admin.users.partials.modals')
@endsection

@section('scripts')
@include('admin.users.partials.scripts')
@endsection
