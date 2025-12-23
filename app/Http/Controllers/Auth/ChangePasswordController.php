<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Cecula\Flow\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class ChangePasswordController extends Controller
{
    public function show()
    {
        return Inertia::render('Auth/ChangePassword');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::find(auth()->id());
        
        $user->update([
            'password' => Hash::make($request->password),
            'is_temporary_password' => false,
        ]);

        return redirect()->route('password.changed');
    }

    public function confirmation()
    {
        return Inertia::render('Auth/PasswordChanged');
    }
}
