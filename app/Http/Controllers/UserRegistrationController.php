<?php

namespace App\Http\Controllers;

use App\Models\CustomerGroup;
use App\Models\Group;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRegistrationController extends Controller
{

    private function getRules(): array
    {
        return [
            'name' => 'required|string|min:5|max:64',
            'mobile' => 'required|numeric|digits_between:7,16|unique:users,mobile',
            'email' => 'nullable|string|email:rfc,dns|unique:users,email',
            'state' => 'required|string|min:1|max:64|exists:states,name',
            'password' => 'required|string|min:8|max:64|confirmed',
            'password_confirmation' => 'required',
            'role' => 'required|string|min:5|max:64|exists:roles,name',
            'groups' => 'nullable|array',
            'groups.*' => sprintf('in:%s', implode(',', Group::getGroupsArray())),
        ];
    }

    public function register(Request $request)
    {
        $validated = $request->validate($this->getRules());

        $role = Role::where('name', $request->role)->first();
        $state = State::where('name', $request->state)->first();

        $user = User::create([
            'role_id' => $role->id,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'state_id' => $state->id
        ]);

        if (!empty($request->groups)) {
            CustomerGroup::saveCustomerToGroups($user->id, $request->groups);
        }

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
