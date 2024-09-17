<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Récupère toutes les notifications
    public function getRecentNotifications()
    {
        $user = Auth::user();
    
        // Récupère les 20 dernières notifications
        $notifications = $user->notifications()->latest()->limit(20)->get();
    
        // Compte le nombre de notifications non lues parmi les 20 dernières
        $unreadCount = $notifications->where('read_at', null)->count();
    
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
    

    // Marquer toutes les notifications comme lues
    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return response()->noContent();
    }
}
