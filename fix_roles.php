<?php
// Test direct database access
require __DIR__ . '/vendor/autoload.php';

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->boot();

use Illuminate\Support\Facades\DB;
use App\Models\User;

// Check current roles
echo "Current users in database:\n";
$users = DB::table('users')->get();
foreach($users as $user) {
    echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Role: " . ($user->role ?? 'NULL') . "\n";
}

// Ensure admin role for admin user
$adminExists = DB::table('users')->where('email', 'admin@pawhome.ph')->exists();
if($adminExists) {
    DB::table('users')
        ->where('email', 'admin@pawhome.ph')
        ->update(['role' => 'admin']);
    echo "\nUpdated admin@pawhome.ph to role: admin\n";
} else {
    // Create admin user
    DB::table('users')->insert([
        'name' => 'Administrator',
        'email' => 'admin@pawhome.ph',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "\nCreated admin user\n";
}

// Ensure we have at least one regular user
$guestExists = DB::table('users')->where('email', 'guest@test.com')->exists();
if(!$guestExists) {
    DB::table('users')->insert([
        'name' => 'Guest User',
        'email' => 'guest@test.com',
        'password' => bcrypt('password'),
        'role' => 'user',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "Created guest user\n";
}

// Set any NULL roles to 'user'
$nullCount = DB::table('users')->whereNull('role')->count();
if($nullCount > 0) {
    DB::table('users')->whereNull('role')->update(['role' => 'user']);
    echo "Updated {$nullCount} users with NULL role to 'user'\n";
}

// Final status
echo "\nFinal status:\n";
$finalUsers = DB::table('users')->get();
foreach($finalUsers as $user) {
    echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Role: {$user->role}\n";
}
?>