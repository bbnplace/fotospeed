<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Login;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Get the user's role
        $role = Role::where('id', auth()->user()->role_id)->first();

        Login::create([
            'user_id' => auth()->user()->id,
            'session_token' => session()->getId(),
            'ip_address' => $request->ip()
        ]);

        if (auth()->user()->is_temporary_password) {
            return redirect()->route('password.change');
        }

        return redirect()->intended($role->name == "Customer" ? RouteServiceProvider::CUSTOMER_HOME : RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->registerLogout(); // Register the logout

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('login'));
    }


    private function registerLogout()
    {
        $session = Login::where('session_token', session()->getId())->first();
        if($session){
            $session->logged_out = true;
            $session->save();
        }
    }
}
