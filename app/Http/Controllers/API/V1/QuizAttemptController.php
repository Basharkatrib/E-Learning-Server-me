<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
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

        $quiz = Quiz::where("courseId", $courseId)
            ->findOrFail();

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

        return response()->json([
            "message" => "Quiz attempt started",
            "attemptId" => $attempt->id,
            "quiz" => $quiz->load(["questions.options" => function ($query) {
                $query->select("id", "question_id", "option_text");
            }])
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
            "question_id" => ["required", "exsits:questions,id"],
            "option_id" => ["nullable", "exists:options,id"],
        ]);

        $question = Question::find($validated["question_id"]);
        $isCorrect = false;
        $pointsEarned = 0;

        if ($question->question_type === "multiple_choice" || $question->question_type === "true_false") {
            $selectedOption = Option::find($validated["option_id"]);
            $isCorrect = $selectedOption ? $selectedOption->is_correct : false;
            $pointsEarned = $isCorrect ? $question->points : 0;
        }

        UserAnswer::updateOrCreate([
            "quiz_attempt_id" => $attemptId,
            "question_id" => $validated["question_id"]
        ], [
            "option_id" => $validated["option_id"] ?? null,
            "is_correct" => $isCorrect,
            "points_earned" => $pointsEarned,
        ]);

        return response()->json(["message" => "Answer submitted"], 200);
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
