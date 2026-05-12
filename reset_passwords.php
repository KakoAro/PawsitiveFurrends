<?php
$pdo = new PDO('mysql:host=localhost;dbname=pawhome;charset=utf8mb4', 'root', '');

// Update admin user's password to 'password' (the one we've been testing)
$password = 'password';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
$result = $stmt->execute([$hash, 'admin@pawhome.ph']);

if ($result && $stmt->rowCount() > 0) {
    echo "SUCCESS: Updated password for admin@pawhome.ph" . PHP_EOL;
    echo "New hash: " . $hash . PHP_EOL;
} else {
    echo "ERROR: Failed to update password" . PHP_EOL;
    if ($stmt->errorInfo()[0] != '00000') {
        echo "Database error: " . $stmt->errorInfo()[2] . PHP_EOL;
    }
}

// Also update any other test users we might need
$testUsers = [
    ['email' => 'guest@test.com', 'name' => 'Guest User'],
    // Add others if needed
];

foreach ($testUsers as $userData) {
    // Check if user exists
    $checkStmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $checkStmt->execute([$userData['email']]);
    if ($checkStmt->rowCount() === 0) {
        // Create the user
        $insertStmt = $pdo->prepare('INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())');
        $insertStmt->execute([$userData['name'], $userData['email'], $hash, 'user']);
        echo "Created user: " . $userData['email'] . PHP_EOL;
    } else {
        // Just update the password to ensure it works
        $updateStmt = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
        $updateStmt->execute([$hash, $userData['email']]);
        echo "Updated password for: " . $userData['email'] . PHP_EOL;
    }
}

echo "Password update complete!" . PHP_EOL;
?>