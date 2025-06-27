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
            ]);
        });

        return response()->json([
            "message" => "Successfully enrolled in the course",
            "enrollment" => [
                "courseId" => $course->id,
                'courseTitle' => $course->title,
                "userId" => Auth::user()->id,
                "enrolledAt" => now()->toDateString(),
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
        //only teacher of the course and admins can see enrolled users
        if (Auth::user()->role !== "admin" || $course->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $users = $course->students()
            ->when($req->has("search"), function ($query) use ($req) {
                $query->where("name", "like", "%" . $req->search . "%")
                    ->orWhere("email", "like", "%" . $req->search . "%");
            });

        return response()->json([
            "data" => $users->map(
                function ($user) {
                    return [
                        "id" => $user->id,
                        "name" => $user->name,
                        "email" => $user->email,
                        "role" => $user->role,
                        "enrolledAt" => $user->pivot->enrolled_at,
                    ];
                }
            ),
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
                    "courseTitle" => $course->title,
                    "courseDescription" => $course->description,
                    "courseThumbnailUrl" => $course->thumbnail_url,
                    "instructor" => [
                        "instructorId" => $course->teacher->id,
                        "instructorName" => $course->teacher->name,
                    ],
                    "categoryName" => $course->category->name,
                    "userEnrolledAt" => $course->pivot->enrolled_at,
                ];
            }),
        ]);
    }
}
