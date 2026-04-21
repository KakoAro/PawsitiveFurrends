<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminPetController;
use App\Http\Controllers\Admin\AdminAdoptionController;

/* ---------------------------------------------------------------
 | PUBLIC ROUTES
 --------------------------------------------------------------- */
Route::get('/', [HomeController::class, 'index'])->name('home');

// Pets
Route::prefix('pets')->name('pets.')->group(function () {
    Route::get('/',            [PetController::class, 'index'])  ->name('index');
    Route::get('/{pet}',       [PetController::class, 'show'])   ->name('show');
});

// Auth (use Laravel Breeze / Jetstream or manual)
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
    Route::get( '/pets/{pet}/adopt',  [AdoptionController::class, 'create']) ->name('adoptions.create');
    Route::post('/pets/{pet}/adopt',  [AdoptionController::class, 'store'])  ->name('adoptions.store');

    // My applications
    Route::get('/my-applications',    [AdoptionController::class, 'myApplications'])->name('adoptions.mine');

    // Favorites
    Route::post('/favorites/{pet}',   [FavoriteController::class, 'toggle'])  ->name('favorites.toggle');
    Route::get('/favorites',          [FavoriteController::class, 'index'])   ->name('favorites.index');
});

/* ---------------------------------------------------------------
 | ADMIN ROUTES
 --------------------------------------------------------------- */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', fn() => redirect()->route('admin.pets.index'))->name('dashboard');

    // Pet CRUD
    Route::resource('pets', AdminPetController::class);

    // Adoptions management
    Route::get('/adoptions',                    [AdminAdoptionController::class, 'index'])  ->name('adoptions.index');
    Route::get('/adoptions/{adoption}',         [AdminAdoptionController::class, 'show'])   ->name('adoptions.show');
    Route::patch('/adoptions/{adoption}/status',[AdminAdoptionController::class, 'updateStatus'])->name('adoptions.status');
});
