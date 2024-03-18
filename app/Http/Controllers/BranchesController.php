<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BranchesController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Branch/List', [
            'endpoint' => ''
        ]);
    }

    public function add()
    {
        return Inertia::render('Backend/Branch/Add', [
            'states' => State::getStatesArray()
        ]);
    }


    public function edit($ref)
    {
        $branch = Branch::where('id', $ref)->first();

        return Inertia::render('Backend/Branch/Edit', [
            'states' => State::getStatesArray(),
            'branch' => $branch
        ]);
    }
}
