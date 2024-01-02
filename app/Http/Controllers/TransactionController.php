<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function transactions()
{
    try {
        $transac = Transaction::all();
        return response()->json($transac);
    } catch (\Exception $e) {
        // Log the error
        \Log::error($e);
        // Return a meaningful error response
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}
}
