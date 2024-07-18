<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'received',
        'paid',
        'produced',
        'delivered',
        'cancelled',
    ];

    public $timestamps = false;
}
