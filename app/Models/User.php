<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Cecula\Flow\Models\Customer;
use Cecula\Flow\Models\Branch;
use Cecula\Flow\Models\State;
use Cecula\Flow\Models\Role;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $username = 'mobile';

    const STATUS_ACTIVE = 'Active';
    const STATUS_INACTIVE = 'Inactive';
    const STATUS_SUSPENDED_TEMP = 'Temporarily Suspended';
    const STATUS_SUSPENDED_PERM = 'Permanently Suspended';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'role_id',
        'state_id',
        'branch_id',
        'auto_login_token_expires_at',
        'auto_login_token',
        'account_status',
        'intended_use',
        'is_temporary_password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_temporary_password' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function isAdmin()
    {
        return in_array($this->role->name, ['Administrator', 'System Admin']);
    }

    public function isCustomer()
    {
        return $this->role->name == 'Customer';
    }

    public function isManagement()
    {
        return $this->role->name == 'Management';
    }

    public function isProduction()
    {
        return $this->role->name == 'Production';
    }

    public function isCashier()
    {
        return $this->role->name == 'Cashier';
    }

    public function isAccountant()
    {
        return $this->role->name == 'Accountant';
    }


    public function isReception()
    {
        return $this->role->name == 'Reception' || $this->role->name == 'Receptionist';
    }

    public function isSysAdmin()
    {
        return $this->role->name == 'System Admin';
    }

    public function canViewAllOrders()
    {
        return $this->isAdmin() || $this->isManagement() || $this->isReception() || $this->isAccountant() || $this->isCashier();
    }

    public function canViewAllFinancials()
    {
        return $this->isAdmin() || $this->isManagement() || $this->isReception() || $this->isCashier();
    }

    public function isFromAdministrativeBranch()
    {
        return $this->branch->is_administrative == 1;
    }
}
