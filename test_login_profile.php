<?php
// Test login and profile access
$ch = curl_init();

// First, get the login page to obtain CSRF token
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$loginPage = curl_exec($ch);

// Extract CSRF token from login page
$csrfToken = '';
if (preg_match('/name="token" value="([^"]+)"/', $loginPage, $matches)) {
    $csrfToken = $matches[1];
} elseif (preg_match('/name="_token" value="([^"]+)"/', $loginPage, $matches)) {
    $csrfToken = $matches[1];
}

// Login with credentials
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 
    'email=admin@pawhome.ph&password=password&_token=' . urlencode($csrfToken));
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "Login HTTP Status: " . $httpCode . PHP_EOL;

// Now try to access profile with the same session
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, 'http://localhost:8000/profile');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_COOKIEFILE, 'cookie.txt');
curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);

$profileResponse = curl_exec($ch2);
$profileHttpCode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);

echo "Profile HTTP Status: " . $profileHttpCode . PHP_EOL;

if ($profileHttpCode === 200) {
    echo "SUCCESS: Profile accessible after login" . PHP_EOL;
    if (strpos($profileResponse, 'My Profile') !== false) {
        echo "Profile page loads correctly" . PHP_EOL;
    } else {
        echo "Profile page may have rendering issues" . PHP_EOL;
        echo "First 150 chars: " . substr($profileResponse, 0, 150) . PHP_EOL;
    }
} else {
    echo "ERROR: Profile not accessible after login" . PHP_EOL;
    echo "Response: " . substr($profileResponse, 0, 200) . PHP_EOL;
}

curl_close($ch);
curl_close($ch2);

// Clean up cookie file
if (file_exists('cookie.txt')) {
    unlink('cookie.txt');
}
?>