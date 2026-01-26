

<?php $__env->startSection('title', 'Booking Confirmed!'); ?>

<?php $__env->startSection('content'); ?>
    <h2>Your Booking is Confirmed!</h2>
    
    <p>Dear <?php echo new \Illuminate\Support\EncodedHtmlString($booking->applicant_name ?? $booking->user_name); ?>,</p>
    
    <p>Congratulations! Your facility booking has been confirmed and is now locked in our system. We look forward to hosting your event!</p>
    
    <div class="info-box info-box-success">
        <h3>Confirmed Booking Details</h3>
        <p><strong>Booking Reference:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString($booking->booking_reference); ?></p>
        <p><strong>Facility:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString($booking->facility_name); ?></p>
        <p><strong>Event Date:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString(\Carbon\Carbon::parse($booking->start_time)->format('l, F d, Y')); ?></p>
        <p><strong>Time:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString(\Carbon\Carbon::parse($booking->start_time)->format('h:i A')); ?> - <?php echo new \Illuminate\Support\EncodedHtmlString(\Carbon\Carbon::parse($booking->end_time)->format('h:i A')); ?></p>
        <p><strong>Duration:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString(\Carbon\Carbon::parse($booking->start_time)->diffInHours(\Carbon\Carbon::parse($booking->end_time))); ?> hours</p>
        <p><strong>Expected Attendees:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString($booking->expected_attendees); ?> people</p>
    </div>
    
    <h3 style="margin-top: 30px; margin-bottom: 15px; font-size: 18px; color: #0f3d3e;">Important Reminders</h3>
    
    <p><strong>What to Bring:</strong></p>
    <ul style="margin-left: 20px; margin-bottom: 15px;">
        <li>Valid government-issued ID</li>
        <li>Official Receipt copy (printed or digital)</li>
        <li>Booking reference number</li>
    </ul>
    
    <p><strong>Arrival Time:</strong><br>
    Please arrive at least 30 minutes before your scheduled start time for setup and orientation.</p>
    
    <p><strong>Facility Rules:</strong><br>
    Please observe all facility rules and regulations. Our staff will brief you upon arrival.</p>
    
    <div class="info-box info-box-warning">
        <h3>Cancellation Policy</h3>
        <p>If you need to cancel or reschedule, please contact us at least 48 hours before your event date.</p>
        <p>Cancellations made less than 48 hours before the event may not be eligible for refund.</p>
    </div>
    
    <div class="divider"></div>
    
    <p style="text-align: center;">
        <a href="<?php echo new \Illuminate\Support\EncodedHtmlString(url('/citizen/reservations/' . $booking->id)); ?>" class="button">
            View Full Booking Details
        </a>
    </p>
    
    <p style="font-size: 14px; color: #6b7280; margin-top: 20px; text-align: center;">
        For questions or concerns, please contact our Facilities Management Office.
    </p>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('emails.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/emails/booking-confirmed.blade.php ENDPATH**/ ?>