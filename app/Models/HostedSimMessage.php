<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostedSimMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        "sender", "recipient","message","response","order_id","status","delivery_status","direction",
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
