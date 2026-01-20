<!DOCTYPE html>
<html>
<head>
    <title>System Audit Trail Report</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #065f46; padding-bottom: 10px; }
        .header h1 { color: #065f46; margin: 0; text-transform: uppercase; font-size: 18px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #065f46; color: white; padding: 10px; text-align: left; text-transform: uppercase; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 9px; color: #999; }
        .badge { padding: 3px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; background: #f3f4f6; }
    </style>
</head>
<body>
    <div class="header">
        <h1>System Audit Trail Report</h1>
        <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Administrator</th>
                <th>Action Type</th>
                <th>System Module</th>
                <th>IP Address</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td><strong>{{ $log->user_name }}</strong></td>
                <td><span class="badge">{{ $log->action }}</span></td>
                <td>{{ $log->module }}</td>
                <td>{{ $log->ip_address }}</td>
                <td>{{ $log->created_at->format('M d, Y | h:i A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        LGU Public Facilities Reservation System - Page 1
    </div>
</body>
</html>