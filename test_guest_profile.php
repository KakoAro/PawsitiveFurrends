<?php
// Test guest user login and profile access
echo "=== Testing Guest User Login and Profile Access ===\n";

// Step 1: Get login page
$ch = curl_init('http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'guest_cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'guest_cookie.txt');
$loginPage = curl_exec($ch);
$token = '';
if (preg_match('/name="_token" value="([^"]+)"/', $loginPage, $m)) {
    $token = $m[1];
}
echo "CSRF token obtained\n";
curl_close($ch);

// Step 2: Login as guest
$ch = curl_init('http://localhost:8000/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 
    http_build_query([
        '_token' => $token,
        'email' => 'guest@test.com',
        'password' => 'password',
        'login_role' => 'user'
    ])
);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'guest_cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'guest_cookie.txt');
$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "Login HTTP Code: $code\n";
echo "Final URL after login: $finalUrl\n";

// Test profile access
$ch = curl_init('http://localhost:8000/profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'guest_cookie.txt');
$profile = curl_exec($ch);
$profileCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Profile HTTP Code: $profileCode\n";

if ($profileCode == 200 && strpos($profile, 'My Profile') !== false) {
    echo "SUCCESS: Guest user can access profile!\n";
    // Check if favorites tab exists
    if (strpos($profile, 'My Favorites') !== false) {
        echo "Favorites tab is present in profile\n";
    } else {
        echo "WARNING: Favorites tab not found in profile\n";
    }
} else {
    echo "ERROR: Guest cannot access profile\n";
    echo "First 200 chars:\n" . substr($profile, 0, 200) . "\n";
}

// Cleanup
unlink('guest_cookie.txt');
?>