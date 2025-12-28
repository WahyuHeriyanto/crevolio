<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = UserNotification::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = UserNotification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['is_read' => 1]);

        return redirect($notification->target_url ?? route('notifications.index'));
    }

    public function markAllAsRead()
    {
        UserNotification::where('user_id', auth()->id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
