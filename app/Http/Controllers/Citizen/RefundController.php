<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    /**
     * Show all refund requests for the logged-in citizen.
     */
    public function index()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $refunds = RefundRequest::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('citizen.refunds.index', compact('refunds'));
    }

    /**
     * Show a specific refund request with method selection form.
     */
    public function show($id)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $refund = RefundRequest::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        return view('citizen.refunds.show', compact('refund'));
    }

    /**
     * Submit the citizen's chosen refund method.
     */
    public function selectMethod(Request $request, $id)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $refund = RefundRequest::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        if ($refund->status !== 'pending_method') {
            return back()->with('error', 'Refund method has already been selected.');
        }

        $request->validate([
            'refund_method' => 'required|in:cash,gcash,maya,bank_transfer',
            'account_name' => 'required_unless:refund_method,cash|nullable|string|max:255',
            'account_number' => 'required_unless:refund_method,cash|nullable|string|max:50',
            'bank_name' => 'required_if:refund_method,bank_transfer|nullable|string|max:255',
        ]);

        $refund->refund_method = $request->input('refund_method');

        if ($request->input('refund_method') !== 'cash') {
            $refund->account_name = $request->input('account_name');
            $refund->account_number = $request->input('account_number');
            if ($request->input('refund_method') === 'bank_transfer') {
                $refund->bank_name = $request->input('bank_name');
            }
        }

        $refund->status = 'pending_processing';
        $refund->save();

        return redirect()
            ->route('citizen.refunds.show', $id)
            ->with('success', 'Refund method selected successfully! Your refund will be processed within 1-3 business days.');
    }
}
