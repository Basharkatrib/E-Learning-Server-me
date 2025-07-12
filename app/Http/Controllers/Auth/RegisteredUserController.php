<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            "email" => ["required", "email", "unique:users,email"],
            "password" => ["required", "confirmed", "min:8"],
            "certificate" => ["nullable", "file", "mimes:pdf,jpg,jpeg,png", "max:10240"],
            "profile_image" => ["nullable", "image", "mimes:jpeg,jpg,png,webp", "max:4096"],
        ]);

        $certificateUrl = null;
        $profileImageUrl = null;

        if ($request->hasFile('certificate')) {
            $path = $request->file('certificate')->store('certificates', 'cloudinary');
            $certificateUrl = Storage::disk('cloudinary')->url($path);
        }

        if ($request->hasFile("profile_image")) {
            $path = $request->file("profile_image")->store("profile_image", "cloudinary");
            $profileImageUrl = Storage::disk("cloudinary")->url($path);
        }

        $user = User::create([
            "name" => $request->input('name'),
            "email" => $request->input('email'),
            "password" => Hash::make($request->input('password')),
            "profile_image" => $profileImageUrl,
            "certificate_url" => $certificateUrl,
        ]);

        event(new Registered($user));

        return response()->json([
            "message" => "User has been created",
            "user" => $user
        ], 200);
    }
}
