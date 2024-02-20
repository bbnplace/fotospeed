<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationsController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Notifications', [

        ]);
    }
}
