

<?php $__env->startSection('title', 'Booking Request Received'); ?>

<?php $__env->startSection('content'); ?>
    <h2>Booking Request Received!</h2>
    
    <p>Dear <?php echo new \Illuminate\Support\EncodedHtmlString($booking->applicant_name ?? $booking->user_name); ?>,</p>
    
    <p>Thank you for submitting your facility booking request. We have received your application and it is now being reviewed by our staff.</p>
    
    <div class="info-box info-box-success">
        <h3>Booking Details</h3>
        <p><strong>Booking Reference:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString($booking->booking_reference); ?></p>
        <p><strong>Facility:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString($booking->facility_name); ?></p>
        <p><strong>Date & Time:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString(\Carbon\Carbon::parse($booking->start_time)->format('F d, Y - h:i A')); ?> to <?php echo new \Illuminate\Support\EncodedHtmlString(\Carbon\Carbon::parse($booking->end_time)->format('h:i A')); ?></p>
        <p><strong>Expected Attendees:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString($booking->expected_attendees); ?> people</p>
        <p><strong>Total Amount:</strong> ₱<?php echo new \Illuminate\Support\EncodedHtmlString(number_format($booking->total_amount, 2)); ?></p>
    </div>
    
    <h3 style="margin-top: 30px; margin-bottom: 15px; font-size: 18px; color: #0f3d3e;">What Happens Next?</h3>
    
    <p><strong>1. Staff Verification (24-48 hours)</strong><br>
    Our staff will review your booking request and verify all submitted documents.</p>
    
    <p><strong>2. Payment Instructions</strong><br>
    Once approved, you'll receive payment instructions via email.</p>
    
    <p><strong>3. Final Confirmation</strong><br>
    After payment verification, your booking will be confirmed.</p>
    
    <div class="divider"></div>
    
    <p style="text-align: center;">
        <a href="<?php echo new \Illuminate\Support\EncodedHtmlString(url('/citizen/reservations/' . $booking->id)); ?>" class="button">
            View Booking Status
        </a>
    </p>
    
    <p style="font-size: 14px; color: #6b7280; margin-top: 20px;">
        <strong>Note:</strong> You can track your booking status anytime by logging into your account and visiting "My Reservations".
    </p>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('emails.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/emails/booking-submitted.blade.php ENDPATH**/ ?>