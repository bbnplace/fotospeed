<?php

namespace App\Models;

use File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id','staff_id','name','usage','path','thumbnail', 'thumbnail_100','data'] ;

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public static function createThumbnail(string $sourceFilePath, string $thumbnailFilePath, int $thumbnailSize = 150)
    {
        $driver = new Driver() ;
        $manager = new ImageManager($driver);
        
        $image = $manager->read($sourceFilePath);
        $image->scale(width: $thumbnailSize);

        $path = dirname($thumbnailFilePath);
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $image->save($thumbnailFilePath);
    }

    public static function deleteMedia(array $media)
    {
        foreach ($media as $value) {
            $image = self::find($value);
            if (!empty($image)) {
                // Delete the main file
                if (!empty($image->path) && Storage::exists($image->path)) {
                    Storage::delete($image->path);
                }
                
                // Delete the thumbnail
                if (!empty($image->thumbnail) && strstr($image->thumbnail, 'thumbnail')) {
                    $thumbnail_path = __DIR__."/../../public".str_replace(env('APP_URL'), '', $image->thumbnail);
                    if (File::exists($thumbnail_path)) {
                        File::delete($thumbnail_path);
                    }
                }

                // Delete the avatar
                if (!empty($image->thumbnail_100)  && strstr($image->thumbnail_100, 'thumbnail')) {
                    $avatar_path = __DIR__."/../../public".str_replace(env('APP_URL'), '', $image->thumbnail_100);
                    if (File::exists($avatar_path)) {
                        File::delete($avatar_path);
                    }
                }

                $image->delete();
            }
        }
    }

    public static function linkOrder($id, $orderId)
    {
        $mediaData = [];
        $media = self::find($id);
        if (!empty($media)) {
            $mediaData = empty($media->data) ? [] : json_decode($media->data, true);
            if(isset($mediaData['orders'])) {
                if (!in_array($orderId, $mediaData['orders'])) {
                    array_push($mediaData['orders'], $orderId);
                }
            } else {
                $mediaData['orders'] = [$orderId];
            }
        } else {
            $mediaData = [
                'orders' => [$orderId]
            ];
        }
        $media->data = json_encode($mediaData);
        $media->save();
    }


    public static function linkProduct($id, $productId)
    {
        $mediaData = [];
        $media = self::find($id);
        if (!empty($media)) {
            $mediaData = empty($media->data) ? [] : json_decode($media->data, true);
            if(isset($mediaData['products'])) {
                if (!in_array($productId, $mediaData['products'])) {
                    array_push($mediaData['products'], $productId);
                }
            } else {
                $mediaData['products'] = [$productId];
            }
        } else {
            $mediaData = [
                'products' => [$productId]
            ];
        }
        $media->data = json_encode($mediaData);
        $media->save();
    }

    public static function unlinkProduct($id, $productId)
    {
        $mediaData = [];
        $media = self::find($id);
        if (!empty($media)) {
            $mediaData = empty($media->data) ? [] : json_decode($media->data, true);
            if(isset($mediaData['products'])) {
                if (in_array($productId, $mediaData['products'])) {
                    $productIndex = array_search($productId, $mediaData['products']);
                    array_splice($mediaData['products'], $productIndex);
                }
            } 
        } 
        $media->data = json_encode($mediaData);
        $media->save();
    }
}
