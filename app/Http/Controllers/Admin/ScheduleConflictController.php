<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleConflictController extends Controller
{
    /**
     * Display a listing of schedule conflicts.
     */
    public function index(Request $request)
    {
        $facilityId = $request->input('facility_id');
        $dateFilter = $request->input('date_filter', 'future'); // future, past, all
        $severity = $request->input('severity'); // resolved, unresolved

        // Get all bookings that might have conflicts
        $query = DB::connection('facilities_db')
            ->table('bookings as a')
            ->select(
                'a.id as booking_id',
                'a.facility_id',
                'a.event_date',
                'a.start_time',
                'a.end_time',
                'a.status',
                'a.purpose',
                DB::raw('COUNT(DISTINCT b.id) as conflict_count')
            )
            ->join('bookings as b', function ($join) {
                $join->on('a.facility_id', '=', 'b.facility_id')
                    ->on('a.event_date', '=', 'b.event_date')
                    ->whereRaw('a.id < b.id') // Prevent duplicates
                    ->whereRaw('(a.start_time < b.end_time AND a.end_time > b.start_time)');
            })
            ->whereIn('a.status', ['staff_verified', 'paid', 'confirmed'])
            ->whereIn('b.status', ['staff_verified', 'paid', 'confirmed']);

        // Apply filters
        if ($facilityId) {
            $query->where('a.facility_id', $facilityId);
        }

        if ($dateFilter === 'future') {
            $query->where('a.event_date', '>=', now()->toDateString());
        } elseif ($dateFilter === 'past') {
            $query->where('a.event_date', '<', now()->toDateString());
        }

        $conflicts = $query->groupBy('a.id', 'a.facility_id', 'a.event_date', 'a.start_time', 'a.end_time', 'a.status', 'a.purpose')
            ->having('conflict_count', '>', 0)
            ->orderBy('a.event_date', 'desc')
            ->orderBy('a.start_time')
            ->paginate(15);

        // Get detailed booking information for each conflict
        $conflictDetails = [];
        foreach ($conflicts as $conflict) {
            $booking = Booking::with(['facility', 'user'])
                ->find($conflict->booking_id);
            
            if ($booking) {
                $conflictingBookings = $this->getConflictingBookings($booking);
                $conflictDetails[] = [
                    'main_booking' => $booking,
                    'conflicts' => $conflictingBookings,
                    'conflict_count' => $conflictingBookings->count(),
                ];
            }
        }

        // Get facilities for filter
        $facilities = DB::connection('facilities_db')
            ->table('facilities')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get counts
        $totalConflicts = count($conflictDetails);
        $futureConflicts = collect($conflictDetails)
            ->filter(fn($c) => $c['main_booking']->event_date >= now()->toDateString())
            ->count();

        return view('admin.schedule-conflicts.index', compact(
            'conflictDetails',
            'facilities',
            'facilityId',
            'dateFilter',
            'totalConflicts',
            'futureConflicts',
            'conflicts'
        ));
    }

    /**
     * Show details of a specific conflict.
     */
    public function show($id)
    {
        $booking = Booking::with(['facility', 'user', 'paymentSlip'])->findOrFail($id);
        $conflictingBookings = $this->getConflictingBookings($booking);

        return view('admin.schedule-conflicts.show', compact('booking', 'conflictingBookings'));
    }

    /**
     * Mark conflict as resolved.
     */
    public function resolve(Request $request, $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // Add resolution notes to booking
            DB::connection('auth_db')->table('audit_logs')->insert([
                'user_id' => session('user_id'),
                'action' => 'resolve_conflict',
                'model' => 'Booking',
                'model_id' => $id,
                'changes' => json_encode([
                    'resolution_notes' => $request->input('notes'),
                    'resolved_at' => now(),
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            return redirect()->route('admin.schedule-conflicts.index')
                ->with('success', 'Conflict marked as resolved.');

        } catch (\Exception $e) {
            \Log::error('Failed to resolve conflict: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to resolve conflict. Please try again.');
        }
    }

    /**
     * Get conflicting bookings for a given booking.
     */
    private function getConflictingBookings($booking)
    {
        return Booking::where('facility_id', $booking->facility_id)
            ->where('event_date', $booking->event_date)
            ->where('id', '!=', $booking->id)
            ->whereIn('status', ['staff_verified', 'paid', 'confirmed'])
            ->where(function($query) use ($booking) {
                $query->where(function($q) use ($booking) {
                    $q->where('start_time', '>=', $booking->start_time)
                      ->where('start_time', '<', $booking->end_time);
                })
                ->orWhere(function($q) use ($booking) {
                    $q->where('end_time', '>', $booking->start_time)
                      ->where('end_time', '<=', $booking->end_time);
                })
                ->orWhere(function($q) use ($booking) {
                    $q->where('start_time', '<=', $booking->start_time)
                      ->where('end_time', '>=', $booking->end_time);
                });
            })
            ->with(['facility', 'user'])
            ->get();
    }
}

