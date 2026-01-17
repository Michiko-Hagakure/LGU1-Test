<!DOCTYPE html>
<html>
<head>
    <style>
        .container { font-family: sans-serif; max-width: 600px; margin: auto; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
        .header { background-color: #064e3b; color: white; padding: 40px 20px; text-align: left; }
        .content { padding: 30px 20px; color: #374151; }
        .receipt-card { background-color: #f0fdf4; border-left: 4px solid #10b981; padding: 20px; border-radius: 4px; }
        .details { margin-top: 10px; line-height: 1.6; }
        .footer { padding: 20px; font-size: 12px; color: #6b7280; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0;">Local Government Unit 1</h1>
            <p style="margin:0; opacity: 0.8;">Facilities Reservation System</p>
        </div>
        <div class="content">
            <h2 style="color: #064e3b;">Payment Confirmed! Booking Reserved</h2>
            <p>Dear {{ $citizen->full_name }},</p>
            <p>Excellent news! Your payment has been verified by our treasurer and your booking is now reserved.</p>
            
            <div class="receipt-card">
                <h3 style="margin-top:0; color: #065f46;">Official Receipt Issued</h3>
                <div class="details">
                    <strong>OR Number:</strong> {{ $transaction->or_number ?? 'OR-2026-0001' }}<br>
                    <strong>Payment Slip:</strong> {{ $transaction->slip_number }}<br>
                    <strong>Amount Paid:</strong> ₱{{ number_format($transaction->amount_due, 2) }}<br>
                    <strong>Payment Date:</strong> {{ \Carbon\Carbon::parse($transaction->paid_at)->format('F d, Y - h:i A') }}
                </div>
            </div>
        </div>
        <div class="footer">
            &copy; 2026 LGU Facilities Reservation System. All rights reserved.
        </div>
    </div>
</body>
</html>