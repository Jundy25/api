<?php

namespace App\Http\Controllers;

use App\Models\Debtors;
use App\Models\User;
use App\Models\Uthang;
use App\Models\History;
use Illuminate\Http\Request;

class InsertDataController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'd_name' => 'required|unique:debtors',
                'email' => 'required|unique:debtors,email',
                'phone' => 'required',
                'address' => 'required',
            ]);

            $debtor = Debtors::create([
                'd_name' => $request->input('d_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'role' => 2,
                'created_at' => now(),
                'data_amount' => 0.00

            ]);
            $user = User::create([
                'name' => $request->input('d_name'),
                'email' => $request->input('email'),
                'password' => $request->input('d_name'),
                'role' => 2,
                'created_at' => now(),
            ]);

            // Log success
            \Log::info('Debtor added successfully');

            return response(['message' => 'Debtor added successfully', 'debtor' => $debtor], 201);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error($e);

            return response(['message' => $e->getMessage()], 500);
        }
    }
    public function destroy($d_id)
{
    try {
        $debtor = Debtors::findOrFail($d_id);
        $user = User::findOrFail($d_id);
        $history = History::findOrFail($d_id);

        // Check if there are unpaid uthangs
        if (Uthang::where('d_id', $d_id)->exists()) {
            // Uthangs are present, return custom error message
            return response(['message' => 'Cannot delete Debtor, Uthangs still unpaid.'], 422);
        }

        $debtor->delete();
        $user->delete();
        $history->delete();

        // Log success
        \Log::info("Debtor deleted successfully");

        return response(['message' => 'Debtor deleted successfully'], 200);
    } catch (\Exception $e) {
        // Check if the exception is due to foreign key constraint violation
        if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] === 1451) {
            // Log the exception
            \Log::error($e);

            // Return custom error message
            return response(['message' => 'Cannot delete Debtor, Uthangs still unpaid.'], 422);
        }

        // Log other exceptions
        \Log::error($e);

        return response(['message' => $e->getMessage()], 500);
    }
}

}
