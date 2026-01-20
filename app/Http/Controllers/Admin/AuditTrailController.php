<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['causer'])
            ->latest();

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('log_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(15);

        $events = ActivityLog::select('event')
            ->distinct()
            ->whereNotNull('event')
            ->pluck('event');

        $logNames = ActivityLog::select('log_name')
            ->distinct()
            ->whereNotNull('log_name')
            ->pluck('log_name');

        return view('admin.audit-trail.index', compact('logs', 'events', 'logNames'));
    }

    public function show($id)
    {
        $log = ActivityLog::with(['causer', 'subject'])->findOrFail($id);
        return view('admin.audit-trail.show', compact('log'));
    }

    public function exportCsv(Request $request)
    {
        $query = ActivityLog::with(['causer'])->latest();

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('log_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->get();

        $filename = 'audit-trail-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['ID', 'Date/Time', 'User', 'Action', 'Module', 'Description', 'IP Address']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->causer?->name ?? 'System',
                    ucfirst($log->event ?? 'N/A'),
                    ucfirst($log->log_name ?? 'N/A'),
                    $log->description,
                    $log->ip_address ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $query = ActivityLog::with(['causer'])->latest();

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('log_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->get();

        $pdf = Pdf::loadView('admin.audit-trail.pdf', compact('logs'));

        return $pdf->download('audit-trail-' . now()->format('Y-m-d-His') . '.pdf');
    }
}
