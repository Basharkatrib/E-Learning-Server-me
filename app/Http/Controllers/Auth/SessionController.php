<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

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

        // Set token cookie (secure, HTTP-only)
        $cookie = Cookie(
            'auth_token',        // Cookie name
            $token,              // Value
            60 * 24 * 7,         // Expiration: 7 days (in minutes)
            null,                // Path
            null,                // Domain
            false,                // Secure (HTTPS only)
            true,                // HttpOnly
            false,               // Raw
            'Strict'             // SameSite
        );

        return response()->json([
            'message' => 'Login successful',
        ])->withCookie($cookie);
    }

    public function destroy(Request $request): JsonResponse
    {
        // Revoke the current access token
        $request->user()?->currentAccessToken()?->delete();

        // Forget the auth token cookie
        $cookie = cookie()->forget('auth_token');

        return response()->json([
            'message' => 'Logged out successfully',
        ])->withCookie($cookie);
    }
}
