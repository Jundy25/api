<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Debtors;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserDataController extends Controller
{
    public function getUserData()
{
    try {
        // Get the authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Assuming 'name' is the common field between User and Debtors
        $debtor = Debtors::where('d_name', $user->name)->first();

        if (!$debtor) {
            return response()->json(['error' => 'Debtor not found for the user'], 404);
        }

        // You can customize the data you want to return
        $userData = [
            'id' => $user->id,
            'd_id' => $debtor->d_id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        return response()->json($userData, 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}



    
    public function sendResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        // Generate a unique token for password reset
        $token = Str::random(60);

        // Save the token in the password_resets table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['email' => $email, 'token' => Hash::make($token), 'created_at' => now()]
        );

        // Send the reset link to the user's email
        Mail::send('emails.reset_password', ['token' => $token], function ($message) use ($email) {
            $message->to($email)->subject('Password Reset Link');
        });

        return response()->json(['message' => 'Password reset link sent successfully'], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:8',
        ]);

        $email = $request->input('email');
        $token = $request->input('token');
        $password = $request->input('password');

        // Retrieve the token from the password_resets table
        $reset = DB::table('password_resets')->where('email', $email)->first();

        if (!$reset) {
            return response()->json(['error' => 'Invalid reset token'], 400);
        }

        // Verify the token
        if (!Hash::check($token, $reset->token)) {
            return response()->json(['error' => 'Invalid reset token'], 400);
        }

        // Update the user's password
        $user = User::where('email', $email)->first();
        $user->update(['password' => Hash::make($password)]);

        // Delete the used reset token
        DB::table('password_resets')->where('email', $email)->delete();

        return response()->json(['message' => 'Password reset successfully'], 200);
    }

}
