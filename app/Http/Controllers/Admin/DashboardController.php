<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Banner;
use App\Models\TrainingCategory;
use App\Models\TrainingHub;
use App\Models\Tool;
use App\Models\Subtool;
use App\Models\ToolMedia;
use App\Models\CalendarContent;
use App\Models\Notification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $usersCount = User::count();
        $bannersCount = Banner::count();
        $trainingCategoriesCount = TrainingCategory::count();
        $trainingHubCount = TrainingHub::count();
        $trainingPdfsCount = TrainingHub::where('type', 'pdf')->count();
        $trainingVideosCount = TrainingHub::where('type', 'video')->count();

        $toolsCount = Tool::count();
        $subtoolsCount = Subtool::count();
        $toolMediaCount = ToolMedia::count();

        $calendarCount = CalendarContent::count();
        $pagesCount = Page::count();
        $faqsCount = Faq::count();

        // Get recent users and recent activity notifications to display on the dashboard
        $recentUsers = User::latest()->take(5)->get();
        $recentNotifications = Notification::latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'usersCount',
            'bannersCount',
            'trainingCategoriesCount',
            'trainingHubCount',
            'trainingPdfsCount',
            'trainingVideosCount',
            'toolsCount',
            'subtoolsCount',
            'toolMediaCount',
            'calendarCount',
            'pagesCount',
            'faqsCount',
            'recentUsers',
            'recentNotifications'
        ));
    }
}
