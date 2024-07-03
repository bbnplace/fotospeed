<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileDownloadsController extends Controller
{
    public function download(Request $request)
    {
        $filepath = $request->filepath;
        $file = Storage::path($filepath);
        return response()->download($file);
    }
}
