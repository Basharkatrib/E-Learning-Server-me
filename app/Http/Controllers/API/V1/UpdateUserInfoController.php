<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class UpdateUserInfoController extends Controller
{
    public function updateProfile(Request $req)
    {
        $user = $req->user();

        $req->validate([
            "name" => ["sometimes", "string", "max:255"],
            "profile_image" => ["sometimes", "image", "mimes:jpeg,jpg,png,webp", "max:4096"],
            "specialization" => ["sometimes", "string", "max:255"],
            "bio" => ["sometimes", "string", "max:255"],
            "country" => ["sometimes", "string", "max:255"],
        ]);

        if ($req->filled("name")) {
            $user->name = $req->name;
        }

        if ($req->hasFile("profile_image")) {
            $uploaded = Cloudinary::uploadApi()->upload(
                $req->file('profile_image')->getRealPath(),
                ['folder' => 'profile_image']
            );

            $user->profile_image = $uploaded['secure_url'];
        }

        if ($req->filled("specialization")) {
            $user->specialization = $req->specialization;
        }

        if ($req->filled("bio")) {
            $user->bio = $req->bio;
        }

        if ($req->filled("country")) {
            $user->country = $req->country;
        }

        $user->save();

        return response()->json([
            "message" => "Profile updated successfully",
            "user" => [
                "userId" => $user->id,
                "userName" => $user->name,
                "userProfileImage" => $user->profile_image,
                "userSpecialization" => $user->specialization,
                "userBio" => $user->bio,
                "userCountry" => $user->country,
            ]
        ]);
    }
}
