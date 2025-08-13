<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NoteController extends Controller
{
    public function index(Request $request)
    {

        $query = Note::where('user_id', Auth::id())
            ->with('video');

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('video_id')) {
            $query->where('video_id', $request->video_id);
        }

        $notes = $query->latest()->get();

        return response()->json(['data' => $notes]);
    }

    public function store(Request $request)
    {
        Log::info('Received note creation request', [
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        try {
            $request->validate([
                'course_id' => [
                    'required',
                    'exists:courses,id',
                    function ($attribute, $value, $fail) {
                        $isEnrolled = Enrollment::where('user_id', Auth::id())
                            ->where('course_id', $value)
                            ->exists();

                        if (!$isEnrolled) {
                            $fail('You are not enrolled in this course.');
                        }
                    },
                ],
                'video_id' => 'nullable|exists:videos,id',
                'title' => 'nullable|string',
                'content' => 'required|string',
            ]);

            $note = Note::create([
                'user_id' => Auth::id(),
                'course_id' => $request->course_id,
                'video_id' => $request->video_id,
                'title' => $request->title,
                'content' => $request->content,
            ]);

            Log::info('Note created successfully', [
                'note_id' => $note->id,
                'note_data' => $note->toArray()
            ]);

            return response()->json([
                'message' => 'Note created successfully',
                'data' => $note
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create note', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to create note',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['data' => $note->load('video')]);
    }

    public function update(Request $request, Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'nullable|string',
            'content' => 'required|string',
        ]);

        $note->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Note updated successfully',
            'data' => $note
        ]);
    }

    public function destroy(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $note->delete();

        return response()->json([
            'message' => 'Note deleted successfully'
        ]);
    }
}
