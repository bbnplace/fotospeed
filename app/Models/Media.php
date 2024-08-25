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

        // $image->resize($thumbnailSize, null, function ($constraint){
        //     $constraint->aspectRatio();
        // });

        $path = dirname($thumbnailFilePath);
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $image->save($thumbnailFilePath);
    }
}
