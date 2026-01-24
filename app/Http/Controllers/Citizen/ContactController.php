<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    public function index()
    {
        return view('citizen.contact.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'category' => 'required|in:general,booking_issue,payment_issue,technical_issue,complaint,suggestion,other',
            'subject' => 'required|string|max:255',
            'custom_subject' => 'required_if:subject,Other|nullable|string|max:255',
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        // Use custom subject if "Other" was selected
        if ($validated['subject'] === 'Other' && !empty($validated['custom_subject'])) {
            $validated['subject'] = $validated['custom_subject'];
        }

        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('contact-attachments', 'public');
                $attachmentPaths[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }
        }

        $inquiry = ContactInquiry::create([
            'user_id' => session('user_id'),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'category' => $request->category,
            'subject' => $request->subject,
            'message' => $request->message,
            'attachments' => $attachmentPaths,
            'status' => 'new',
            'priority' => $this->determinePriority($request->category),
        ]);

        // Send auto-response email
        // TODO: Implement email sending when email settings are configured

        return redirect()->route('citizen.contact.success')
            ->with('ticket_number', $inquiry->ticket_number);
    }

    public function success()
    {
        return view('citizen.contact.success');
    }

    public function myInquiries()
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login');
        }

        $inquiries = ContactInquiry::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('citizen.contact.my-inquiries', compact('inquiries'));
    }

    public function showInquiry($ticketNumber)
    {
        $userId = session('user_id');
        
        $inquiry = ContactInquiry::where('ticket_number', $ticketNumber)
            ->where('user_id', $userId)
            ->firstOrFail();

        return view('citizen.contact.show', compact('inquiry'));
    }

    private function determinePriority($category)
    {
        return match($category) {
            'technical_issue', 'payment_issue' => 'high',
            'complaint' => 'normal',
            default => 'normal',
        };
    }
}
