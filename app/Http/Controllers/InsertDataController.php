<?php

namespace App\Http\Controllers;

use App\Models\Debtors;
use App\Models\User;
use App\Models\Uthang;
use App\Models\History;
use App\Models\Image;
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
            $d_id = Debtors::where('d_name', $request->input('d_name'))->value('d_id');
            $user = User::create([
                'name' => $request->input('d_name'),
                'email' => $request->input('email'),
                'd_id' => $d_id,
                'password' => $request->input('d_name'),
                'role' => 2,
                'created_at' => now(),
            ]);

            History::create([
                'transaction' => "Added New Debtor {$request->input('d_name')}", 
                'd_id' => $d_id,
                'name' => $request->input('d_name'),
                'date' => now()
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

            $user = User::where('d_id', $d_id);

            $image = Image::where('d_id', $d_id)->first(); // Assuming you want to retrieve the first image

            // Check if there are unpaid uthangs
            if (Uthang::where('d_id', $d_id)->exists()) {
                // Uthangs are present, return custom error message
                return response(['message' => 'Cannot delete Debtor, Uthangs still unpaid.'], 422);
            }

            History::create([
                'transaction' => "Deleted {$debtor->d_name}", 
                'd_id' => $debtor->d_id,
                'name' => $debtor->d_name,
                'date' => now()
            ]);
            // Use transactions to ensure atomicity
            \DB::beginTransaction();

            try {
                $debtor->delete();
                $user->delete();
                if ($image) {
                    $image->delete();
                }

                \DB::commit(); // Commit the transaction if everything is successful

                
                // Log success outside of the try-catch block
                \Log::info("Debtor deleted successfully");

                return response(['message' => 'Debtor deleted successfully'], 200);
            } catch (\Exception $e) {
                \DB::rollBack(); // Roll back the transaction in case of any exception

                // Log the exception
                \Log::error('Debtor deletion failed: ' . $e->getMessage());

                // Re-throw the exception after logging
                throw $e;
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Log the exception
            \Log::error($e);

            // Return custom error message for 404 error
            return response(['message' => 'Debtor not found'], 404);
        } catch (\Exception $e) {
            // Log other exceptions
            \Log::error('Debtor deletion failed: ' . $e->getMessage());

            return response(['message' => 'Debtor deletion failed: ' . $e->getMessage()], 500);
        }
    }



}
