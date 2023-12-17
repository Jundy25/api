<?php
// app/Http/Controllers/ApiController.php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function getCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function getItems()
    {
        $items = Item::with('category')->get();
        return response()->json($items);
    }

    // Add more methods for CRUD operations as needed
}
