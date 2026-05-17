<?php

namespace App\Providers;

use App\Models\Adoption;
use App\Models\CommunityPost;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            if (!Auth::check()) {
                return;
            }

            $user = Auth::user();
            $pendingAdoptions = 0;
            $pendingCommunity = 0;
            $totalNotif = 0;
            $pendingList = collect();
            $unreadNotifs = 0;
            $myNotifs = collect();

            if ($user->role === 'admin') {
                $pendingAdoptions = Adoption::where('status', 'pending')->count();
                $pendingCommunity = CommunityPost::where('status', 'pending')->count();
                $totalNotif = $pendingAdoptions + $pendingCommunity;
                $pendingList = Adoption::where('status', 'pending')
                    ->with(['pet', 'user'])
                    ->latest()
                    ->take(5)
                    ->get();
            } else {
                $unreadNotifs = Notification::where('user_id', $user->id)
                    ->where('is_read', false)
                    ->count();
                $myNotifs = Notification::where('user_id', $user->id)
                    ->latest('created_at')
                    ->take(6)
                    ->get();
            }

            $view->with(compact('pendingAdoptions', 'pendingCommunity', 'totalNotif', 'pendingList', 'unreadNotifs', 'myNotifs'));
        });
    }
}
