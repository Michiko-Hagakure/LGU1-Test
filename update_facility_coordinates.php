<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Sample coordinates for Metro Manila facilities
$coordinates = [
    // Manila City Hall area
    ['lat' => 14.5995, 'lng' => 120.9842, 'city' => 'Manila'],
    ['lat' => 14.6042, 'lng' => 120.9822, 'city' => 'Manila'],
    
    // Quezon City area
    ['lat' => 14.6760, 'lng' => 121.0437, 'city' => 'Quezon City'],
    ['lat' => 14.6507, 'lng' => 121.0494, 'city' => 'Quezon City'],
    
    // Makati area
    ['lat' => 14.5547, 'lng' => 121.0244, 'city' => 'Makati'],
    ['lat' => 14.5657, 'lng' => 121.0446, 'city' => 'Makati'],
    
    // Pasig area
    ['lat' => 14.5764, 'lng' => 121.0851, 'city' => 'Pasig'],
    ['lat' => 14.5844, 'lng' => 121.0794, 'city' => 'Pasig'],
];

$facilities = DB::connection('facilities_db')->table('facilities')->get();

echo "Updating " . $facilities->count() . " facilities with coordinates...\n\n";

foreach ($facilities as $index => $facility) {
    $coord = $coordinates[$index % count($coordinates)];
    
    DB::connection('facilities_db')
        ->table('facilities')
        ->where('facility_id', $facility->facility_id)
        ->update([
            'latitude' => $coord['lat'],
            'longitude' => $coord['lng'],
            'city' => $coord['city'],
            'full_address' => $facility->address . ', ' . $coord['city'] . ', Metro Manila',
            'view_count' => rand(50, 500),
            'rating' => rand(35, 50) / 10, // Random rating between 3.5 and 5.0
        ]);
    
    echo "✓ Updated: {$facility->name}\n";
    echo "  Location: {$coord['city']}\n";
    echo "  Coordinates: {$coord['lat']}, {$coord['lng']}\n\n";
}

echo "\n✅ All facilities updated successfully!\n";
echo "📍 Total facilities with coordinates: " . $facilities->count() . "\n";
