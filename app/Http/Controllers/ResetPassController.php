<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ResetPassController extends Controller
{
    public function resetPassword(Request $request)
    {
        // Validate new password and confirm password
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = User::find($request->user_id);

        // Check if the user exists
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Log the old and new passwords for debugging
        \Log::info('Old Password:', ['old_password' => $request->old_password]);
        \Log::info('New Password:', ['new_password' => $request->new_password]);

        // Validate old password
        if (!Hash::check($request->old_password, $user->password)) {
            // Log the error for debugging
            \Log::error('Old password is incorrect');

            return response()->json(['error' => 'Old password is incorrect'], 401);
        }

        // Set and hash the new password
        $user->password = bcrypt($request->new_password);

        // Save the user model
        if ($user->save()) {
            // Log success message for debugging
            \Log::info('Password reset successfully');

            return response()->json(['message' => 'Password reset successfully']);
        } else {
            // Log the error for debugging
            \Log::error('Error saving the new password');

            return response()->json(['error' => 'Error saving the new password'], 500);
        }
    }
}
