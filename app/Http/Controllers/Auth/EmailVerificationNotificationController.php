<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{

    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse
    {

        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.'
            ], 200);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification link sent.'
        ], 202);
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 202);
        }

        // Regenerate and send new verification link
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'New verification link sent!'],202);
    }
}
