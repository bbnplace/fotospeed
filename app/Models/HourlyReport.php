<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourlyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'hour',
        'received',
        'paid',
        'produced',
        'delivered',
        'cancelled',
    ];

    public $timestamps = false;
}
