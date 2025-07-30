<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResource;
use App\Models\Course;
use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizAttemptController extends Controller
{
    public function start(Request $req, Course $course)
    {
        //check if the user is enrolled and has completed all videos
        if (!Auth::user()->enrolledCourses()
            ->where("course_id", $course->id)
            ->where("videos_completed", true)
            ->exists()) {
            return response()->json([
                "message" => "You must complete all course videos before taking this quiz"
            ], 403);
        }

        // Get the quiz associated with this course
        $quiz = $course->quiz()->firstOrFail();

        // Check for existing incomplete attempt
        $existingAttempt = QuizAttempt::where("user_id", Auth::id())
            ->where("quiz_id", $quiz->id)
            ->where("status", "in_progress")
            ->first();

        if ($existingAttempt) {
            return response()->json([
                "message" => "You have an existing attempt in progress",
                "attemptId" => $existingAttempt->id,
            ]);
        }

        $attempt = QuizAttempt::create([
            "user_id" => Auth::id(),
            "quiz_id" => $quiz->id,
            "started_at" => now(),
            "status" => "in_progress",
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

        \Log::info('Submitting answers for attempt ' . $attemptId, $validated);

        foreach ($validated["answers"] as $answer) {
            $question = Question::find($answer["question_id"]);
            $isCorrect = false;
            $pointsEarned = 0;

            // Check if user provided an answer
            if (!empty($answer["option_id"])) {
                if (in_array($question->question_type, ["multiple_choice", "true_false"])) {
                    $selectedOption = Option::find($answer["option_id"]);
                    if ($selectedOption) {
                        $isCorrect = $selectedOption->is_correct;
                        $pointsEarned = $isCorrect ? $question->points : 0;
                    }
                }
            }

            // Create or update user answer
            $userAnswer = UserAnswer::updateOrCreate([
                "user_id" => Auth::id(),
                "quiz_attempt_id" => $attemptId,
                "question_id" => $answer["question_id"],
            ], [
                "option_id" => $answer["option_id"] ?? null,
                "is_correct" => $isCorrect,
                "points_earned" => $pointsEarned,
            ]);

            \Log::info('Saved user answer', [
                'attempt_id' => $attemptId,
                'question_id' => $answer["question_id"],
                'option_id' => $answer["option_id"] ?? 'null',
                'is_correct' => $isCorrect,
                'points_earned' => $pointsEarned,
                'user_answer_id' => $userAnswer->id,
                'user_id' => Auth::id()
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
        $scorePercentage = $totalPossible > 0 ? ($totalScore / $totalPossible) * 100 : 0;

        $attempt->update([
            "completed_at" => now(),
            "status" => "completed",
            "score" => $scorePercentage
        ]);

        return response()->json([
            "message" => "Quiz completed",
            "score" => $scorePercentage,
            "total_questions" => $attempt->quiz->questions()->count(),
            "correct_answers" => $attempt->answers()->where('is_correct', true)->count(),
            "passed" => $attempt->quiz->passing_score
                ? $scorePercentage >= $attempt->quiz->passing_score
                : null
        ]);
    }

    public function results($attemptId)
    {
        $attempt = QuizAttempt::with([
            "quiz.questions.options", 
            "answers.option", 
            "answers.question"
        ])
            ->where("user_id", Auth::id())
            ->findOrFail($attemptId);

        \Log::info('Fetching results for attempt', [
            'attempt_id' => $attemptId,
            'user_id' => Auth::id(),
            'total_answers' => $attempt->answers->count()
        ]);

        // Calculate detailed results
        $totalQuestions = $attempt->quiz->questions->count();
        $correctAnswers = $attempt->answers->where('is_correct', true)->count();
        $incorrectAnswers = $attempt->answers->where('is_correct', false)->count();
        $unansweredQuestions = $totalQuestions - $attempt->answers->count();

        // Get detailed answer analysis
        $answerAnalysis = [];
        foreach ($attempt->quiz->questions as $question) {
            $userAnswer = $attempt->answers->where('question_id', $question->id)->first();
            $correctOption = $question->options->where('is_correct', true)->first();
            
            \Log::info('Processing question', [
                'question_id' => $question->id,
                'user_answer_exists' => $userAnswer ? true : false,
                'user_answer_option_id' => $userAnswer ? $userAnswer->option_id : null,
                'user_answer_is_correct' => $userAnswer ? $userAnswer->is_correct : null
            ]);
            
            $answerAnalysis[] = [
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'question_type' => $question->question_type,
                'user_answer' => $userAnswer ? [
                    'option_id' => $userAnswer->option_id,
                    'option_text' => $userAnswer->option ? $userAnswer->option->option_text : null,
                    'is_correct' => $userAnswer->is_correct,
                    'points_earned' => $userAnswer->points_earned
                ] : null,
                'correct_answer' => $correctOption ? [
                    'option_id' => $correctOption->id,
                    'option_text' => $correctOption->option_text
                ] : null,
                'all_options' => $question->options->map(function($option) {
                    return [
                        'id' => $option->id,
                        'text' => $option->option_text,
                        'is_correct' => $option->is_correct
                    ];
                })
            ];
        }

        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
        $passed = $score >= $attempt->quiz->passing_score;

        return response()->json([
            "score" => round($score, 2),
            "passed" => $passed,
            "total_questions" => $totalQuestions,
            "correct_answers" => $correctAnswers,
            "incorrect_answers" => $incorrectAnswers,
            "unanswered_questions" => $unansweredQuestions,
            "passing_score" => $attempt->quiz->passing_score,
            "answer_analysis" => $answerAnalysis
        ]);
    }

}
