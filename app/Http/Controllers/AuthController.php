<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Debtors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $model;

    public function __construct(){
        $this->model = new User();
    }
  
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string'
    ]);

    try {
        if (!Auth::attempt($credentials)) {
            return response(['message' => "Invalid Email or Password"], 401);
        }

        $user = $this->model->where('email', $request->email)->first();
        $tokenName = $request->email . Str::random(8);

        // Set token expiration time based on "remember me" flag
        $tokenExpiration = $request->has('remember_me') ? now()->addWeeks(1) : null;

        $token = $user->createToken($tokenName, ['role'])->plainTextToken;
        $user->tokens()->where('name', $tokenName)->update(['expires_at' => $tokenExpiration]);

        $role = $user->role;

        return response(['token' => $token, 'role' => $role], 200);
    } catch (\Exception $e) {
        return response(['message' => $e->getMessage()], 500);
    }
}



     
public function register(Request $request)
{try {

    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|confirmed|min:6',
    ]);

    try {
        $request['role'] = '2';
        $this->model->create($request->all());
        return response(['message' => "Successfully created"], 201); // 201 Created
    } catch (\Exception $e) {
        return response(['message' => $e->getMessage()], 500); // 500 Internal Server Error
    }
    // Log success
    \Log::info('Registration successful');

    return response(['message' => "Successfully created"], 201);
} catch (\Exception $e) {
    // Log the exception
    \Log::error($e);

    return response(['message' => $e->getMessage()], 500);
}

}

public function debtors()
{
    try {
        $debtors = Debtors::all();
        return response()->json($debtors);
    } catch (\Exception $e) {
        // Log the error
        \Log::error($e);
        // Return a meaningful error response
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}

}
