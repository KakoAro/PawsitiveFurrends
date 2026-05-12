<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use App\Models\CommunityPost;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $adoptions = Adoption::query()
            ->where('user_id', $user->id)
            ->with(['pet', 'shelter'])
            ->latest()
            ->get();

        $communityPosts = CommunityPost::query()
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $favoritePets = $user->favoritePets()->with(['tags','shelter'])->get();

        return view('profile.show', compact('user', 'adoptions', 'communityPosts', 'favoritePets'));
    }
}