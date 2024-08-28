<?php

namespace App\Http\Middleware;

use App\Models\Role;
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
                'email' => $userData->email,
                'mobile' => $userData->mobile,
                'name' => $userData->name,
                'branch_id' => $userData->branch_id,
                'role' => $role->name,
                'role_ref' => $userData->role_id,
                'isAdmin' => $userData->isAdmin(),
                'isClient' => $userData->isCustomer(),
                'isAdminBranch' => $userData->branch->is_administrative == 1,
            ];
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $userData,
            ],
        ];
    }
}
