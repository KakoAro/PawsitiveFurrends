<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adoption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAdoptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Adoption::with(['pet','user','shelter'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $adoptions = $query->paginate(15);
        return view('admin.adoptions.index', compact('adoptions'));
    }

    public function show(Adoption $adoption)
    {
        $adoption->load(['pet','user','shelter','reviewer']);
        return view('admin.adoptions.show', compact('adoption'));
    }

    public function updateStatus(Request $request, Adoption $adoption)
    {
        $request->validate([
            'status'      => 'required|in:pending,reviewing,approved,rejected,completed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $adoption->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        // Update pet status when adoption is approved/rejected
        if ($request->status === 'approved') {
            $adoption->pet->update(['status' => 'adopted']);
        } elseif ($request->status === 'rejected') {
            $adoption->pet->update(['status' => 'available']);
        }

        return back()->with('success', "Application status updated to '{$request->status}'.");
    }
}
