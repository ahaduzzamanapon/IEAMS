<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * View all notifications
     */
    public function index()
    {
        // Run checks dynamically to ensure fresh alerts are generated
        Notification::checkAndGenerateNotifications();

        $notifications = Notification::with('asset')->latest()->paginate(15);
        return view('setups.notifications', compact('notifications'));
    }

    /**
     * Get unread notifications for AJAX top-bar dropdown
     */
    public function unread()
    {
        Notification::checkAndGenerateNotifications();

        $unread = Notification::whereNull('read_at')->latest()->get();
        return response()->json($unread);
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->update(['read_at' => now()]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllRead()
    {
        try {
            Notification::whereNull('read_at')->update(['read_at' => now()]);
            return back()->with('success', 'All notifications marked as read.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
