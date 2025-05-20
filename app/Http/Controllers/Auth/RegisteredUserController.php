<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
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
            "email" => ["required",  "email", "unique:users,email"],
            "password" => ["required", "confirmed", Rules\Password::min(8)],
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->string("password")),
        ]);

        //This will triger the register event.
        event(new Registered($user));

        //Here we will create the user's token.
        $token = $user->createToken("auth_token")->plainTextToken;

        //Set token cookie (secure, HTTP-only)
        $cookie = Cookie(
            'auth_token',        // Cookie name
            $token,              // Value
            60 * 24 * 7,         // Expiration: 7 days (in minutes)
            null,                // Path
            null,                // Domain
            true,                // Secure (HTTPS only)
            true,                // HttpOnly
            false,               // Raw
            'Strict'             // SameSite
        );

        return response()->json([
            "message" => "user has been created",
        ], 200)->withCookie($cookie);
    }
}
