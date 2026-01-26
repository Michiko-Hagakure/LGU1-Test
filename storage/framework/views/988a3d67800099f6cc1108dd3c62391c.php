

<?php $__env->startSection('title', 'Payment Received - Under Review'); ?>

<?php $__env->startSection('content'); ?>
    <h2>Payment Received - Under Review</h2>
    
    <p>Dear <?php echo new \Illuminate\Support\EncodedHtmlString($booking->applicant_name ?? $booking->user_name); ?>,</p>
    
    <p>Thank you! We have received your payment submission and it is now being reviewed by our treasurer.</p>
    
    <div class="info-box info-box-success">
        <h3>Payment Details</h3>
        <p><strong>Payment Slip:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString($paymentSlip->slip_number); ?></p>
        <p><strong>Facility:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString($booking->facility_name); ?></p>
        <p><strong>Amount Paid:</strong> ₱<?php echo new \Illuminate\Support\EncodedHtmlString(number_format($paymentSlip->amount_due, 2)); ?></p>
        <p><strong>Payment Method:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString(strtoupper($paymentSlip->payment_channel ?? $paymentSlip->payment_method ?? 'N/A')); ?></p>
        <?php if($paymentSlip->transaction_reference): ?>
        <p><strong>Reference Number:</strong> <?php echo new \Illuminate\Support\EncodedHtmlString($paymentSlip->transaction_reference); ?></p>
        <?php endif; ?>
    </div>
    
    <h3 style="margin-top: 30px; margin-bottom: 15px; font-size: 18px; color: #0f3d3e;">What Happens Next?</h3>
    
    <p><strong>1. Treasurer Verification (24-48 hours)</strong><br>
    Our treasurer will verify your payment with our records.</p>
    
    <p><strong>2. Official Receipt Issuance</strong><br>
    Once verified, an Official Receipt will be generated and sent to you.</p>
    
    <p><strong>3. Final Booking Confirmation</strong><br>
    Your booking will be confirmed and locked in our system.</p>
    
    <div class="divider"></div>
    
    <p style="text-align: center;">
        <a href="<?php echo new \Illuminate\Support\EncodedHtmlString(url('/citizen/reservations/' . $booking->id)); ?>" class="button">
            View Booking Status
        </a>
    </p>
    
    <p style="font-size: 14px; color: #6b7280; margin-top: 20px;">
        <strong>Note:</strong> You will receive an email notification once your payment has been verified.
    </p>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('emails.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/emails/payment-submitted.blade.php ENDPATH**/ ?>