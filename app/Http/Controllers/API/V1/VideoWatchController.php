<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;

class VideoWatchController extends Controller
{
    public function markAsWatched(Request $request, Video $video)
    {
        $user = $request->user();

        $user->watchedVideos()->syncWithoutDetaching([
            $video->id => ['watched_at' => now()]
        ]);

        return response()->json([
            'message' => 'Video marked as watched.',
            'video_id' => $video->id,
        ]);
    }

    public function getWatchedVideos(Request $request)
    {
        $user = $request->user();

        $watchedIds = $user->watchedVideos()->pluck('videos.id');

        return response()->json([
            'watched' => $watchedIds,
        ]);
    }
}
