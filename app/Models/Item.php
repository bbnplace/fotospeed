<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'height',
        'width',
        'weight',
        'print_price',
        'sheet_price',
        'cover_print_price',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public static function getItemsArray()
    {
        $items = [];
        $itemsCollection = self::get('name');
        if(!empty($itemsCollection))
        {
            foreach($itemsCollection as $item){
                array_push($items, $item->name);
            }
        }

        return $items;
    }
}
