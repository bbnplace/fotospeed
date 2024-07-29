<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class A2pSmsMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        "recipient", "message","response","order_id","status","delivery_status",
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
