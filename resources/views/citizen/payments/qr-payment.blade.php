@extends('layouts.citizen')

@section('title', 'Scan to Pay')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <!-- Back Button -->
    <div class="mb-gr-md">
        <a href="{{ route('citizen.payment-slips.show', $paymentSlip->id) }}" class="inline-flex items-center text-small font-medium text-gray-600 hover:text-gray-900">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-gr-xs"></i>
            Back to Payment Slip
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg text-center">
        <!-- Header -->
        <div class="mb-gr-lg">
            <div class="w-16 h-16 bg-lgu-green rounded-full mx-auto mb-gr-md flex items-center justify-center">
                <i data-lucide="qr-code" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-h2 font-bold text-lgu-headline mb-gr-xs">Scan QR to Pay</h1>
            <p class="text-body text-gray-600">Use your GCash, Maya, or any bank app to scan this QR code</p>
        </div>

        <!-- Payment Amount -->
        <div class="bg-gray-50 rounded-lg p-gr-md mb-gr-lg">
            <p class="text-small text-gray-500 mb-gr-xs">Amount to Pay</p>
            <p class="text-h1 font-bold text-lgu-headline">₱{{ number_format($amount, 2) }}</p>
            <p class="text-small text-gray-500 mt-gr-xs">{{ $paymentSlip->slip_number }}</p>
        </div>

        <!-- QR Code -->
        <div class="bg-white border-2 border-gray-200 rounded-lg p-gr-md mb-gr-lg inline-block">
            <img src="{{ $qrImage }}" alt="Payment QR Code" class="w-64 h-64 mx-auto">
        </div>

        <!-- Timer -->
        <div class="mb-gr-lg">
            <p class="text-small text-gray-500 mb-gr-xs">QR Code expires in</p>
            <p class="text-h3 font-bold text-orange-600" id="countdown">30:00</p>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-gr-md mb-gr-lg text-left">
            <h3 class="font-semibold text-blue-800 mb-gr-sm flex items-center">
                <i data-lucide="info" class="w-4 h-4 mr-gr-xs"></i>
                How to Pay
            </h3>
            <ol class="text-sm text-blue-700 space-y-2">
                <li class="flex items-start">
                    <span class="font-bold mr-2">1.</span>
                    Open your GCash, Maya, or bank app
                </li>
                <li class="flex items-start">
                    <span class="font-bold mr-2">2.</span>
                    Select "Scan QR" or "Pay via QR"
                </li>
                <li class="flex items-start">
                    <span class="font-bold mr-2">3.</span>
                    Scan the QR code above
                </li>
                <li class="flex items-start">
                    <span class="font-bold mr-2">4.</span>
                    Confirm the payment in your app
                </li>
                <li class="flex items-start">
                    <span class="font-bold mr-2">5.</span>
                    Wait for confirmation on this page
                </li>
            </ol>
        </div>

        <!-- Status -->
        <div id="payment-status" class="mb-gr-lg">
            <div class="flex items-center justify-center text-gray-500">
                <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Waiting for payment...</span>
            </div>
        </div>

        <!-- Cancel Button -->
        <a href="{{ route('citizen.payment-slips.show', $paymentSlip->id) }}" class="btn-secondary inline-flex items-center">
            <i data-lucide="x" class="w-4 h-4 mr-gr-xs"></i>
            Cancel
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Countdown timer
    let timeLeft = {{ $expiresIn }} * 60; // Convert minutes to seconds
    const countdownEl = document.getElementById('countdown');
    
    const timer = setInterval(function() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 0) {
            clearInterval(timer);
            countdownEl.textContent = 'Expired';
            countdownEl.classList.add('text-red-600');
            document.getElementById('payment-status').innerHTML = `
                <div class="flex items-center justify-center text-red-600">
                    <i data-lucide="x-circle" class="w-5 h-5 mr-2"></i>
                    <span>QR code expired. Please generate a new one.</span>
                </div>
            `;
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
        
        timeLeft--;
    }, 1000);

    // Poll for payment status
    const paymentIntentId = '{{ $paymentIntentId }}';
    const paymentSlipId = {{ $paymentSlip->id }};
    
    const checkPaymentStatus = async () => {
        try {
            const response = await fetch(`/citizen/payments/${paymentSlipId}/check-qr-status?intent=${paymentIntentId}`);
            const data = await response.json();
            
            if (data.status === 'succeeded' || data.status === 'paid') {
                clearInterval(timer);
                clearInterval(statusChecker);
                
                document.getElementById('payment-status').innerHTML = `
                    <div class="flex items-center justify-center text-green-600">
                        <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                        <span>Payment successful! Redirecting...</span>
                    </div>
                `;
                if (typeof lucide !== 'undefined') lucide.createIcons();
                
                setTimeout(() => {
                    window.location.href = '/citizen/payments/' + paymentSlipId + '?payment=success';
                }, 2000);
            }
        } catch (error) {
            console.error('Error checking payment status:', error);
        }
    };
    
    // Check status every 5 seconds
    const statusChecker = setInterval(checkPaymentStatus, 5000);
});
</script>
@endsection
