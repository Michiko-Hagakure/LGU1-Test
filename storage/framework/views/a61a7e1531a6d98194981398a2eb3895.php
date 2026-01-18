

<?php $__env->startSection('page-title', 'All Bookings'); ?>
<?php $__env->startSection('page-subtitle', 'View and manage all facility reservations'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-gr-xl">
    <!-- Page Header -->
    <div class="bg-indigo-600 rounded-2xl p-gr-xl shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-gr-md">
                <div class="w-16 h-16 bg-indigo-700 rounded-xl flex items-center justify-center">
                    <i data-lucide="list-checks" class="w-8 h-8 text-white"></i>
                </div>
                <div>
                    <h1 class="text-h2 font-bold mb-gr-xs text-white">All Bookings</h1>
                    <p class="text-body text-indigo-100">Complete overview of all facility reservations</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-caption text-indigo-100 mb-1">Total Bookings</p>
                <p class="text-h1 font-bold text-white"><?php echo e($bookings->total()); ?></p>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-lgu-stroke p-gr-lg">
        <form method="GET" action="<?php echo e(route('admin.bookings.index')); ?>" class="space-y-gr-md">
            <div class="flex flex-col lg:flex-row gap-gr-md">
                <!-- Search -->
                <div class="flex-1">
                    <label class="block text-small font-medium text-lgu-paragraph mb-gr-xs">Search</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="<?php echo e(request('search')); ?>"
                            placeholder="Search by booking ID or citizen name..." 
                            class="w-full px-gr-md py-gr-sm pl-10 border border-lgu-stroke rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-button text-small"
                        >
                        <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="w-full lg:w-56">
                    <label class="block text-small font-medium text-lgu-paragraph mb-gr-xs">Status</label>
                    <select 
                        name="status" 
                        class="w-full px-gr-md py-gr-sm border border-lgu-stroke rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-button text-small"
                    >
                        <option value="all" <?php echo e(request('status', 'all') == 'all' ? 'selected' : ''); ?>>All Statuses</option>
                        <option value="pending_staff_verification" <?php echo e(request('status') == 'pending_staff_verification' ? 'selected' : ''); ?>>Pending Verification</option>
                        <option value="staff_verified" <?php echo e(request('status') == 'staff_verified' ? 'selected' : ''); ?>>Staff Verified</option>
                        <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                        <option value="confirmed" <?php echo e(request('status') == 'confirmed' ? 'selected' : ''); ?>>Confirmed</option>
                        <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                        <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                        <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>Expired</option>
                    </select>
                </div>

                <!-- Facility Filter -->
                <div class="w-full lg:w-56">
                    <label class="block text-small font-medium text-lgu-paragraph mb-gr-xs">Facility</label>
                    <select 
                        name="facility_id" 
                        class="w-full px-gr-md py-gr-sm border border-lgu-stroke rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-button text-small"
                    >
                        <option value="all" <?php echo e(request('facility_id', 'all') == 'all' ? 'selected' : ''); ?>>All Facilities</option>
                        <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($facility->facility_id); ?>" <?php echo e(request('facility_id') == $facility->facility_id ? 'selected' : ''); ?>>
                                <?php echo e($facility->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-gr-md">
                <!-- Date From -->
                <div class="flex-1">
                    <label class="block text-small font-medium text-lgu-paragraph mb-gr-xs">Event Date From</label>
                    <input 
                        type="date" 
                        name="date_from" 
                        value="<?php echo e(request('date_from')); ?>"
                        class="w-full px-gr-md py-gr-sm border border-lgu-stroke rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-button text-small"
                    >
                </div>

                <!-- Date To -->
                <div class="flex-1">
                    <label class="block text-small font-medium text-lgu-paragraph mb-gr-xs">Event Date To</label>
                    <input 
                        type="date" 
                        name="date_to" 
                        value="<?php echo e(request('date_to')); ?>"
                        class="w-full px-gr-md py-gr-sm border border-lgu-stroke rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-button text-small"
                    >
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-gr-sm">
                    <button type="submit" class="px-gr-lg py-gr-sm bg-lgu-button hover:bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg transition-colors whitespace-nowrap">
                        <i data-lucide="filter" class="w-5 h-5 inline mr-2"></i>
                        Apply Filters
                    </button>
                    <a href="<?php echo e(route('admin.bookings.index')); ?>" class="px-gr-lg py-gr-sm bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors whitespace-nowrap">
                        <i data-lucide="x" class="w-5 h-5 inline mr-2"></i>
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <?php if($bookings->isEmpty()): ?>
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-lgu-stroke p-gr-xl text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-gr-md">
                <i data-lucide="inbox" class="w-12 h-12 text-gray-400"></i>
            </div>
            <h3 class="text-h3 font-bold text-lgu-headline mb-gr-sm">No Bookings Found</h3>
            <p class="text-body text-lgu-paragraph mb-gr-md">
                <?php if(request()->hasAny(['search', 'status', 'facility_id', 'date_from', 'date_to'])): ?>
                    No bookings match your current filters. Try adjusting your search criteria.
                <?php else: ?>
                    There are no bookings in the system yet.
                <?php endif; ?>
            </p>
            <?php if(request()->hasAny(['search', 'status', 'facility_id', 'date_from', 'date_to'])): ?>
                <a href="<?php echo e(route('admin.bookings.index')); ?>" class="inline-flex items-center px-gr-lg py-gr-sm bg-lgu-button hover:bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg transition-colors">
                    <i data-lucide="refresh-cw" class="w-5 h-5 mr-2"></i>
                    Clear All Filters
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Bookings Table -->
        <div class="bg-white rounded-xl shadow-sm border border-lgu-stroke overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-lgu-headline text-white">
                        <tr>
                            <th class="px-gr-md py-gr-sm text-left text-small font-semibold">Booking ID</th>
                            <th class="px-gr-md py-gr-sm text-left text-small font-semibold">Citizen</th>
                            <th class="px-gr-md py-gr-sm text-left text-small font-semibold">Facility</th>
                            <th class="px-gr-md py-gr-sm text-left text-small font-semibold">Event Date</th>
                            <th class="px-gr-md py-gr-sm text-left text-small font-semibold">Status</th>
                            <th class="px-gr-md py-gr-sm text-left text-small font-semibold">Amount</th>
                            <th class="px-gr-md py-gr-sm text-left text-small font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                // Get citizen name from booking data (fallback for cross-database issues)
                                $citizenName = $booking->user_name ?? $booking->applicant_name ?? 'Unknown';
                                
                                // Status badge configuration
                                $statusConfig = [
                                    'pending_staff_verification' => [
                                        'color' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                        'icon' => 'clock',
                                        'label' => 'Pending Verification'
                                    ],
                                    'staff_verified' => [
                                        'color' => 'bg-green-100 text-green-800 border-green-300',
                                        'icon' => 'check-circle',
                                        'label' => 'Staff Verified'
                                    ],
                                    'paid' => [
                                        'color' => 'bg-blue-100 text-blue-800 border-blue-300',
                                        'icon' => 'credit-card',
                                        'label' => 'Paid'
                                    ],
                                    'confirmed' => [
                                        'color' => 'bg-purple-100 text-purple-800 border-purple-300',
                                        'icon' => 'check-check',
                                        'label' => 'Confirmed'
                                    ],
                                    'rejected' => [
                                        'color' => 'bg-red-100 text-red-800 border-red-300',
                                        'icon' => 'x-circle',
                                        'label' => 'Rejected'
                                    ],
                                    'cancelled' => [
                                        'color' => 'bg-gray-100 text-gray-800 border-gray-300',
                                        'icon' => 'ban',
                                        'label' => 'Cancelled'
                                    ],
                                    'expired' => [
                                        'color' => 'bg-orange-100 text-orange-800 border-orange-300',
                                        'icon' => 'clock-alert',
                                        'label' => 'Expired'
                                    ]
                                ];
                                
                                $status = $statusConfig[$booking->status] ?? [
                                    'color' => 'bg-gray-100 text-gray-800 border-gray-300',
                                    'icon' => 'help-circle',
                                    'label' => ucfirst(str_replace('_', ' ', $booking->status))
                                ];
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Booking ID -->
                                <td class="px-gr-md py-gr-sm">
                                    <span class="text-small font-mono font-semibold text-lgu-headline"><?php echo e(str_pad($booking->id, 6, '0', STR_PAD_LEFT)); ?></span>
                                </td>

                                <!-- Citizen Name -->
                                <td class="px-gr-md py-gr-sm">
                                    <div class="flex items-center gap-gr-xs">
                                        <div class="w-8 h-8 bg-lgu-button rounded-full flex items-center justify-center text-lgu-button-text font-semibold text-caption">
                                            <?php echo e(strtoupper(substr($citizenName, 0, 1))); ?>

                                        </div>
                                        <span class="text-small text-lgu-headline"><?php echo e($citizenName); ?></span>
                                    </div>
                                </td>

                                <!-- Facility -->
                                <td class="px-gr-md py-gr-sm">
                                    <div>
                                        <p class="text-small font-medium text-lgu-headline"><?php echo e($booking->facility->name); ?></p>
                                        <p class="text-caption text-lgu-paragraph"><?php echo e($booking->facility->lguCity->city_name); ?></p>
                                    </div>
                                </td>

                                <!-- Event Date -->
                                <td class="px-gr-md py-gr-sm">
                                    <div>
                                        <p class="text-small font-medium text-lgu-headline"><?php echo e(\Carbon\Carbon::parse($booking->start_time)->format('M d, Y')); ?></p>
                                        <p class="text-caption text-lgu-paragraph"><?php echo e(\Carbon\Carbon::parse($booking->start_time)->format('h:i A')); ?></p>
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="px-gr-md py-gr-sm">
                                    <span class="inline-flex items-center gap-1.5 px-gr-sm py-1 rounded-full border text-caption font-medium <?php echo e($status['color']); ?>">
                                        <i data-lucide="<?php echo e($status['icon']); ?>" class="w-3.5 h-3.5"></i>
                                        <?php echo e($status['label']); ?>

                                    </span>
                                </td>

                                <!-- Amount -->
                                <td class="px-gr-md py-gr-sm">
                                    <span class="text-small font-semibold text-lgu-headline">₱<?php echo e(number_format($booking->total_amount, 2)); ?></span>
                                </td>

                                <!-- Actions -->
                                <td class="px-gr-md py-gr-sm">
                                    <a href="<?php echo e(route('admin.bookings.review', $booking->id)); ?>" class="inline-flex items-center gap-1.5 px-gr-sm py-1.5 bg-lgu-button hover:bg-lgu-highlight text-lgu-button-text text-caption font-medium rounded-lg transition-colors">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="bg-white rounded-xl shadow-sm border border-lgu-stroke p-gr-md">
            <div class="flex flex-col md:flex-row items-center justify-between gap-gr-md">
                <div class="text-small text-lgu-paragraph">
                    Showing <span class="font-semibold"><?php echo e($bookings->firstItem()); ?></span> to <span class="font-semibold"><?php echo e($bookings->lastItem()); ?></span> of <span class="font-semibold"><?php echo e($bookings->total()); ?></span> bookings
                </div>
                <div>
                    <?php echo e($bookings->links()); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/bookings/index.blade.php ENDPATH**/ ?>