<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Faq;
use App\Models\Page;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $usersCount = User::count();
        $faqsCount = Faq::count();
        $pagesCount = Page::count();

        // Get recent users and recent faqs to display on the dashboard
        $recentUsers = User::latest()->take(5)->get();
        $recentFaqs = Faq::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'usersCount',
            'faqsCount',
            'pagesCount',
            'recentUsers',
            'recentFaqs'
        ));
    }
}
