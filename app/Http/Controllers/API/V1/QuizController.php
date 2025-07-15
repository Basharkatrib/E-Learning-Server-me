<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuizeRequest;
use App\Http\Requests\UpdateQuizeRequest;
use App\Http\Resources\QuizResource;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index(Request $req, $courseId)
    {
        // Anyone can view published quizzes
        $quizzes = Quiz::where("course_id", $courseId)
            ->when($req->has("published"), function ($query) use ($req) {
                $query->where("is_published", $req->published);
            })
            ->get();

        return response()->json($quizzes);
    }

    public function store(StoreQuizeRequest $req, $courseId)
    {
        $course = Course::findOrFail($courseId);

        // Only the teacher can create quizzes
        if (Auth::user()->id !== $course->user_id) {
            return response()->json([
                "message" => "Only the creator of the course can create quizzes"
            ], 403);
        }

        $quiz = Quiz::create([
            "course_id" => $courseId,
            "title" => $req->title,
            "description" => $req->description,
            "time_limit" => $req->timeLimit,
            "passing_score" => $req->passingScore,
            "is_published" => $req->isPublished ?? false,
        ]);

        return response()->json($quiz, 201);
    }

    public function show($courseId, $quizId)
    {
        $quiz = Quiz::with(["questions.options"])
            ->where("course_id", $courseId)
            ->findOrFail($quizId);

        return new QuizResource($quiz);
    }

    public function update(UpdateQuizeRequest $req, $courseId, $quizId)
    {
        $course = Course::findOrFail($courseId);
        $quiz = Quiz::where("course_id", $courseId)
            ->findOrFail($quizId);


        // Only the teacher can update the quiz
        if (Auth::user()->id !== $course->user_id) {
            return response()->json([
                "message" => "Only the creator of the course can update the quiz"
            ], 403);

            $quiz->update($req->validate());

            return response()->json($quiz);
        }
    }

    public function destroy($courseId, $quizId)
    {
        $course = Course::findOrFail($courseId);
        $quiz = Quiz::where("course_id", $courseId)
            ->findOrFail($quizId);

        // Only the teacher can delete the quiz
        if (Auth::user()->id !== $course->user_id) {
            return response()->json([
                "message" => "Only the creator of the course can delete the quiz"
            ], 403);
        }

        $quiz->delete();

        return response()->json(null, 204);
    }
}
