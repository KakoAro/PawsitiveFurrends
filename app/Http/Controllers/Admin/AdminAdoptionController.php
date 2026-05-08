<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adoption;
use App\Models\Notification;
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



   public function updateStatus(Request $request, Adoption $adoption) {
    $request->validate([
        'status'      => 'required|in:pending,reviewing,approved,rejected,completed',
        'admin_notes' => 'nullable|string|max:1000',
    ]);

    $oldStatus = $adoption->status;

    $adoption->update([
        'status'      => $request->status,
        'admin_notes' => $request->admin_notes,
        'reviewed_at' => now(),
        'reviewed_by' => Auth::id(),
    ]);

    // Update pet status
    if ($request->status === 'approved') {
        $adoption->pet->update(['status' => 'adopted']);
    } elseif ($request->status === 'rejected') {
        $adoption->pet->update(['status' => 'available']);
    }

    // Send notification to the applicant
    if ($oldStatus !== $request->status) {
        $messages = [
            'approved'  => "Great news! Your adoption application for {$adoption->pet->name} has been APPROVED! Please contact the shelter to arrange pickup.",
            'rejected'  => "We're sorry, your adoption application for {$adoption->pet->name} was not approved at this time. Please contact us for more information.",
            'reviewing' => "Your adoption application for {$adoption->pet->name} is now being reviewed. We'll update you soon!",
            'completed' => "Congratulations! Your adoption of {$adoption->pet->name} is now complete. Welcome to the family!",
        ];

        $titles = [
            'approved'  => "Application Approved",
            'rejected'  => "Application Update",
            'reviewing' => "Application Under Review",
            'completed' => "Adoption Complete",
        ];

        if (isset($messages[$request->status])) {
            \App\Models\Notification::create([
                'user_id'    => $adoption->user_id,
                'title'      => $titles[$request->status],
                'message'    => $messages[$request->status],
                'type'       => 'adoption_' . $request->status,
                'related_id' => $adoption->id,
                'is_read'    => false,
            ]);
        }
    }

    return back()->with('success', "Application status updated to '{$request->status}'.");
}
}