<?php

// app/Http/Controllers/UthangController.php

namespace App\Http\Controllers;

use App\Models\Uthang;
use App\Models\Debtors;
use App\Models\Item;
use App\Models\History;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UthangController extends Controller
{
    public function uthangs()
    {
        $uthangs = Uthang::all();

        return response()->json($uthangs);
    }

    public function getUthangsByDebtorId($id)
    {
        $results = DB::table('uthangs')
            ->select('uthangs.u_id', 'items.item_name', 'uthangs.quantity', 'uthangs.price', DB::raw('uthangs.quantity * uthangs.price AS total'), DB::raw('DATE_FORMAT(uthangs.added_on, "%m/%d/%y") AS date'))
            ->join('items', 'uthangs.item_id', '=', 'items.item_id')
            ->where('uthangs.d_id', $id)
            ->get();

        return response()->json($results);
    }

    public function addUthang(Request $request)
    {
    try {
        
        $data = $request->input();
        $debtor = Debtors::find($data['d_id']);
        $uthangsCount = Uthang::where('d_id', $data['d_id'])->count();
        $lastpay = $debtor->last_payment_date;


        if ($uthangsCount > 0 && $debtor->due_date === null) {
            $dueDate = (!$lastpay) ? Carbon::now()->addDays(15) : Carbon::parse($debtor->last_payment_date)->addDays(15);

            $debtor->update([
                'due_date' => $dueDate,]);
        } else if ($uthangsCount <= 0){
            $dueDate = (!$lastpay) ? Carbon::now()->addDays(15) : Carbon::parse($debtor->last_payment_date)->addDays(15);

            $debtor->update([
                'due_date' => $dueDate,]);
            
        }
        
        
        $itemPrice = Item::where('item_id', $data['item_id'])->value('price');
        $totalPrice = $itemPrice * $data['quantity'];

        $uthang = new Uthang([
            'd_id' => $data['d_id'], 
            'item_id' => $data['item_id'], 
            'quantity' => $data['quantity'], 
            'price' => $itemPrice, 
            'total' => $totalPrice, 
            'added_on' => now(), 
            'updated_at' => now(), 
        ]);

        $uthang->save();



        return response()->json(['message' => 'Uthang added successfully'], 201);
    } catch (\Exception $e) {
        if (strpos($e->getMessage(), 'Total price exceeds 1000 for this item') !== false) {
            return response()->json(['error' => 'Total price exceeds 1000 for this item'], 400);
        } if (strpos($e->getMessage(), 'Exceeded maximum total of Debt') !== false) {
            return response()->json(['error' => 'Exceeded maximum total of Debt'], 400);
        } else {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}

public function updateUthang(Request $request, $u_id)
{
    try {
        $uthang = Uthang::find($u_id);

        if (!$uthang) {
            return response()->json(['error' => 'Uthang not found'], 404);
        }

        $data = $request->only(['quantity', 'item_id']);

        // Update the fields
        $itemPrice = Item::where('item_id', $data['item_id'])->value('price');
        $totalPrice = $itemPrice * $data['quantity'];

        $uthang->update([
            'item_id' => $data['item_id'],
            'quantity' => $data['quantity'],
            'price' => $itemPrice,
            'total' => $totalPrice,
            'updated_at' => now(),
        ]);

        // Fetch the updated uthang to include it in the response
        $updatedUthang = Uthang::find($u_id);

        return response()->json([
            'message' => 'Uthang updated successfully',
            'uthang' => $updatedUthang,
        ], 200);
    } catch (\Exception $e) {
        \Log::error($e);

        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function deleteUthang($u_id)
    {
        try {
            
            $uthang = Uthang::with('item')->find($u_id);

                if (!$uthang) {
                    return response()->json(['error' => 'Uthang not found'], 404);
                }

                $debtor = Debtors::find($uthang->d_id);

                if (!$debtor) {
                    return response()->json(['error' => 'Debtor not found for Uthang'], 404);
                }

            if (!$uthang) {
                return response()->json(['error' => 'Uthang not found'], 404);
            }
            $qty = $uthang->quantity;
            $itemId= $uthang->item->item_id;
            $itemName = $uthang->item->item_name;
            $itemPrice = $uthang->item->price;
            $debtor = Debtors::find($uthang->d_id);
            $totalPrice = $qty * $itemPrice;
            History::create([
                'transaction' => "Paid utang {$itemName} x{$uthang->quantity}", 
                'd_id' => $uthang->d_id,
                'payment' => $totalPrice,
                'name' => $debtor->d_name,
                'date' => now()
            ]);
            Sale::create([
                'item_id' => $itemId,
                'quantity_sold' => $qty,
                'price' => $totalPrice,
                'sale_date' => now(),
                'debtor_name' => $debtor->d_name,
            ]);
            
            $uthang->delete();
            
            $uthangsCount = Uthang::where('d_id', $debtor->d_id)->count();
            
            if ($uthangsCount <= 0){
                $debtor->update([
                    'due_date' => null,
                ]);
            }

            return response()->json(['message' => 'Uthang deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error($e);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    // Add other methods as needed for update, delete, etc.
}
