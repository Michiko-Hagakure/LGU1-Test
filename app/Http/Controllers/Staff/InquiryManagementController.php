<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InquiryManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactInquiry::query();

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority != 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->has('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }

        if ($request->has('assigned') && $request->assigned === 'me') {
            $query->assignedTo(session('user_id'));
        } elseif ($request->has('assigned') && $request->assigned === 'unassigned') {
            $query->unassigned();
        }

        $inquiries = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'new' => ContactInquiry::new()->count(),
            'open' => ContactInquiry::open()->count(),
            'urgent' => ContactInquiry::urgent()->count(),
            'unassigned' => ContactInquiry::unassigned()->count(),
            'my_assigned' => ContactInquiry::assignedTo(session('user_id'))->count(),
        ];

        return view('staff.inquiries.index', compact('inquiries', 'stats'));
    }

    public function show($id)
    {
        $inquiry = ContactInquiry::with(['user', 'assignedStaff'])->findOrFail($id);
        return view('staff.inquiries.show', compact('inquiry'));
    }

    public function assign(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $inquiry = ContactInquiry::findOrFail($id);
        $inquiry->assignTo($request->assigned_to);

        DB::connection('auth_db')->table('audit_logs')->insert([
            'user_id' => session('user_id'),
            'action' => 'assign_inquiry',
            'model_type' => 'ContactInquiry',
            'model_id' => $inquiry->id,
            'changes' => json_encode(['assigned_to' => $request->assigned_to]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);

        return back()->with('success', 'Inquiry assigned successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,open,pending,resolved,closed',
        ]);

        $inquiry = ContactInquiry::findOrFail($id);
        $inquiry->update(['status' => $request->status]);

        return back()->with('success', 'Status updated successfully');
    }

    public function updatePriority(Request $request, $id)
    {
        $request->validate([
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        $inquiry = ContactInquiry::findOrFail($id);
        $inquiry->update(['priority' => $request->priority]);

        return back()->with('success', 'Priority updated successfully');
    }

    public function addNote(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string',
        ]);

        $inquiry = ContactInquiry::findOrFail($id);
        $currentNotes = $inquiry->staff_notes ?? '';
        $newNote = "[" . now()->format('Y-m-d H:i') . " - " . session('user_name') . "]\n" . $request->note;
        
        $inquiry->update([
            'staff_notes' => $currentNotes . "\n\n" . $newNote,
        ]);

        return back()->with('success', 'Note added successfully');
    }

    public function resolve(Request $request, $id)
    {
        $request->validate([
            'resolution' => 'required|string',
        ]);

        $inquiry = ContactInquiry::findOrFail($id);
        $inquiry->markResolved($request->resolution);

        // TODO: Send resolution email to citizen

        return back()->with('success', 'Inquiry marked as resolved');
    }

    public function close($id)
    {
        $inquiry = ContactInquiry::findOrFail($id);
        $inquiry->close();

        return back()->with('success', 'Inquiry closed');
    }
}
