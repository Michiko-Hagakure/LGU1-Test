<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class FundRequestController extends Controller
{
    /**
     * Display all fund requests from Energy Efficiency
     */
    public function index()
    {
        $requests = FundRequest::orderBy('id', 'desc')->get();
        
        // Count stats
        $stats = [
            'total' => $requests->count(),
            'pending' => $requests->where('status', 'pending')->count(),
            'approved' => $requests->where('status', 'Approved')->count(),
            'rejected' => $requests->where('status', 'Rejected')->count(),
            'total_amount' => $requests->sum('amount'),
            'approved_amount' => $requests->where('status', 'Approved')->sum('amount'),
        ];

        return view('admin.fund-requests.index', compact('requests', 'stats'));
    }

    /**
     * Update the status of a fund request
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'feedback' => 'nullable|string|max:1000',
            'assigned_facility' => 'nullable|string|max:255',
            'scheduled_date' => 'nullable|date',
            'scheduled_time' => 'nullable|string|max:10',
            'approved_amount' => 'nullable|numeric|min:0',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $fundRequest = FundRequest::findOrFail($id);
        $fundRequest->status = $validated['status'];
        $fundRequest->feedback = $validated['feedback'] ?? null;
        
        // Store approval details as JSON in a response_data field or individual fields
        if ($validated['status'] === 'Approved') {
            $responseData = [
                'approved_amount' => $validated['approved_amount'] ?? $fundRequest->amount,
                'assigned_facility' => $validated['assigned_facility'] ?? null,
                'scheduled_date' => $validated['scheduled_date'] ?? null,
                'scheduled_time' => $validated['scheduled_time'] ?? null,
                'admin_notes' => $validated['admin_notes'] ?? null,
                'approved_at' => now()->toDateTimeString(),
                'approved_by' => auth()->user()->name ?? 'Admin',
            ];
            $fundRequest->response_data = json_encode($responseData);
        }
        
        $fundRequest->save();

        return redirect()->to(URL::signedRoute('admin.fund-requests.index'))
            ->with('success', 'Fund request ' . strtolower($validated['status']) . ' successfully.');
    }
}
