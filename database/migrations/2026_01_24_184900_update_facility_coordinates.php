<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Updates facility coordinates to their actual Google Maps locations.
     */
    public function up(): void
    {
        // Buena Park Sports Complex - Barangay 177, Camarin, Caloocan City, North Manila
        DB::table('facilities')
            ->where('facility_name', 'Buena Park Sports Complex')
            ->update([
                'latitude' => 14.7566,
                'longitude' => 121.0450,
                'full_address' => 'Barangay 177, Camarin, Caloocan City, North Manila',
                'city' => 'Caloocan City'
            ]);

        // Bulwagan Katipunan - New Caloocan City Hall, 8th Avenue, Grace Park, Barangay 130
        // Coordinates: 14°38'55.75"N, 120°59'26.24"E = 14.6488, 120.9906
        DB::table('facilities')
            ->where('facility_name', 'LIKE', '%Bulwagan%')
            ->update([
                'latitude' => 14.6488,
                'longitude' => 120.9906,
                'full_address' => 'New Caloocan City Hall, 8th Avenue, Grace Park, Barangay 130, Caloocan City',
                'city' => 'Caloocan City'
            ]);

        DB::table('facilities')
            ->where('facility_name', 'LIKE', '%Katipunan%')
            ->update([
                'latitude' => 14.6488,
                'longitude' => 120.9906,
                'full_address' => 'New Caloocan City Hall, 8th Avenue, Grace Park, Barangay 130, Caloocan City',
                'city' => 'Caloocan City'
            ]);

        // Pacquiao Court (Caloocan Sports Complex) - Bagumbong Rd, Corner Malapitan Road, Barangay 171
        // Coordinates: 14.7578° N, 121.0366° E
        DB::table('facilities')
            ->where('facility_name', 'LIKE', '%Pacquiao%')
            ->update([
                'latitude' => 14.7578,
                'longitude' => 121.0366,
                'full_address' => 'Bagumbong Road, Corner Malapitan Road, Barangay 171, Caloocan City, 1420 Metro Manila',
                'city' => 'Caloocan City'
            ]);

        // QC M.I.C.E. facilities - Quezon City M.I.C.E. Center, Quezon City Hall Compound, Elliptical Road, Diliman
        // Coordinates: 14.6548° N, 121.0505° E
        $miceCoords = [
            'latitude' => 14.6548,
            'longitude' => 121.0505,
            'full_address' => 'Quezon City M.I.C.E. Center, Quezon City Hall Compound, Elliptical Road, Diliman, Quezon City',
            'city' => 'Quezon City'
        ];

        DB::table('facilities')
            ->where('facility_name', 'LIKE', '%M.I.C.E.%')
            ->update($miceCoords);

        DB::table('facilities')
            ->where('facility_name', 'LIKE', '%MICE%')
            ->update($miceCoords);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset coordinates to null
        DB::table('facilities')
            ->whereIn('facility_name', [
                'Buena Park Sports Complex',
                'Bulwagan Function Hall',
                'Katipunan Hall',
                'Pacquiao Court'
            ])
            ->orWhere('facility_name', 'LIKE', '%M.I.C.E.%')
            ->orWhere('facility_name', 'LIKE', '%MICE%')
            ->update([
                'latitude' => null,
                'longitude' => null,
                'full_address' => null
            ]);
    }
};
