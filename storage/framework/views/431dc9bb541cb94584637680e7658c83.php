

<?php $__env->startSection('page-title', 'Payment Analytics'); ?>
<?php $__env->startSection('page-subtitle', 'Track payment trends, methods, and revenue performance'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="pb-gr-2xl">
    <div class="mb-gr-lg">
        <div class="flex items-center justify-between">
            <div>
        </div>
    </div>

    
    <div class="px-gr-lg py-gr-md">
        <div class="bg-white rounded-xl shadow-md border-2 border-lgu-stroke p-gr-md">
            <form method="GET" action="<?php echo e(route('admin.analytics.payments')); ?>" class="flex items-end gap-gr-sm">
                <div class="flex-1">
                    <label for="start_date" class="block text-sm font-semibold mb-gr-xs text-lgu-headline">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="<?php echo e($startDate); ?>"
                           class="w-full px-gr-sm py-gr-xs rounded-lg border-2 border-lgu-stroke focus:border-lgu-green focus:ring-0">
                </div>
                <div class="flex-1">
                    <label for="end_date" class="block text-sm font-semibold mb-gr-xs text-lgu-headline">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="<?php echo e($endDate); ?>"
                           class="w-full px-gr-sm py-gr-xs rounded-lg border-2 border-lgu-stroke focus:border-lgu-green focus:ring-0">
                </div>
                <button type="submit" class="px-gr-md py-gr-sm btn-primary text-white rounded-lg hover:bg-opacity-90 transition-all font-semibold">
                    Apply Filter
                </button>
            </form>
        </div>
    </div>

    
    <div class="px-gr-lg grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gr-md mb-gr-md">
        
        <div class="bg-white rounded-xl shadow-md border-2 border-lgu-stroke p-gr-md">
            <div class="flex items-center gap-gr-sm mb-gr-xs">
            <div class="w-12 h-12 bg-lgu-highlight bg-opacity-10 rounded-full flex items-center justify-center">
                    <span class="text-h3 font-bold" style="color: #00473e;">₱</span>
                </div>
                <h3 class="text-sm font-semibold text-lgu-paragraph">Total Revenue</h3>
            </div>
            <p class="text-h2 font-bold text-lgu-headline">₱<?php echo e(number_format($totalRevenue, 2)); ?></p>
            <p class="text-xs text-lgu-paragraph mt-gr-xs">Paid transactions</p>
        </div>

        
        <div class="bg-white rounded-xl shadow-md border-2 border-lgu-stroke p-gr-md">
            <div class="flex items-center gap-gr-sm mb-gr-xs">
                <div class="w-12 h-12 bg-lgu-highlight bg-opacity-10 rounded-full flex items-center justify-center">
                    <i data-lucide="receipt" class="w-6 h-6" style="color: #00473e;"></i>
                </div>
                <h3 class="text-sm font-semibold text-lgu-paragraph">Total Transactions</h3>
            </div>
            <p class="text-h2 font-bold text-lgu-headline"><?php echo e(number_format($totalTransactions)); ?></p>
            <p class="text-xs text-lgu-paragraph mt-gr-xs">All payment slips</p>
        </div>

        
        <div class="bg-white rounded-xl shadow-md border-2 border-lgu-stroke p-gr-md">
            <div class="flex items-center gap-gr-sm mb-gr-xs">
            <div class="w-12 h-12 bg-lgu-highlight bg-opacity-10 rounded-full flex items-center justify-center">
                    <i data-lucide="circle-check" class="w-6 h-6" style="color: #00473e;"></i>
                </div>
                <h3 class="text-sm font-semibold text-lgu-paragraph">Success Rate</h3>
            </div>
            <p class="text-h2 font-bold text-lgu-headline"><?php echo e(number_format($successRate, 1)); ?>%</p>
            <p class="text-xs text-lgu-paragraph mt-gr-xs">Payment completion rate</p>
        </div>

        
        <div class="bg-white rounded-xl shadow-md border-2 border-lgu-stroke p-gr-md">
            <div class="flex items-center gap-gr-sm mb-gr-xs">
            <div class="w-12 h-12 bg-lgu-highlight bg-opacity-10 rounded-full flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6" style="color: #00473e;"></i>
                </div>
                <h3 class="text-sm font-semibold text-lgu-paragraph">Pending Payments</h3>
            </div>
            <p class="text-h2 font-bold text-lgu-headline">₱<?php echo e(number_format($pendingAmount, 2)); ?></p>
            <p class="text-xs text-lgu-paragraph mt-gr-xs"><?php echo e($pendingPayments); ?> pending slips</p>
        </div>
    </div>

    <div class="px-gr-lg grid grid-cols-1 lg:grid-cols-2 gap-gr-md">
        
        <div class="bg-white rounded-xl shadow-md border-2 border-lgu-stroke p-gr-lg">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md">Payment Methods</h2>
            
            <?php if($paymentMethodBreakdown->isNotEmpty()): ?>
                <div class="space-y-gr-sm">
                    <?php $__currentLoopData = $paymentMethodBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-gr-sm rounded-lg hover:bg-lgu-background-light transition-all">
                            <div class="flex items-center gap-gr-sm">
                                <div class="w-10 h-10 bg-lgu-green bg-opacity-10 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-bold text-lgu-green"><?php echo e(strtoupper(substr($method->payment_method ?? 'N/A', 0, 2))); ?></span>
                                </div>
                                <div>
                                    <p class="font-semibold text-lgu-headline"><?php echo e(ucfirst($method->payment_method ?? 'Unknown')); ?></p>
                                    <p class="text-xs text-lgu-paragraph"><?php echo e($method->count); ?> transactions</p>
                                </div>
                            </div>
                            <p class="font-bold text-lgu-green">₱<?php echo e(number_format($method->total, 2)); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-center text-lgu-paragraph py-gr-lg">No payment data available</p>
            <?php endif; ?>
        </div>

        
        <div class="bg-white rounded-xl shadow-md border-2 border-lgu-stroke p-gr-lg">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md">Payment Status</h2>
            
            <?php if($statusBreakdown->isNotEmpty()): ?>
                <div class="space-y-gr-sm">
                    <?php $__currentLoopData = $statusBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $statusColor = [
                                'paid' => 'green',
                                'pending' => 'yellow',
                                'overdue' => 'red',
                                'cancelled' => 'gray',
                            ][$status->status] ?? 'gray';
                        ?>
                        <div class="flex items-center justify-between p-gr-sm rounded-lg hover:bg-lgu-background-light transition-all">
                            <div class="flex items-center gap-gr-sm">
                                <div class="w-3 h-3 rounded-full bg-<?php echo e($statusColor); ?>-500"></div>
                                <p class="font-semibold text-lgu-headline"><?php echo e(ucfirst($status->status)); ?></p>
                            </div>
                            <p class="font-bold text-lgu-headline"><?php echo e($status->count); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-center text-lgu-paragraph py-gr-lg">No status data available</p>
            <?php endif; ?>
        </div>

        
        <div class="bg-white rounded-xl shadow-md border-2 border-lgu-stroke p-gr-lg lg:col-span-2">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md">Top Revenue Generating Facilities</h2>
            
            <?php if($topFacilities->isNotEmpty()): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-lgu-stroke">
                                <th class="text-left py-gr-sm px-gr-md text-sm font-semibold text-lgu-headline">Facility</th>
                                <th class="text-center py-gr-sm px-gr-md text-sm font-semibold text-lgu-headline">Bookings</th>
                                <th class="text-right py-gr-sm px-gr-md text-sm font-semibold text-lgu-headline">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $topFacilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b border-lgu-stroke hover:bg-lgu-background-light transition-all">
                                    <td class="py-gr-sm px-gr-md text-lgu-paragraph"><?php echo e($facility->facility_name); ?></td>
                                    <td class="py-gr-sm px-gr-md text-center text-lgu-paragraph"><?php echo e($facility->booking_count); ?></td>
                                    <td class="py-gr-sm px-gr-md text-right font-bold text-lgu-green">₱<?php echo e(number_format($facility->total_revenue, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-lgu-paragraph py-gr-lg">No facility data available</p>
            <?php endif; ?>
        </div>

        
        <div class="bg-white rounded-xl shadow-md border-2 border-lgu-stroke p-gr-lg lg:col-span-2">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md">Processing Metrics</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-md">
                <div class="p-gr-md bg-lgu-background-light rounded-lg">
                    <p class="text-sm text-lgu-paragraph mb-gr-xs">Average Processing Time</p>
                    <p class="text-h2 font-bold text-lgu-headline"><?php echo e(number_format($avgProcessingTime ?? 0, 1)); ?> hours</p>
                    <p class="text-xs text-lgu-paragraph mt-gr-xs">From creation to payment</p>
                </div>
                
                <div class="p-gr-md bg-lgu-background-light rounded-lg">
                    <p class="text-sm text-lgu-paragraph mb-gr-xs">Date Range</p>
                    <p class="text-h3 font-bold text-lgu-headline"><?php echo e(Carbon\Carbon::parse($startDate)->format('M d')); ?> - <?php echo e(Carbon\Carbon::parse($endDate)->format('M d, Y')); ?></p>
                    <p class="text-xs text-lgu-paragraph mt-gr-xs"><?php echo e(Carbon\Carbon::parse($startDate)->diffInDays(Carbon\Carbon::parse($endDate))); ?> days</p>
                </div>
            </div>
    </div>
</div>

<script>
// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\local-government-unit-1-ph.com\resources\views/admin/analytics/payments.blade.php ENDPATH**/ ?>