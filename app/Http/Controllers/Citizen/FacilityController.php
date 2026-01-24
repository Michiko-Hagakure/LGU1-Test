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
            ->get();
        
        // Start query for facilities from facilities_db (showing all including "coming soon")
        $query = FacilityDb::with('lguCity')
            ->orderBy('name');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->whereHas('lguCity', function($q) use ($request) {
                $q->where('city_name', $request->city);
            });
        }

        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->capacity);
        }

        // Filter by city
        if ($request->has('city_id') && $request->city_id != '') {
            $query->where('lgu_city_id', $request->city_id);
        }
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by capacity
        if ($request->has('capacity') && $request->capacity != '') {
            $query->where('capacity', '>=', $request->capacity);
        }
        
        // Sort
        $sortBy = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');
        
        if (in_array($sortBy, ['name', 'capacity', 'per_person_rate', 'hourly_rate'])) {
            $query->orderBy($sortBy, $sortDirection);
        }
        
        // Paginate results
        $facilities = $query->orderBy('name')->paginate(9);
        $facilities->appends($request->query());

        // Get user's favorited facility IDs
        $favoritedIds = [];
        if (session('user_id')) {
            $favoritedIds = \App\Models\UserFavorite::where('user_id', session('user_id'))
                ->pluck('facility_id')
                ->toArray();
        }

        
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
        
        return view('citizen.browse-facilities', compact('facilities', 'facilityTypes', 'cities', 'favoritedIds'));
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
