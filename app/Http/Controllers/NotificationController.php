<?php

namespace App\Http\Controllers;

use App\AppNotification;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get active logged-in entity (User or Company)
     */
    protected function getActiveEntity()
    {
        if (Auth::guard('company')->check()) {
            return [
                'type' => 'company',
                'id' => Auth::guard('company')->user()->id,
                'entity' => Auth::guard('company')->user(),
            ];
        } elseif (Auth::check()) {
            return [
                'type' => 'user',
                'id' => Auth::user()->id,
                'entity' => Auth::user(),
            ];
        }
        return null;
    }

    /**
     * Fetch recent notifications for header bell dropdown (JSON)
     */
    public function fetch(Request $request)
    {
        $auth = $this->getActiveEntity();
        if (!$auth) {
            return response()->json([
                'unread_count' => 0,
                'notifications' => [],
            ]);
        }

        $unreadCount = AppNotification::where('notifiable_type', $auth['type'])
            ->where('notifiable_id', $auth['id'])
            ->unread()
            ->count();

        $notifications = AppNotification::where('notifiable_type', $auth['type'])
            ->where('notifiable_id', $auth['id'])
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'message' => $item->message,
                    'type' => $item->type,
                    'icon' => $item->icon ?: 'fa-bell',
                    'color' => $item->color ?: '#2563EB',
                    'is_read' => (bool) $item->is_read,
                    'read_url' => route('notification.read', $item->id),
                    'time_ago' => $item->created_at ? $item->created_at->diffForHumans() : 'Just now',
                ];
            });

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Click notification -> Mark as read -> 302 Redirect to target URL
     */
    public function readAndRedirect($id)
    {
        $auth = $this->getActiveEntity();
        if (!$auth) {
            return redirect()->route('login');
        }

        $notification = AppNotification::where('id', $id)
            ->where('notifiable_type', $auth['type'])
            ->where('notifiable_id', $auth['id'])
            ->first();

        if ($notification) {
            $notification->markAsRead();
            $targetUrl = $notification->target_url ?: '/';
            if (strpos($targetUrl, 'http://') === 0 || strpos($targetUrl, 'https://') === 0) {
                $parsed = parse_url($targetUrl);
                $targetUrl = ($parsed['path'] ?? '/') . (isset($parsed['query']) ? '?' . $parsed['query'] : '') . (isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '');
            }
            return redirect()->to($targetUrl);
        }

        return redirect()->back();
    }

    /**
     * Mark all notifications as read for current user
     */
    public function markAllAsRead(Request $request)
    {
        $auth = $this->getActiveEntity();
        if (!$auth) {
            return response()->json(['success' => false], 401);
        }

        AppNotification::where('notifiable_type', $auth['type'])
            ->where('notifiable_id', $auth['id'])
            ->unread()
            ->update([
                'is_read' => 1,
                'read_at' => Carbon::now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Dedicated Full Notifications Center Page
     */
    public function allNotifications(Request $request)
    {
        $auth = $this->getActiveEntity();
        if (!$auth) {
            return redirect()->route('login');
        }

        $filter = $request->input('filter', 'all');

        $query = AppNotification::where('notifiable_type', $auth['type'])
            ->where('notifiable_id', $auth['id'])
            ->orderBy('id', 'desc');

        if ($filter === 'unread') {
            $query->unread();
        }

        $notifications = $query->paginate(15);
        $unreadCount = AppNotification::where('notifiable_type', $auth['type'])
            ->where('notifiable_id', $auth['id'])
            ->unread()
            ->count();

        return view('notification.all_notifications', compact('notifications', 'unreadCount', 'filter', 'auth'));
    }
}
