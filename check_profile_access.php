<?php
// Test what happens when accessing profile without authentication
$ch = curl_init('http://localhost:8000/profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

echo "Profile HTTP Status: " . $httpCode . PHP_EOL;
echo "Final URL: " . $finalUrl . PHP_EOL;
echo "Response length: " . strlen($response) . " bytes" . PHP_EOL;

// Check if we got redirected to login
if (strpos($finalUrl, 'login') !== false) {
    echo "REDirected to login page (expected for unauthenticated access)" . PHP_EOL;
} else {
    echo "NOT redirected to login - this is the problem!" . PHP_EOL;
}

// Check content type and what we actually got
if (strpos($response, '<!DOCTYPE html>') !== false) {
    echo "Received HTML response" . PHP_EOL;
    if (strpos($response, 'Login — Pawsitive Furrends') !== false) {
        echo "Got login page content (correct behavior)" . PHP_EOL;
    } elseif (strpos($response, 'My Profile') !== false) {
        echo "Got profile page content (WRONG - should redirect to login)" . PHP_EOL;
    } else {
        echo "Got some other HTML content" . PHP_EOL;
        echo "First 100 chars: " . substr($response, 0, 100) . PHP_EOL;
    }
} else {
    echo "Non-HTTP response received" . PHP_EOL;
}

curl_close($ch);
?>