<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageTemplateController extends Controller
{
    public function index()
    {
        $templates = DB::connection('mysql')
            ->table('message_templates')
            ->whereNull('deleted_at')
            ->orderBy('category')
            ->orderBy('type')
            ->orderBy('name')
            ->get();
        
        return view('admin.templates.index', compact('templates'));
    }

    public function trash()
    {
        $templates = DB::connection('mysql')
            ->table('message_templates')
            ->whereNotNull('deleted_at')
            ->orderBy('deleted_at', 'desc')
            ->get();
        
        return view('admin.templates.trash', compact('templates'));
    }

    public function create()
    {
        return view('admin.templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:booking,payment,reminder,general',
            'type' => 'required|in:email,sms,in-app',
            'subject' => 'required_if:type,email|nullable|string|max:255',
            'body' => 'required|string',
            'variables' => 'nullable|array',
        ]);

        try {
            DB::connection('mysql')->table('message_templates')->insert([
                'name' => $request->name,
                'category' => $request->category,
                'type' => $request->type,
                'subject' => $request->subject,
                'body' => $request->body,
                'variables' => json_encode($request->variables ?? []),
                'is_active' => true,
                'version' => 1,
                'created_by' => session('user_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('admin.templates.index')
                ->with('success', 'Message template created successfully!');
        } catch (\Exception $e) {
            \Log::error('Template creation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to create template: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $template = DB::connection('mysql')
            ->table('message_templates')
            ->where('id', $id)
            ->first();

        if (!$template) {
            return redirect()->route('admin.templates.index')
                ->with('error', 'Template not found.');
        }

        return view('admin.templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:booking,payment,reminder,general',
            'type' => 'required|in:email,sms,in-app',
            'subject' => 'required_if:type,email|nullable|string|max:255',
            'body' => 'required|string',
            'variables' => 'nullable|array',
        ]);

        try {
            DB::connection('mysql')
                ->table('message_templates')
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'category' => $request->category,
                    'type' => $request->type,
                    'subject' => $request->subject,
                    'body' => $request->body,
                    'variables' => json_encode($request->variables ?? []),
                    'updated_by' => session('user_id'),
                    'updated_at' => now(),
                ]);

            return redirect()->route('admin.templates.index')
                ->with('success', 'Message template updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Template update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update template: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::connection('mysql')
                ->table('message_templates')
                ->where('id', $id)
                ->update([
                    'deleted_at' => now(),
                    'deleted_by' => session('user_id'),
                ]);

            return redirect()->route('admin.templates.index')
                ->with('success', 'Message template moved to trash!');
        } catch (\Exception $e) {
            \Log::error('Template deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete template: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            DB::connection('mysql')
                ->table('message_templates')
                ->where('id', $id)
                ->update([
                    'deleted_at' => null,
                    'deleted_by' => null,
                ]);

            return redirect()->route('admin.templates.trash')
                ->with('success', 'Message template restored successfully!');
        } catch (\Exception $e) {
            \Log::error('Template restoration failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to restore template: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            DB::connection('mysql')
                ->table('message_templates')
                ->where('id', $id)
                ->delete();

            return redirect()->route('admin.templates.trash')
                ->with('success', 'Message template permanently deleted!');
        } catch (\Exception $e) {
            \Log::error('Template permanent deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to permanently delete template: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $template = DB::connection('mysql')
                ->table('message_templates')
                ->where('id', $id)
                ->first();

            if (!$template) {
                return back()->with('error', 'Template not found.');
            }

            DB::connection('mysql')
                ->table('message_templates')
                ->where('id', $id)
                ->update([
                    'is_active' => !$template->is_active,
                    'updated_at' => now(),
                ]);

            return back()->with('success', 'Template status updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Template status toggle failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update template status: ' . $e->getMessage());
        }
    }

    public function preview(Request $request, $id)
    {
        $template = DB::connection('mysql')
            ->table('message_templates')
            ->where('id', $id)
            ->first();

        if (!$template) {
            return response()->json(['error' => 'Template not found'], 404);
        }

        // Replace variables with sample data
        $sampleData = [
            'citizen_name' => 'Juan Dela Cruz',
            'booking_id' => 'BK-2026-001',
            'facility_name' => 'City Sports Complex',
            'booking_date' => '2026-02-15',
            'booking_time' => '2:00 PM - 4:00 PM',
            'amount' => '500.00',
            'transaction_id' => 'TXN-2026-001',
            'payment_date' => '2026-01-21',
        ];

        $body = $template->body;
        $subject = $template->subject;

        foreach ($sampleData as $key => $value) {
            $body = str_replace('{{' . $key . '}}', $value, $body);
            if ($subject) {
                $subject = str_replace('{{' . $key . '}}', $value, $subject);
            }
        }

        return response()->json([
            'subject' => $subject,
            'body' => $body,
            'type' => $template->type,
        ]);
    }
}
