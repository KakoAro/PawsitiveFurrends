<?php
// Complete test: login as admin, then access profile
$ch = curl_init();

// Step 1: Get login page to obtain CSRF token
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$loginPage = curl_exec($ch);

// Extract CSRF token from login page
$csrfToken = '';
if (preg_match('/name="_token" value="([^"]+)"/', $loginPage, $matches)) {
    $csrfToken = $matches[1];
} else {
    // Fallback: look for any token input
    if (preg_match('/name="token" value="([^"]+)"/', $loginPage, $matches)) {
        $csrfToken = $matches[1];
    }
}

if (empty($csrfToken)) {
    echo "ERROR: Could not find CSRF token in login page" . PHP_EOL;
    echo "First 500 chars of login page: " . substr($loginPage, 0, 500) . PHP_EOL;
    exit(1);
}

// Step 2: Login with credentials
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 
    'email=admin@pawhome.ph&password=password&_token=' . $csrfToken);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true); // Include headers to see redirect

$loginResponse = curl_exec($ch);
$loginHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$loginHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$loginHeader = substr($loginResponse, 0, $loginHeaderSize);
$loginBody = substr($loginResponse, $loginHeaderSize);

echo "Login HTTP Status: " . $loginHttpCode . PHP_EOL;
echo "Login Headers: " . $loginHeader . PHP_EOL;

// Step 3: Access profile with same session
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, 'http://localhost:8000/profile');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_COOKIEFILE, 'cookie.txt');
curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch2, CURLOPT_HEADER, true);

$profileResponse = curl_exec($ch2);
$profileHttpCode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
$profileHeaderSize = curl_getinfo($ch2, CURLINFO_HEADER_SIZE);
$profileHeader = substr($profileResponse, 0, $profileHeaderSize);
$profileBody = substr($profileResponse, $profileHeaderSize);

echo "Profile HTTP Status: " . $profileHttpCode . PHP_EOL;
echo "Profile Headers: " . $profileHeader . PHP_EOL;

if ($profileHttpCode === 200) {
    echo "SUCCESS: Profile accessible after login" . PHP_EOL;
    if (strpos($profileBody, 'My Profile') !== false) {
        echo "Profile page loads correctly with expected content" . PHP_EOL;
    } else {
        echo "Profile page loads but content may be incorrect" . PHP_EOL;
        echo "First 200 chars of body: " . substr($profileBody, 0, 200) . PHP_EOL;
    }
} else {
    echo "ERROR: Profile not accessible after login" . PHP_EOL;
    echo "Response body: " . $profileBody . PHP_EOL;
}

// Cleanup
curl_close($ch);
curl_close($ch2);
if (file_exists('cookie.txt')) {
    unlink('cookie.txt');
}
?>