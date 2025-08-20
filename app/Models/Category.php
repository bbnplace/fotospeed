<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public $timestamps = false;

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public static function getCategoriesArray()
    {
        $categories = [];
        $categoriesCollection = self::get('name');
        if(!empty($categoriesCollection))
        {
            foreach($categoriesCollection as $category){
                array_push($categories, $category->name);
            }
        }

        return $categories;
    }
}
