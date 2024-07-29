<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'group_id'] ;

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public static function getCustomerGroupsArray($customerId)
    {
        $customerGroupsArray = [];
        $customerGroups = self::where('user_id', $customerId)->get();
        if (!empty($customerGroups)) {
            foreach ($customerGroups as $customerGroup) {
                $group = Group::where('id', $customerGroup->group_id)->first(['name']);
                array_push($customerGroupsArray, $group->name);
            }
        }
        return $customerGroupsArray;
    }

    public static function saveCustomerToGroups(int $customerId, array $groups): void
    {
        self::where('user_id', $customerId)->delete();
        foreach ($groups as $group) {
            $groupData = Group::where('name', $group)->first();
            self::create([
                'group_id' => $groupData->id,
                'user_id' => $customerId
            ]);
        }
    }
}
