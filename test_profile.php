<?php
$ch = curl_init('http://localhost:8000/profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "HTTP Status: " . $httpCode . PHP_EOL;
if ($httpCode === 200) {
    echo "SUCCESS: Profile page is accessible" . PHP_EOL;
    echo "Response length: " . strlen($response) . " bytes" . PHP_EOL;
    // Check if it contains expected content
    if (strpos($response, 'My Profile') !== false) {
        echo "Profile page contains expected title" . PHP_EOL;
    } else {
        echo "Profile page may not be rendering correctly" . PHP_EOL;
        echo "First 200 chars: " . substr($response, 0, 200) . PHP_EOL;
    }
} else {
    echo "ERROR: Profile page returned HTTP " . $httpCode . PHP_EOL;
    echo "Response: " . $response . PHP_EOL;
}
curl_close($ch);
?>