<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const CUSTOMER = 1;
    const RECEPTION = 2;
    const PRODUCTION = 3;
    const MANAGEMENT = 4;
    const ADMINISTRATION = 5;
    const SYSTEM_ADMIN = 6;

    protected $fillable = ['name'];

    public $timestamps = false;

    public static function getRolesArray()
    {
        $roles = [];
        $rolesCollection = self::get('name');
        if(!empty($rolesCollection))
        {
            foreach($rolesCollection as $role){
                array_push($roles, $role->name);
            }
        }

        return $roles;
    }
}
