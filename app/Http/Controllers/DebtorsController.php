<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Debtors;
use App\Models\Uthang;
use App\Models\Sale;
use App\Models\History;
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
    public function updateStatus(Request $request, $d_id)
    {
        try {
            $debtor = Debtors::find($d_id);
    
            if (!$debtor) {
                return response()->json(['error' => 'Debtor not found'], 404);
            }
    
            $data = $request->only(['status']);
            
            $debtor->update([
                'status' => $data['status'],
            ]);

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
    
            // Update the associated user record if it exists
            if ($debtor->user) {
                $debtor->user->update([
                    'name' => $data['d_name'],
                    'updated_at' => now(),
                ]);
            }
    
            // Fetch the updated debtor to include it in the response
            $updatedDebtor = Debtors::with('user')->find($d_id);
    
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

    public function dataPayment(Request $request, $d_id)
    {
        $request->validate([
            'data_amount' => 'required|numeric',
        ]);

        try {
            // Retrieve the debtor
            $debtor = Debtors::findOrFail($d_id);
            $partial = $request->input('data_amount');
            // Update the data_amount field with the partial payment
            $debtor->update([
                'data_amount' => $debtor->data_amount + $partial,
                'last_payment_date' => now(),
            ]);
            $debtorName = Debtors::where('d_id', $d_id)->value('d_name');
            History::create([
                'transaction' => "Partially Paid: ₱{$partial}.00",
                'd_id' => $d_id,
                'name' => $debtorName,
                'date' => now()
            ]);

            Sale::create([
                'item_id' => 13,
                'quantity_sold' => 0,
                'price' => $partial,
                'sale_date' => now(),
                'debtor_name' => $debtorName,
            ]);

            return response()->json(['message' => 'Partial payment successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }


    public function updateDataAmount($d_id, Request $request)
    {
        $newDataAmount = $request->input('data_amount');

        try {
            // Update data_amount in the database (replace Debtor with your actual model)
            $debtor = Debtors::find($d_id);
            $debtor->data_amount = $newDataAmount;
            $debtor->save();
            $debtor->update([
                'due_date' => null,
                'last_payment_date' => now(),
            ]);
 
            return response()->json([], 204); // No content
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function deleteUtangs(Request $request, $d_id)
    {   
        $data_amount = $request->input('data_amount');

        try {
            // Log before deletion
            logger('Before deletion: ' . now());
    
            // Get the debtor's name
            $debtorName = Debtors::where('d_id', $d_id)->value('d_name');
    
            // Get the grand total
            $grandTotal = Uthang::where('d_id', $d_id)->sum('total');
    
            // Delete utangs associated with the debtor
            Uthang::where('d_id', $d_id)->delete();
            $debtor = Debtors::find($d_id);
            $debtor->update([
                'due_date' => null,
            ]);
    
            // Insert a message into the history table
            if($data_amount >= $grandTotal){
                History::create([
                    'transaction' => "Uthangs Fully Paid. Grand Total: ₱{$data_amount}.00", 
                    'd_id' => $d_id,
                    'name' => $debtorName,
                    'date' => now()
                ]);

                Sale::create([
                    'item_id' => 12,
                    'quantity_sold' => 0,
                    'price' => $data_amount,
                    'sale_date' => now(),
                    'debtor_name' => $debtorName,
                ]);
            }elseif($data_amount < $grandTotal){
                History::create([
                    'transaction' => "Balance Fully Paid. Total: ₱{$data_amount}.00", 
                    'd_id' => $d_id,
                    'name' => $debtorName,
                    'date' => now()
                ]);

                Sale::create([
                    'item_id' => 14,
                    'quantity_sold' => 0,
                    'price' => $data_amount,
                    'sale_date' => now(),
                    'debtor_name' => $debtorName,
                ]);
            }
            // Log after deletion
            logger('After deletion: ' . now());
    
            return response()->json(['message' => 'Uthangs deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting utangs: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function checkBalance($d_id, Request $request){
        try {
            $newDataAmount = $request->input('newDataAmount');
    
            $debtor = Debtors::find($d_id);
            $debtor->data_amount = $newDataAmount;
            $debtor->save();
            Uthang::where('d_id', $d_id)->delete();
    
            return response()->json(['message' => 'Check successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
    



}

