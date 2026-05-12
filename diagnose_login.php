<?php
// Check login response body for error messages
echo "=== Login Diagnosis ===\n";

// Get login page for CSRF token
$ch = curl_init('http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
$loginPage = curl_exec($ch);
$token = '';
if (preg_match('/name="_token" value="([^"]+)"/', $loginPage, $m)) {
    $token = $m[1];
}
curl_close($ch);

echo "Token: " . substr($token, 0, 20) . "\n";

// Attempt login - don't follow redirects
$ch = curl_init();
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
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true); // Get headers
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Don't follow redirects
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
$response = curl_exec($ch);
$headerSize = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headers = substr($response, 0, $headerSize);
$body = substr($response, $headerSize);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Login Immediate Response Code: $code\n";
echo "Response Headers:\n$headers\n";
echo "Response Body (first 500 chars):\n" . substr($body, 0, 500) . "\n";

// Check for error messages in body
if (strpos($body, 'credentials do not match') !== false) {
    echo "ERROR: Invalid credentials message found\n";
} elseif (strpos($body, 'The CSRF token is invalid') !== false || strpos($body, 'Page Expired') !== false) {
    echo "ERROR: CSRF token invalid\n";
} elseif (strpos($body, 'These fields must be filled') !== false) {
    echo "ERROR: Validation error - missing fields\n";
} else {
    echo "Login response doesn't contain obvious error messages\n";
}
?>