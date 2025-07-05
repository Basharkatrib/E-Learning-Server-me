<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\UserOtps;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class RegisterUserFromPhoneController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "email", "unique:users,email"],
            "password" => ["required", "confirmed", "min:8"],
        ]);

        $user = User::create([
            "name" => $request->input('name'),
            "email" => $request->input('email'),
            "password" => Hash::make($request->input('password')),
        ]);

        //Generates otp

        $otp = rand(100000, 999999);

        UserOtps::create([
            "user_id" => $user->id,
            "otp" => $otp,
            "expires_at" => now()->addMinutes(15),
        ]);

        //send the otp
        Mail::raw("Your verification code is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Email Verification Code');
        });

        return response()->json([
            "message" => "User has been created",
            "user" => $user
        ], 200);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            "user_id" => ["required", "exists:users,id"],
            "otp" => ["required", "string"],
        ]);

        $otpRec = UserOtps::where("user_id", $request->user_id)
            ->where("otp", $request->otp)
            ->where("expires_at", ">", now())
            ->first();

        if (!$otpRec) {
            return response()->json([
                "message" => "Invalid or expired OTP"
            ], 400);
        }

        $user = User::find($request->user_id);
        $user->email_verified_at = now();
        $user->save();

        $otpRec->delete(); // remove OTP after successful verification

        return response()->json(
            [
                "message" => "Email verified successfully"
            ],
            200
        );
    }
    /**
     * Send password reset OTP
     */
    public function sendResetOtp(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(20);

        // Delete any existing OTPs for this user
        UserOtps::where('user_id', $user->id)->delete();

        // Create new OTP record
        UserOtps::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => $expiresAt
        ]);

        // Send OTP to user's email
        Mail::raw("Your password reset OTP is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Password Reset OTP');
        });

        return response()->json([
            'message' => 'OTP sent to your email',
            'user_id' => $user->id
        ]);
    }

    /**
     * Reset password with OTP verification
     */
    public function resetPasswordWithOtp(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|string',
            'password' => 'required|confirmed|min:8',
        ]);

        // Verify OTP
        $otpRecord = UserOtps::where('user_id', $request->user_id)
            ->where('otp', $request->otp)
            ->where('expires_at', '>',now())
            ->first();

        if (!$otpRecord) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        // Update password
        $user = User::find($request->user_id);
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the used OTP
        $otpRecord->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }
}
