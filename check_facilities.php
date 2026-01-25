<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$facilities = DB::connection('facilities_db')
    ->table('facilities')
    ->select('facility_id', 'name', 'address', 'city', 'latitude', 'longitude')
    ->get();

echo "Current Facilities in Database:\n";
echo str_repeat("=", 80) . "\n\n";

foreach ($facilities as $facility) {
    echo "ID: {$facility->facility_id}\n";
    echo "Name: {$facility->name}\n";
    echo "Address: {$facility->address}\n";
    echo "City: {$facility->city}\n";
    echo "Coordinates: {$facility->latitude}, {$facility->longitude}\n";
    echo str_repeat("-", 80) . "\n";
}

echo "\nTotal Facilities: " . $facilities->count() . "\n";
