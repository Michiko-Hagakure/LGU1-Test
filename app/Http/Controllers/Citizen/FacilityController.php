<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityDb;
use App\Models\LguCity;
use App\Models\Location;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of facilities for citizens to browse
     */
    public function index(Request $request)
    {
        // Get user data from session
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }
        
        // Get all cities for filter dropdown (from facilities_db)
        $cities = LguCity::where('status', 'active')
            ->orderBy('city_name')
            ->pluck('city_name')
            ->unique();
        
        // Get user's favorited facility IDs for prioritization
        $favoritedIds = \App\Models\UserFavorite::where('user_id', $userId)
            ->pluck('facility_id')
            ->toArray();
        
        // Start query for facilities from facilities_db
        $query = FacilityDb::with('lguCity')
            ->select('facilities.*');
        
        // Filter by shared favorites (from URL parameter)
        $sharedFavorites = null;
        if ($request->filled('favorites')) {
            $sharedFavoriteIds = array_filter(explode(',', $request->favorites), 'is_numeric');
            if (!empty($sharedFavoriteIds)) {
                $query->whereIn('facility_id', $sharedFavoriteIds);
                $sharedFavorites = true;
            }
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }
        
        // Filter by capacity
        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->capacity);
        }
        
        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where(function($q) use ($request) {
                $q->where('per_person_rate', '>=', $request->min_price)
                  ->orWhere('hourly_rate', '>=', $request->min_price);
            });
        }
        
        if ($request->filled('max_price')) {
            $query->where(function($q) use ($request) {
                $q->where('per_person_rate', '<=', $request->max_price)
                  ->orWhere('hourly_rate', '<=', $request->max_price);
            });
        }
        
        // Filter by availability
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('is_available', true);
            } elseif ($request->availability === 'coming_soon') {
                $query->where('is_available', false);
            }
        }
        
        // Filter by amenities (if amenities field exists)
        if ($request->filled('amenities')) {
            $amenities = $request->amenities;
            $query->where(function($q) use ($amenities) {
                foreach ($amenities as $amenity) {
                    $q->orWhere('amenities', 'LIKE', "%{$amenity}%");
                }
            });
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'favorites');
        
        switch ($sortBy) {
            case 'popularity':
                $query->orderByDesc('view_count');
                break;
            case 'rating':
                $query->orderByDesc('rating');
                break;
            case 'price_low':
                $query->orderByRaw('COALESCE(per_person_rate, hourly_rate) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(per_person_rate, hourly_rate) DESC');
                break;
            case 'capacity_high':
                $query->orderByDesc('capacity');
                break;
            case 'capacity_low':
                $query->orderBy('capacity');
                break;
            case 'name':
                $query->orderBy('name');
                break;
            case 'favorites':
            default:
                // Prioritize favorited facilities
                if (!empty($favoritedIds)) {
                    $query->orderByRaw('CASE WHEN facility_id IN (' . implode(',', $favoritedIds) . ') THEN 0 ELSE 1 END');
                }
                $query->orderBy('name');
                break;
        }
        
        // Paginate results
        $facilities = $query->paginate(9);
        $facilities->appends($request->query());

        // Facility types for filter
        $facilityTypes = collect([
            'gymnasium',
            'convention_center',
            'function_hall',
            'sports_complex',
            'auditorium',
            'meeting_room',
            'other'
        ]);
        
        // Available amenities for filter
        $availableAmenities = [
            'wifi' => 'Wi-Fi',
            'parking' => 'Parking',
            'ac' => 'Air Conditioning',
            'projector' => 'Projector',
            'sound_system' => 'Sound System',
            'catering' => 'Catering Services',
            'tables_chairs' => 'Tables & Chairs',
            'stage' => 'Stage',
            'restrooms' => 'Restrooms',
            'accessibility' => 'PWD Accessibility'
        ];
        
        // Get facilities with coordinates for map
        $facilitiesWithCoords = FacilityDb::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('facility_id', 'name', 'latitude', 'longitude', 'city', 'address', 'is_available')
            ->get();
        
        return view('citizen.browse-facilities', compact(
            'facilities', 
            'facilityTypes', 
            'cities', 
            'favoritedIds',
            'availableAmenities',
            'facilitiesWithCoords',
            'sharedFavorites'
        ));
    }
    
    /**
     * Display the specified facility
     */
    public function show($id)
    {
        // Get user data from session
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }
        
        // Get facility details from facilities_db with relationships
        $facility = FacilityDb::with(['lguCity'])
            ->where('facility_id', $id)
            ->first();
        
        if (!$facility) {
            return redirect()->route('citizen.browse-facilities')
                ->with('error', 'Facility not found.');
        }
        
        return view('citizen.facility-details', compact('facility'));
    }
}
