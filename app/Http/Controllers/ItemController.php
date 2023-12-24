<?php
// app/Http/Controllers/ApiController.php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function items()
    {
        $items = Item::all();
        return response()->json($items);
    }

};