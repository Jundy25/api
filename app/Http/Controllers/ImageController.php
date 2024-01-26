<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
{
    try {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'd_id' => 'required',
        ]);

        $image = $request->file('image');
        $debtorId = $request->input('d_id'); // Corrected line to get 'd_id'

        // Store the image in storage and get the path
        $imagePath = $image->store('public/images');

        // Save the image path and debtor ID to the database
            $savedImage = Image::create([
                'd_id' => $debtorId,
                'image_path' => $imagePath,
            ]);

        return response()->json(['imageId' => $savedImage->id], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    public function getImage($d_id)
    {
        try {
            $image = Image::where('d_id', $d_id)->first();

            if (!$image) {
                return response()->json(['error' => 'Image not found'], 404);
            }

            $imagePath = $image->image_path;


            return response()->file(storage_path("app/$imagePath"));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
