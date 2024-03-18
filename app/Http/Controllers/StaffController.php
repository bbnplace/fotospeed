<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StaffController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Staff/List', [
            'endpoint' => ''
        ]);
    }

    public function add()
    {
        return Inertia::render('Backend/Staff/Add', [
            'states' => State::getStatesArray()
        ]);
    }


    public function edit($ref)
    {
        $user = User::where('id', $ref)->first();

        return Inertia::render('Backend/Staff/Edit', [
            'states' => State::getStatesArray(),
            'user' => $user
        ]);
    }
}
