<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function getNotifications()
    {
        return response()->json(['notifications' => Auth::user()->unreadNotifications()->limit(20)->get(), 'admin' => []]);
    }

    public function getAllNotifications()
    {
        return response()->json(Auth::user()->notifications);
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(Auth::user()->notifications);
    }

    public function markAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();
    }

    public function deleteNotification(DatabaseNotification $notification)
    {
        $notification->delete();

        return response()->json();
    }
}
