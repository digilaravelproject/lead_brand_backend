<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dealer Login - AdvisorX Pro</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/advisorx-pro-logo.jpg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-white flex items-center justify-center p-5">
    <div class="w-full max-w-md">
        <div class="flex flex-col items-center mb-8">
            <img src="{{ asset('images/advisorx-pro-logo.jpg') }}"
                 alt="AdvisorX Pro logo"
                 class="h-32 w-32 rounded-3xl object-cover border border-amber-400/50 shadow-xl shadow-amber-500/25 mb-4">
            <h1 class="text-3xl font-extrabold text-white tracking-tight text-center">AdvisorX <span class="text-amber-400">Pro</span></h1>
            <p class="text-slate-400 text-sm mt-1">Dealer Portal</p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl">
            @if($errors->any())
                <div class="mb-5 bg-red-500/10 border border-red-500/30 text-red-300 p-3 rounded-xl text-sm">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('dealer.login.submit') }}" class="space-y-5">
                @csrf
                <label class="block text-sm text-slate-400">Email Address
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-amber-500 outline-none">
                </label>
                <label class="block text-sm text-slate-400">Password
                    <input type="password" name="password" required class="mt-2 w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-amber-500 outline-none">
                </label>
                <label class="flex items-center gap-2 text-sm text-slate-400"><input type="checkbox" name="remember"> Remember me</label>
                <button class="w-full bg-amber-600 hover:bg-amber-500 rounded-xl py-3 font-semibold">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>
