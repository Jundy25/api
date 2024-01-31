<?php
// app/Http/Controllers/ApiController.php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function items()
    {
        // Get items excluding those with the category "Pay"
        $items = Item::where('category', '!=', 'Pay')->get();
        // Return JSON response
        return response()->json($items);
    }


    public function addItem(Request $request)
    {
        try {
            $request->validate([
                'item' => 'required',
                'price' => 'required',
                'category' => 'required',
            ]);

            $item = Item::create([
                'item_name' => $request->input('item'),
                'price' => $request->input('price'),
                'category' => $request->input('category'),
                'created_at' => now(),
            ]);

            // Log success
            \Log::info('Item added successfully');

            return response(['message' => 'Item added successfully', 'item' => $item], 201);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error($e);

            return response(['message' => $e->getMessage()], 500);
        }
    }

    public function editItem(Request $request, $item_id)
    {
        try {
            $item = Item::find($item_id);
    
            if (!$item) {
                return response()->json(['error' => 'Item not found'], 404);
            }
    
            $data = $request->only(['item_name', 'price', 'category']);
            
            $item->update([
                'item_name' => $data['item_name'],
                'price' => $data['price'],
                'category' => $data['category'],
                'updated_at' => now(),
            ]);


            return response(['message' => 'Item updated successfully', 'item' => $item], 201);
        } catch (\Exception $e) {
            \Log::error($e); // Log the error
            dd($e->getMessage());
    
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteItem($item_id)
    {
        try {
            $item = Item::findOrFail($item_id);
             

            // Use transactions to ensure atomicity
            \DB::beginTransaction();

            try {
                $item->delete();

                \DB::commit(); // Commit the transaction if everything is successful

                // Log success outside of the try-catch block
                \Log::info("Item deleted successfully");

                return response(['message' => 'Item deleted successfully'], 200);
            } catch (\Exception $e) {
                \DB::rollBack(); // Roll back the transaction in case of any exception

                // Log the exception
                \Log::error('Item deletion failed: ' . $e->getMessage());

                // Re-throw the exception after logging
                throw $e;
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Log the exception
            \Log::error($e);

            // Return custom error message for 404 error
            return response(['message' => 'Item not found'], 404);
        } catch (\Exception $e) {
            // Log other exceptions
            \Log::error('Item deletion failed: ' . $e->getMessage());

            return response(['message' => 'Item deletion failed: ' . $e->getMessage()], 500);
        }
    }

};