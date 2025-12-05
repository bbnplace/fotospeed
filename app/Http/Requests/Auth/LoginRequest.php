<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'mobile' => ['required', 'numeric'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('mobile', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'mobile' => trans('auth.failed'),
            ]);
        }

        // Check account status after successful credential validation
        $user = Auth::user();
        
        if ($user->account_status !== User::STATUS_ACTIVE) {
            // Log the user out immediately
            Auth::logout();
            
            // Provide specific error messages based on status
            $whoToContact = $user->isCustomer() ? 'Support' : 'an Administrator';
            $errorMessage = match($user->account_status) {
                User::STATUS_INACTIVE => "Your account is inactive. Please contact $whoToContact for assistance.",
                User::STATUS_SUSPENDED_TEMP => "Your account has been temporarily suspended. Please contact $whoToContact for assistance.",
                User::STATUS_SUSPENDED_PERM => "Your account has been permanently suspended. Please contact $whoToContact for assistance.",
                default => "our account is not active. Please contact $whoToContact for assistance.",
            };
            
            RateLimiter::hit($this->throttleKey());
            
            throw ValidationException::withMessages([
                'mobile' => $errorMessage,
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'mobile' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('mobile')).'|'.$this->ip());
    }
}
