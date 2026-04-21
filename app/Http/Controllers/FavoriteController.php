<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Pet $pet)
    {
        $user = auth()->user();
        $user->favoritePets()->toggle($pet->id);

        $isFav = $user->favoritePets()->where('pet_id', $pet->id)->exists();

        if (request()->wantsJson()) {
            return response()->json(['favorited' => $isFav]);
        }

        return back()->with('success', $isFav ? "Added {$pet->name} to favorites!" : "Removed from favorites.");
    }

    public function index()
    {
        $pets = auth()->user()->favoritePets()->with(['tags','shelter'])->paginate(12);
        return view('favorites.index', compact('pets'));
    }
}
