<?php
$pdo = new PDO('mysql:host=localhost;dbname=pawhome;charset=utf8mb4', 'root', '');
$stmt = $pdo->query('SELECT password FROM users WHERE email = "admin@pawhome.ph"');
$row = $stmt->fetch();
if ($row) {
    echo 'Stored hash: ' . $row['password'] . PHP_EOL;
    echo 'Length: ' . strlen($row['password']) . PHP_EOL;
    // Check if it looks like a bcrypt hash
    if (preg_match('/^\$2y\$/', $row['password'])) {
        echo 'Appears to be a bcrypt hash' . PHP_EOL;
    } else {
        echo 'Does NOT appear to be a bcrypt hash' . PHP_EOL;
    }
} else {
    echo 'Admin user not found' . PHP_EOL;
}
?>