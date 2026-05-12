<?php
$pdo = new PDO('mysql:host=localhost;dbname=pawhome;charset=utf8mb4', 'root', '');
$stmt = $pdo->query('SELECT password FROM users WHERE email = "admin@pawhome.ph"');
$row = $stmt->fetch();
if ($row) {
    $storedHash = $row['password'];
    echo "Stored hash: " . $storedHash . PHP_EOL;
    
    $password = 'password';
    if (password_verify($password, $storedHash)) {
        echo "SUCCESS: Password '$password' matches the stored hash" . PHP_EOL;
    } else {
        echo "ERROR: Password '$password' does NOT match the stored hash" . PHP_EOL;
    }
} else {
    echo 'Admin user not found' . PHP_EOL;
}
?>