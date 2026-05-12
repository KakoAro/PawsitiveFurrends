<?php
$password = 'password';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password: $password" . PHP_EOL;
echo "Generated hash: $hash" . PHP_EOL;
echo "Hash length: " . strlen($hash) . PHP_EOL;

// Now check what's in the database
$pdo = new PDO('mysql:host=localhost;dbname=pawhome;charset=utf8mb4', 'root', '');
$stmt = $pdo->query('SELECT password FROM users WHERE email = "admin@pawhome.ph"');
$row = $stmt->fetch();
$dbHash = $row['password'];
echo "Database hash: $dbHash" . PHP_EOL;
echo "Database hash length: " . strlen($dbHash) . PHP_EOL;

echo "Do they match? " . ($hash === $dbHash ? 'YES' : 'NO') . PHP_EOL;

// Try to verify the password against the database hash
if (password_verify($password, $dbHash)) {
    echo "password_verify says: MATCH" . PHP_EOL;
} else {
    echo "password_verify says: NO MATCH" . PHP_EOL;
}
?>