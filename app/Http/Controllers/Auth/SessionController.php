<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    /** 
     *Handel an incoming authentication request
     */
    public function store(LoginRequest $request): JsonResponse
    {
        //Handel the request
        $request->authenticate();

        // Get the authenticated user
        $user = Auth::user();

        // Create a new token
        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            "user" => [
                "userName" => $user->name,
                "email" => $user->email,
            ],
            "token" => $token,
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                "message" =>  "Unauthorized",
            ], 401);
        }

        // Get the authenticated user
        $user = $request->user(); // or Auth::user()

        // Revoke all tokens (logout from all devices)
        $user->currentAccessToken()->delete();

        return response()->json([
            "message" => "Logged-out successfully"
        ], 200);
    }
}
