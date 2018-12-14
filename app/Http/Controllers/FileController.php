<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        return File::all();
    }

    public function upload(Request $request)
    {
        $file = $request->file('document');

        $file->store('documents');

        return File::create([
            'name' => $file->getClientOriginalName(),
            'path' => $file->hashName(),
            'mime_type' => $file->getClientMimeType()
        ]);
    }

    public function download(File $file)
    {
        $path = Storage::path('documents/' . $file->path);

        $name = $file->name;

        $headers = [
            'Content-Type' => $file->mime_type
        ];

        return response()->download($path, $name, $headers);
    }

    public function destroy(File $file)
    {
        Storage::delete('documents/' . $file->path);

        $file->delete();
    }
}
