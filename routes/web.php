<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\Admin\AdminPetController;
use App\Http\Controllers\Admin\AdminAdoptionController;

/* ---------------------------------------------------------------
 | PUBLIC ROUTES
 --------------------------------------------------------------- */
Route::get('/', [HomeController::class, 'index'])->name('home');

// Pets
Route::prefix('pets')->name('pets.')->group(function () {
    Route::get('/',      [PetController::class, 'index'])->name('index');
    Route::get('/{pet}', [PetController::class, 'show'])->name('show');
});

// Community (public - view only)
Route::get('/community', [CommunityController::class, 'index'])->name('community.index');

// Static pages
Route::get('/adoption-guide',   fn() => view('pages.adoption-guide'))->name('adoption-guide');
Route::get('/pet-care-tips',    fn() => view('pages.pet-care-tips'))->name('pet-care-tips');
Route::get('/shelter-partners', fn() => view('pages.shelter-partners'))->name('shelter-partners');
Route::get('/success-stories',  fn() => view('pages.success-stories'))->name('success-stories');
Route::get('/about',            fn() => view('pages.about'))->name('about');
Route::get('/contact',          fn() => view('pages.contact'))->name('contact');
Route::get('/privacy',          fn() => view('pages.privacy'))->name('privacy');
Route::get('/terms',            fn() => view('pages.terms'))->name('terms');
Route::post('/contact', function(\Illuminate\Http\Request $request) {
    $request->validate(['name'=>'required','email'=>'required|email','subject'=>'required','message'=>'required']);
    \App\Models\ContactMessage::create($request->only('name','email','subject','message'));
    return back()->with('success', 'Message sent! We will get back to you within 24 hours.');
})->name('contact.send');

// Auth
Route::get('/login',    [App\Http\Controllers\Auth\LoginController::class,    'showLoginForm'])->name('login');
Route::post('/login',   [App\Http\Controllers\Auth\LoginController::class,    'login']);
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register',[App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::post('/logout',  [App\Http\Controllers\Auth\LoginController::class,    'logout'])->name('logout');

/* ---------------------------------------------------------------
 | AUTHENTICATED USER ROUTES
 --------------------------------------------------------------- */
Route::middleware(['auth'])->group(function () {

    // Adoption application
    Route::get( '/pets/{pet}/adopt', [AdoptionController::class, 'create'])->name('adoptions.create');
    Route::post('/pets/{pet}/adopt', [AdoptionController::class, 'store'])->name('adoptions.store');
    Route::get('/my-applications',   [AdoptionController::class, 'myApplications'])->name('adoptions.mine');

    // Favorites
    Route::post('/favorites/{pet}',  [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites',         [FavoriteController::class, 'index'])->name('favorites.index');

    // Community (authenticated - create & manage)
    Route::get('/community/create',   [CommunityController::class, 'create'])->name('community.create');
    Route::post('/community',         [CommunityController::class, 'store'])->name('community.store');
    Route::get('/community/my-posts', [CommunityController::class, 'myPosts'])->name('community.mine');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
});

/* ---------------------------------------------------------------
 | ADMIN ROUTES
 --------------------------------------------------------------- */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', fn() => redirect()->route('admin.pets.index'))->name('dashboard');

    // Pet CRUD
    Route::resource('pets', AdminPetController::class);

    // Adoptions management
    Route::get('/adoptions',                     [AdminAdoptionController::class, 'index'])->name('adoptions.index');
    Route::get('/adoptions/{adoption}',          [AdminAdoptionController::class, 'show'])->name('adoptions.show');
    Route::patch('/adoptions/{adoption}/status', [AdminAdoptionController::class, 'updateStatus'])->name('adoptions.status');

    // Community moderation (no /admin/ prefix here — already inside prefix('admin'))
    Route::get('/community', function() {
        $posts = \App\Models\CommunityPost::with('user')->latest()->paginate(15);
        return view('admin.community.index', compact('posts'));
    })->name('community.index');

    Route::patch('/community/{post}/status', function(\App\Models\CommunityPost $post, \Illuminate\Http\Request $request) {
        $post->update(['status' => $request->status]);
        return back()->with('success', 'Post status updated.');
    })->name('community.status');
});