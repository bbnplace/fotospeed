<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'type',
        'capability',
        'specification',
        'model_number',
        'serial_number',
        'physical_location',
        'department',
        'status',
        'note',
    ];
}
