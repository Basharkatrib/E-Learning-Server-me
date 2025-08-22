<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\DeviceToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

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

        // Check if email is verified
        if (!$user->email_verified_at) {
            Auth::logout();
            return response()->json([
                'message' => 'Please verify your email address before logging in.',
                'email_verification_required' => true,
                'email' => $user->email
            ], 403);
        }

        //for FCM
        if ($request->filled("fcm_token")) {
            DeviceToken::updateOrCreate(
                [
                    "user_id" => $user->id,
                    "device_token" => $request->fcm_token,
                ],
            );
        }

        // Create a new token
        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'phoneNumber' => $user->phone_number,
                'email' => $user->email,
                'profile_image' => $user->profile_image,
                'email_verified_at' => $user->email_verified_at,
                'role' => $user->role // Include role if needed
            ],
            'token' => $token,
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

    public function redirectToGoogle()
    {
        try {
            $url = Socialite::driver('google')
                ->stateless()
                ->scopes(['openid', 'profile', 'email'])
                ->redirect()
                ->getTargetUrl();
            return response()->json(['url' => $url]);
        } catch (\Exception $e) {
            \Log::error('Google redirect error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to initialize Google login'], 500);
        }
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->scopes(['openid', 'profile', 'email'])
                ->user();

            // Log Google user data for debugging
            \Log::info('Google user data:', [
                'id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'avatar' => $googleUser->avatar
            ]);

            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                // Split the name into first and last name
                $nameParts = explode(' ', $googleUser->name, 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? null;

                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone_number' => null,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                    'role' => 'student'
                ]);
            } else {
                // Update email_verified_at if not already set
                if (!$user->email_verified_at) {
                    $user->email_verified_at = now();
                    $user->save();
                }
            }

            // Create token for API authentication
            $token = $user->createToken('auth_token')->plainTextToken;

            // Prepare user data
            $userData = [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name ?? null,
                'phone_number' => $user->phone_number ?? null,
                'email' => $user->email,
                'profile_image' => $user->profile_image ?? $googleUser->avatar,
                'email_verified_at' => $user->email_verified_at,
                'role' => $user->role // Include role if needed
            ];

            // Redirect to frontend login page with data
            return redirect('https://learnovaeducation.netlify.app/login?token=' . $token . '&user=' . urlencode(json_encode($userData)));
        } catch (\Exception $e) {
            // Redirect to frontend with error
            return redirect('https://learnovaeducation.netlify.app/login?error=' . urlencode('Failed to authenticate with Google'));
        }
    }
}
