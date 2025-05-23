<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
    RegisteredUserController,
    EmailVerificationNotificationController,
    NewPassWordController,
    PasswordResetLinkController,
    SessionController,
    VerifyEmailController,
};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//public routes
Route::middleware("guest:sanctum")->group(function () {
    Route::post("/register", [RegisteredUserController::class, "store"])
        ->name("register");

    Route::post("/login", [SessionController::class, "store"])
        ->name("login");

    Route::post("/forgot-password", [PasswordResetLinkController::class, "store"])
        ->name("password.reset");

    Route::post("/reset-password", [NewPassWordController::class, "store"])
        ->name("password.store");
});

// Protected routes (require auth via Sanctum token)
Route::middleware("auth:sanctum")->group(function () {

    Route::delete("/logout", [SessionController::class, "destroy"])
        ->name("logout");

    Route::post("/email/verification-notification", [EmailVerificationNotificationController::class, "store"])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::post("/resend-email-verification-link", [EmailVerificationNotificationController::class, "resend"])
        ->middleware("throttle:6.1")
        ->name("verification.link.resend");
});

// Email verification (signed URL + auth)
Route::get("/verify-email/{id}/{hash}", [VerifyEmailController::class, "__invoke"])
    ->middleware(["signed", "throttle:6,1"])
    ->name('verification.verify');
