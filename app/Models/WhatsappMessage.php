<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        "recipient","message","response","status","order_id","delivery_status","direction"
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
