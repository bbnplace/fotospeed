<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailMessage extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'subject', 'body', 'response', "order_id", "status", "delivery_status", "direction"];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
