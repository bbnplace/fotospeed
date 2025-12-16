<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Display the customer's profile form.
     */
    public function customerEdit(Request $request): Response
    {
        return Inertia::render('Customer/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'loyalty' => \App\Models\RewardPoint::getPointsBreakdown($request->user()->id),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'mobile'=> 'required|numeric|digits_between:10,15|unique:users,mobile,'.$request->user()->id,
            'name' => 'required|string|min:5|max:64',
            'email' => 'required|email|max:255|unique:users,email,'.$request->user()->id,
        ]);

        $request->user()->mobile = $request->mobile;
        $request->user()->name = $request->name;
        $request->user()->email = $request->email;
        $request->user()->save();

        if ($request->user()->isCustomer()) {
            return Redirect::route('customer.profile.edit');
        }

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
