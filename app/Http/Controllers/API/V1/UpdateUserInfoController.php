<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateUserInfoController extends Controller
{
    public function updateProfile(Request $req)
    {
        $user = $req->user();

        $req->validate([
            "name" => ["sometimes", "string", "max:255"],
            "profile_image" => ["sometimes", "image", "mimes:jpeg,jpg,png,webp", "max:4096"],
        ]);

        if ($req->has("name")) {
            $user->name = $req->name;
        }

        if ($req->hasFile("profile_image")) {
            // Delete old image from Cloudinary if exists
            if ($user->profile_image) {
                Cloudinary::destroy($user->profile_image);
            }

            if ($req->hasFile("profile_image")) {
                $path = $req->file("profile_image")->store("profile_image", "cloudinary");
                Storage::disk("cloudinary")->url($path);
            }
        }

        $user->save();

        return response()->json([
            "message" => "Profile updated successfully",
            "user" => [
                "userId" => $user->id,
                "userName" => $user->name,
                "userProfileImage" => $user->profile_image
            ]
        ]);
    }
}
