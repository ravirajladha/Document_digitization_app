<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Receiver, Notification};


class NotificationController extends Controller
{

    public function showNotifications()
    {
        // dd("test");
        Notification::query()->update(['is_read' => 1]);
        $notifications = Notification::with(['compliance'])->orderBy('created_at', 'desc')
            ->get();
        // dd($notificatons);
        return view('pages.notifications', [
            'notifications' => $notifications,


        ]);
    }
}
