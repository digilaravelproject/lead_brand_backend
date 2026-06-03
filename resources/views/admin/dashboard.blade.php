@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Analytics Overview')

@section('styles')
<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-up {
        animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .stat-card-glow-indigo:hover {
        box-shadow: 0 10px 30px -10px rgba(99, 102, 241, 0.2);
    }
    .stat-card-glow-purple:hover {
        box-shadow: 0 10px 30px -10px rgba(168, 85, 247, 0.2);
    }
    .stat-card-glow-pink:hover {
        box-shadow: 0 10px 30px -10px rgba(236, 72, 153, 0.2);
    }
</style>
@endsection

@section('content')
<div class="space-y-8 animate-slide-up">
    <!-- Welcome section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-indigo-950/20 to-slate-900 border border-slate-800/80 rounded-3xl p-6 md:p-8">
        <!-- Floating shapes -->
        <div class="absolute right-0 top-0 -mt-8 -mr-8 h-40 w-40 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="absolute right-20 bottom-0 -mb-8 h-32 w-32 rounded-full bg-purple-500/10 blur-2xl"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white">Welcome back, {{ Auth::guard('admin')->user()->name }}!</h1>
                <p class="text-slate-400 mt-1">Here is a quick summary of what is happening with LeadBrand today.</p>
            </div>
            <div class="flex items-center space-x-2 bg-slate-800/50 backdrop-blur-md px-4 py-2 border border-slate-700/50 rounded-2xl text-xs font-bold text-indigo-400">
                <span class="h-2 w-2 bg-indigo-500 rounded-full animate-ping"></span>
                <span>System Online</span>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Users Card -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 stat-card-glow-indigo group">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Registered Users</span>
                    <h3 class="text-3xl font-black text-white mt-2">{{ $usersCount }}</h3>
                </div>
                <div class="h-12 w-12 bg-indigo-600/10 border border-indigo-500/20 rounded-2xl flex items-center justify-center text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-md">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-5 flex items-center space-x-1.5 text-xs font-semibold text-indigo-400">
                <a href="{{ route('admin.users.index') }}" class="hover:underline flex items-center space-x-1">
                    <span>Manage all users</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- FAQs Card -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 stat-card-glow-purple group">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total FAQs</span>
                    <h3 class="text-3xl font-black text-white mt-2">{{ $faqsCount }}</h3>
                </div>
                <div class="h-12 w-12 bg-purple-600/10 border border-purple-500/20 rounded-2xl flex items-center justify-center text-purple-400 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300 shadow-md">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-5 flex items-center space-x-1.5 text-xs font-semibold text-purple-400">
                <a href="{{ route('admin.faqs.index') }}" class="hover:underline flex items-center space-x-1">
                    <span>Manage FAQ records</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Static Pages Card -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 stat-card-glow-pink group">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Static Pages</span>
                    <h3 class="text-3xl font-black text-white mt-2">{{ $pagesCount }}</h3>
                </div>
                <div class="h-12 w-12 bg-pink-600/10 border border-pink-500/20 rounded-2xl flex items-center justify-center text-pink-400 group-hover:bg-pink-600 group-hover:text-white transition-all duration-300 shadow-md">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-5 flex items-center space-x-1.5 text-xs font-semibold text-pink-400">
                <a href="{{ route('admin.pages.index') }}" class="hover:underline flex items-center space-x-1">
                    <span>Manage static content</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Listing Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Users table -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-base font-bold text-white">Recent Registrations</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-indigo-400 font-semibold hover:underline">View All</a>
            </div>
            
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="border-b border-slate-800/80 text-xs text-slate-400 font-semibold uppercase tracking-wider">
                            <th class="pb-3 pl-2">User</th>
                            <th class="pb-3">Destination</th>
                            <th class="pb-3 text-right">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/30">
                        @forelse($recentUsers as $user)
                            <tr class="text-slate-300 hover:bg-slate-800/20 transition-colors">
                                <td class="py-3.5 pl-2">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-9 w-9 bg-slate-800 rounded-xl flex items-center justify-center font-bold text-indigo-400 text-xs overflow-hidden">
                                            @if($user->profile_photo)
                                                <img src="{{ asset($user->profile_photo) }}" alt="Avatar" class="h-full w-full object-cover">
                                            @else
                                                {{ substr($user->name, 0, 2) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-semibold text-white leading-none">{{ $user->name }}</div>
                                            <span class="text-xs text-slate-500">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 text-xs">{{ $user->destination ?: 'N/A' }}</td>
                                <td class="py-3.5 text-right text-xs text-slate-500">{{ $user->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-8 text-slate-500">No users registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent FAQs -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-base font-bold text-white">Recent FAQ Inquiries</h3>
                <a href="{{ route('admin.faqs.index') }}" class="text-xs text-indigo-400 font-semibold hover:underline">View All</a>
            </div>

            <div class="flex-1 space-y-4 overflow-y-auto max-h-[350px] pr-1">
                @forelse($recentFaqs as $faq)
                    <div class="p-4 bg-slate-950/40 border border-slate-850 rounded-2xl space-y-2">
                        <div class="flex items-start justify-between gap-2">
                            <span class="font-bold text-sm text-white">{{ $faq->question }}</span>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $faq->status == 1 ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-800 text-slate-400 border border-slate-700/50' }}">
                                {{ $faq->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 line-clamp-2">{{ strip_tags($faq->answer) }}</p>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-500">No FAQ inquiries posted yet.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
