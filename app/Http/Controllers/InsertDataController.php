<?php

namespace App\Http\Controllers;

use App\Models\Debtors;
use Illuminate\Http\Request;

class InsertDataController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'd_name' => 'required|unique:debtors',
                'phone' => 'required',
                'address' => 'required',
            ]);

            $debtor = Debtors::create([
                'd_name' => $request->input('d_name'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
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
            $debtor->delete();

            // Log success
            \Log::info("Debtor deleted successfully");

            return response(['message' => 'Debtor deleted successfully'], 200);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error($e);

            return response(['message' => $e->getMessage()], 500);
        }
    }
}
