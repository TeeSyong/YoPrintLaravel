<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCsv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName(); 
            $timestamp = now()->format('YmdHis'); 
            $fileName = "upload_{$timestamp}_{$originalName}"; 
            $filePath = $file->storeAs('uploads', $fileName); 
            Redis::lpush('csv_queue', $filePath);
            \App\Models\Upload::firstOrCreate(
                ['file_name' => basename($filePath)],
                [
                    'status' => 'pending',
                    // 'original_file_name' => $originalName,
                    'created_at' => now()
                ]
            );
            ProcessCsv::dispatch($filePath);
            return response()->json([
                'message' => 'Upload scheduled',
                'file_name' => basename($filePath),
                // 'original_file_name' => $originalName
            ]);
        }
        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function status()
    {
        $uploads = \App\Models\Upload::all()->map(function ($upload) {
            return [
                'file_name' => $upload->file_name,
                // 'original_file_name' => $upload->original_file_name,
                'status' => $upload->status,
                'created_at' => $upload->created_at ? $upload->created_at->toIso8601String() : null
            ];
        });
        return response()->json($uploads);
    }
}