<?php
// Test with proper method switching after login
echo "=== Testing Login and Profile Access ===\n";

// Get login page first
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
$loginPage = curl_exec($ch);

// Extract CSRF token
$token = '';
if (preg_match('/name="_token" value="([^"]+)"/', $loginPage, $m)) {
    $token = $m[1];
}
echo "CSRF Token extracted\n";

// Login POST
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
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$loginResponse = curl_exec($ch);
$loginCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "Login HTTP Code: $loginCode\n";

if ($loginCode == 302) {
    echo "Login successful (302 redirect)\n";
} else {
    echo "Login may have failed (HTTP $loginCode)\n";
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $loginBody = substr($loginResponse, $headerSize);
    if (strpos($loginBody, 'The provided credentials do not match') !== false) {
        echo "Login error: Invalid credentials\n";
    }
}

// Now access profile with GET
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/profile');
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, null);
curl_setopt($ch, CURLOPT_HEADER, true);
$profileResponse = curl_exec($ch);
$profileCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$profileHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$profileHeader = substr($profileResponse, 0, $profileHeaderSize);
$profileBody = substr($profileResponse, $profileHeaderSize);

echo "Profile HTTP Code: $profileCode\n";

// Show response headers to see redirects
echo "Profile response headers:\n";
$lines = explode("\r\n", $profileHeader);
foreach ($lines as $line) {
    if (stripos($line, 'Location:') === 0 || stripos($line, 'HTTP/') === 0) {
        echo "  $line\n";
    }
}

if ($profileCode == 302) {
    echo "Profile access redirected (likely to login)\n";
    if (preg_match('/Location: ([^\r\n]+)/', $profileHeader, $m)) {
        $loc = trim($m[1]);
        echo "Redirect URL: $loc\n";
    }
} elseif ($profileCode == 200 && strpos($profileBody, 'My Profile') !== false) {
    echo "SUCCESS: Profile page loaded!\n";
} else {
    echo "Profile page content check:\n";
    echo "First 200 chars of body:\n";
    echo substr($profileBody, 0, 200) . "\n";
}

curl_close($ch);
unlink('cookie.txt');
?>