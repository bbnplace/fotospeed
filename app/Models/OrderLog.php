<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    use HasFactory;

    public $fillable = [
        'order_id',
        'staff_id',
        'process_id'
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function process()
    {
        return $this->belongsTo(OrderStatus::class, 'process_id');
    }
}
