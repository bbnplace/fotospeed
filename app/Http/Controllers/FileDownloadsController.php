<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileDownloadsController extends Controller
{
    public function download(Request $request)
    {
        $filepath = $request->filepath;
        $file = Storage::disk('public')->path($filepath);
        return response()->download($file);
    }

    public function fetch(Request $request)
    {
        $filepath = $request->filepath;
        $fileContent = Storage::disk('public')->get($filepath);

        return response($fileContent)->withHeaders([
            "Content-Type"=> $request->mimeType
        ]);
    }
}
