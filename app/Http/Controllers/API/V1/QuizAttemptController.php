<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResource;
use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizAttemptController extends Controller
{
    public function start(Request $req, $courseId, $quizId)
    {
        //check if the user is enrolled and has completed all videos
        if (!Auth::user()->enrolledCourses()
            ->where("course_id", $courseId)
            ->where("videos_completed", true)
            ->exists()) {
            return response()->json([
                "message" => "You must complete all course videos before taking this quiz"
            ], 403);
        }

        $quiz = Quiz::where("course_id", $courseId)
            ->where("id", $quizId)
            ->firstOrFail();

        // Check for existing incomplete attempt
        $existingAttempt = QuizAttempt::where("user_id", Auth::id())
            ->where("quiz_id", $quizId)
            ->where("status", "in_progress")
            ->first();

        if ($existingAttempt) {
            return response()->json([
                "message" => "You have an existing attempt in progress",
                "attemptId" => $existingAttempt->id
            ]);
        }

        $attempt = QuizAttempt::create([
            "user_id" => Auth::id(),
            "quiz_id" => $quizId,
            "started_at" => now(),
            "status" => "in_progress"
        ]);

        $quiz->load("questions.options");

        return response()->json([
            "message" => "Quiz attempt started",
            "attemptId" => $attempt->id,
            "quiz" => new QuizResource($quiz),
        ]);
    }

    public function submitAnswer(Request $req, $attemptId)
    {
        $attempt = QuizAttempt::where("user_id", Auth::id())
            ->findOrFail($attemptId);

        if ($attempt->status !== "in_progress") {
            return response()->json(["message" => "This attempt is no longer in progress"], 400);
        }

         $validated = $req->validate([
        "answers" => ["required", "array"],
        "answers.*.question_id" => ["required", "exists:questions,id"],
        "answers.*.option_id" => ["nullable", "exists:options,id"],
    ]);

    foreach ($validated["answers"] as $answer) {
        $question = Question::find($answer["question_id"]);
        $isCorrect = false;
        $pointsEarned = 0;

        if (in_array($question->question_type, ["multiple_choice", "true_false"])) {
            $selectedOption = Option::find($answer["option_id"]);
            $isCorrect = $selectedOption ? $selectedOption->is_correct : false;
            $pointsEarned = $isCorrect ? $question->points : 0;
        }

        UserAnswer::updateOrCreate([
            "quiz_attempt_id" => $attemptId,
            "question_id" => $answer["question_id"]
        ], [
            "option_id" => $answer["option_id"] ?? null,
            "is_correct" => $isCorrect,
            "points_earned" => $pointsEarned,
        ]);
    }

    return response()->json(["message" => "Answers submitted successfully"]);
    }

    public function complete(Request $req, $attemptId)
    {
        $attempt = QuizAttempt::where("user_id", Auth::id())
            ->findOrFail($attemptId);

        if ($attempt->status !== "in_progress") {
            return response()->json(["message" => "This attempt is no longer in progress"], 400);
        }

        //calculate the score
        $totalScore = $attempt->answers()->sum("points_earned");
        $totalPossible = $attempt->quiz->questions()->sum("points");

        $attempt->update([
            "completed_at" => now(),
            "status" => "completed",
            "score" => $totalScore
        ]);

        return response()->json([
            "message" => "Quiz completed",
            "score" => $totalScore,
            "totalPossible" => $totalPossible,
            "passed" => $attempt->quiz->passing_score
                ? $totalScore >= $attempt->quiz->passing_score
                : null
        ]);
    }

    public function results($attemptId)
    {
        $attempt = QuizAttempt::with(["quiz", "answers.option", "answers.question"])
            ->where("user_id", Auth::id())
            ->findOrFail($attemptId);

        return response()->json($attempt);
    }
}
