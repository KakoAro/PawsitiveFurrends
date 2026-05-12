<?php
require __DIR__ . '/bootstrap/app.php';

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

Model::unguard();

echo "Starting user role assignment...\n";

// Get all users
$users = User::all();
echo "Found " . $users->count() . " users\n";

// Update any users with NULL role to 'user'
$updated = 0;
foreach ($users as $user) {
    if (is_null($user->role)) {
        $user->role = 'user';
        $user->save();
        $updated++;
        echo "Set user ID " . $user->id . " (" . $user->email . ") role to 'user'\n";
    }
}

// Ensure we have at least one admin user
$adminUser = User::where('email', 'admin@pawhome.ph')->first();
if ($adminUser) {
    if ($adminUser->role !== 'admin') {
        $adminUser->role = 'admin';
        $adminUser->save();
        echo "Set admin user (ID: " . $adminUser->id . ") role to 'admin'\n";
    } else {
        echo "Admin user already has 'admin' role\n";
    }
} else {
    // Create admin user if doesn't exist
    $adminUser = User::create([
        'name' => 'Administrator',
        'email' => 'admin@pawhome.ph',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);
    echo "Created admin user (ID: " . $adminUser->id . ")\n";
}

// Create a test guest user if we don't have regular users
$guestUser = User::where('email', 'guest@test.com')->first();
if (!$guestUser) {
    $guestUser = User::create([
        'name' => 'Guest User',
        'email' => 'guest@test.com',
        'password' => bcrypt('password'),
        'role' => 'user'
    ]);
    echo "Created guest user (ID: " . $guestUser->id . ")\n";
}

// Show final status
echo "\nFinal user status:\n";
$allUsers = User::all();
foreach ($allUsers as $user) {
    echo "- ID: " . $user->id . " | Name: " . $user->name . " | Email: " . $user->email . " | Role: " . $user->role . "\n";
}

echo "Done!\n";
?>