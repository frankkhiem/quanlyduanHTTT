<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getListNotifications() {
        $user_id = Auth::id();
        $listNotification = Notification::where('user_id', $user_id)
                                        ->orderByDesc('id')
                                        ->paginate(10);
        return response()->json($listNotification, 200);
    }

    public function getDetailNotificationById($id) {
        $notification = Notification::find($id);
        $notification->update([
            'is_view_detail' => true,
        ]);

        return response()->json($notification, 200);
    }

    public function getNumberNewNotifications() {
        $user_id = Auth::id();
        $numberNewNotis = Notification::where('user_id', $user_id)
                                        ->where('is_watched', false)
                                        ->count();
        return response()->json($numberNewNotis, 200);
    }

    public function watchedNewNotifications() {
        $user_id = Auth::id();
        Notification::where('user_id', $user_id)
                    ->where('is_watched', false)
                    ->update([
                        'is_watched' => true,
                    ]);
       
        return response()->json([
            'message' => 'success'
        ], 200);
    }
}
