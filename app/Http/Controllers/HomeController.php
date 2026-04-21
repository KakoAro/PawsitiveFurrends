<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Shelter;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPets = Pet::available()->featured()->with(['tags', 'shelter'])->limit(6)->get();
        $totalPets     = Pet::available()->count();
        $totalShelters = Shelter::where('is_active', true)->count();
        $speciesCounts = Pet::available()
            ->selectRaw('species, COUNT(*) as total')
            ->groupBy('species')
            ->pluck('total', 'species');

        return view('home', compact('featuredPets', 'totalPets', 'totalShelters', 'speciesCounts'));
    }
}
