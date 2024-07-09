<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PermissionsController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Permissions', [

        ]);
    }

    public function restrictionNotice()
    {
        return Inertia::render('AdminOnly', [
            
        ]);
    }
}
