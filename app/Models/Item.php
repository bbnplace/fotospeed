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
        'slug',
        'description',
        'height',
        'width',
        'weight',
        'print_price',
        'sheet_price',
        'cover_print_price',
        'product_photos',
        'process_data',
        'primary_order_processing_branch',
        'order_processing_branches'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public static function getItemsArray()
    {
        $items = [];
        $itemsCollection = self::get(['name', 'process_data']);
        if(!empty($itemsCollection))
        {
            foreach($itemsCollection as $item){
                // Only include items that have defined processes
                $processData = json_decode($item->process_data);
                if (!empty($processData) && !empty($processData->processes)) {
                    array_push($items, $item->name);
                }
            }
        }

        return $items;
    }

    public static function getItem(int $id)
    {
        return self::where('id', $id)->first();
    }

    public static function getProcessData(int $id)
    {
        $item = self::where('id', $id)->first(['process_data']);
        return json_decode($item->process_data);
    }

    /**
     * The Tasks for a Process
     * @param int $itemId     The ID of the fetched item
     * @param string $process The name of the process
     * @return array          An array of tasks
     */
    public static function getProcessTasks(int $itemId, string $process) : array {
        return self::getProcessData($itemId)->tasks->$process;
    }

    public static function getProductPhotoIds(Item $item): array
    {
        $mediaIds = [];
        if (!empty($item->product_photos)) {
            $productPhotos = json_decode($item->product_photos);
            if (!empty($productPhotos->images)) {
                foreach ($productPhotos->images as $media) {
                    array_push($mediaIds, $media->id);
                }
            }
        }
        return $mediaIds;
    }
}
