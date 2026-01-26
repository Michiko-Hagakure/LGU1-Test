

<?php $__env->startSection('page-title', 'Transaction History'); ?>
<?php $__env->startSection('page-subtitle', 'View your payment transactions'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="pb-gr-2xl">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-gr-md mb-gr-lg">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-600 mb-gr-2xs">Total Paid</p>
                    <p class="text-h3 font-bold text-green-600">₱<?php echo e(number_format($totalPaid, 2)); ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="circle-check" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-600 mb-gr-2xs">Total Transactions</p>
                    <p class="text-h3 font-bold text-gray-900"><?php echo e(number_format($totalTransactions)); ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="receipt" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-600 mb-gr-2xs">Pending Amount</p>
                    <p class="text-h3 font-bold text-orange-600">₱<?php echo e(number_format($pendingAmount, 2)); ?></p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md mb-gr-lg">
        <form method="GET" action="<?php echo e(route('citizen.transactions.index')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-gr-md">
            <div>
                <label class="block text-caption font-semibold text-gray-700 mb-gr-2xs">Start Date</label>
                <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" class="input-field">
            </div>
            <div>
                <label class="block text-caption font-semibold text-gray-700 mb-gr-2xs">End Date</label>
                <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" class="input-field">
            </div>
            <div>
                <label class="block text-caption font-semibold text-gray-700 mb-gr-2xs">Status</label>
                <select name="status" class="input-field">
                    <option value="">All Statuses</option>
                    <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                </select>
            </div>
            <div class="flex items-end space-x-gr-sm">
                <button type="submit" class="btn-primary flex-1">
                    <i data-lucide="search" class="w-4 h-4 mr-gr-xs"></i>
                    Filter
                </button>
                <a href="<?php echo e(route('citizen.transactions.index')); ?>" class="btn-secondary">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions List -->
    <?php if($transactions->isEmpty()): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-2xl text-center">
        <i data-lucide="receipt" class="w-16 h-16 mx-auto mb-gr-md text-gray-400"></i>
        <h3 class="text-h5 font-bold text-gray-900 mb-gr-xs">No Transactions Yet</h3>
        <p class="text-small text-gray-600 mb-gr-lg">Your payment transactions will appear here</p>
        <a href="<?php echo e(route('citizen.browse-facilities')); ?>" class="btn-primary inline-flex items-center">
            <i data-lucide="search" class="w-4 h-4 mr-gr-xs"></i>
            Browse Facilities
        </a>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Reference</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Date</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Facility & Event</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Amount</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Payment Method</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Status</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-gr-md py-gr-sm">
                            <span class="font-mono text-small font-semibold text-gray-900"><?php echo e($transaction->slip_number ?? $transaction->or_number ?? 'N/A'); ?></span>
                        </td>
                        <td class="px-gr-md py-gr-sm text-small text-gray-900">
                            <?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('M d, Y')); ?><br>
                            <span class="text-caption text-gray-500"><?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('h:i A')); ?></span>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <p class="text-small font-semibold text-gray-900"><?php echo e($transaction->facility_name); ?></p>
                            <p class="text-caption text-gray-500"><?php echo e($transaction->event_name ?? 'N/A'); ?></p>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <span class="text-small font-bold text-gray-900">₱<?php echo e(number_format($transaction->amount_due, 2)); ?></span>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <span class="inline-flex items-center px-gr-xs py-gr-3xs rounded-full text-caption font-medium
                                <?php if($transaction->payment_method == 'gcash'): ?> bg-blue-100 text-blue-700
                                <?php elseif($transaction->payment_method == 'paymaya'): ?> bg-green-100 text-green-700
                                <?php elseif($transaction->payment_method == 'bank_transfer'): ?> bg-purple-100 text-purple-700
                                <?php else: ?> bg-gray-100 text-gray-700
                                <?php endif; ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $transaction->payment_method ?? 'Cash'))); ?>

                            </span>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <span class="inline-flex items-center px-gr-xs py-gr-3xs rounded-full text-caption font-medium
                                <?php if($transaction->status == 'paid'): ?> bg-green-100 text-green-700
                                <?php elseif($transaction->status == 'pending'): ?> bg-orange-100 text-orange-700
                                <?php elseif($transaction->status == 'cancelled'): ?> bg-red-100 text-red-700
                                <?php else: ?> bg-gray-100 text-gray-700
                                <?php endif; ?>">
                                <i data-lucide="<?php echo e($transaction->status == 'paid' ? 'circle-check' : ($transaction->status == 'pending' ? 'clock' : 'x-circle')); ?>" class="w-3 h-3 mr-gr-3xs"></i>
                                <?php echo e(ucfirst($transaction->status)); ?>

                            </span>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <a href="<?php echo e(route('citizen.transactions.show', $transaction->id)); ?>" class="text-lgu-green hover:text-lgu-green-dark font-medium text-small">
                                View Details
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($transactions->hasPages()): ?>
        <div class="px-gr-md py-gr-md border-t border-gray-200">
            <?php echo e($transactions->links()); ?>

        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<script>
// Initialize Lucide icons
lucide.createIcons();
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.citizen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/citizen/transactions/index.blade.php ENDPATH**/ ?>