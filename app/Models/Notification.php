<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = "custom_notifications";

    protected $fillable = [
        "user_id", "title", "message", "url"
    ] ;
}
