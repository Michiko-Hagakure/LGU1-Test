

<?php $__env->startSection('page-title', 'Booking Review'); ?>
<?php $__env->startSection('page-subtitle', 'Review and verify booking details'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-gr-xl">
    <!-- Page Header with Status -->
    <div class="bg-lgu-headline rounded-2xl p-gr-xl text-white shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-gr-md">
            <div class="flex items-center gap-gr-md">
                <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <i data-lucide="clipboard-check" class="w-8 h-8 text-white"></i>
                </div>
                <div>
                    <h1 class="text-h2 font-bold mb-gr-xs">Booking Review</h1>
                    <p class="text-body text-gray-200">Booking ID: #<?php echo e($booking->id); ?></p>
                </div>
            </div>
            
            <!-- Status Badge -->
            <div class="flex items-center gap-gr-sm">
                <?php
                    $statusConfig = [
                        'pending' => ['bg' => 'bg-yellow-500', 'text' => 'Pending Verification', 'icon' => 'clock'],
                        'staff_verified' => ['bg' => 'bg-blue-500', 'text' => 'Awaiting Payment', 'icon' => 'credit-card'],
                        'paid' => ['bg' => 'bg-green-500', 'text' => 'Payment Verified', 'icon' => 'check-circle'],
                        'confirmed' => ['bg' => 'bg-purple-500', 'text' => 'Confirmed', 'icon' => 'badge-check'],
                        'rejected' => ['bg' => 'bg-red-500', 'text' => 'Rejected', 'icon' => 'x-circle'],
                        'cancelled' => ['bg' => 'bg-gray-500', 'text' => 'Cancelled', 'icon' => 'ban'],
                    ];
                    $status = $statusConfig[$booking->status] ?? ['bg' => 'bg-gray-500', 'text' => $booking->status, 'icon' => 'help-circle'];
                ?>
                <div class="<?php echo e($status['bg']); ?> px-gr-lg py-gr-sm rounded-lg flex items-center gap-2">
                    <i data-lucide="<?php echo e($status['icon']); ?>" class="w-5 h-5"></i>
                    <span class="font-bold text-body"><?php echo e($status['text']); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Deadline Warning (if applicable) -->
    <?php if($booking->status === 'staff_verified' && $paymentDeadline): ?>
        <div class="bg-<?php echo e($hoursRemaining <= 12 ? 'red' : 'yellow'); ?>-50 border-2 border-<?php echo e($hoursRemaining <= 12 ? 'red' : 'yellow'); ?>-200 rounded-xl p-gr-lg">
            <div class="flex items-center gap-gr-md">
                <div class="w-12 h-12 bg-<?php echo e($hoursRemaining <= 12 ? 'red' : 'yellow'); ?>-500 rounded-lg flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-white"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-h4 font-bold text-<?php echo e($hoursRemaining <= 12 ? 'red' : 'yellow'); ?>-900 mb-1">
                        <?php echo e($hoursRemaining <= 0 ? 'Payment Deadline Passed!' : 'Payment Deadline Approaching'); ?>

                    </h3>
                    <p class="text-small text-<?php echo e($hoursRemaining <= 12 ? 'red' : 'yellow'); ?>-700">
                        <?php if($hoursRemaining <= 0): ?>
                            Deadline was <?php echo e($paymentDeadline->format('M d, Y g:i A')); ?>. Booking may need to be cancelled.
                        <?php else: ?>
                            <strong><?php echo e($hoursRemaining); ?> hours remaining</strong> until <?php echo e($paymentDeadline->format('M d, Y g:i A')); ?>

                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Schedule Conflicts Warning -->
    <?php if($conflicts->isNotEmpty()): ?>
        <div class="bg-red-50 border-2 border-red-200 rounded-xl p-gr-lg">
            <div class="flex items-start gap-gr-md">
                <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i data-lucide="alert-octagon" class="w-6 h-6 text-white"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-h4 font-bold text-red-900 mb-gr-sm">Schedule Conflict Detected!</h3>
                    <div class="space-y-2">
                        <?php $__currentLoopData = $conflicts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conflict): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($conflict && is_object($conflict)): ?>
                                <?php
                                    // Fetch user details from auth_db directly to avoid cross-database relationship issues
                                    $conflictUser = \DB::connection('auth_db')->table('users')->where('id', $conflict->user_id)->first();
                                    $conflictUserName = $conflictUser->name ?? $conflict->applicant_name ?? 'N/A';
                                ?>
                                <div class="bg-white rounded-lg p-gr-sm border border-red-200">
                                    <p class="text-small font-semibold text-red-900">
                                        Booking #<?php echo e($conflict->id); ?> - <?php echo e($conflictUserName); ?>

                                    </p>
                                    <p class="text-caption text-red-700">
                                        <?php echo e(\Carbon\Carbon::parse($conflict->start_time)->format('M d, Y g:i A')); ?> - 
                                        <?php echo e(\Carbon\Carbon::parse($conflict->end_time)->format('g:i A')); ?>

                                    </p>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gr-lg">
        <!-- Left Column: Booking Details -->
        <div class="lg:col-span-2 space-y-gr-lg">
            
            <!-- Citizen Information -->
            <div class="bg-white rounded-xl shadow-sm border border-lgu-stroke p-gr-lg">
                <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center gap-2">
                    <i data-lucide="user" class="w-6 h-6"></i>
                    Citizen Information
                </h2>
                <div class="grid grid-cols-2 gap-gr-md">
                    <div>
                        <p class="text-caption text-gray-500 mb-1">Full Name</p>
                        <p class="text-body font-semibold text-lgu-headline"><?php echo e($user->name); ?></p>
                    </div>
                    <div>
                        <p class="text-caption text-gray-500 mb-1">Email</p>
                        <p class="text-body font-semibold text-lgu-headline"><?php echo e($user->email); ?></p>
                    </div>
                    <div>
                        <p class="text-caption text-gray-500 mb-1">Phone</p>
                        <p class="text-body font-semibold text-lgu-headline"><?php echo e($user->phone ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-caption text-gray-500 mb-1">Address</p>
                        <p class="text-body font-semibold text-lgu-headline"><?php echo e($user->address ?? 'N/A'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Facility & Event Details -->
            <div class="bg-white rounded-xl shadow-sm border border-lgu-stroke p-gr-lg">
                <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center gap-2">
                    <i data-lucide="building-2" class="w-6 h-6"></i>
                    Facility & Event Details
                </h2>
                <div class="space-y-gr-md">
                    <div class="bg-lgu-bg rounded-lg p-gr-md">
                        <p class="text-caption text-gray-500 mb-1">Facility</p>
                        <p class="text-h4 font-bold text-lgu-headline"><?php echo e($booking->facility->name); ?></p>
                        <p class="text-small text-lgu-paragraph"><?php echo e($booking->facility->lguCity->city_name); ?></p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-gr-md">
                        <div>
                            <p class="text-caption text-gray-500 mb-1">Event Date & Time</p>
                            <p class="text-body font-semibold text-lgu-headline">
                                <?php echo e(\Carbon\Carbon::parse($booking->start_time)->format('M d, Y')); ?>

                            </p>
                            <p class="text-small text-lgu-paragraph">
                                <?php echo e(\Carbon\Carbon::parse($booking->start_time)->format('g:i A')); ?> - 
                                <?php echo e(\Carbon\Carbon::parse($booking->end_time)->format('g:i A')); ?>

                            </p>
                        </div>
                        <div>
                            <p class="text-caption text-gray-500 mb-1">Number of Attendees</p>
                            <p class="text-body font-semibold text-lgu-headline"><?php echo e($booking->expected_attendees); ?> people</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-caption text-gray-500 mb-1">Event Purpose</p>
                        <p class="text-body text-lgu-paragraph"><?php echo e($booking->purpose ?? 'Not specified'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Equipment -->
            <?php if($equipment && $equipment->count() > 0): ?>
            <div class="bg-white rounded-xl shadow-sm border border-lgu-stroke p-gr-lg">
                <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center gap-2">
                    <i data-lucide="package" class="w-6 h-6"></i>
                    Equipment Requested
                </h2>
                <div class="space-y-gr-sm">
                    <?php $__currentLoopData = $equipment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-gr-sm bg-lgu-bg rounded-lg">
                            <div class="flex items-center gap-gr-sm">
                                <div class="w-10 h-10 bg-lgu-highlight/10 rounded-lg flex items-center justify-center">
                                    <i data-lucide="box" class="w-5 h-5 text-lgu-highlight"></i>
                                </div>
                                <div>
                                    <p class="text-body font-semibold text-lgu-headline"><?php echo e($item->name); ?></p>
                                    <p class="text-caption text-lgu-paragraph">Quantity: <?php echo e($item->pivot->quantity); ?></p>
                                </div>
                            </div>
                            <p class="text-body font-bold text-green-600">₱<?php echo e(number_format($item->pivot->subtotal, 2)); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Uploaded Documents -->
            <div class="bg-white rounded-xl shadow-sm border border-lgu-stroke p-gr-lg">
                <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center gap-2">
                    <i data-lucide="file-check" class="w-6 h-6"></i>
                    Uploaded Documents
                </h2>
                <div class="grid grid-cols-3 gap-gr-md">
                    <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $path): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border-2 border-lgu-stroke rounded-lg p-gr-sm hover:border-lgu-highlight cursor-pointer transition-colors" onclick="openDocumentModal('<?php echo e(asset('storage/' . $path)); ?>', '<?php echo e(ucwords(str_replace('_', ' ', $key))); ?>')">
                            <img src="<?php echo e(asset('storage/' . $path)); ?>" alt="<?php echo e($key); ?>" class="w-full h-32 object-cover rounded-lg mb-gr-xs">
                            <p class="text-caption text-center text-lgu-paragraph"><?php echo e(ucwords(str_replace('_', ' ', $key))); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

        </div>

        <!-- Right Column: Actions & Summary -->
        <div class="space-y-gr-lg">
            
            <!-- Payment Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-lgu-stroke p-gr-lg sticky top-4">
                <h3 class="text-h4 font-bold text-lgu-headline mb-gr-md">Payment Summary</h3>
                <div class="space-y-gr-sm">
                    <div class="flex justify-between text-small">
                        <span class="text-lgu-paragraph">Base Rate</span>
                        <span class="font-semibold text-lgu-headline">₱<?php echo e(number_format($booking->base_rate, 2)); ?></span>
                    </div>
                    <?php if($equipment && $equipment->count() > 0): ?>
                    <div class="flex justify-between text-small">
                        <span class="text-lgu-paragraph">Equipment</span>
                        <span class="font-semibold text-lgu-headline">₱<?php echo e(number_format($booking->equipment_total, 2)); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($booking->extension_hours > 0): ?>
                    <div class="flex justify-between text-small">
                        <span class="text-lgu-paragraph">Extension (<?php echo e($booking->extension_hours); ?>h)</span>
                        <span class="font-semibold text-lgu-headline">₱<?php echo e(number_format($booking->extension_price, 2)); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="border-t-2 border-lgu-stroke pt-gr-sm mt-gr-sm">
                        <div class="flex justify-between">
                            <span class="text-body font-bold text-lgu-headline">Total Amount</span>
                            <span class="text-h3 font-bold text-green-600">₱<?php echo e(number_format($booking->total_amount, 2)); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-gr-lg space-y-gr-sm">
                    <?php if($booking->status === 'staff_verified'): ?>
                        <!-- Payment Verification Actions -->
                        <form method="POST" action="<?php echo e(route('admin.bookings.confirm-payment', $booking->id)); ?>" id="confirmPaymentForm">
                            <?php echo csrf_field(); ?>
                            <button type="button" onclick="confirmPayment()" class="w-full px-gr-lg py-gr-md bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                                Confirm Payment
                            </button>
                        </form>
                        
                        <button onclick="openRejectModal()" class="w-full px-gr-lg py-gr-md bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="x-circle" class="w-5 h-5"></i>
                            Reject Payment
                        </button>

                    <?php elseif($booking->status === 'paid'): ?>
                        <!-- Final Confirmation -->
                        <form method="POST" action="<?php echo e(route('admin.bookings.final-confirm', $booking->id)); ?>" id="finalConfirmForm">
                            <?php echo csrf_field(); ?>
                            <button type="button" onclick="finalConfirm()" class="w-full px-gr-lg py-gr-md bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i data-lucide="badge-check" class="w-5 h-5"></i>
                                Final Confirmation
                            </button>
                        </form>

                    <?php elseif($booking->status === 'confirmed'): ?>
                        <div class="bg-green-50 border-2 border-green-200 rounded-lg p-gr-md text-center">
                            <i data-lucide="check-circle" class="w-12 h-12 text-green-600 mx-auto mb-2"></i>
                            <p class="text-body font-bold text-green-900">Booking Confirmed</p>
                            <p class="text-small text-green-700">No further action required</p>
                        </div>
                    <?php endif; ?>

                    <a href="<?php echo e(route('admin.bookings.index')); ?>" class="w-full px-gr-lg py-gr-md bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                        Back to All Bookings
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Document Viewer Modal -->
<div id="documentModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-sm max-h-[85vh] flex flex-col overflow-hidden shadow-2xl">
        <div class="flex-shrink-0 bg-white border-b border-gray-200 p-gr-sm flex items-center justify-between">
            <h3 id="modalTitle" class="text-body font-bold text-lgu-headline"></h3>
            <button onclick="closeDocumentModal()" class="p-1 hover:bg-gray-100 rounded-lg transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="flex-1 p-gr-md bg-gray-50 flex items-center justify-center overflow-auto min-h-0">
            <img id="modalImage" src="" alt="Document" style="max-width: 280px; max-height: 400px; width: auto; height: auto;" class="object-contain rounded-lg">
        </div>
    </div>
</div>

<!-- Reject Payment Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-gr-lg">
        <h3 class="text-h3 font-bold text-lgu-headline mb-gr-md">Reject Payment</h3>
        <form method="POST" action="<?php echo e(route('admin.bookings.reject-payment', $booking->id)); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-gr-md">
                <label class="block text-small font-medium text-lgu-paragraph mb-gr-xs">Rejection Reason</label>
                <textarea name="rejection_reason" rows="4" required class="w-full px-gr-md py-gr-sm border border-lgu-stroke rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-button" placeholder="Explain why the payment is being rejected..."></textarea>
            </div>
            <div class="flex gap-gr-sm">
                <button type="button" onclick="closeRejectModal()" class="flex-1 px-gr-lg py-gr-sm bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-gr-lg py-gr-sm bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition-colors">
                    Reject Payment
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmPayment() {
    Swal.fire({
        title: 'Confirm Payment',
        text: 'Confirm that payment has been received and verified?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Confirm Payment',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('confirmPaymentForm').submit();
        }
    });
}

function finalConfirm() {
    Swal.fire({
        title: 'Final Confirmation',
        text: 'This is the final confirmation. Proceed?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#9333ea',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Proceed',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('finalConfirmForm').submit();
        }
    });
}

function openDocumentModal(imageSrc, title) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('documentModal').classList.remove('hidden');
    lucide.createIcons();
}

function closeDocumentModal() {
    document.getElementById('documentModal').classList.add('hidden');
}

function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modals on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDocumentModal();
        closeRejectModal();
    }
});

// Initialize Lucide icons on page load
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\local-government-unit-1-ph.com\resources\views/admin/bookings/review.blade.php ENDPATH**/ ?>