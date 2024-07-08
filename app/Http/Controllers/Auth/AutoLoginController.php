<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoLoginController extends Controller
{
    public function autoLogin(Request $request, $token)
    {
        // Check if the token is valid and not expired
        $user = User::where('auto_login_token', $token)
                    ->where('auto_login_token_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return redirect(route('login'))->withErrors(['message' => 'Invalid or expired login link.']);
        }

        // Log in the user
        Auth::login($user);

        // Clear the token
        $user->auto_login_token = null;
        $user->auto_login_token_expires_at = null;
        $user->save();

        // Redirect to the intended page or homepage
        // Get list of open invoices.
        $invoices = Invoice::where('user_id', auth()->user()->id)->where('invoice_status_id', 1)->get();
        if ($invoices->count() == 1) {
            return redirect(route('customer.invoice', [$invoices[0]->id]));
        }
        return redirect(route('customer.invoices'));
    }
}
