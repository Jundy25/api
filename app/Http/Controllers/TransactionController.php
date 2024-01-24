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
        // Logic to fetch all transactions in descending order
        $transac = Transaction::orderBy('h_id', 'desc')->get();

        return response()->json($transac);
    } catch (\Exception $e) {
        // Log the error
        \Log::error($e);
        // Return a meaningful error response
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
    }


    public function getTransactionsByDebtorId($d_id)
    {
    try {
        // Logic to fetch transactions for the specified debtor ID in descending order
        $transactions = Transaction::where('d_id', $d_id)
            ->orderBy('h_id', 'desc')
            ->get();

        return response()->json($transactions);
    } catch (\Exception $e) {
        // Log the error
        \Log::error($e);
        // Return a meaningful error response
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
    }

    public function getUserTransactions($d_id)
    {
    try {
        // Logic to fetch transactions for the specified debtor ID in descending order
        $transactions = Transaction::where('d_id', $d_id)
            ->get();

        return response()->json($transactions);
    } catch (\Exception $e) {
        // Log the error
        \Log::error($e);
        // Return a meaningful error response
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
    }

}
