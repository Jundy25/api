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

public function updateDebtor(Request $request, $d_id)
{
    try {
        $debtor = Debtors::find($d_id);

        if (!$debtor) {
            return response()->json(['error' => 'Debtor not found'], 404);
        }

        $data = $request->only(['d_name', 'phone', 'address']);
        
        $debtor->update([
            'd_name' => $data['d_name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'updated_at' => now(),
        ]);

        // Fetch the updated debtor to include it in the response
        $updatedDebtor = Debtors::find($d_id);

        return response()->json([
            'message' => 'Debtor updated successfully',
            'debtor' => $updatedDebtor,
        ], 200);
    } catch (\Exception $e) {
        \Log::error($e); // Log the error
        dd($e->getMessage());

        return response()->json(['error' => $e->getMessage()], 500);
    }
}


}
