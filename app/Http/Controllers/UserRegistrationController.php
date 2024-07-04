<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRegistrationController extends Controller
{
    protected $rules = [
        'name' => 'required|string|min:5|max:64',
        'mobile' => 'required|numeric|digits_between:7,16|unique:users,mobile',
        'email' => 'required|string|email:rfc,dns|unique:users,email',
        'state' => 'required|string|min:1|max:64|exists:states,name',
        'password' => 'required|string|min:8|max:64|confirmed',
        'password_confirmation' => 'required',
        'role' => 'required|string|min:5|max:64|exists:roles,name',
    ];

    public function register(Request $request)
    {
        $validated = $request->validate($this->rules);

        $role = Role::where('name', $request->role)->first();
        $state = State::where('name', $request->state)->first();

        User::create([
            'role_id' => $role->id,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'state_id' => $state->id
        ]);

        // TODO: Send login link to the customer's mobile number and email.

        return redirect()->route($this->determineRedirectRoute($request->role))
            ->with('status', 'Account Created');
    }

    private function determineRedirectRoute($role)
    {
        switch ($role)
        {
            case 'Customer':
                return 'customers';
            default:
                return 'staff';
        }
    }
}
