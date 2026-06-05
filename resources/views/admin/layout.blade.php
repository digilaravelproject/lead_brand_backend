<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - LeadBrand</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.05);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.2);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, 0.4);
        }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen md:h-screen md:overflow-hidden flex flex-col md:flex-row overflow-x-hidden">

    <!-- Mobile Sidebar Backdrop (Overlay) -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-40 transition-opacity duration-300 opacity-0"></div>

    <!-- Sidebar Component -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 w-72 bg-slate-900 border-r border-slate-800/80 z-50 transform -translate-x-full md:translate-x-0 md:sticky md:top-0 md:h-screen transition-transform duration-300 cubic-bezier(0.16, 1, 0.3, 1) flex flex-col">
        <!-- Sidebar Brand -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800/80">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center shadow-md shadow-indigo-500/20">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white leading-none tracking-tight">LeadBrand</h2>
                    <span class="text-xs text-indigo-400 font-semibold tracking-wider uppercase">Admin Portal</span>
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Dashboard Link -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center space-x-3 px-4 py-3.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600/15 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Manage Users Link -->
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center space-x-3 px-4 py-3.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600/15 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Manage Users</span>
            </a>

            <!-- Manage Banners Link -->
            <a href="{{ route('admin.banners.index') }}" 
               class="flex items-center space-x-3 px-4 py-3.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.banners.*') ? 'bg-indigo-600/15 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Manage Banners</span>
            </a>

            <!-- Manage Training Categories Link -->
            <a href="{{ route('admin.training-categories.index') }}" 
               class="flex items-center space-x-3 px-4 py-3.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.training-categories.*') ? 'bg-indigo-600/15 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Training Categories</span>
            </a>

            <!-- Manage Training Hub Link -->
            <a href="{{ route('admin.training-hubs.index') }}" 
               class="flex items-center space-x-3 px-4 py-3.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.training-hubs.*') ? 'bg-indigo-600/15 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span>Training Hub</span>
            </a>

            <!-- Business Tools Link -->
            <a href="{{ route('admin.tools.index') }}" 
               class="flex items-center space-x-3 px-4 py-3.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.tools.*') ? 'bg-indigo-600/15 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Business Tools</span>
            </a>

            <!-- Calendar Content Link -->
            <a href="{{ route('admin.calendar-contents.index') }}" 
               class="flex items-center space-x-3 px-4 py-3.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.calendar-contents.*') ? 'bg-indigo-600/15 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Calendar Content</span>
            </a>

            <!-- Manage Static Pages Link -->
            <a href="{{ route('admin.pages.index') }}" 
               class="flex items-center space-x-3 px-4 py-3.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.pages.*') ? 'bg-indigo-600/15 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Static Pages</span>
            </a>

            <!-- Manage FAQs Link -->
            <a href="{{ route('admin.faqs.index') }}" 
               class="flex items-center space-x-3 px-4 py-3.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('admin.faqs.*') ? 'bg-indigo-600/15 text-indigo-400 border-l-4 border-indigo-500' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Manage FAQs</span>
            </a>

        </nav>

        <!-- Sidebar Footer (Logout Info) -->
        <div class="p-4 border-t border-slate-800/80 bg-slate-950/20">
            <button onclick="document.getElementById('logout-form').submit();" 
                    class="w-full flex items-center justify-center space-x-2 px-4 py-3 rounded-xl bg-slate-800 hover:bg-red-500/15 text-slate-300 hover:text-red-400 border border-slate-700/50 hover:border-red-500/30 transition-all font-semibold text-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Log Out</span>
            </button>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-h-screen md:h-screen md:overflow-y-auto overflow-x-hidden">
        
        <!-- Header (Navbar) -->
        <header class="h-20 bg-slate-900 border-b border-slate-800/80 px-6 flex items-center justify-between sticky top-0 z-30">
            <!-- Mobile Toggle / Breadcrumb -->
            <div class="flex items-center space-x-4">
                <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-slate-800 p-2 rounded-xl border border-slate-800/80 hover:bg-slate-800/40 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex items-center space-x-2 text-sm text-slate-400">
                    <span class="font-semibold text-white text-base">@yield('page_title', 'Dashboard')</span>
                </div>
            </div>

            <!-- Navbar Actions -->
            <div class="flex items-center space-x-4 relative">
                
                <!-- Profile Dropdown Button -->
                <div class="relative">
                    <button onclick="toggleDropdown('profile-dropdown')" 
                            class="flex items-center space-x-3 p-1.5 rounded-2xl hover:bg-slate-800/50 border border-transparent hover:border-slate-800/80 transition-all focus:outline-none text-left">
                        <div class="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-extrabold uppercase shadow-sm overflow-hidden">
                            @if(Auth::guard('admin')->user()->profile_photo)
                                <img src="{{ asset(Auth::guard('admin')->user()->profile_photo) }}" alt="Avatar" class="h-full w-full object-cover">
                            @else
                                {{ substr(Auth::guard('admin')->user()->name, 0, 2) }}
                            @endif
                        </div>
                        <div class="hidden sm:block pr-2">
                            <div class="text-sm font-semibold text-white leading-none">{{ Auth::guard('admin')->user()->name }}</div>
                            <span class="text-xs text-slate-400">Administrator</span>
                        </div>
                        <svg class="h-4 w-4 text-slate-400 hidden sm:block transition-transform duration-200" id="dropdown-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profile-dropdown" class="hidden absolute right-0 mt-3 w-56 bg-slate-900 border border-slate-800/80 rounded-2xl shadow-xl py-2 z-40 transform origin-top-right transition-all">
                        <div class="px-4 py-2.5 border-b border-slate-800/80">
                            <span class="block text-xs text-slate-400">Signed in as</span>
                            <span class="block text-sm font-semibold text-white truncate">{{ Auth::guard('admin')->user()->email }}</span>
                        </div>
                        <button onclick="openModal('profile-modal'); closeDropdown('profile-dropdown');" 
                                class="w-full flex items-center space-x-2 px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-800 hover:text-white transition-colors text-left">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Update Profile</span>
                        </button>
                        <button onclick="document.getElementById('logout-form').submit();" 
                                class="w-full flex items-center space-x-2 px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors text-left border-t border-slate-800/80 mt-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Log Out</span>
                        </button>
                    </div>
                </div>

            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 p-6 md:p-8 bg-slate-950">
            <!-- Toast notification system -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-sm flex items-center space-x-3 shadow-lg shadow-emerald-950/20 animate-fade-in">
                    <svg class="h-5 w-5 text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any() && !session('errors_updated_profile'))
                <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-300 text-sm flex-col space-y-1 shadow-lg shadow-red-950/20 animate-fade-in">
                    <div class="font-bold flex items-center space-x-2 text-red-400">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Action failed! Please check details below.</span>
                    </div>
                    <ul class="list-disc list-inside mt-1 text-red-300 pl-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Update Profile Modal -->
    <div id="profile-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('profile-modal')"></div>
        
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl z-10 animate-fade-in max-h-[90vh] flex flex-col">
            @csrf
            <!-- Header -->
            <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
                <h3 class="text-lg font-bold text-white">Update Admin Profile</h3>
                <button type="button" onclick="closeModal('profile-modal')" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form Body -->
            <div class="p-6 overflow-y-auto space-y-5 flex-1 scrollbar">
                <!-- Profile Image Selection -->
                <div class="flex items-center space-x-5">
                    <div class="h-20 w-20 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-extrabold text-2xl uppercase shadow-md overflow-hidden relative group border border-slate-700/50" id="avatar-preview-box">
                        @if(Auth::guard('admin')->user()->profile_photo)
                            <img src="{{ asset(Auth::guard('admin')->user()->profile_photo) }}" alt="Avatar" id="avatar-preview-img" class="h-full w-full object-cover">
                        @else
                            <span id="avatar-preview-placeholder">{{ substr(Auth::guard('admin')->user()->name, 0, 2) }}</span>
                        @endif
                    </div>
                    <div>
                        <label for="profile_photo" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Change Profile Photo</label>
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" onchange="previewAvatar(this)"
                               class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 file:cursor-pointer cursor-pointer">
                        <p class="text-xs text-slate-500 mt-1">Accepts PNG, JPG, JPEG up to 2MB.</p>
                    </div>
                </div>

                <div>
                    <label for="profile_name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Display Name</label>
                    <input type="text" name="name" id="profile_name" value="{{ Auth::guard('admin')->user()->name }}" required
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-3 px-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                </div>

                <div>
                    <label for="profile_email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" id="profile_email" value="{{ Auth::guard('admin')->user()->email }}" required
                           class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-3 px-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm">
                </div>

                <div class="border-t border-slate-800 pt-4">
                    <p class="text-xs text-slate-400 mb-4">Leave password fields blank if you do not want to change it.</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="profile_password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">New Password</label>
                            <input type="password" name="password" id="profile_password"
                                   class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-3 px-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm"
                                   placeholder="Min. 6 chars">
                        </div>
                        <div>
                            <label for="profile_password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="profile_password_confirmation"
                                   class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-3 px-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm"
                                   placeholder="Repeat password">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-slate-800 px-6 py-5 flex justify-end space-x-3 bg-slate-950/40 flex-shrink-0">
                <button type="button" onclick="closeModal('profile-modal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-700/60 hover:bg-slate-800 text-slate-300 hover:text-white transition-colors text-sm font-semibold">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white shadow-md shadow-indigo-500/10 transition-colors text-sm font-semibold">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Layout JS (Vanilla, zero deps) -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                // Open sidebar
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.add('opacity-100');
                }, 20);
            } else {
                // Close sidebar
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.remove('opacity-100');
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                }, 300);
            }
        }

        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const arrow = document.getElementById('dropdown-arrow');
            
            if (dropdown.classList.contains('hidden')) {
                // Open dropdown
                dropdown.classList.remove('hidden');
                // Animation trigger
                dropdown.style.opacity = '0';
                dropdown.style.transform = 'scale(0.95) translateY(-10px)';
                setTimeout(() => {
                    dropdown.style.transition = 'all 0.2s cubic-bezier(0.16, 1, 0.3, 1)';
                    dropdown.style.opacity = '1';
                    dropdown.style.transform = 'scale(1) translateY(0)';
                }, 10);
                if (arrow) arrow.classList.add('rotate-180');
            } else {
                closeDropdown(dropdownId);
            }
        }

        function closeDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const arrow = document.getElementById('dropdown-arrow');
            
            dropdown.style.opacity = '0';
            dropdown.style.transform = 'scale(0.95) translateY(-10px)';
            setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 200);
            if (arrow) arrow.classList.remove('rotate-180');
        }

        // Close dropdowns on outside click
        window.addEventListener('click', function(e) {
            const btn = document.querySelector('[onclick*="toggleDropdown"]');
            const dropdown = document.getElementById('profile-dropdown');
            if (dropdown && !dropdown.classList.contains('hidden') && !btn.contains(e.target) && !dropdown.contains(e.target)) {
                closeDropdown('profile-dropdown');
            }
        });

        // Modal triggers
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
        }

        // Preview profile image upload
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let img = document.getElementById('avatar-preview-img');
                    const box = document.getElementById('avatar-preview-box');
                    const placeholder = document.getElementById('avatar-preview-placeholder');
                    
                    if (!img) {
                        img = document.createElement('img');
                        img.id = 'avatar-preview-img';
                        img.className = 'h-full w-full object-cover';
                        box.innerHTML = '';
                        box.appendChild(img);
                    }
                    img.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
