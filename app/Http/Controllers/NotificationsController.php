<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = 10;
        $page = $request->query("pg", 1);
        $keyphrase = $request->query("search", null);
        $additionalParams = [];

        $query = Notification::query();
        $query->where('user_id', auth()->user()->id);
        if ($keyphrase) {
            $query->where("title","like","%". $keyphrase ."%");
            $query->orwhere("message","like","%". $keyphrase ."%");
            $additionalParams["search"] = $keyphrase;
        }
        $notifications = $query->orderBy('created_at','desc')->paginate($pageSize, ['*'],'pg', $page);
        if(!empty($additionalParams)) {
            $notifications->appends($additionalParams);
        }

        return Inertia::render('Backend/Notifications/List', [
            'notifications' => $notifications,
            'keyword' => $keyphrase,
            'note' => session('note')
        ]);
    }

   
    public function notification($id)
    {
        $notification = Notification::where('user_id', auth()->user()->id)->where('id', $id)->first();
        
        return Inertia::render('Backend/Notifications/Notification', [
            'notification' => $notification,
        ]);
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

        $unreadNotification = Notification::where('user_id', auth()->user()->id)->where('read', 0)->count();
        return [
            'notifications' => $groupedNotifications,
            'count' => $totalNotifications,
            'unread' => $unreadNotification,
        ];
    }
}
