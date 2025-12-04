<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerRegistrationController extends Controller
{
    public function create()
    {
        return view('marketing.signup', [
            'title' => 'Sign Up',
            'description' => 'Create an account to get started with Fotospeed.',
            'page' => 'signup',
            'states' => State::orderBy('name')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|numeric|digits_between:9,14|unique:users,mobile',
            'email' => 'nullable|string|email:rfc,dns|max:255|unique:users,email',
            'state' => 'required|string|exists:states,name',
            'intended_use' => 'required|string|max:1000',
        ]);

        $state = State::where('name', $request->state)->first();
        $role = Role::where('name', 'Customer')->first();

        $user = User::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'state_id' => $state->id,
            'role_id' => $role->id,
            'intended_use' => $request->intended_use,
            'account_status' => User::STATUS_INACTIVE,
            'password' => Hash::make(Str::random(16)), // Random password as they need admin approval
        ]);

        return redirect()->route('signup')->with('success', 'Sent! Your request is pending approval. You will be contacted by an administrator.');
    }
}
