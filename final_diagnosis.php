<?php
// Detailed test: Login then profile with explicit cookie handling
echo "=== Detailed Login-Profile Test ===\n";

// Step 1: Get login page to obtain initial session and CSRF token
$ch = curl_init('http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
$loginPage = curl_exec($ch);
$cookies = curl_getinfo($ch, CURLINFO_COOKIELIST);
echo "Initial cookies count: " . count($cookies) . "\n";
$token = '';
if (preg_match('/name="_token" value="([^"]+)"/', $loginPage, $m)) {
    $token = $m[1];
}
curl_close($ch);

echo "Token: " . substr($token, 0, 20) . "\n";

// Step 2: Perform login POST
$ch = curl_init('http://localhost:8000/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 
    '_token=' . urlencode($token) . 
    '&email=admin@pawhome.ph' . 
    '&password=password' . 
    '&login_role=admin'
);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

echo "Login Final HTTP Code: $code\n";
echo "Final URL after following redirects: $finalUrl\n";

// Check if it contained profile content
if (strpos($body, 'My Profile') !== false) {
    echo "Login resulted in profile page content in response body\n";
} elseif (strpos($body, 'My Profile') === false && strpos($body, 'Login') !== false) {
    echo "Login response contains login page (login failed)\n";
}

// Show any error messages
if (preg_match('/<div[^>]*class="[^"]*alert[^"]*"[^>]*>(.*?)<\/div>/si', $body, $m)) {
    echo "Alert message: " . strip_tags($m[1]) . "\n";
}

// Save cookies to file after login
curl_close($ch);

echo "\n--- Checking saved cookies ---\n";
if (file_exists('cookie.txt')) {
    $cookieContent = file_get_contents('cookie.txt');
    echo "Cookie file size: " . strlen($cookieContent) . " bytes\n";
    // Count lines
    $lines = explode("\n", trim($cookieContent));
    echo "Cookie entries: " . count($lines) . "\n";
    // Show first few cookies
    foreach (array_slice($lines, 0, 3) as $line) {
        echo "Cookie: $line\n";
    }
}

echo "\n--- Now trying to access profile page ---\n";
$ch = curl_init('http://localhost:8000/profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
$profile = curl_exec($ch);
$profileCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$profileFinal = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "Profile access HTTP Code: $profileCode\n";
echo "Profile final URL: $profileFinal\n";

if ($profileCode == 200 && strpos($profile, 'My Profile') !== false) {
    echo "SUCCESS: Profile is accessible!\n";
} else {
    echo "Profile access failed or returned wrong content\n";
    if (strpos($profile, 'Login') !== false || strpos($profile, 'login') !== false) {
        echo "We got login page content instead of profile\n";
    }
    echo "First 200 chars of response:\n" . substr($profile, 0, 200) . "\n";
}

// Cleanup
unlink('cookie.txt');
?>