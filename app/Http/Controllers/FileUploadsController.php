<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FileUploadsController extends Controller
{
    public function uploadImage(Request $request, $usage)
    {
        $settings = Setting::first();

        $request->validate([
            'files' => sprintf(
                'required|file|mimes:%s|max:%d'
                , $settings->file_mime_types
                , $settings->max_file_size
            ),
        ]);

        $file = $request->file('files');
        // If the file is an image, generate a thumbnail
        $thumbnailPath = null;
        $previewImgPath = null;

        $fileDirectory = "";
        switch (true) {
            case strstr($file->getMimeType(), 'image/'):
                $path = $file->store("/images");

                $srcFile = Storage::get($path);
                $thumbFile = str_replace("images/","images/thumbnails/", $path);
                
                // Create thumbnail for the size specified by the user
                Media::createThumbnail($srcFile, $thumbFile, $settings->thumbnail_size);
                $thumbnailPath = sprintf('%s/%s', env('APP_URL'), $thumbFile);

                // Create another thumbnail for dropdown
                $previewImgSize = 100;
                $previewImageFile = str_replace("images/","images/thumbnails/100/", $path);
                Media::createThumbnail($srcFile, $previewImageFile, $previewImgSize);
                $previewImgPath = sprintf('%s/%s', env('APP_URL'), $previewImageFile);
                break;
            case strstr($file->getMimeType(), 'pdf'):
                $path = $file->store("/pdfs");
                $thumbnailPath = sprintf('%s/%s', env('APP_URL'), 'images/pdf-icon.png');
                $previewImgPath = $thumbnailPath;
                break;
            case strstr($file->getMimeType(), 'zip'):
                $path = $file->store("/zips");
                $thumbnailPath = sprintf('%s/%s', env('APP_URL'), 'images/zip-icon.png');
                $previewImgPath = $thumbnailPath;
                break;
        }
        
        
        // Store the thumbnail and get the path to the thumbnail
        // Save uploaded file to database
        $uploader = auth()->user()->isCustomer() ?'customer_id':'staff_id';

        if (!empty($usage) && !in_array(strtolower($usage), ['order','product','profile'])) {
            $usage = 'order';
        }

        return Media::create([
            'path' => $path,
            'thumbnail' => $thumbnailPath,
            'thumbnail_100' => $previewImgPath,
            'usage' => strtolower($usage) ?? 'Order',
            $uploader => auth()->user()->id,
        ]);
    }


    public function uploadSpreadsheets(Request $request)
    {
        $path = $request->file("spreadsheet")->store("/spreadsheets");
        return [
            'path' => $path,
        ];
    }

    public function get($path, $type)
    {
        $data = Storage::get($path);
        return response($data, 200, [
            'Content-Type' => $type
        ]);
    }
}
