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
            "firstName" => ["required", "string", "max:255"],
            "lastName" => ["required", "string", "max:255"],
            "phoneNumber" => ["required", "string"],
            "email" => ["required", "email", "unique:users,email"],
            "password" => ["required", "confirmed", "min:8"],
            "certificate" => ["nullable", "file", "mimes:pdf,jpg,jpeg,png", "max:10240"],
        ]);

        $certificateUrl = null;

        if ($request->hasFile('certificate')) {
            $path = $request->file('certificate')->store('certificates', 'cloudinary');
            $certificateUrl = Storage::disk('cloudinary')->url($path);
        }

        $user = User::create([
            "first_name" => $request->input("firstName"),
            "last_name" => $request->input("lastName"),
            "phone_number" => $request->input("phoneNumber"),
            "email" => $request->input('email'),
            "password" => Hash::make($request->input('password')),
            "certificate_url" => $certificateUrl,
        ]);

        event(new Registered($user));

        return response()->json([
            "message" => "User has been created",
            "user" => $user
        ], 200);
    }
}
