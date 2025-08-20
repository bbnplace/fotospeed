<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;

class ContactsController extends Controller
{
    public function index()
    {
        $branches = Branch::all();

        return view('marketing.contact', [
            'title' => 'Contact Us',
            'description' => 'Get in touch with us for any inquiries or support.',
            'page' => 'contact',
            'offices' => $branches
        ]);
    }
}
