<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserSubscriptionAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $user?->refresh();
        if ($user && ! $user->hasSubscriptionAccess()) {
            return response()->json([
                'status' => false,
                'message' => $user->approval_status === 'disapproved'
                    ? 'Your subscription has been disapproved by the dealer.'
                    : 'Your four-day free subscription has ended and is awaiting dealer approval.',
            ], 403);
        }

        return $next($request);
    }
}
