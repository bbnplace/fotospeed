<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HeartbeatController extends Controller
{
    /**
     * Keep session alive and refresh CSRF token
     */
    public function ping(Request $request)
    {
        // Simply touching the session keeps it alive
        $request->session()->put('last_activity', now());
        
        return response()->json([
            'status' => 'alive',
            'csrf_token' => csrf_token(),
            'timestamp' => now()->toIso8601String()
        ]);
    }
}
