<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Get all notifications (latest first) and the count of unread items.
     */
    public function index()
    {
        try {
            // Order by id desc as requested
            $notifications = Notification::orderBy('id', 'desc')->get();
            $unreadCount = Notification::where('is_read', false)->count();

            // Transform notifications to include formatted time_ago
            $formattedNotifications = $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'is_read' => (bool)$notification->is_read,
                    'created_at' => $notification->created_at->toIso8601String(),
                    'updated_at' => $notification->updated_at->toIso8601String(),
                    'time_ago' => $notification->created_at->diffForHumans(),
                ];
            });

            return response()->json([
                'status' => 'success',
                'unread_count' => $unreadCount,
                'notifications' => $formattedNotifications,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllRead()
    {
        try {
            Notification::where('is_read', false)->update(['is_read' => true]);

            return response()->json([
                'status' => 'success',
                'message' => 'All notifications marked as read'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to mark notifications as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single notification by id.
     */
    public function show($id)
    {
        try {
            $notification = Notification::find($id);

            if (!$notification) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Notification not found'
                ], 404);
            }

            $formatted = [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'is_read' => (bool)$notification->is_read,
                'created_at' => $notification->created_at->toIso8601String(),
                'updated_at' => $notification->updated_at->toIso8601String(),
                'time_ago' => $notification->created_at->diffForHumans(),
            ];

            return response()->json([
                'status' => 'success',
                'notification' => $formatted,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch notification: ' . $e->getMessage()
            ], 500);
        }
    }
}
