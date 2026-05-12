<?php
// Test with fresh cURL handle for profile
echo "=== Testing Login and Profile Access (separate handles) ===\n";

// Step 1: Get CSRF token
$ch1 = curl_init('http://localhost:8000/login');
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch1, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch1, CURLOPT_COOKIEFILE, 'cookie.txt');
$loginPage = curl_exec($ch1);
curl_close($ch1);

$token = '';
if (preg_match('/name="_token" value="([^"]+)"/', $loginPage, $m)) {
    $token = $m[1];
}
echo "CSRF Token: " . substr($token, 0, 15) . "...\n";

// Step 2: Login POST
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, 'http://localhost:8000/login');
curl_setopt($ch2, CURLOPT_POST, true);
curl_setopt($ch2, CURLOPT_POSTFIELDS, 
    http_build_query([
        '_token' => $token,
        'email' => 'admin@pawhome.ph',
        'password' => 'password',
        'login_role' => 'admin'
    ])
);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch2, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch2, CURLOPT_COOKIEFILE, 'cookie.txt');
$loginResponse = curl_exec($ch2);
$loginCode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);

echo "Login HTTP Code: $loginCode\n";

// Step 3: Access profile with GET (fresh handle)
$ch3 = curl_init('http://localhost:8000/profile');
curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch3, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch3, CURLOPT_COOKIEFILE, 'cookie.txt');
$profileResponse = curl_exec($ch3);
$profileCode = curl_getinfo($ch3, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch3, CURLINFO_EFFECTIVE_URL);
curl_close($ch3);

echo "Profile HTTP Code: $profileCode\n";
echo "Final URL: $finalUrl\n";

if ($profileCode == 200 && strpos($profileResponse, 'My Profile') !== false) {
    echo "SUCCESS: Profile accessible with My Profile heading\n";
} elseif ($profileCode == 302 && strpos($finalUrl, 'login') !== false) {
    echo "Profile access redirected to login (user not authenticated)\n";
} else {
    echo "Unsuccessful profile access\n";
    echo "Response first 200 chars:\n";
    echo substr($profileResponse, 0, 200) . "\n";
}

// Cleanup
unlink('cookie.txt');
?>