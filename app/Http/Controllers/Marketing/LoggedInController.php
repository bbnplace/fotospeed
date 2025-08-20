<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoggedInController extends Controller
{
    public function loggedIn(Request $request)
    {
        // Logic for handling logged-in user actions
        // This could include fetching user-specific data, preferences, etc.
        
        return Inertia::render('Client/LoggedIn', [
            'title' => 'Welcome Back',
            'description' => 'Manage your orders and preferences.',
            'product' => $request->input('product') ? $request->input('product') : null,
        ]);
    }
}
