<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RefundController extends Controller
{
    /**
     * Display the refund queue for the treasurer.
     */
    public function index(Request $request)
    {
        $query = RefundRequest::query()->orderBy('created_at', 'desc');

        // Filter by status
        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('applicant_name', 'like', "%{$search}%")
                  ->orWhere('applicant_email', 'like', "%{$search}%");
            });
        }

        $refunds = $query->paginate(20);

        $stats = [
            'pending_method' => RefundRequest::where('status', 'pending_method')->count(),
            'pending_processing' => RefundRequest::where('status', 'pending_processing')->count(),
            'processing' => RefundRequest::where('status', 'processing')->count(),
            'completed' => RefundRequest::where('status', 'completed')->count(),
        ];

        return view('treasurer.refunds.index', compact('refunds', 'stats', 'status'));
    }

    /**
     * Show a specific refund request detail.
     */
    public function show($id)
    {
        $refund = RefundRequest::findOrFail($id);

        // Get booking details
        $booking = DB::connection('facilities_db')
            ->table('bookings')
            ->leftJoin('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->where('bookings.id', $refund->booking_id)
            ->select('bookings.*', 'facilities.name as facility_name')
            ->first();

        return view('treasurer.refunds.show', compact('refund', 'booking'));
    }

    /**
     * Process a refund (mark as processing or completed).
     */
    public function process(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:processing,completed',
            'treasurer_notes' => 'nullable|string|max:500',
            'or_number' => 'nullable|string|max:50',
        ]);

        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $refund = RefundRequest::findOrFail($id);

        // Only allow processing refunds that have a method selected
        if ($refund->status === 'pending_method') {
            return back()->with('error', 'Citizen has not yet selected a refund method.');
        }

        $action = $request->input('action');

        if ($action === 'processing') {
            $refund->status = 'processing';
            $refund->processed_by = $userId;
            $message = 'Refund marked as processing.';
        } elseif ($action === 'completed') {
            $refund->status = 'completed';
            $refund->processed_by = $userId;
            $refund->processed_at = Carbon::now();
            if ($request->filled('or_number')) {
                $refund->or_number = $request->input('or_number');
            }
            $message = 'Refund completed successfully!';
        }

        if ($request->filled('treasurer_notes')) {
            $existing = $refund->treasurer_notes ?? '';
            $refund->treasurer_notes = $existing . "\n[" . Carbon::now()->format('Y-m-d H:i') . "] " . $request->input('treasurer_notes');
        }

        $refund->save();

        // Send notification to citizen when refund is completed
        if ($action === 'completed') {
            try {
                $user = \App\Models\User::find($refund->user_id);
                if ($user) {
                    $user->notify(new \App\Notifications\RefundCompleted($refund));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send refund completed notification: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('treasurer.refunds.show', $id)
            ->with('success', $message);
    }

    /**
     * Return refunds as JSON for AJAX polling.
     */
    public function getRefundsJson(Request $request)
    {
        $query = RefundRequest::query()->orderBy('created_at', 'desc');

        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('applicant_name', 'like', "%{$search}%");
            });
        }

        $refunds = $query->limit(50)->get();

        $stats = [
            'pending_method' => RefundRequest::where('status', 'pending_method')->count(),
            'pending_processing' => RefundRequest::where('status', 'pending_processing')->count(),
            'processing' => RefundRequest::where('status', 'processing')->count(),
            'completed' => RefundRequest::where('status', 'completed')->count(),
        ];

        return response()->json(['data' => $refunds, 'stats' => $stats]);
    }
}
