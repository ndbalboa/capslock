<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScanController extends Controller
{
    public function upload(Request $request)
    {
        $image = $request->input('image');
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));

        $fileName = 'scanned_document_' . time() . '.png';
        Storage::disk('public')->put($fileName, $imageData);

        return response()->json(['message' => 'Document uploaded successfully!', 'file' => $fileName]);
    }
}

