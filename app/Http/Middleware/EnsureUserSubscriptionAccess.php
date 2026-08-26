<?php

namespace App\Http\Middleware;

use App\Models\Admin;
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
            $dealerExpired = $user->dealer_id !== null && ! $user->dealer?->hasSubscriptionAccess();
            $admin = Admin::query()->first();
            $adminNumbers = array_values(array_filter([
                $admin?->phone_number,
                $admin?->alternative_phone_number,
            ]));

            return response()->json([
                'status' => false,
                'message' => $dealerExpired
                    ? 'Your dealer subscription has expired. Please contact the administrator to renew it.'
                    : ($user->approval_status === 'disapproved'
                        ? 'Your subscription has been disapproved by the dealer.'
                        : 'Your four-day free subscription has ended and is awaiting dealer approval.'),
                'data' => [
                    'subscription_required' => true,
                    'dealer_subscription_expired' => $dealerExpired,
                    'admin_mobile_numbers' => $adminNumbers,
                ],
            ], 403);
        }

        return $next($request);
    }
}
