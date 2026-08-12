<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dealer = Auth::guard('dealer')->user();
        $userCount = $dealer->users()->count();
        $expiredCount = $dealer->users()->where('subscription_ends_at', '<=', now())->where('approval_status', 'pending')->count();
        $recentUsers = $dealer->users()->latest()->take(5)->get();

        return view('dealer.dashboard', compact('dealer', 'userCount', 'expiredCount', 'recentUsers'));
    }
}
