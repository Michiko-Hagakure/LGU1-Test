<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking Google Maps API configuration...\n\n";

$apiKey = config('services.google_maps.api_key');

if ($apiKey) {
    echo "✓ API Key found in config\n";
    echo "Key: " . substr($apiKey, 0, 20) . "...\n";
    echo "Length: " . strlen($apiKey) . " characters\n";
} else {
    echo "✗ API Key NOT found in config\n";
    echo "\nChecking .env file directly...\n";
    $envKey = env('GOOGLE_MAPS_API_KEY');
    if ($envKey) {
        echo "✓ Found in .env: " . substr($envKey, 0, 20) . "...\n";
        echo "⚠ But config() is returning null - run: php artisan config:clear\n";
    } else {
        echo "✗ Not found in .env either\n";
    }
}
