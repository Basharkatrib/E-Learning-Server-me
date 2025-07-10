<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Models\Course;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
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
    public function store(StoreRatingRequest $req, Course $course)
    {
        if (!$course->students()->where("user_id", Auth::id())->exists()) {
            return response()->json([
                "message" => "you must be enrolled in the course to submit a rating."
            ], 403);
        }

        //check if the user already rated this course
        if ($course->ratings()->where("user_id", Auth::id())->exists()) {
            return response()->json([
                "message" => "you have already rated this course!",
            ], 409);
        }

        $rating = Rating::create([
            "course_id" => $course->id,
            "user_id" => Auth::id(),
            "rating" => $req->rating,
            "review" => $req->review,
        ]);

        return response()->json([
            "message" => "rating submitted successfully",
            "rating" => $rating
        ], 200);
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
    public function update(UpdateRatingRequest $req, Course $course, Rating $rating)
    {

        if ($rating->course_id !== $course->id) {
            return response()->json(["message" => "Rating does not belong to this course"], 400);
        }

        if ($rating->user_id !== Auth::id()) {
            return response()->json([
                "message" => "unauthorized"
            ], 403);
        }

        $rating->update([
            "rating" => $req->rating ?? $rating->rating,
            "review" => $req->review ?? $rating->review,
        ]);

        return response()->json([
            "message" => "Rating updated successfully",
            "rating" => $rating
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Rating $rating)
    {

        if ($rating->course_id !== $course->id) {
            return response()->json(["message" => "Rating does not belong to this course"], 400);
        }

        if ($rating->user_id !== Auth::id()) {
            return response()->json([
                "message" => "unauthorized"
            ], 403);
        }

        $rating->delete();

        return response()->json([
            "message" => "Rating deleted successfully"
        ], 200);
    }

    public function myRating(Course $course)
    {
        $rating = $course->ratings()->where('user_id', Auth::id())->first();
        if (!$rating) {
            return response()->json(['message' => 'No rating found'], 404);
        }
        return response()->json(['rating' => $rating], 200);
    }
    
}
