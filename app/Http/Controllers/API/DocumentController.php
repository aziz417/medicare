<?php

namespace App\Http\Controllers\API;

use App\Models\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DocumentController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:jpeg,jpg,png,pdf,txt'
        ]);
        $file = $request->file('document');
        $path = $request->path ?? "documents";
        $path = save_file($file, "uploads/{$path}", $request->name);
        $asset = Asset::create([
            'model_id' => $request->id, 
            'model' => $request->type, 
            'name' => $request->name ?? $file->getClientOriginalName(), 
            'content' => $path, 
            'link' => asset($path), 
            'type' => $file->getClientMimeType()
        ]);

        if( $asset ){
            return response()->json([
                'status' => true,
                'data' => $asset
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => "File upload failed!"
        ]);
    }
}
