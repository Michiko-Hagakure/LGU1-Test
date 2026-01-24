<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityDb;
use App\Models\User;
use App\Models\UserFavorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    private function getAuthUser()
    {
        $userId = session('user_id');
        if (!$userId) {
            abort(401, 'Unauthorized');
        }
        return User::findOrFail($userId);
    }

    public function index()
    {
        $user = $this->getAuthUser();
        
        // Get user favorites from auth_db
        $userFavorites = UserFavorite::where('user_id', $user->id)
            ->orderBy('favorited_at', 'desc')
            ->paginate(12);
        
        // Load facility details from facilities_db
        $facilityIds = $userFavorites->pluck('facility_id')->toArray();
        $facilitiesData = FacilityDb::whereIn('facility_id', $facilityIds)
            ->with(['lguCity'])
            ->get()
            ->keyBy('facility_id');
        
        // Attach facility data to each favorite
        $favorites = $userFavorites->map(function($favorite) use ($facilitiesData) {
            $facility = $facilitiesData->get($favorite->facility_id);
            if ($facility) {
                $facility->favorited_at = $favorite->favorited_at;
                $facility->favorite_id = $favorite->id;
            }
            return $facility;
        })->filter(); // Remove null values
        
        // Create a paginator with the mapped data
        $favorites = new \Illuminate\Pagination\LengthAwarePaginator(
            $favorites,
            $userFavorites->total(),
            $userFavorites->perPage(),
            $userFavorites->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('citizen.favorites.index', compact('favorites'));
    }

    public function store(Request $request)
    {
        $user = $this->getAuthUser();
        
        $request->validate([
            'facility_id' => 'required|exists:facilities_db.facilities,facility_id'
        ]);

        $facility = FacilityDb::where('facility_id', $request->facility_id)->firstOrFail();

        if ($user->hasFavorited($request->facility_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Facility is already in your favorites.'
            ], 400);
        }

        UserFavorite::create([
            'user_id' => $user->id,
            'facility_id' => $request->facility_id,
            'favorited_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Facility added to favorites!',
            'facility_name' => $facility->facility_name
        ]);
    }

    public function destroy($facilityId)
    {
        $user = $this->getAuthUser();
        
        $favorite = UserFavorite::where('user_id', $user->id)
            ->where('facility_id', $facilityId)
            ->firstOrFail();

        $facility = FacilityDb::where('facility_id', $facilityId)->first();
        $facilityName = $facility ? $facility->name : 'Facility';
        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Removed from favorites.',
            'facility_name' => $facilityName
        ]);
    }

    public function toggle(Request $request)
    {
        $user = $this->getAuthUser();
        
        $request->validate([
            'facility_id' => 'required|exists:facilities_db.facilities,facility_id'
        ]);

        // Check for existing favorite including soft-deleted ones
        $favorite = UserFavorite::withTrashed()
            ->where('user_id', $user->id)
            ->where('facility_id', $request->facility_id)
            ->first();

        $facility = FacilityDb::where('facility_id', $request->facility_id)->firstOrFail();

        if ($favorite && !$favorite->trashed()) {
            // Favorite exists and is active - remove it
            $favorite->delete();
            
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Removed from favorites.',
                'facility_name' => $facility->name
            ]);
        } else if ($favorite && $favorite->trashed()) {
            // Favorite exists but was soft-deleted - restore it
            $favorite->restore();
            $favorite->update(['favorited_at' => now()]);
            
            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Added to favorites!',
                'facility_name' => $facility->name
            ]);
        } else {
            // No favorite exists - create new one
            UserFavorite::create([
                'user_id' => $user->id,
                'facility_id' => $request->facility_id,
                'favorited_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Added to favorites!',
                'facility_name' => $facility->name
            ]);
        }
    }
}
