<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileUploadsController extends Controller
{
    public function uploadImage(Request $request)
    {
        sleep(5);

        $settings = Setting::first();

        $request->validate([
            'files' => sprintf(
                'required|file|mimes:%s|max:%d'
                , $settings->file_mime_types
                , $settings->max_file_size
            ),
        ]);

        $path = $request->file('files')->store("/images");

        // Generate a thumbnail of the photograph
        // Store the thumbnail and get the path to the thumbnail
        // Save uploaded file to database
        // Return the path to the thumbnail

        return [
            'path' => $path,
        ];
    }


    public function uploadSpreadsheets(Request $request)
    {
        $path = $request->file("spreadsheet")->store("/spreadsheets");
        return [
            'path' => $path
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
