<?php
// Test with a real browser session simulation
session_start();

// Test 1: Direct GET to profile without login
echo "=== Test 1: Direct profile access (not logged in) ===\n";
$ch = curl_init('http://localhost:8000/profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
echo "HTTP Code: $code\n";
echo "Final URL: $finalUrl\n";
if (strpos($response, 'My Profile') !== false) {
    echo "Profile content found (ERROR: should not be visible without login)\n";
} elseif (strpos($response, 'Login') !== false) {
    echo "Redirected to login page (CORRECT)\n";
}
curl_close($ch);

echo "\n=== Test 2: Login and then access profile ===\n";
// Step 1: Get login page to get CSRF token
$ch = curl_init('http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
$loginPage = curl_exec($ch);

// Extract CSRF token
$token = '';
if (preg_match('/name="_token" value="([^"]+)"/', $loginPage, $m)) {
    $token = $m[1];
    echo "Got CSRF token\n";
}

// Step 2: Login with credentials POST
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 
    http_build_query([
        '_token' => $token,
        'email' => 'admin@pawhome.ph',
        'password' => 'password',
        'login_role' => 'admin'
    ])
);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$loginResponse = curl_exec($ch);
$loginCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "Login response code: $loginCode\n";

// Step 3: Access profile page on same handle (same cookies)
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/profile');
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_HEADER, true);
// Remove POST options
curl_setopt($ch, CURLOPT_POSTFIELDS, null);
$profileResponse = curl_exec($ch);
$profileCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$profileFinalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
echo "Profile response code: $profileCode\n";
echo "Profile final URL: $profileFinalUrl\n";

if ($profileCode == 302) {
    echo "Redirected away from profile (not logged in)\n";
} elseif (strpos($profileResponse, 'My Profile') !== false) {
    echo "SUCCESS: Profile page content found\n";
} else {
    echo "Profile response doesn't contain 'My Profile'\n";
    echo "First 300 chars of response:\n";
    echo substr($profileResponse, 0, 300) . "\n";
}
curl_close($ch);
unlink('cookie.txt');
?>