<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class GroupsController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/Group/List', [

        ]);
    }

    public function add()
    {
        return Inertia::render('Backend/Group/Add', [
        ]);
    }


    public function edit($ref)
    {

        return Inertia::render('Backend/Group/Edit', [
        ]);
    }
}
