<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Enroll the authenticated student in a course
     */
    public function enroll(Request $req, Course $course)
    {
        //Only students can enroll
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if ($user->role !== "student") {
            return response()->json(["message" => "Only students can enroll"], 403);
        }

        if ($user->courses()->where("course_id", $course->id)->exists()) {
            return response()->json(["message" => "User is already enrolled in this course"], 409);
        }

        //enroll th student
        DB::transaction(function () use ($course) {
            Auth::user()->courses()->attach($course->id, [
                "enrolled_at" => now(),
                "progress" => 0,
                "videos_completed" => false,
                "completed_at" => null
            ]);
        });

        return response()->json([
            "message" => "Successfully enrolled in the course",
            "enrollment" => [
                "courseId" => $course->id,
                'courseTitle' => $course->title,
                "userId" => Auth::user()->id,
                "enrolledAt" => now()->toDateString(),
                "progress" => 0,
                "videosCompleted" => false
            ]
        ]);
    }

    /**
     * Unenroll a user from a course
     */
    public function unenroll(Request $req, Course $course)
    {
        //Only students can unenroll
        if (Auth::user()->role !== "student") {
            return response()->json([
                "message" => "only enrolled students can unenroll"
            ], 403);
        }

        if (!Auth::user()->courses()->where("course_id", $course->id)->exists()) {
            return response()->json([
                "message" => "User is not enrolled in this course"
            ], 404);
        }

        Auth::user()->courses()->detach($course->id);

        return response()->json([
            "messgae" => "Successfully unenrolled from the course"
        ]);
    }

    /**
     * get all enrolled users for a course
     */
    public function enrolledUsers(Request $req, Course $course)
    {

        if (!Auth::check()) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $users = $course->students()
            ->when($req->has("search"), function ($query) use ($req) {
                $query->where("name", "like", "%" . $req->search . "%")
                    ->orWhere("email", "like", "%" . $req->search . "%");
            })
            ->get();

        return response()->json([
            "data" => $users->map(function ($user) {
                return [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "role" => $user->role,
                    "enrolledAt" => $user->pivot->enrolled_at,
                ];
            }),
        ]);
    }


    /**
     * Get all courses a user is enrolled in
     */
    public function userEnrollments(User $user, Request $request)
    {
        // Users can only see their own enrollments unless they're admin
        $authUser = Auth::user();

        // Authorization: only admins or the user themself can view
        if (!$authUser || (!$authUser->isAdmin() && $authUser->id !== $user->id)) {
            return response()->json([
                "message" => "Unauthorized"
            ], 403);
        }

        $courses = $user->courses()
            ->with(["category", "teacher"])
            ->when($request->has("category_id"), function ($query) use ($request) {
                $query->where("category_id", $request->category_id);
            })
            ->when($request->has("search"), function ($query) use ($request) {
                $query->where("title", "like", "%" . $request->search . "%");
            })
            ->orderBy("course_user.created_at", "desc")->get();

        return response()->json([
            "data" => $courses->map(function ($course) {
                return [
                    "courseId" => $course->id,
                    "courseTitle" => [
                        "en" => $course->getTranslation('title', 'en'),
                        "ar" => $course->getTranslation('title', 'ar')
                    ],
                    "courseDescription" => [
                        "en" => $course->getTranslation('description', 'en'),
                        "ar" => $course->getTranslation('description', 'ar')
                    ],
                    "courseThumbnailUrl" => $course->thumbnail_url,
                    "instructor" => [
                        "instructorId" => $course->teacher->id,
                        "instructorName" => $course->teacher->name,
                    ],
                    "categoryName" => [
                        "en" => $course->category->getTranslation('name', 'en'),
                        "ar" => $course->category->getTranslation('name', 'ar')
                    ],
                    "userEnrolledAt" => $course->pivot->enrolled_at,
                ];
            }),
        ]);
    }

    public function isEnrolled(Request $request)
    {
        $userId = $request->input('user_id');
        $courseId = $request->input('course_id');

        if (!$userId || !$courseId) {
            return response()->json(['message' => 'user_id and course_id are required'], 422);
        }

        $user = User::find($userId);
        $course = Course::find($courseId);

        if (!$user || !$course) {
            return response()->json(['message' => 'User or Course not found'], 404);
        }

        $isEnrolled = $user->courses()->where('course_id', $courseId)->exists();

        if ($isEnrolled) {
            return response()->json([
                'userId' => $userId,
                'courseId' => $courseId,
                'isEnrolled' => true,
            ], 200);
        } else {
            return response()->json([
                'userId' => $userId,
                'courseId' => $courseId,
                'isEnrolled' => false,
            ], 409);
        }
    }

    /**
     * Get course progress for authenticated user
     */
    public function getCourseProgress(Course $course)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(["message" => "Unauthenticated"], 401);
        }

        $enrollment = $user->enrolledCourses()
            ->where("course_id", $course->id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                "message" => "User is not enrolled in this course"
            ], 404);
        }

        return response()->json([
            "courseId" => $course->id,
            "userId" => $user->id,
            "progress" => $enrollment->pivot->progress,
            "videosCompleted" => (bool)$enrollment->pivot->videos_completed,
            "completedAt" => $enrollment->pivot->completed_at,
            "canTakeQuiz" => (bool)$enrollment->pivot->videos_completed
        ]);
    }

    /**
     * Update course progress
     */
    public function updateProgress(Request $request, Course $course)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(["message" => "Unauthenticated"], 401);
        }

        $request->validate([
            "progress" => ["required", "integer", "min:0", "max:100"],
            "videos_completed" => ["sometimes", "boolean"],
        ]);

        $enrollment = $user->enrolledCourses()
            ->where("course_id", $course->id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                "message" => "User is not enrolled in this course"
            ], 404);
        }

        $updateData = [
            "progress" => $request->progress
        ];

        if ($request->has("videos_completed")) {
            $updateData["videos_completed"] = $request->videos_completed;
            $updateData["completed_at"] = $request->videos_completed ? now() : null;
        }

        $user->enrolledCourses()->updateExistingPivot($course->id, $updateData);

        return response()->json([
            "message" => "Progress updated successfully",
            "progress" => $request->progress,
            "videosCompleted" => $request->videos_completed ?? $enrollment->pivot->videos_completed
        ]);
    }
}
