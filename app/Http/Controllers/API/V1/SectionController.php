<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
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
     * POST /api/v1/sections (Teacher only)
     */
    public function store(Request $request)
    {
        if (!$request->user()->isTeacher()) {
            return response()->json(["message" => "Only teachers can add sections"], 403);
        }

        $validate = $request->validate([
            "title" => ["required", "string"],
            "course_id" => ["required", "exists:courses,id"],
        ]);

        $section = Section::create($validate);

        return response()->json($section, 201);
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
