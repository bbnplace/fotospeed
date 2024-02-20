<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class OrdersController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Orders', [

        ]);
    }

    public function add()
    {
        return Inertia::render('Backend/OrderCreate', [

        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            
        ]);
    }
}
