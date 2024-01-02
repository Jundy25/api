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
            return response(['message' => "Account is not registered"], 401); // 401 Unauthorized
        }

        $user = $this->model->where('email', $request->email)->first();
        $token = $user->createToken($request->email . Str::random(8))->plainTextToken;
        $role = $user->role;

        return response(['token' => $token, 'role' => $role], 200);
        $user = $request->user(); // Fetch the authenticated user


    } catch (\Exception $e) {
        return response(['message' => $e->getMessage()], 500); // 500 Internal Server Error
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
