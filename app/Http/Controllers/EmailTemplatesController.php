<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class EmailTemplatesController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/EmailTemplates', [

        ]);
    }
}
