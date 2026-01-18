<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function auditIndex()
    {
        $logs = AuditLog::orderBy('created_at', 'desc')->get();
        return view('admin.analytics.audit', compact('logs'));
    }
    public function exportPDF()
    {
        $logs = \App\Models\AuditLog::latest()->get();
        $pdf = Pdf::loadView('admin.analytics.exports.audit_pdf', compact('logs'))
              ->setPaper('a4', 'landscape');
        return $pdf->download('Audit_Trail_Report.pdf');
    }
}