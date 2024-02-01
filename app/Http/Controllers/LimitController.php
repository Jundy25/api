<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Limit;
use Illuminate\Http\JsonResponse;


class LimitController extends Controller
{
    public function inventoryLimit(): JsonResponse
    {
        $limits = Limit::find(1);

        if (!$limits) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $responseData = [
            'id' => $limits->id,
            'type' => $limits->type,
            'amount' => $limits->amount,
            // Add other fields as needed
        ];

        return response()->json($responseData);
    }

    public function limitByDebtor(): JsonResponse
    {
        $limits = Limit::find(2);

        if (!$limits) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $responseData = [
            'id' => $limits->id,
            'type' => $limits->type,
            'amount' => $limits->amount,
            // Add other fields as needed
        ];

        return response()->json($responseData);
    }

    public function interest(): JsonResponse
    {
        $limits = Limit::find(3);

        if (!$limits) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $responseData = [
            'id' => $limits->id,
            'type' => $limits->type,
            'amount' => $limits->amount,
            // Add other fields as needed
        ];

        return response()->json($responseData);
    }
    public function update(Request $request){
        try {
            $InventoryLimit = Limit::find(1);
            $LimitByDebtor = Limit::find(2);
            $Interest = Limit::find(3);
    
            $data = $request->only(['overallLimit', 'debtorLimit', 'interestMultiplier']);
    
            $InventoryLimit->update([
                'amount' => $data['overallLimit'],
                'updated_at' => now(),
            ]);

            $LimitByDebtor->update([
                'amount' => $data['debtorLimit'],
                'updated_at' => now(),
            ]);

            $Interest->update([
                'amount' => $data['interestMultiplier'],
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Updated successfully',
            ], 200);
        
    } catch (\Exception $e) {
        \Log::error($e); // Log the error
        return response()->json(['error' => $e->getMessage()], 500);
    }
    
    
}

}