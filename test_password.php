<?php
$hash = '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
$password = 'password';

if (password_verify($password, $hash)) {
    echo "SUCCESS: Password '$password' matches the stored hash" . PHP_EOL;
} else {
    echo "ERROR: Password '$password' does NOT match the stored hash" . PHP_EOL;
    echo "Hash: " . $hash . PHP_EOL;
}
?>