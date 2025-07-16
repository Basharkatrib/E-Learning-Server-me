<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        $query = Course::with(["category", "teacher", "skills"]);

        //Filter by category if provided
        if ($req->has("category_id")) {
            $query->where("category_id", $req->category_id);
        }

        //Filter by difficulty_level if provided
        if ($req->has("difficulty_level")) {
            $query->where("difficulty_level", $req->difficulty_level);
        }

        // Search by title if provided
        if ($req->has("search")) {
            $query->where("title", "like", "%" . $req->search . "%");
        }

        $courses = $query->paginate($request->per_page ?? 50);

        return response()->json($courses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $req)
    {
        //only teachers can create courses
        if (!Auth::user()->isTeacher) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $course = new Course($req->validate());
        $course->user_id = Auth::id();

        //handel thumbnail upload if needed
        if ($req->hasFile("thumbnail")) {
            $path = $req->file("thumbnail")->store("thumbnails", "public");
            $course->thumbnail_url = $path;
        }

        $course->save();

        //Attach skills if provided
        if ($req->has("skills")) {
            $skillIds = Skill::whereIn("name", $req->skills)
                ->pluck("id")
                ->toArray();
            $course->skills()->sync($skillIds);
        }

        return response()->json($course, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load(["category", "teacher", "skills", "sections.videos", "faqs", "ratings", "benefits"]);
        return response()->json($course);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $req, Course $course)
    {
        // Only the course owner or admin can update
        if (Auth::id() !== $course->user_id && !Auth::user()->isAdmin) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $course->fill($req->validated());

        // Handle thumbnail update if needed
        if ($req->hasFile("thumbnail")) {
            $path = $req->file("thumbnail")->store("thumbnails", "public");
            $course->thumbnail_url = $path;
        }

        $course->save();

        // Update skills if provided
        if ($req->has("skills")) {
            $skillIds = Skill::whereIn("name", $req->skills)
                ->pluck("id")
                ->toArray();
            $course->skills()->sync($skillIds);
        }

        return response()->json($course);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        if (Auth::id() !== $course->user_id && !Auth::user()->isAdmin) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $course->delete();
        return response()->json([
            "message" => "course has been deleted"
        ], 204);
    }

    /**
     * Get courses created by the authenticated teacher.
     */
    public function myCourses()
    {
        if (!Auth::user()->isTeacher) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $courses = Course::where('user_id', Auth::id())
            ->with(['category', 'skills', 'benefits'])
            ->paginate(10);

        return response()->json($courses);
    }

    /**
     * Get trending courses based on ratings
     */
    public function trending()
    {
        $courses = Course::with(['category', 'teacher', 'ratings'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->orderByDesc('ratings_avg_rating')
            ->orderByDesc('ratings_count')
            ->take(6)
            ->get();

        return response()->json([
            'data' => $courses
        ]);
    }

}
