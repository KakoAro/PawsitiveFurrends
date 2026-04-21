<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdoptionController extends Controller
{
    public function create(Pet $pet)
    {
        abort_if($pet->status !== 'available', 404);
        return view('pets.adopt', compact('pet'));
    }

    public function store(Request $request, Pet $pet)
    {
        abort_if($pet->status !== 'available', 404);

        $validated = $request->validate([
            'applicant_name'  => 'required|string|max:100',
            'applicant_email' => 'required|email|max:180',
            'applicant_phone' => 'required|string|max:30',
            'address'         => 'required|string',
            'housing_type'    => 'required|in:house,apartment,condo,other',
            'has_yard'        => 'boolean',
            'other_pets'      => 'nullable|string|max:500',
            'reason'          => 'required|string|min:50|max:1000',
            'experience'      => 'nullable|string|max:1000',
        ]);

        // Prevent duplicate applications
        $existing = Adoption::where('pet_id', $pet->id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending','reviewing','approved'])
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have an active application for this pet.');
        }

        Adoption::create([
            ...$validated,
            'pet_id'     => $pet->id,
            'user_id'    => Auth::id(),
            'shelter_id' => $pet->shelter_id,
            'has_yard'   => $request->boolean('has_yard'),
            'status'     => 'pending',
        ]);

        // Mark pet as pending
        $pet->update(['status' => 'pending']);

        return redirect()->route('adoptions.mine')
            ->with('success', "Your application for {$pet->name} has been submitted! We'll be in touch within 24 hours.");
    }

    public function myApplications()
    {
        $applications = Adoption::where('user_id', Auth::id())
            ->with(['pet', 'shelter'])
            ->latest()
            ->paginate(10);

        return view('adoptions.mine', compact('applications'));
    }
}
