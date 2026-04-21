<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Tag;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $query = Pet::available()->with(['tags', 'shelter']);

        // Species filter
        if ($request->filled('species')) {
            $query->bySpecies($request->species);
        }

        // Size filter
        if ($request->filled('size')) {
            $query->bySize($request->size);
        }

        // Gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Age group filter
        if ($request->filled('age_group')) {
            $query->where(function ($q) use ($request) {
                match ($request->age_group) {
                    'baby'   => $q->whereRaw('(age_years * 12 + age_months) < 12'),
                    'young'  => $q->whereRaw('(age_years * 12 + age_months) BETWEEN 12 AND 35'),
                    'adult'  => $q->whereRaw('(age_years * 12 + age_months) BETWEEN 36 AND 95'),
                    'senior' => $q->whereRaw('(age_years * 12 + age_months) >= 96'),
                    default  => null,
                };
            });
        }

        // Breed / keyword search
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name',  'like', $search)
                  ->orWhere('breed','like', $search);
            });
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'oldest'   => $query->oldest(),
            'name_asc' => $query->orderBy('name'),
            default    => $query->latest(),
        };

        $pets = $query->paginate(12)->withQueryString();
        $tags = Tag::all();

        return view('pets.index', compact('pets', 'tags'));
    }

    public function show(Pet $pet)
    {
        abort_if($pet->status !== 'available', 404);
        $pet->load(['tags', 'shelter', 'images']);
        $related = Pet::available()
            ->where('species', $pet->species)
            ->where('id', '!=', $pet->id)
            ->limit(4)->get();

        $isFavorited = auth()->check()
            ? $pet->favoritedBy()->where('user_id', auth()->id())->exists()
            : false;

        return view('pets.show', compact('pet', 'related', 'isFavorited'));
    }
}
