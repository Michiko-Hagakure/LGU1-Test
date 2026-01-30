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
        ]);

        $fundRequest = FundRequest::findOrFail($id);
        $fundRequest->status = $validated['status'];
        $fundRequest->feedback = $validated['feedback'] ?? null;
        $fundRequest->save();

        return redirect()->to(URL::signedRoute('admin.fund-requests.index'))
            ->with('success', 'Fund request ' . strtolower($validated['status']) . ' successfully.');
    }
}
