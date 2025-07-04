// <?php

// use App\Http\Controllers\API\V1\{
//     CategoryController,
//     CourseController,
//     EnrollmentController
// };
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Auth\{
//     RegisteredUserController,
//     RegisterUserFromPhoneController,
//     EmailVerificationNotificationController,
//     NewPassWordController,
//     PasswordResetLinkController,
//     SessionController,
//     VerifyEmailController,
// };

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// //This routes made for authentication

// //public routes
// Route::middleware("guest:sanctum")->group(function () {
//     Route::post("/register", [RegisteredUserController::class, "store"])
//         ->name("register");

//     Route::post("/phone-register", [RegisterUserFromPhoneController::class, "store"]);

//     Route::post("/login", [SessionController::class, "store"])
//         ->name("login");

//     Route::post("/forgot-password", [PasswordResetLinkController::class, "store"])
//         ->name("password.reset");

//     Route::post("/reset-password", [NewPassWordController::class, "store"])
//         ->name("password.store");
// });

// // Protected routes (require auth via Sanctum token)
// Route::middleware("auth:sanctum")->group(function () {

//     Route::delete("/logout", [SessionController::class, "destroy"])
//         ->name("logout");

//     Route::post("/email/verification-notification", [EmailVerificationNotificationController::class, "store"])
//         ->middleware('throttle:6,1')
//         ->name('verification.send');
// });

// Route::post("/resend-email-verification-link", [EmailVerificationNotificationController::class, "resend"])
//     ->middleware("throttle:6.1")
//     ->name("verification.link.resend");

// // Email verification (signed URL + auth)
// Route::get("/verify-email/{id}/{hash}", [VerifyEmailController::class, "__invoke"])
//     ->middleware(["signed", "throttle:6,1"])
//     ->name('verification.verify');

// Route::post("/verify-otp", [RegisterUserFromPhoneController::class, "verifyOtp"]);

//This routes made for category/courses/sections/videos

// Route::group(["prefix" => "v1", "namespace" => "App\Http\Controllers\API\V1"], function () {
//     Route::get("categories", [CategoryController::class, "index"]);
//     Route::get('/courses', [CourseController::class, 'index']);
//     Route::get('/courses/{course}', [CourseController::class, 'show']);

//     //enrollments routes
//     Route::post("/courses/{course}/enroll", [EnrollmentController::class, "enroll"])->middleware("auth:sanctum");
//     Route::delete("/courses/{course}/unenroll", [EnrollmentController::class, "unenroll"])->middleware("auth:sanctum");
//     Route::get("/courses/{course}/enrollments", [EnrollmentController::class, "enrolledUsers"])->middleware("auth:sanctum");
//     Route::get("/users/{user}/enrollments", [EnrollmentController::class, "userEnrollments"])->middleware("auth:sanctum");
//     Route::post('/enrollment/check', [EnrollmentController::class, 'isEnrolled'])->middleware("auth:sanctum");

// });

// Route::middleware('auth:sanctum')->get('/notifications', function (Request $request) {
//     return $request->user()->unreadNotifications;
// });

// Route::middleware('auth:sanctum')->post('/notifications/read', function (Request $request) {
//     $request->user()->unreadNotifications->markAsRead();
//     return response()->noContent();
// });





