<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Cecula\Flow\Models\RewardPoint;
use Cecula\Flow\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
       return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $userData = $request->user();
        if ($userData) {
            $role = Role::where('id', $userData->role_id)->first();
            $userData = [
                'id' => $userData->id,
                'email' => $userData->email,
                'mobile' => $userData->mobile,
                'name' => $userData->name,
                'branch_id' => $userData->branch_id,
                'role' => $role->name,
                'role_id' => $userData->role_id, // For Pusher channel subscriptions
                'role_ref' => $userData->role_id,
                'isAdmin' => $userData->isAdmin(),
                'isClient' => $userData->isCustomer(),
                'isAdminBranch' => $userData->branch ? $userData->branch->is_administrative == 1 : false,
                'points' => RewardPoint::getAvailablePoints($userData->id),
            ];
        }

        return [
            ...parent::share($request),
            'site' => [
                'name' => config('app.name'),
                'url' => config('app.url'),
                'homeRoute' => route('marketing.home'),
                'logo' => asset('images/logo.png'),
                'favicon' => asset('images/favicon.ico'),
                'currency' => config('app.currency', '₦'),
                'currencyCode' => 'NGN',
                'locale' => config('app.locale', 'en'),
                'org_name' => Setting::first()->org_name ?? config('app.name'),
            ],
            'auth' => [
                'user' => $userData,
            ],
            'note' => $request->session()->get('note'),
            'error' => $request->session()->get('error'),
        ];
    }
}
