<?php
// Comprehensive test of login and profile access
echo "=== Starting Comprehensive Test ===\n";

// Initialize cURL
$ch = curl_init();

// Step 1: Get the login page to obtain CSRF token and initial cookies
echo "Step 1: Fetching login page...\n";
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

$loginPage = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "  Login page HTTP Status: $httpCode\n";

if ($httpCode !== 200) {
    echo "  ERROR: Failed to load login page\n";
    exit(1);
}

// Extract CSRF token from the login page
$csrfToken = '';
if (preg_match('/name="_token" value="([^"]+)"/', $loginPage, $matches)) {
    $csrfToken = $matches[1];
    echo "  CSRF Token found: " . substr($csrfToken, 0, 20) . "...\n";
} else {
    echo "  WARNING: CSRF token not found in login page\n";
    // Try alternative token name
    if (preg_match('/name="token" value="([^"]+)"/', $loginPage, $matches)) {
        $csrfToken = $matches[1];
        echo "  Found alternative token name: " . substr($csrfToken, 0, 20) . "...\n";
    }
}

// Step 2: Attempt login as admin
echo "\nStep 2: Attempting login as admin...\n";
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 
    'email=admin@pawhome.ph&password=password&_token=' . urlencode($csrfToken) .
    '&login_role=admin'); // Include our role selection
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true); // Capture headers to see redirect

$loginResponse = curl_exec($ch);
$loginHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$loginHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$loginHeader = substr($loginResponse, 0, $loginHeaderSize);
$loginBody = substr($loginResponse, $loginHeaderSize);

echo "  Login HTTP Status: $loginHttpCode\n";

// Check if login was successful (should redirect)
if ($loginHttpCode === 302) {
    echo "  Login successful (redirect received)\n";
    // Extract redirect location from headers
    if (preg_match('/Location: ([^\r\n]+)/', $loginHeader, $matches)) {
        $redirectUrl = trim($matches[1]);
        echo "  Redirecting to: $redirectUrl\n";
    }
} elseif ($loginHttpCode === 200) {
    echo "  Login returned 200 (possibly validation error)\n";
    // Check if there are error messages in the response
    if (strpos($loginBody, 'The provided credentials do not match') !== false) {
        echo "  ERROR: Invalid credentials\n";
    } elseif (strpos($loginBody, 'login_role') !== false) {
        echo "  INFO: Login form re-displayed (possibly missing token)\n";
    }
} else {
    echo "  ERROR: Unexpected HTTP status: $loginHttpCode\n";
    echo "  Response body: " . substr($loginBody, 0, 200) . "\n";
}

// Step 3: Access the profile page
echo "\nStep 3: Accessing profile page...\n";
$profileResponse = curl_exec($ch); // Re-use same handle to maintain session
$profileHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$profileHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$profileHeader = substr($profileResponse, 0, $profileHeaderSize);
$profileBody = substr($profileResponse, $profileHeaderSize);

echo "  Profile HTTP Status: $profileHttpCode\n";

if ($profileHttpCode === 200) {
    echo "  SUCCESS: Profile page accessible\n";
    // Check if we got the expected profile content
    if (strpos($profileBody, 'My Profile') !== false) {
        echo "  SUCCESS: Profile page contains expected title\n";
    } else {
        echo "  WARNING: Profile page may not contain expected content\n";
        // Show beginning of body to see what we got
        $preview = substr($profileBody, 0, 300);
        echo "  Body preview: $preview\n";
    }
} elseif ($profileHttpCode === 302) {
    echo "  REDIRECT: Profile access redirected (possibly to login)\n";
    if (preg_match('/Location: ([^\r\n]+)/', $profileHeader, $matches)) {
        $redirectUrl = trim($matches[1]);
        echo "  Redirecting to: $redirectUrl\n";
        if (strpos($redirectUrl, 'login') !== false) {
            echo "  INFO: Redirected to login (session may have expired)\n";
        }
    }
} else {
    echo "  ERROR: Unexpected HTTP status for profile: $profileHttpCode\n";
    echo "  Response headers: " . $profileHeader . "\n";
    echo "  Response body: " . substr($profileBody, 0, 200) . "\n";
}

// Step 4: Test logout
echo "\nStep 4: Testing logout...\n";
$logoutResponse = curl_exec($ch); // Re-use same handle
$logoutHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "  Logout HTTP Status: $logoutHttpCode\n";

if ($logoutHttpCode === 302) {
    echo "  Logout successful (redirect received)\n";
} else {
    echo "  Logout returned: $logoutHttpCode\n";
}

// Cleanup
curl_close($ch);
if (file_exists('cookie.txt')) {
    unlink('cookie.txt');
}

echo "\n=== Test Complete ===\n";
?>