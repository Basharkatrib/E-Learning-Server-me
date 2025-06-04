<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->user()->isTeacher()) {
            return response()->json(["message" => "Only teachers can upload videos"], 403);
        }

        $vaildate = $request->validate([
            "title" => ["required", "string"],
            "section_id" => ["required", "exists:secions,id"],
            "video" => ["required", "file", "mims:mp4,mov,avi", "max:20480"], //max size:20MB
        ]);

        $path = $request->file("video")->store("video", "public");

        $video = Video::create([
            "title" => $vaildate["title"],
            "section_id" => $vaildate["section_id"],
            "video_url" => Storage::url($path),
        ]);

        return response()->json($video, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
