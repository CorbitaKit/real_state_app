<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function readNotification($notification_id)
    {
        $notification = Notification::find($notification_id);

        $notification->is_read = 1;

        $notification->save();
    }

    public function clearNotification(): void
    {
        $user = Auth::user();
        $notifications = Notification::where('is_read', 0)->get();
        foreach ($notifications as $notification) {

            $notification->is_read = 1;

            $notification->save();
        }
    }
}
