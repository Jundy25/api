<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Notifications\CustomResetPasswordNotification;

class PasswordResetTokenController extends Controller
{
    
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email'),
            new CustomResetPasswordNotification($request->email)
        );

        if ($status != Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return response()->json(['status' => __($status)]);
    }
    public function getPasswordResetEmail(Request $request)
    {
        // Validate request if necessary

        // Retrieve user by email
        $user = User::where('email', $request->input('email'))->first();

        // Generate a password reset token
        $token = Str::random(64);

        // Create the notification instance
        $notification = new CustomResetPasswordNotification($token);

        // Get the mail content
        $mailContent = $notification->toMail($user)->toArray();

        return response()->json(['mailContent' => $mailContent]);
    }
}
