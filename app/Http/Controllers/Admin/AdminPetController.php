<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Shelter;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPetController extends Controller
{
    public function index()
    {
        $pets = Pet::with(['shelter','tags'])->latest()->paginate(15);
        return view('admin.pets.index', compact('pets'));
    }

    public function create()
    {
        $shelters = Shelter::where('is_active', true)->get();
        $tags     = Tag::all();
        return view('admin.pets.create', compact('shelters', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePet($request);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('pets', 'public');
        }

        $pet = Pet::create($validated);
        $pet->tags()->sync($request->input('tags', []));

        return redirect()->route('admin.pets.index')->with('success', "Pet '{$pet->name}' created successfully.");
    }

    public function edit(Pet $pet)
    {
        $shelters = Shelter::where('is_active', true)->get();
        $tags     = Tag::all();
        return view('admin.pets.edit', compact('pet', 'shelters', 'tags'));
    }

    public function update(Request $request, Pet $pet)
    {
        $validated = $this->validatePet($request, $pet->id);

        if ($request->hasFile('cover_image')) {
            if ($pet->cover_image) Storage::disk('public')->delete($pet->cover_image);
            $validated['cover_image'] = $request->file('cover_image')->store('pets', 'public');
        }

        $pet->update($validated);
        $pet->tags()->sync($request->input('tags', []));

        return redirect()->route('admin.pets.index')->with('success', "Pet '{$pet->name}' updated.");
    }

    public function destroy(Pet $pet)
    {
        if ($pet->cover_image) Storage::disk('public')->delete($pet->cover_image);
        $pet->delete();
        return redirect()->route('admin.pets.index')->with('success', 'Pet removed.');
    }

    private function validatePet(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'shelter_id'     => 'required|exists:shelters,id',
            'name'           => 'required|string|max:80',
            'species'        => 'required|in:dog,cat,rabbit,bird,other',
            'breed'          => 'nullable|string|max:100',
            'gender'         => 'required|in:male,female,unknown',
            'age_years'      => 'required|integer|min:0',
            'age_months'     => 'required|integer|min:0|max:11',
            'size'           => 'required|in:small,medium,large,extra_large',
            'color'          => 'nullable|string|max:80',
            'weight_kg'      => 'nullable|numeric|min:0',
            'description'    => 'nullable|string',
            'health_notes'   => 'nullable|string',
            'is_vaccinated'  => 'boolean',
            'is_neutered'    => 'boolean',
            'is_microchipped'=> 'boolean',
            'good_with_kids' => 'boolean',
            'good_with_dogs' => 'boolean',
            'good_with_cats' => 'boolean',
            'status'         => 'required|in:available,pending,adopted,removed',
            'featured'       => 'boolean',
            'cover_image'    => 'nullable|image|max:2048',
        ]);
    }
}
