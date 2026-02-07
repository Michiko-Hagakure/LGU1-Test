@extends('emails.layout')

@section('title', 'Refund Completed')

@section('content')
    <h2>Refund Completed</h2>
    
    <p>Dear {{ $refund->applicant_name }},</p>
    
    <p>We are pleased to inform you that your refund has been <strong>successfully processed</strong>.</p>
    
    <div class="info-box" style="background: #ecfdf5; border-left: 4px solid #10b981;">
        <h3 style="color: #065f46;">Refund Details</h3>
        <p><strong>Booking Reference:</strong> {{ $refund->booking_reference }}</p>
        <p><strong>Facility:</strong> {{ $refund->facility_name ?? 'N/A' }}</p>
        <p><strong>Refund Amount:</strong> <span style="font-size: 18px; font-weight: bold; color: #059669;">₱{{ number_format($refund->refund_amount, 2) }}</span></p>
        <p><strong>Refund Method:</strong> {{ ucfirst(str_replace('_', ' ', $refund->refund_method)) }}</p>
        @if($refund->or_number)
        <p><strong>OR Number:</strong> {{ $refund->or_number }}</p>
        @endif
    </div>

    @if($refund->refund_method === 'cash')
    <div class="info-box info-box-warning">
        <h3>Cash Refund</h3>
        <p>Please visit the City Treasurer's Office to claim your cash refund. Bring your receipt or Official Receipt number for verification.</p>
    </div>
    @else
    <p>The refund has been sent to your <strong>{{ ucfirst(str_replace('_', ' ', $refund->refund_method)) }}</strong> account. Please allow up to 24 hours for the amount to reflect in your account.</p>
    @endif
    
    <div class="divider"></div>
    
    <p style="font-size: 14px; color: #6b7280; margin-top: 20px; text-align: center;">
        If you have any questions about your refund, please contact the City Treasurer's Office.
    </p>
@endsection
