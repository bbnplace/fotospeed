<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = false;

    public static function getStatesArray()
    {
        $states = self::orderBy('name', 'asc')->get();
        $statesArray = [];
        foreach ($states as $state) {
            array_push($statesArray, $state->name);
        }

        return $statesArray;
    }
}
