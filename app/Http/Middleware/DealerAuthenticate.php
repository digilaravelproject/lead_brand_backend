<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DealerAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('dealer')->check()) {
            return redirect()->route('dealer.login');
        }

        if (! Auth::guard('dealer')->user()->is_active) {
            Auth::guard('dealer')->logout();

            return redirect()->route('dealer.login')->withErrors(['email' => 'Your dealer account is inactive.']);
        }

        return $next($request);
    }
}
