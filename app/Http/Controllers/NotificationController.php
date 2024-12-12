<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function readNotification($notification_id)
    {
        $notification = Notification::find($notification_id);

        $notification->is_read = 1;

        $notification->save();
    }
}
