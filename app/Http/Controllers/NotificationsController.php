<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationsController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Notifications', [

        ]);
    }

    public function records(Request $request)
    {
        $query = Notification::query();

    }

    public function recent()
    {
        $query = Notification::query();
        $query->where('user_id', auth()->user()->id);
        $totalNotifications = $query->count();

        $notifications = $query->orderBy('created_at','desc')->limit(10)->get(['id','title','message','read','created_at']);
        
        $groupedNotifications = $notifications->groupBy(function ($notification){
            $notificationDate = Carbon::parse($notification->created_at);
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();

            if ($notificationDate->isToday()) {
                return 'Today';
            } elseif ($notificationDate->isYesterday()) {
                return 'Yesterday';
            }elseif ($notificationDate->between($today->subDays(6), $today)) {
                return $notificationDate->format('l'); // Day of the week
            } else {
                return $notificationDate->format('F j, Y'); // Full date
            }
        });

        return [
            'notifications' => $groupedNotifications,
            'count' => $totalNotifications,
        ];
    }
}
