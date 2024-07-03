<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

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
