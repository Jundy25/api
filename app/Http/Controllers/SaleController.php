<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function sales()
    {
        // Fetch sales data with the required fields
        $sales = Sale::select('sales.sale_id', 'items.item_name', 'sales.quantity_sold', 'sales.price', 'sales.debtor_name', 'sales.sale_date')
        ->join('items', 'sales.item_id', '=', 'items.item_id')
        ->orderBy('sale_id', 'desc')
        ->get();

        return response()->json($sales);
}
}
