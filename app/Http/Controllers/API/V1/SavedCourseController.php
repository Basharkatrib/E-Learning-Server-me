<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedCourseController extends Controller
{
    public function index()
    {
        $savedCourses = Auth::user()->savedCourses()->with(['category', 'teacher'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $savedCourses
        ]);
    }

    public function store(Course $course)
    {
        $user = Auth::user();
        
        if ($user->savedCourses()->where('course_id', $course->id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course already saved'
            ], 400);
        }

        $user->savedCourses()->attach($course->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Course saved successfully'
        ]);
    }

    public function destroy(Course $course)
    {
        $user = Auth::user();
        $user->savedCourses()->detach($course->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Course removed from saved courses'
        ]);
    }

    public function isSaved(Course $course)
    {
        $isSaved = Auth::user()->savedCourses()->where('course_id', $course->id)->exists();
        
        return response()->json([
            'status' => 'success',
            'is_saved' => $isSaved
        ]);
    }
} 