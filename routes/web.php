<?php

use App\Http\Controllers\Admin\AdminAdoptionController;
use App\Http\Controllers\Admin\AdminPetController;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ProfileController;
use App\Models\CommunityPost;
use App\Models\ContactMessage;
use App\Models\ShelterPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/* ---------------------------------------------------------------
 | PUBLIC ROUTES
 --------------------------------------------------------------- */
Route::get('/', [HomeController::class, 'index'])->name('home');

// Pets
Route::prefix('pets')->name('pets.')->group(function () {
    Route::get('/', [PetController::class, 'index'])->name('index');
    Route::get('/{pet}', [PetController::class, 'show'])->name('show');
});

// Community (public - view only)
Route::get('/community', [CommunityController::class, 'index'])->name('community.index');

// Static pages
Route::get('/adoption-guide', fn () => view('pages.adoption-guide'))->name('adoption-guide');
Route::get('/pet-care-tips', fn () => view('pages.pet-care-tips'))->name('pet-care-tips');
Route::get('/shelter-partners', fn () => view('pages.shelter-partners'))->name('shelter-partners');
Route::get('/success-stories', fn () => view('pages.success-stories'))->name('success-stories');
Route::get('/about', fn () => view('pages.about'))->name('about');
Route::get('/contact', fn () => view('pages.contact'))->name('contact');
Route::get('/privacy', fn () => view('pages.privacy'))->name('privacy');
Route::get('/terms', fn () => view('pages.terms'))->name('terms');
Route::post('/contact', function (Request $request) {
    $request->validate(['name' => 'required', 'email' => 'required|email', 'subject' => 'required', 'message' => 'required']);
    ContactMessage::create($request->only('name', 'email', 'subject', 'message'));

    return back()->with('success', 'Message sent! We will get back to you within 24 hours.');
})->name('contact.send');

// Auth
Route::get('/login', [LoginController::class,    'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class,    'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class,    'logout'])->name('logout');

/* ---------------------------------------------------------------
 | AUTHENTICATED USER ROUTES
 --------------------------------------------------------------- */
Route::middleware(['auth'])->group(function () {

// routes/web.php
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

// OR for a community feature:
Route::get('/community/users/{user}', [UserController::class, 'show'])->name('users.show');

Route::get('/notifications/read', function() {
    \App\Models\Notification::where('user_id', Auth::id())
        ->where('is_read', false)
        ->update(['is_read' => true]);
    return back()->with('success', 'All notifications marked as read.');
})->name('notifications.read');

    // Adoption application
    Route::get('/pets/{pet}/adopt', [AdoptionController::class, 'create'])->name('adoptions.create');
    Route::post('/pets/{pet}/adopt', [AdoptionController::class, 'store'])->name('adoptions.store');
    Route::get('/my-applications', [AdoptionController::class, 'myApplications'])->name('adoptions.mine');

    // Favorites
    Route::post('/favorites/{pet}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

    // Community (authenticated - create & manage)
    Route::get('/community/create', [CommunityController::class, 'create'])->name('community.create');
    Route::post('/community', [CommunityController::class, 'store'])->name('community.store');
   Route::get('/community/my-posts', function() {
    return redirect()->route('profile');
})->name('community.mine');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    
});

/* ---------------------------------------------------------------
 | ADMIN ROUTES
 --------------------------------------------------------------- */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', fn () => redirect()->route('admin.pets.index'))->name('dashboard');

    // Pet CRUD
    Route::resource('pets', AdminPetController::class);

    // Adoptions management
    Route::get('/adoptions', [AdminAdoptionController::class, 'index'])->name('adoptions.index');
    Route::get('/adoptions/{adoption}', [AdminAdoptionController::class, 'show'])->name('adoptions.show');
    Route::patch('/adoptions/{adoption}/status', [AdminAdoptionController::class, 'updateStatus'])->name('adoptions.status');

    // Community moderation (no /admin/ prefix here — already inside prefix('admin'))
    Route::get('/community', function () {
        $posts = CommunityPost::with('user')->latest()->paginate(15);

        return view('admin.community.index', compact('posts'));
    })->name('community.index');

    Route::patch('/community/{post}/status', function (CommunityPost $post, Request $request) {
        $post->update(['status' => $request->status]);

        return back()->with('success', 'Post status updated.');
    })->name('community.status');



    // Shelter Posts (admin only)
    Route::get('/shelter-posts', function () {
        $posts = ShelterPost::with('author')->latest()->paginate(12);

        return view('admin.shelter-posts.index', compact('posts'));
    })->name('shelter-posts.index');

    Route::get('/shelter-posts/create', function () {
        return view('admin.shelter-posts.create');
    })->name('shelter-posts.create');

    Route::post('/shelter-posts', function (Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string|max:2000',
            'category' => 'required|in:news,event,spotlight,update',
            'image' => 'required|image|max:5120',
        ]);
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('shelter-posts', 'public');
        }
        ShelterPost::create([...$validated, 'user_id' => Auth::user()->id,]);

        return redirect()->route('admin.shelter-posts.index')->with('success', 'Post published successfully!');
    })->name('shelter-posts.store');

    Route::delete('/shelter-posts/{post}', function (ShelterPost $post) {
        if ($post->image && ! str_starts_with($post->image, 'http')) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();

        return back()->with('success', 'Post deleted.');
    })->name('shelter-posts.destroy');
});
