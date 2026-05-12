<?php
// Direct database connection to fix user roles
$host = 'localhost';
$db   = 'pawhome';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connected to database successfully\n";
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Check current users
$stmt = $pdo->query("SELECT id, name, email, role FROM users");
$users = $stmt->fetchAll();
echo "Found " . count($users) . " users:\n";
foreach($users as $user) {
    echo "ID: {$user['id']} | Name: {$user['name']} | Email: {$user['email']} | Role: " . ($user['role'] ?? 'NULL') . "\n";
}

// Update any NULL roles to 'user'
$nullStmt = $pdo->prepare("UPDATE users SET role = 'user' WHERE role IS NULL");
$nullStmt->execute();
$nullCount = $nullStmt->rowCount();
if($nullCount > 0) {
    echo "Updated {$nullCount} users with NULL role to 'user'\n";
}

// Ensure admin user has admin role
$adminEmail = 'admin@pawhome.ph';
$adminCheck = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$adminCheck->execute([$adminEmail]);
if($adminCheck->rowCount() > 0) {
    $adminUpdate = $pdo->prepare("UPDATE users SET role = 'admin' WHERE email = ?");
    $adminUpdate->execute([$adminEmail]);
    echo "Set admin user ({$adminEmail}) role to 'admin'\n";
} else {
    // Create admin user
    $adminInsert = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $password = password_hash('password', PASSWORD_DEFAULT);
    $adminInsert->execute(['Administrator', $adminEmail, $password, 'admin']);
    echo "Created admin user with email: {$adminEmail}\n";
}

// Create test guest user if needed
$guestEmail = 'guest@test.com';
$guestCheck = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$guestCheck->execute([$guestEmail]);
if($guestCheck->rowCount() == 0) {
    $guestInsert = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $password = password_hash('password', PASSWORD_DEFAULT);
    $guestInsert->execute(['Guest User', $guestEmail, $password, 'user']);
    echo "Created guest user with email: {$guestEmail}\n";
}

// Final status check
$stmt = $pdo->query("SELECT id, name, email, role FROM users ORDER BY id");
$finalUsers = $stmt->fetchAll();
echo "\nFinal user status:\n";
foreach($finalUsers as $user) {
    echo "ID: {$user['id']} | Name: {$user['name']} | Email: {$user['email']} | Role: {$user['role']}\n";
}

echo "\nDone! You can now test login with:\n";
echo "- Admin: admin@pawhome.ph / password\n";
echo "- Guest: guest@test.com / password\n";
?>