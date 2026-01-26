

<?php $__env->startSection('title', 'Payment History'); ?>

<?php $__env->startSection('page-title', 'Payment History'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-gr-lg">
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gr-md">
        <!-- Total Verified Payments -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-500 font-medium mb-gr-xs">Total Verified</p>
                    <h3 class="text-h3 font-bold text-lgu-headline"><?php echo e(number_format($stats['total_verified'])); ?></h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Amount Collected -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-500 font-medium mb-gr-xs">Total Collected</p>
                    <h3 class="text-h3 font-bold text-lgu-button">₱<?php echo e(number_format($stats['total_amount'], 2)); ?></h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i data-lucide="coins" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Today's Verified -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-500 font-medium mb-gr-xs">Today's Verified</p>
                    <h3 class="text-h3 font-bold text-lgu-headline"><?php echo e(number_format($stats['today_verified'])); ?></h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i data-lucide="calendar-check" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Today's Amount -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-500 font-medium mb-gr-xs">Today's Amount</p>
                    <h3 class="text-h3 font-bold text-lgu-button">₱<?php echo e(number_format($stats['today_amount'], 2)); ?></h3>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
        <form method="GET" action="<?php echo e(route('treasurer.payment-history')); ?>" class="space-y-gr-md">
            
            <!-- Search and Payment Method Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-md">
                <!-- Search Bar -->
                <div class="relative">
                    <label for="search" class="block text-caption font-semibold text-gray-700 mb-gr-xs">Search Payments</label>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="<?php echo e(request('search')); ?>"
                           placeholder="Slip #, OR #, Citizen Name, Email..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent text-body">
                    <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-[42px] transform pointer-events-none"></i>
                </div>

                <!-- Payment Method Filter -->
                <div>
                    <label for="payment_method" class="block text-caption font-semibold text-gray-700 mb-gr-xs">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent text-body">
                        <option value="all" <?php echo e(request('payment_method') === 'all' ? 'selected' : ''); ?>>All Methods</option>
                        <option value="cash" <?php echo e(request('payment_method') === 'cash' ? 'selected' : ''); ?>>Cash</option>
                        <option value="gcash" <?php echo e(request('payment_method') === 'gcash' ? 'selected' : ''); ?>>GCash</option>
                        <option value="paymaya" <?php echo e(request('payment_method') === 'paymaya' ? 'selected' : ''); ?>>PayMaya</option>
                        <option value="bank_transfer" <?php echo e(request('payment_method') === 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                        <option value="credit_card" <?php echo e(request('payment_method') === 'credit_card' ? 'selected' : ''); ?>>Credit Card</option>
                    </select>
                </div>
            </div>

            <!-- Date Range Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-md">
                <!-- Date From -->
                <div>
                    <label for="date_from" class="block text-caption font-semibold text-gray-700 mb-gr-xs">Date From</label>
                    <input type="date" 
                           name="date_from" 
                           id="date_from" 
                           value="<?php echo e(request('date_from')); ?>"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent text-body">
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="block text-caption font-semibold text-gray-700 mb-gr-xs">Date To</label>
                    <input type="date" 
                           name="date_to" 
                           id="date_to" 
                           value="<?php echo e(request('date_to')); ?>"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent text-body">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-gr-sm">
                <button type="submit" class="bg-lgu-button text-lgu-button-text px-gr-md py-gr-xs rounded-lg hover:bg-lgu-highlight transition-colors font-semibold text-body inline-flex items-center gap-gr-xs">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    Apply Filters
                </button>
                <a href="<?php echo e(route('treasurer.payment-history')); ?>" class="border border-gray-300 text-gray-700 px-gr-md py-gr-xs rounded-lg hover:bg-gray-50 transition-colors font-semibold text-body inline-flex items-center gap-gr-xs">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Payment History Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-gr-md border-b border-gray-200">
            <h2 class="text-h3 font-bold text-lgu-headline">Verified Payments</h2>
            <p class="text-small text-gray-600 mt-gr-xs">Complete history of all verified and paid reservations</p>
        </div>

        <?php if($paymentSlips->count() > 0): ?>
        <table class="w-full table-fixed divide-y divide-gray-200">
            <colgroup>
                <col style="width: 10%;"> <!-- Slip # -->
                <col style="width: 10%;"> <!-- OR # -->
                <col style="width: 22%;"> <!-- Citizen -->
                <col style="width: 13%;"> <!-- Facility -->
                <col style="width: 11%;"> <!-- Amount -->
                <col style="width: 9%;"> <!-- Method -->
                <col style="width: 13%;"> <!-- Paid Date -->
                <col style="width: 6%;"> <!-- Actions -->
            </colgroup>
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-2 py-2.5 text-left text-xs font-bold text-gray-600 uppercase">Slip #</th>
                    <th class="px-2 py-2.5 text-left text-xs font-bold text-gray-600 uppercase">OR #</th>
                    <th class="px-2 py-2.5 text-left text-xs font-bold text-gray-600 uppercase">Citizen</th>
                    <th class="px-2 py-2.5 text-left text-xs font-bold text-gray-600 uppercase">Facility</th>
                    <th class="px-2 py-2.5 text-left text-xs font-bold text-gray-600 uppercase">Amount</th>
                    <th class="px-2 py-2.5 text-left text-xs font-bold text-gray-600 uppercase">Method</th>
                    <th class="px-2 py-2.5 text-left text-xs font-bold text-gray-600 uppercase">Date</th>
                    <th class="px-2 py-2.5 text-center text-xs font-bold text-gray-600 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__currentLoopData = $paymentSlips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-2 py-2.5 whitespace-nowrap overflow-hidden">
                        <span class="text-xs font-bold text-lgu-button truncate block"><?php echo e($slip->slip_number); ?></span>
                    </td>
                    <td class="px-2 py-2.5 whitespace-nowrap overflow-hidden">
                        <?php if($slip->or_number): ?>
                            <span class="text-xs font-semibold text-green-600 truncate block"><?php echo e($slip->or_number); ?></span>
                        <?php else: ?>
                            <span class="text-xs text-gray-400">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-2 py-2.5 overflow-hidden">
                        <div class="text-xs font-semibold text-gray-900 truncate"><?php echo e($slip->applicant_name); ?></div>
                        <div class="text-xs text-gray-500 truncate"><?php echo e($slip->applicant_email); ?></div>
                    </td>
                    <td class="px-2 py-2.5 overflow-hidden">
                        <div class="text-xs text-gray-900 truncate"><?php echo e($slip->facility_name); ?></div>
                    </td>
                    <td class="px-2 py-2.5 whitespace-nowrap overflow-hidden">
                        <span class="text-xs font-bold text-lgu-headline truncate block">₱<?php echo e(number_format($slip->amount_due, 2)); ?></span>
                    </td>
                    <td class="px-2 py-2.5 whitespace-nowrap overflow-hidden">
                        <?php
                            $methodColors = [
                                'cash' => 'bg-green-100 text-green-800',
                                'gcash' => 'bg-blue-100 text-blue-800',
                                'maya' => 'bg-purple-100 text-purple-800',
                                'paymaya' => 'bg-purple-100 text-purple-800',
                                'bpi' => 'bg-red-100 text-red-800',
                                'bdo' => 'bg-blue-100 text-blue-800',
                                'metrobank' => 'bg-orange-100 text-orange-800',
                                'unionbank' => 'bg-green-100 text-green-800',
                                'landbank' => 'bg-green-100 text-green-800',
                                'bank_transfer' => 'bg-yellow-100 text-yellow-800',
                                'credit_card' => 'bg-pink-100 text-pink-800',
                            ];
                            $colorClass = $methodColors[$slip->payment_method] ?? 'bg-gray-100 text-gray-800';
                        ?>
                        <div class="space-y-0.5">
                            <span class="inline-block px-1.5 py-0.5 rounded text-xs font-bold <?php echo e($colorClass); ?> truncate">
                                <?php echo e(strtoupper($slip->payment_channel ?? $slip->payment_method ?? 'N/A')); ?>

                            </span>
                            <?php if(isset($slip->is_test_transaction) && $slip->is_test_transaction): ?>
                                <span class="block px-1 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-800">TEST</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-2 py-2.5 whitespace-nowrap overflow-hidden">
                        <div class="text-xs text-gray-900 truncate"><?php echo e(\Carbon\Carbon::parse($slip->paid_at)->format('M d, Y')); ?></div>
                        <div class="text-xs text-gray-500 truncate"><?php echo e(\Carbon\Carbon::parse($slip->paid_at)->format('h:i A')); ?></div>
                    </td>
                    <td class="px-2 py-2.5 whitespace-nowrap text-center">
                        <a href="<?php echo e(route('treasurer.payment-slips.show', $slip->id)); ?>" class="inline-flex items-center justify-center text-lgu-button hover:text-lgu-highlight transition-colors" title="View Details">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-gr-md py-gr-md border-t border-gray-200">
            <?php echo e($paymentSlips->withQueryString()->links()); ?>

        </div>
        <?php else: ?>
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-gr-md">
                <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>
            </div>
            <h3 class="text-body font-semibold text-gray-900 mb-gr-xs">No Payment History</h3>
            <p class="text-small text-gray-500">
                <?php if(request()->has('search') || request()->has('payment_method') || request()->has('date_from')): ?>
                    No payments found matching your filters. Try adjusting your search criteria.
                <?php else: ?>
                    No payments have been verified yet.
                <?php endif; ?>
            </p>
        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Initialize Lucide icons
    lucide.createIcons();
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.treasurer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/treasurer/payment-history/index.blade.php ENDPATH**/ ?>