<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Debtors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DebtorsController extends Controller
{
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
