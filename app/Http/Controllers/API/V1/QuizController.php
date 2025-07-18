<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResource;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    // Get all quizzes for a course
    public function index(Request $req, $courseId)
    {
        $quizzes = Quiz::where("course_id", $courseId)
            ->with(['questions.options'])
            ->get();

        return response()->json($quizzes);
    }

    // Check if user has already attempted the quiz
    private function hasAttemptedQuiz($quizId)
    {
        return QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quizId)
            ->exists();
    }

    // Get a specific quiz with its questions
    public function show($courseId, $quizId)
    {
        // Check if user has already attempted this quiz
        if ($this->hasAttemptedQuiz($quizId)) {
            return response()->json([
                'error' => 'لقد قمت بإجراء هذا الاختبار مسبقاً',
                'has_attempted' => true
            ], 403);
        }

        $quiz = Quiz::with(["questions.options"])
            ->where("course_id", $courseId)
            ->findOrFail($quizId);

        return response()->json([
            'quiz' => $quiz,
            'has_attempted' => false
        ]);
    }

    // Submit quiz answers
    public function submit(Request $request, $courseId, $quizId)
    {
        // Check if user has already attempted this quiz
        if ($this->hasAttemptedQuiz($quizId)) {
            return response()->json([
                'error' => 'لقد قمت بإجراء هذا الاختبار مسبقاً',
                'has_attempted' => true
            ], 403);
        }

        $quiz = Quiz::findOrFail($quizId);
        
        // Create an attempt
        $attempt = QuizAttempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quizId,
            'status' => 'completed',
            'started_at' => now(),
            'completed_at' => now()
        ]);

        // Calculate score
        $totalQuestions = $quiz->questions()->count();
        $correctAnswers = 0;

        foreach ($request->answers as $questionId => $answerId) {
            $question = $quiz->questions()->find($questionId);
            if ($question && $question->options()->where('id', $answerId)->where('is_correct', true)->exists()) {
                $correctAnswers++;
            }
        }

        $score = ($correctAnswers / $totalQuestions) * 100;
        
        // Update attempt with score
        $attempt->update([
            'score' => $score,
        ]);

        return response()->json([
            'score' => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'passed' => $score >= ($quiz->passing_score ?? 60)
        ]);
    }

    // Check quiz attempt status
    public function checkAttemptStatus($courseId, $quizId)
    {
        $quiz = Quiz::where("course_id", $courseId)
            ->findOrFail($quizId);

        $latestAttempt = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quizId)
            ->where('status', 'completed') // Only consider completed attempts
            ->latest()
            ->first();

        return response()->json([
            'has_attempted' => (bool) $latestAttempt,
            'latest_attempt' => $latestAttempt ? [
                'score' => $latestAttempt->score,
                'status' => $latestAttempt->status,
                'completed_at' => $latestAttempt->completed_at,
                'passed' => $latestAttempt->score >= ($quiz->passing_score ?? 60)
            ] : null,
            'can_submit' => !$latestAttempt // Can only submit if no completed attempt exists
        ]);
    }
}
