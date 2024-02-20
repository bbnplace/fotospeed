<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SmsTemplatesController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/SmsTemplates', [

        ]);
    }
}
