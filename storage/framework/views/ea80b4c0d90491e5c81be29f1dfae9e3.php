

<?php $__env->startSection('title', 'Payment Slip Details'); ?>
<?php $__env->startSection('page-title', 'Payment Slip'); ?>
<?php $__env->startSection('page-subtitle', $paymentSlip->slip_number); ?>

<?php $__env->startSection('page-content'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Hide print-only on screen */
#payment-slip-print {
    display: none;
}

@media print {
    /* Hide everything with no-print class */
    .no-print {
        display: none !important;
    }
    
    /* Show only the print section */
    #payment-slip-print {
        display: block !important;
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
    }
    
    /* Clean page */
    body {
        background: white !important;
    }
    
    @page {
        margin: 2cm;
    }
}
</style>
<?php $__env->stopPush(); ?>

<!-- Print-Only Header -->
<div id="payment-slip-print" style="display: none;">
    <h1 style="font-size: 48px; font-weight: bold; color: #1f2937; text-align: center; margin: 0;">
        Slip # <?php echo e($paymentSlip->slip_number); ?>

    </h1>
</div>

<div class="space-y-6 no-print">
    <!-- Back Button -->
    <div>
        <a href="<?php echo e(route('citizen.payment-slips')); ?>" 
           class="inline-flex items-center text-lgu-button hover:text-lgu-highlight font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
            </svg>
            Back to Payment Slips
        </a>
    </div>

    <!-- Status Alert -->
    <div class="no-print">
    <?php
        // Use match for fixed Tailwind classes
        $statusInfo = match($paymentSlip->status) {
            'unpaid' => [
                'bg' => 'bg-orange-50', 'border' => 'border-orange-500', 'text' => 'text-orange-800', 'icon' => 'text-orange-500',
                'label' => 'Awaiting Payment',
                'message' => 'Please settle this payment before the due date to confirm your booking.'
            ],
            'paid' => [
                'bg' => 'bg-green-50', 'border' => 'border-green-500', 'text' => 'text-green-800', 'icon' => 'text-green-500',
                'label' => 'Payment Confirmed',
                'message' => 'Your payment has been confirmed. Thank you for settling this promptly!'
            ],
            'expired' => [
                'bg' => 'bg-red-50', 'border' => 'border-red-500', 'text' => 'text-red-800', 'icon' => 'text-red-500',
                'label' => 'Payment Expired',
                'message' => 'This payment slip has expired. Please contact support if you need assistance.'
            ],
            default => [
                'bg' => 'bg-gray-50', 'border' => 'border-gray-500', 'text' => 'text-gray-800', 'icon' => 'text-gray-500',
                'label' => ucfirst($paymentSlip->status),
                'message' => ''
            ]
        };
        $dueDate = \Carbon\Carbon::parse($paymentSlip->payment_deadline);
        $isOverdue = $paymentSlip->status === 'unpaid' && $dueDate->isPast();
        $daysUntilDue = $isOverdue ? abs($dueDate->diffInDays(now(), false)) : $dueDate->diffInDays(now(), false);
    ?>

    <div class="<?php echo e($statusInfo['bg']); ?> border-l-8 <?php echo e($statusInfo['border']); ?> p-6 rounded-xl shadow-lg <?php echo e($isOverdue ? 'animate-pulse' : ''); ?>">
        <div class="flex items-start">
            <div class="flex-shrink-0 mt-1">
                <?php if($paymentSlip->status === 'paid'): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="<?php echo e($statusInfo['icon']); ?>">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>
                    </svg>
                <?php elseif($paymentSlip->status === 'expired'): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="<?php echo e($statusInfo['icon']); ?>">
                        <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                    </svg>
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="<?php echo e($statusInfo['icon']); ?>">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                <?php endif; ?>
            </div>
            <div class="ml-4 flex-1">
                <div class="flex items-center gap-3 flex-wrap">
                    <h3 class="text-2xl font-bold <?php echo e($statusInfo['text']); ?>"><?php echo e($statusInfo['label']); ?></h3>
                    <?php if($isOverdue): ?>
                        <span class="px-4 py-1.5 bg-red-600 text-white text-sm font-bold rounded-full shadow-lg inline-flex items-center gap-1">
                            <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                            <?php echo e($daysUntilDue); ?> <?php echo e($daysUntilDue == 1 ? 'DAY' : 'DAYS'); ?> OVERDUE
                        </span>
                    <?php elseif($paymentSlip->status === 'unpaid' && $daysUntilDue <= 3): ?>
                        <span class="px-4 py-1.5 bg-yellow-500 text-white text-sm font-bold rounded-full shadow-lg inline-flex items-center gap-1">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            <?php echo e($daysUntilDue); ?> <?php echo e($daysUntilDue == 1 ? 'DAY' : 'DAYS'); ?> LEFT
                        </span>
                    <?php endif; ?>
                </div>
                <p class="text-base <?php echo e($statusInfo['text']); ?> mt-2 leading-relaxed">
                    <?php echo e($statusInfo['message']); ?>

                </p>
            </div>
        </div>
    </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 no-print">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Slip Information -->
            <div class="bg-white shadow-lg rounded-xl p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 pb-6 border-b-2 border-gray-200 gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-1">Payment Slip Details</h2>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600">Slip #</span>
                            <span class="font-mono bg-gray-100 px-3 py-1 rounded-lg text-sm font-bold text-gray-900"><?php echo e($paymentSlip->slip_number); ?></span>
                        </div>
                    </div>
                    <button onclick="window.print()" 
                            class="px-5 py-2.5 bg-gray-100 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-semibold shadow-sm hover:shadow-md cursor-pointer flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
                        </svg>
                        Print Slip
                    </button>
                </div>

                <!-- Facility & Booking Info -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600 mb-1">Facility</h3>
                        <p class="text-lg font-bold text-gray-900"><?php echo e($paymentSlip->facility_name); ?></p>
                        <p class="text-sm text-gray-600"><?php echo e($paymentSlip->facility_address); ?></p>
                        <?php if($paymentSlip->city_code): ?>
                            <span class="inline-block mt-1 px-2 py-1 bg-lgu-bg text-lgu-headline text-xs font-semibold rounded">
                                <?php echo e($paymentSlip->city_code); ?>

                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Booking Date</h3>
                            <p class="text-base font-semibold text-gray-900"><?php echo e(\Carbon\Carbon::parse($paymentSlip->start_time)->format('F d, Y')); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Time</h3>
                            <p class="text-base font-semibold text-gray-900"><?php echo e(\Carbon\Carbon::parse($paymentSlip->start_time)->format('g:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($paymentSlip->end_time)->format('g:i A')); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Purpose</h3>
                            <p class="text-base font-semibold text-gray-900"><?php echo e($paymentSlip->purpose); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Attendees</h3>
                            <p class="text-base font-semibold text-gray-900"><?php echo e($paymentSlip->expected_attendees); ?> people</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Breakdown -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Payment Breakdown</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Base Rate (3 hours):</span>
                        <span class="font-semibold">₱<?php echo e(number_format($paymentSlip->base_rate, 2)); ?></span>
                    </div>
                    <?php if($paymentSlip->extension_rate > 0): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Extension Charges:</span>
                            <span class="font-semibold">₱<?php echo e(number_format($paymentSlip->extension_rate, 2)); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if($paymentSlip->equipment_total > 0): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Equipment:</span>
                            <span class="font-semibold">₱<?php echo e(number_format($paymentSlip->equipment_total, 2)); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold">₱<?php echo e(number_format($paymentSlip->subtotal, 2)); ?></span>
                        </div>
                    </div>
                    <?php if($paymentSlip->resident_discount_amount > 0): ?>
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Resident Discount:</span>
                            <span>- ₱<?php echo e(number_format($paymentSlip->resident_discount_amount, 2)); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if($paymentSlip->special_discount_amount > 0): ?>
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Special Discount:</span>
                            <span>- ₱<?php echo e(number_format($paymentSlip->special_discount_amount, 2)); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if($paymentSlip->total_discount > 0): ?>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-sm text-green-600 font-bold">
                                <span>Total Discount:</span>
                                <span>- ₱<?php echo e(number_format($paymentSlip->total_discount, 2)); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="border-t-2 border-gray-300 pt-3">
                        <div class="flex justify-between text-lg font-bold text-lgu-headline">
                            <span>Total Amount Due:</span>
                            <span>₱<?php echo e(number_format($paymentSlip->amount_due, 2)); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selected Equipment -->
            <?php if($equipment->isNotEmpty()): ?>
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Selected Equipment</h3>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $equipment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900"><?php echo e($item->equipment_name); ?></p>
                                    <p class="text-sm text-gray-600">Quantity: <?php echo e($item->quantity); ?> × ₱<?php echo e(number_format($item->price_per_unit, 2)); ?></p>
                                </div>
                                <p class="text-lg font-bold text-lgu-headline">₱<?php echo e(number_format($item->subtotal, 2)); ?></p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Payment Instructions (for unpaid slips) -->
            <?php if($paymentSlip->status === 'unpaid'): ?>
                <div class="bg-blue-50 border-2 border-blue-300 rounded-xl p-8 shadow-lg no-print">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                            <i data-lucide="credit-card" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-blue-900">How to Pay</h3>
                            <p class="text-sm text-blue-700">Choose your payment method below</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Cash at CTO -->
                        <div class="bg-white border-2 border-gray-200 rounded-lg p-5">
                            <h4 class="font-bold text-lg text-gray-900 mb-3 flex items-center gap-2">
                                <i data-lucide="building-2" class="w-5 h-5 text-lgu-button"></i>
                                Pay at City Treasurer's Office (Cash)
                            </h4>
                            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700 ml-2">
                                <li>Visit the City Treasurer's Office</li>
                                <li>Show this payment slip (print or mobile)</li>
                                <li>Pay <strong class="text-lgu-headline">₱<?php echo e(number_format($paymentSlip->amount_due, 2)); ?></strong> to the cashier</li>
                                <li>Treasurer will mark payment as received in the system</li>
                                <li>You'll receive an Official Receipt automatically</li>
                            </ol>
                        </div>

                        <!-- Online Payment -->
                        <div class="bg-white border-2 border-gray-200 rounded-lg p-5">
                            <h4 class="font-bold text-lg text-gray-900 mb-3 flex items-center gap-2">
                                <i data-lucide="smartphone" class="w-5 h-5 text-lgu-button"></i>
                                Pay Online (Cashless)
                            </h4>
                            
                            <?php if(config('payment.paymongo_enabled')): ?>
                            <!-- Paymongo Automated Payment (Primary) -->
                            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="zap" class="w-4 h-4 text-blue-600"></i>
                                    <span class="text-sm font-bold text-blue-800">Instant Payment</span>
                                </div>
                                <p class="text-sm text-gray-700 mb-3">
                                    Pay instantly via GCash, Maya, GrabPay, or Card. No reference number needed - payment is automatically confirmed!
                                </p>
                                <a href="<?php echo e(route('citizen.payment-slips.paymongo', $paymentSlip->id)); ?>"
                                   class="w-full bg-lgu-button text-lgu-button-text font-bold py-4 rounded-lg hover:bg-lgu-highlight transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                                    Pay Instantly Now
                                </a>
                            </div>
                            
                            <div class="relative my-4">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="bg-white px-2 text-gray-500">or pay manually</span>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Manual Payment (Fallback) -->
                            <p class="text-sm text-gray-700 mb-4">
                                <?php if(config('payment.paymongo_enabled')): ?>
                                Prefer to pay manually? Send payment via GCash/Maya/Bank, then enter your reference number.
                                <?php else: ?>
                                Pay using GCash, Maya, or Bank Transfer. Simply send payment and enter your reference number!
                                <?php endif; ?>
                            </p>
                            <a href="<?php echo e(route('citizen.payment-slips.cashless', $paymentSlip->id)); ?>"
                               class="w-full <?php echo e(config('payment.paymongo_enabled') ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-lgu-button text-lgu-button-text hover:bg-lgu-highlight'); ?> font-bold py-4 rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                <i data-lucide="edit-3" class="w-5 h-5"></i>
                                <?php echo e(config('payment.paymongo_enabled') ? 'Pay Manually (Enter Reference)' : 'Pay Online Now'); ?>

                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Download Official Receipt (for paid slips) -->
            <?php if($paymentSlip->status === 'paid' && $paymentSlip->transaction_reference): ?>
                <div class="bg-green-50 border-2 border-green-300 rounded-xl p-8 shadow-lg">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                            <i data-lucide="file-text" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-green-900">Payment Received</h3>
                            <p class="text-sm text-green-700">Your official receipt is ready</p>
                        </div>
                    </div>
                    
                    <div class="bg-white border-2 border-green-200 rounded-lg p-6 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Official Receipt Number</p>
                                <p class="text-2xl font-bold text-green-600"><?php echo e($paymentSlip->transaction_reference); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600 mb-1">Date Issued</p>
                                <p class="text-base font-semibold text-gray-900"><?php echo e(\Carbon\Carbon::parse($paymentSlip->paid_at)->format('M d, Y')); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <a href="<?php echo e(route('citizen.payments.receipt', $paymentSlip->id)); ?>" 
                       class="w-full bg-green-600 text-white font-bold py-4 rounded-lg hover:bg-green-700 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i data-lucide="download" class="w-5 h-5"></i>
                        Download Official Receipt (PDF)
                    </a>
                </div>
            <?php endif; ?>

            
            
        </div>

        <!-- Sidebar: Important Dates & Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6 sticky top-8 space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Important Dates</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lgu-button mr-2 mt-0.5">
                                <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Payment Due</p>
                                <p class="text-gray-600"><?php echo e($dueDate->format('F d, Y')); ?></p>
                                <?php if($paymentSlip->status === 'unpaid'): ?>
                                    <?php if($isOverdue): ?>
                                        <p class="text-red-600 font-bold text-xs"><?php echo e(abs($dueDate->diffInDays(Carbon\Carbon::now()))); ?> days overdue</p>
                                    <?php else: ?>
                                        <p class="text-blue-600 text-xs"><?php echo e($dueDate->diffInDays(Carbon\Carbon::now())); ?> days remaining</p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if($paymentSlip->paid_at): ?>
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600 mr-2 mt-0.5">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Paid On</p>
                                    <p class="text-gray-600"><?php echo e(\Carbon\Carbon::parse($paymentSlip->paid_at)->format('F d, Y')); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lgu-button mr-2 mt-0.5">
                                <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Event Date</p>
                                <p class="text-gray-600"><?php echo e(\Carbon\Carbon::parse($paymentSlip->start_time)->format('F d, Y')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                
                

                <div class="border-t border-gray-200 pt-4 no-print">
                    <h4 class="text-sm font-bold text-gray-900 mb-3">Quick Actions</h4>
                    <div class="space-y-3">
                        <a href="<?php echo e(route('citizen.reservations.show', $paymentSlip->booking_id)); ?>" 
                           class="block w-full px-4 py-3 bg-lgu-button text-lgu-button-text text-center font-semibold rounded-lg hover:bg-lgu-highlight transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                            </svg>
                            View Booking
                        </a>
                        <a href="<?php echo e(route('citizen.payment-slips')); ?>" 
                           class="block w-full px-4 py-3 bg-gray-200 text-gray-700 text-center font-semibold rounded-lg hover:bg-gray-300 transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
                            </svg>
                            Back to List
                        </a>
                        <button onclick="window.print()" 
                                class="block w-full px-4 py-3 bg-gray-100 border-2 border-gray-300 text-gray-700 text-center font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200 shadow-sm hover:shadow-md cursor-pointer flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>
                            </svg>
                            Print Slip
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Show coming soon alert with SweetAlert2
function showComingSoonAlert() {
    Swal.fire({
        icon: 'info',
        title: 'Coming Soon!',
        text: 'Online payment integration coming soon!',
        confirmButtonColor: '#0f5b3a',
        confirmButtonText: 'Okay'
    });
}

// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.citizen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/citizen/payments/show.blade.php ENDPATH**/ ?>