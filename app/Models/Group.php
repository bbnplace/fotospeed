<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public static function getGroupsArray()
    {
        $groups = [];
        $groupsCollection = self::get('name');
        if(!empty($groupsCollection))
        {
            foreach($groupsCollection as $group){
                array_push($groups, $group->name);
            }
        }

        return $groups;
    }
}
