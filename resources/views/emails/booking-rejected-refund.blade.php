@extends('emails.layout')

@section('title', 'Booking Rejected - Refund Pending')

@section('content')
    <h2>Booking Rejected - Refund Pending</h2>
    
    <p>Dear {{ $booking->applicant_name ?? $booking->user_name }},</p>
    
    <p>We regret to inform you that your facility booking has been <strong>rejected</strong> by the administrator. Since you have already paid for this booking, a <strong>full refund</strong> has been initiated.</p>
    
    <div class="info-box info-box-error">
        <h3>Rejected Booking Details</h3>
        <p><strong>Booking Reference:</strong> {{ $booking->booking_reference }}</p>
        <p><strong>Facility:</strong> {{ $booking->facility_name }}</p>
        <p><strong>Event Date:</strong> {{ \Carbon\Carbon::parse($booking->start_time)->format('F d, Y - h:i A') }}</p>
        <p><strong>Status:</strong> Rejected</p>
    </div>
    
    @if($reason)
    <div class="info-box info-box-warning">
        <h3>Reason for Rejection</h3>
        <p>{{ $reason }}</p>
    </div>
    @endif

    <div class="info-box" style="background: #ecfdf5; border-left: 4px solid #10b981;">
        <h3 style="color: #065f46;">Refund Information</h3>
        <p><strong>Original Amount Paid:</strong> ₱{{ number_format($refund->original_amount, 2) }}</p>
        <p><strong>Refund Percentage:</strong> {{ number_format($refund->refund_percentage, 0) }}%</p>
        <p><strong>Refund Amount:</strong> <span style="font-size: 18px; font-weight: bold; color: #059669;">₱{{ number_format($refund->refund_amount, 2) }}</span></p>
        <p><strong>Processing Time:</strong> 1-3 business days after you select your refund method</p>
    </div>

    <h3 style="margin-top: 30px; margin-bottom: 15px; font-size: 18px; color: #0f3d3e;">Choose Your Refund Method</h3>
    
    <p>Please log in to the Citizen Portal and select how you would like to receive your refund:</p>
    
    <ul style="margin-left: 20px; margin-bottom: 15px;">
        <li><strong>Cash</strong> - Visit the City Treasurer's Office with your receipt/OR number</li>
        <li><strong>GCash</strong> - Provide your GCash number for electronic transfer</li>
        <li><strong>Maya</strong> - Provide your Maya account number</li>
        <li><strong>Bank Transfer</strong> - Provide your bank name and account number</li>
    </ul>
    
    <div class="divider"></div>
    
    <p style="text-align: center;">
        <a href="{{ url('/citizen/facilities') }}" class="button">
            Go to Citizen Portal
        </a>
    </p>
    
    <p style="font-size: 14px; color: #6b7280; margin-top: 20px; text-align: center;">
        If you have any questions about your refund, please contact the City Treasurer's Office.
    </p>
@endsection
