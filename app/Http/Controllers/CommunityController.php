<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index()
    {
        $query = CommunityPost::query()
            ->where('status', 'approved')
            ->with('user');

        $category = request()->get('category');
        // Only apply filter if category is provided and valid
        if ($category && in_array($category, ['stray', 'rescued', 'lost', 'found'])) {
            $query->where('category', $category);
        }

        $posts = $query->latest()
            ->paginate(12);

        return view('community.index', compact('posts'));
    }

    public function create()
    {
        return view('community.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string|min:20|max:1000',
             'category'    => 'required|in:stray,rescued,lost,found',
            'location'    => 'nullable|string|max:200',
            'contact'     => 'nullable|string|max:100',
            'image'       => 'nullable|image|max:3072',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('community', 'public');
        }

        CommunityPost::create([
            ...$validated,
            'user_id' => Auth::id(),
            'status'  => 'pending',
        ]);

        return redirect()->route('community.index')
            ->with('success', 'Your post has been submitted and is pending review. Thank you for helping! 🐾');
    }

    public function myPosts()
    {
         /** @var \Illuminate\Pagination\LengthAwarePaginator $posts */
        $posts = CommunityPost::query()
    ->where('user_id', Auth::id())
    ->latest()
    ->paginate(10);
        return view('community.my-posts', compact('posts'));
    }
}