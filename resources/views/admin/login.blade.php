<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - LeadBrand</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .bg-gradient-mesh {
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, rgb(49, 46, 129) 0, transparent 50%), 
                radial-gradient(at 100% 100%, rgb(88, 28, 135) 0, transparent 50%),
                radial-gradient(at 100% 0%, rgb(15, 23, 42) 0, transparent 50%),
                radial-gradient(at 0% 100%, rgb(67, 56, 202) 0, transparent 50%);
        }
    </style>
</head>
<body class="bg-gradient-mesh min-h-screen flex items-center justify-center p-4 md:p-6 overflow-x-hidden">

    <div class="w-full max-w-md animate-fade-in">
        
        <!-- Logo/Brand Icon -->
        <div class="flex flex-col items-center mb-8">
            <div class="h-16 w-16 bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/30 mb-3 transform hover:rotate-12 transition-transform duration-300">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Lead<span class="text-indigo-400 font-normal">Brand</span></h1>
            <p class="text-slate-400 text-sm mt-1">Administration Control Suite</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/80 border border-slate-800/80 backdrop-blur-xl rounded-3xl p-8 shadow-2xl relative overflow-hidden">
            <!-- Decorative light stripe -->
            <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent"></div>
            
            <h2 class="text-xl font-semibold text-white mb-6 text-center">Sign in to Admin Account</h2>

            <!-- Errors Alert -->
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-200 text-sm">
                    <div class="font-medium">Please fix the following error(s):</div>
                    <ul class="list-disc list-inside mt-1 text-red-300">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-200 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                               class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-3 pl-10 pr-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm"
                               placeholder="admin@leadbrand.com">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Password</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" name="password" id="password" required
                               class="w-full bg-slate-950/60 border border-slate-800 rounded-xl py-3 pl-10 pr-4 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition-all text-sm"
                               placeholder="••••••••••••">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" 
                           class="h-4 w-4 rounded bg-slate-950 border-slate-800 text-indigo-600 focus:ring-indigo-500/40 focus:ring-offset-slate-900 focus:ring-2">
                    <label for="remember" class="ml-2 text-sm text-slate-400 cursor-pointer">Remember my device</label>
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 shadow-lg shadow-indigo-500/20 text-sm">
                    Access Dashboard
                </button>
            </form>

            <!-- Pre-filled info for easy copying -->
            <div class="mt-8 pt-6 border-t border-slate-800/80">
                <div class="bg-indigo-950/40 border border-indigo-900/30 rounded-2xl p-4 text-xs text-slate-300">
                    <div class="flex items-center space-x-1.5 text-indigo-400 font-bold mb-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Demo Credentials</span>
                    </div>
                    <div class="space-y-1 font-mono">
                        <div class="flex justify-between items-center">
                            <span>Email: <span class="text-white select-all">admin@leadbrand.com</span></span>
                            <button onclick="navigator.clipboard.writeText('admin@leadbrand.com'); alert('Email copied!');" class="text-indigo-400 hover:text-white px-1">Copy</button>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Pass: <span class="text-white select-all">Admin@123</span></span>
                            <button onclick="navigator.clipboard.writeText('Admin@123'); alert('Password copied!');" class="text-indigo-400 hover:text-white px-1">Copy</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-6 text-xs text-slate-500">
            &copy; 2026 LeadBrand. All rights reserved.
        </div>
    </div>

</body>
</html>
