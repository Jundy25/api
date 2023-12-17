<?php
// app/Http/Controllers/ApiController.php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ItemController extends Controller
{
    public function categories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function items()
{
    try {
        $items = Item::with('categories')->get();
        return response()->json($items);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


}
