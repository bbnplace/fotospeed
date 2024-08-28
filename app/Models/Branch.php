<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'state_id',
        'is_administrative'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public static function getBranchesArray()
    {
        $branches = self::orderBy('name', 'asc')->get();
        $branchesArray = [];
        foreach ($branches as $branch) {
            array_push($branchesArray, $branch->name);
        }

        return $branchesArray;
    }
}
