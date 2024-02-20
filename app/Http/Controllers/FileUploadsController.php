<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileUploadsController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'files' => 'required|file|max:10240', // Max size: 10 MB
        ]);

        $path = $request->file('files')->store("/images");

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
}
