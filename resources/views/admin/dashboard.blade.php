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
    .stat-card-glow-gold:hover { box-shadow: 0 10px 30px -10px rgba(245, 158, 11, 0.25); }
    .stat-card-glow-yellow:hover { box-shadow: 0 10px 30px -10px rgba(234, 179, 8, 0.25); }
    .stat-card-glow-amber:hover { box-shadow: 0 10px 30px -10px rgba(245, 158, 11, 0.25); }
    .stat-card-glow-sky:hover { box-shadow: 0 10px 30px -10px rgba(14, 165, 233, 0.25); }
    .stat-card-glow-emerald:hover { box-shadow: 0 10px 30px -10px rgba(16, 185, 129, 0.25); }
    .stat-card-glow-rose:hover { box-shadow: 0 10px 30px -10px rgba(244, 63, 94, 0.25); }
    .stat-card-glow-orange:hover { box-shadow: 0 10px 30px -10px rgba(249, 115, 22, 0.25); }
    .stat-card-glow-teal:hover { box-shadow: 0 10px 30px -10px rgba(20, 184, 166, 0.25); }
</style>
@endsection

@section('content')
<div class="space-y-8 animate-slide-up">
    <!-- Welcome section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-amber-950/20 to-slate-900 border border-slate-800/80 rounded-3xl p-6 md:p-8">
        <!-- Floating shapes -->
        <div class="absolute right-0 top-0 -mt-8 -mr-8 h-40 w-40 rounded-full bg-amber-500/10 blur-3xl"></div>
        <div class="absolute right-20 bottom-0 -mb-8 h-32 w-32 rounded-full bg-yellow-500/10 blur-2xl"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white">Welcome back, {{ Auth::guard('admin')->user()->name }}!</h1>
                <p class="text-slate-400 mt-1">Here is a quick summary of what is happening with AdvisorX Pro today.</p>
            </div>
            <div class="flex items-center space-x-2 bg-slate-800/50 backdrop-blur-md px-4 py-2 border border-slate-700/50 rounded-2xl text-xs font-bold text-amber-400 w-fit">
                <span class="h-2 w-2 bg-amber-500 rounded-full animate-ping"></span>
                <span>System Online</span>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid (Modified to show all integrated functionalities) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Users Card -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 stat-card-glow-gold group flex flex-col justify-between min-h-[145px]">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Registered Users</span>
                    <h3 class="text-3xl font-black text-white mt-2">{{ $usersCount }}</h3>
                </div>
                <div class="h-11 w-11 bg-amber-600/10 border border-amber-500/20 rounded-xl flex items-center justify-center text-amber-400 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300 shadow-md">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs font-semibold text-amber-400">
                <a href="{{ route('admin.users.index') }}" class="hover:underline flex items-center space-x-1">
                    <span>Manage all users</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Banners Card -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 stat-card-glow-yellow group flex flex-col justify-between min-h-[145px]">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Active Banners</span>
                    <h3 class="text-3xl font-black text-white mt-2">{{ $bannersCount }}</h3>
                </div>
                <div class="h-11 w-11 bg-yellow-600/10 border border-yellow-500/20 rounded-xl flex items-center justify-center text-yellow-400 group-hover:bg-yellow-600 group-hover:text-white transition-all duration-300 shadow-md">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs font-semibold text-yellow-400">
                <a href="{{ route('admin.banners.index') }}" class="hover:underline flex items-center space-x-1">
                    <span>Manage banners</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Training Categories Card -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 stat-card-glow-amber group flex flex-col justify-between min-h-[145px]">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Training Categories</span>
                    <h3 class="text-3xl font-black text-white mt-2">{{ $trainingCategoriesCount }}</h3>
                </div>
                <div class="h-11 w-11 bg-amber-600/10 border border-amber-500/20 rounded-xl flex items-center justify-center text-amber-400 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300 shadow-md">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs font-semibold text-amber-400">
                <a href="{{ route('admin.training-categories.index') }}" class="hover:underline flex items-center space-x-1">
                    <span>Manage categories</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Training Hub resources -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 stat-card-glow-sky group flex flex-col justify-between min-h-[145px]">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Training Media</span>
                    <h3 class="text-3xl font-black text-white mt-2">{{ $trainingHubCount }}</h3>
                </div>
                <div class="h-11 w-11 bg-sky-600/10 border border-sky-500/20 rounded-xl flex items-center justify-center text-sky-400 group-hover:bg-sky-600 group-hover:text-white transition-all duration-300 shadow-md">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs font-semibold text-sky-400">
                <span class="text-[10px] text-slate-500 font-mono">{{ $trainingPdfsCount }} PDFs • {{ $trainingVideosCount }} Videos</span>
                <a href="{{ route('admin.training-hubs.index') }}" class="hover:underline flex items-center space-x-1">
                    <span>Library</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Business Tools Card -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 stat-card-glow-emerald group flex flex-col justify-between min-h-[145px]">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Business Tools</span>
                    <h3 class="text-3xl font-black text-white mt-2">{{ $toolsCount }}</h3>
                </div>
                <div class="h-11 w-11 bg-emerald-600/10 border border-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shadow-md">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs font-semibold text-emerald-400">
                <span class="text-[10px] text-slate-500 font-mono">{{ $subtoolsCount }} Subs • {{ $toolMediaCount }} Medias</span>
                <a href="{{ route('admin.tools.index') }}" class="hover:underline flex items-center space-x-1">
                    <span>Manage tools</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Calendar Content Card -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 stat-card-glow-rose group flex flex-col justify-between min-h-[145px]">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Calendar PDF Files</span>
                    <h3 class="text-3xl font-black text-white mt-2">{{ $calendarCount }}</h3>
                </div>
                <div class="h-11 w-11 bg-rose-600/10 border border-rose-500/20 rounded-xl flex items-center justify-center text-rose-400 group-hover:bg-rose-600 group-hover:text-white transition-all duration-300 shadow-md">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs font-semibold text-rose-400">
                <a href="{{ route('admin.calendar-contents.index') }}" class="hover:underline flex items-center space-x-1">
                    <span>Manage calendar</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Static Pages Card -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 stat-card-glow-orange group flex flex-col justify-between min-h-[145px]">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Static Pages</span>
                    <h3 class="text-3xl font-black text-white mt-2">{{ $pagesCount }}</h3>
                </div>
                <div class="h-11 w-11 bg-orange-600/10 border border-orange-500/20 rounded-xl flex items-center justify-center text-orange-400 group-hover:bg-orange-600 group-hover:text-white transition-all duration-300 shadow-md">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs font-semibold text-orange-400">
                <a href="{{ route('admin.pages.index') }}" class="hover:underline flex items-center space-x-1">
                    <span>Manage pages</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- FAQs Card -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 transition-all duration-300 transform hover:-translate-y-1 stat-card-glow-teal group flex flex-col justify-between min-h-[145px]">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">FAQs Published</span>
                    <h3 class="text-3xl font-black text-white mt-2">{{ $faqsCount }}</h3>
                </div>
                <div class="h-11 w-11 bg-teal-600/10 border border-teal-500/20 rounded-xl flex items-center justify-center text-teal-400 group-hover:bg-teal-600 group-hover:text-white transition-all duration-300 shadow-md">
                    <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs font-semibold text-teal-400">
                <a href="{{ route('admin.faqs.index') }}" class="hover:underline flex items-center space-x-1">
                    <span>Manage FAQs</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

    </div>

    <!-- Listings & Live Timeline Feed Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Users table -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-base font-bold text-white">Recent Registrations</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-amber-400 font-semibold hover:underline">View All</a>
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
                                        <div class="h-9 w-9 bg-slate-800 rounded-xl flex items-center justify-center font-bold text-amber-400 text-xs overflow-hidden">
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

        <!-- Recent Activity Feed (System Notifications) -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-3xl p-6 flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-base font-bold text-white">Live Activity Feed</h3>
                <span class="text-[10px] text-slate-500 uppercase tracking-wider font-semibold">Latest updates</span>
            </div>

            <div class="flex-1 space-y-4 overflow-y-auto max-h-[350px] pr-1 scrollbar">
                @forelse($recentNotifications as $notification)
                    <div class="p-4 bg-slate-950/40 border border-slate-850 rounded-2xl flex items-start space-x-3 hover:bg-slate-900/40 transition-colors">
                        <!-- Left indicator color based on type -->
                        @php
                            $badgeColor = 'bg-slate-700';
                            if ($notification->type === 'training') $badgeColor = 'bg-sky-500';
                            elseif ($notification->type === 'tools') $badgeColor = 'bg-emerald-500';
                            elseif ($notification->type === 'banner') $badgeColor = 'bg-yellow-500';
                            elseif ($notification->type === 'user') $badgeColor = 'bg-amber-500';
                        @endphp
                        <span class="h-2.5 w-2.5 rounded-full {{ $badgeColor }} mt-1.5 flex-shrink-0 animate-pulse"></span>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-bold text-sm text-white truncate">{{ $notification->title }}</span>
                                <span class="text-[10px] text-slate-500 font-medium whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">{{ $notification->message }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-500 flex flex-col items-center justify-center space-y-2">
                        <svg class="h-10 w-10 text-slate-750" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>No recent activity logs recorded.</span>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
