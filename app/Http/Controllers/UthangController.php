<?php

// app/Http/Controllers/UthangController.php

namespace App\Http\Controllers;

use App\Models\Uthang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UthangController extends Controller
{
    public function uthang()
    {
        $uthangs = Uthang::all();

        return response()->json($uthangs);
    }

    public function store(Request $request)
    {
        $uthang = Uthang::create($request->all());

        return response()->json($uthang, 201);
    }

    // Add other methods as needed for update, delete, etc.
}
