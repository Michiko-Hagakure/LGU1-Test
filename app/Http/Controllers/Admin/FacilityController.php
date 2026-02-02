<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    /**
     * Display a listing of facilities.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $facilityType = $request->input('facility_type');
        $status = $request->input('status');
        $cityId = $request->input('city_id');
        $showDeleted = $request->input('show_deleted', false);

        $query = DB::connection('facilities_db')
            ->table('facilities')
            ->leftJoin('lgu_cities', 'facilities.lgu_city_id', '=', 'lgu_cities.id')
            ->select(
                'facilities.*',
                'lgu_cities.city_name'
            );

        // Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('facilities.name', 'LIKE', "%{$search}%")
                  ->orWhere('facilities.address', 'LIKE', "%{$search}%")
                  ->orWhere('facilities.description', 'LIKE', "%{$search}%");
            });
        }

        // Filter by facility type
        if ($facilityType) {
            $query->where('facilities.type', $facilityType);
        }

        // Filter by status
        if ($status) {
            $query->where('facilities.status', $status);
        }

        // Filter by city
        if ($cityId) {
            $query->where('facilities.lgu_city_id', $cityId);
        }

        // Show deleted
        if ($showDeleted) {
            $query->whereNotNull('facilities.deleted_at');
        } else {
            $query->whereNull('facilities.deleted_at');
        }

        $facilities = $query->orderBy('facilities.created_at', 'desc')
            ->paginate(15);

        // Get counts for tabs
        $activeFacilitiesCount = DB::connection('facilities_db')
            ->table('facilities')
            ->whereNull('deleted_at')
            ->count();

        $archivedFacilitiesCount = DB::connection('facilities_db')
            ->table('facilities')
            ->whereNotNull('deleted_at')
            ->count();

        // Get cities for filter dropdown
        $cities = DB::connection('facilities_db')
            ->table('lgu_cities')
            ->where('status', 'active')
            ->orderBy('city_name')
            ->get();

        // Facility types
        $facilityTypes = [
            'gymnasium' => 'Gymnasium',
            'convention_center' => 'Convention Center',
            'function_hall' => 'Function Hall',
            'sports_complex' => 'Sports Complex',
            'covered_court' => 'Covered Court',
            'auditorium' => 'Auditorium',
            'meeting_room' => 'Meeting Room',
            'other' => 'Other'
        ];

        // Status options
        $statusOptions = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'under_maintenance' => 'Under Maintenance',
            'coming_soon' => 'Coming Soon'
        ];

        return view('admin.facilities.index', compact(
            'facilities',
            'cities',
            'facilityTypes',
            'statusOptions',
            'search',
            'facilityType',
            'status',
            'cityId',
            'showDeleted',
            'activeFacilitiesCount',
            'archivedFacilitiesCount'
        ));
    }

    /**
     * Show the form for creating a new facility.
     */
    public function create()
    {
        // Get cities for dropdown
        $cities = DB::connection('facilities_db')
            ->table('lgu_cities')
            ->where('status', 'active')
            ->orderBy('city_name')
            ->get();

        return view('admin.facilities.create', compact('cities'));
    }

    /**
     * Store a newly created facility in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'per_person_rate' => 'required|numeric|min:0',
            'is_available' => 'nullable|in:0,1',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image_path')) {
                $imagePath = $request->file('image_path')->store('facilities', 'public');
            }

            // Insert facility
            $facilityId = DB::connection('facilities_db')->table('facilities')->insertGetId([
                'lgu_city_id' => $request->city_id,
                'name' => $request->name,
                'description' => $request->description,
                'capacity' => $request->capacity,
                'address' => $request->address,
                'per_person_rate' => $request->per_person_rate,
                'is_available' => $request->is_available ?? 1,
                'image_path' => $imagePath,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log activity
            DB::connection('auth_db')->table('audit_logs')->insert([
                'user_id' => session('user_id'),
                'action' => 'create',
                'model' => 'Facility',
                'model_id' => $facilityId,
                'changes' => json_encode($request->except(['_token', 'image_path'])),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            return redirect()->route('admin.facilities.index')
                ->with('success', 'Facility created successfully!');

        } catch (\Exception $e) {
            \Log::error('Failed to create facility: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create facility. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified facility.
     */
    public function edit($id)
    {
        $facility = DB::connection('facilities_db')
            ->table('facilities')
            ->leftJoin('lgu_cities', 'facilities.lgu_city_id', '=', 'lgu_cities.id')
            ->select('facilities.*', 'lgu_cities.city_name')
            ->where('facilities.facility_id', $id)
            ->first();

        if (!$facility) {
            return redirect()->route('admin.facilities.index')
                ->with('error', 'Facility not found.');
        }

        // Get cities for dropdown
        $cities = DB::connection('facilities_db')
            ->table('lgu_cities')
            ->where('status', 'active')
            ->orderBy('city_name')
            ->get();

        // Get facility images
        $facilityImages = DB::connection('facilities_db')
            ->table('facility_images')
            ->where('facility_id', $id)
            ->orderBy('sort_order')
            ->get();

        return view('admin.facilities.edit', compact(
            'facility',
            'cities',
            'facilityImages'
        ));
    }

    /**
     * Update the specified facility in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'city_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'per_person_rate' => 'required|numeric|min:0',
            'is_available' => 'nullable|in:0,1',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Get existing facility
            $facility = DB::connection('facilities_db')
                ->table('facilities')
                ->where('facility_id', $id)
                ->first();

            if (!$facility) {
                return redirect()->route('admin.facilities.index')
                    ->with('error', 'Facility not found.');
            }

            // Handle primary image upload
            $imagePath = $facility->image_path;
            if ($request->hasFile('image_path')) {
                // Delete old image
                if ($facility->image_path) {
                    Storage::disk('public')->delete($facility->image_path);
                }
                $imagePath = $request->file('image_path')->store('facilities', 'public');
            }

            // Handle additional images upload
            if ($request->hasFile('additional_images')) {
                $currentMaxOrder = DB::connection('facilities_db')
                    ->table('facility_images')
                    ->where('facility_id', $id)
                    ->max('sort_order') ?? 0;

                foreach ($request->file('additional_images') as $index => $image) {
                    $additionalImagePath = $image->store('facilities', 'public');
                    DB::connection('facilities_db')->table('facility_images')->insert([
                        'facility_id' => $id,
                        'image_path' => $additionalImagePath,
                        'sort_order' => $currentMaxOrder + $index + 1,
                        'is_primary' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            // Update facility
            DB::connection('facilities_db')->table('facilities')
                ->where('facility_id', $id)
                ->update([
                    'lgu_city_id' => $request->city_id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'capacity' => $request->capacity,
                    'address' => $request->address,
                    'per_person_rate' => $request->per_person_rate,
                    'is_available' => $request->is_available ?? 1,
                    'image_path' => $imagePath,
                    'updated_at' => now()
                ]);

            // Log activity
            DB::connection('auth_db')->table('audit_logs')->insert([
                'user_id' => session('user_id'),
                'action' => 'update',
                'model' => 'Facility',
                'model_id' => $id,
                'changes' => json_encode($request->except(['_token', '_method', 'image_path'])),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            return redirect()->route('admin.facilities.index')
                ->with('success', 'Facility updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Failed to update facility: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update facility. Please try again.')
                ->withInput();
        }
    }

    /**
     * Soft delete the specified facility.
     */
    public function destroy($id)
    {
        try {
            // Check if facility has active bookings
            $activeBookings = DB::connection('facilities_db')
                ->table('bookings')
                ->where('facility_id', $id)
                ->whereIn('status', ['pending', 'staff_verified', 'paid', 'confirmed'])
                ->count();

            if ($activeBookings > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete facility with active bookings. Please cancel all bookings first.');
            }

            // Soft delete
            DB::connection('facilities_db')->table('facilities')
                ->where('facility_id', $id)
                ->update([
                    'deleted_at' => now(),
                    'is_available' => 0
                ]);

            // Log activity
            DB::connection('auth_db')->table('audit_logs')->insert([
                'user_id' => session('user_id'),
                'action' => 'delete',
                'model' => 'Facility',
                'model_id' => $id,
                'changes' => json_encode(['deleted' => true]),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now()
            ]);

            return redirect()->route('admin.facilities.index')
                ->with('success', 'Facility deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Failed to delete facility: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete facility. Please try again.');
        }
    }

    /**
     * Restore a soft-deleted facility.
     */
    public function restore($id)
    {
        try {
            DB::connection('facilities_db')->table('facilities')
                ->where('facility_id', $id)
                ->update([
                    'deleted_at' => null,
                    'is_available' => 1
                ]);

            // Log activity
            DB::connection('auth_db')->table('audit_logs')->insert([
                'user_id' => session('user_id'),
                'action' => 'restore',
                'model' => 'Facility',
                'model_id' => $id,
                'changes' => json_encode(['restored' => true]),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now()
            ]);

            return redirect()->route('admin.facilities.index')
                ->with('success', 'Facility restored successfully!');

        } catch (\Exception $e) {
            \Log::error('Failed to restore facility: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to restore facility. Please try again.');
        }
    }

    /**
     * Delete the primary facility image.
     */
    public function deletePrimaryImage(Request $request, $facilityId)
    {
        try {
            $facility = DB::connection('facilities_db')
                ->table('facilities')
                ->where('facility_id', $facilityId)
                ->first();

            if (!$facility) {
                return response()->json(['success' => false, 'message' => 'Facility not found.'], 404);
            }

            if ($facility->image_path) {
                // Delete from storage
                Storage::disk('public')->delete($facility->image_path);

                // Update database
                DB::connection('facilities_db')
                    ->table('facilities')
                    ->where('facility_id', $facilityId)
                    ->update(['image_path' => null, 'updated_at' => now()]);
            }

            return response()->json(['success' => true, 'message' => 'Primary image deleted successfully.']);

        } catch (\Exception $e) {
            \Log::error('Failed to delete primary facility image: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete image.'], 500);
        }
    }

    /**
     * Delete a facility image.
     */
    public function deleteImage(Request $request, $facilityId, $imageId)
    {
        try {
            $image = DB::connection('facilities_db')
                ->table('facility_images')
                ->where('id', $imageId)
                ->where('facility_id', $facilityId)
                ->first();

            if (!$image) {
                return response()->json(['success' => false, 'message' => 'Image not found.'], 404);
            }

            // Delete from storage
            Storage::disk('public')->delete($image->image_path);

            // Delete from database
            DB::connection('facilities_db')
                ->table('facility_images')
                ->where('id', $imageId)
                ->delete();

            return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);

        } catch (\Exception $e) {
            \Log::error('Failed to delete facility image: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete image.'], 500);
        }
    }
}

