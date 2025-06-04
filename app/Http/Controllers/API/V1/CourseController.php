<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Course::with('category')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user->isTeacher()) {
            return response()->json([
                "message" => "Only teachers can create courses!"
            ], 403);
        }

        $vaildData = $request->validate([
            "title" => ["required", "string"],
            "description" => ["string", "nullable"],
            "category_id" => ["required", "exists:categories,id"],
        ]);

        $course = Course::create([
            ...$vaildData,
            "created_by" => $user->id,
        ]);

        return response()->json([
            "message" => "the course has been created successfully",
            "course" => $course
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Course::with(['sections.videos'])->findOrFail($id);
        return response()->json($course);
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
