<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'height',
        'weight',
        'print_price',
        'sheet_price',
        'cover_print_price',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
