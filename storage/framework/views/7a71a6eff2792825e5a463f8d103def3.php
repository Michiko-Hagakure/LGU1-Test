

<?php $__env->startSection('page-title', 'Schedule Conflicts Monitor'); ?>
<?php $__env->startSection('page-subtitle', 'Detect and resolve booking conflicts'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-gr-lg">
    
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">Schedule Conflicts Monitor</h1>
            <p class="text-body text-lgu-paragraph">Monitor and resolve booking conflicts across all facilities</p>
        </div>
        <div class="flex items-center gap-gr-sm">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-gr-md py-gr-sm">
                <div class="text-caption text-gray-600 uppercase mb-1">Total Conflicts</div>
                <div class="text-h2 font-bold text-lgu-tertiary"><?php echo e($totalConflicts); ?></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-gr-md py-gr-sm">
                <div class="text-caption text-gray-600 uppercase mb-1">Future Conflicts</div>
                <div class="text-h2 font-bold text-amber-600"><?php echo e($futureConflicts); ?></div>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
        <form method="GET" action="<?php echo e(route('admin.schedule-conflicts.index')); ?>" class="space-y-gr-md">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gr-md">
                
                <div>
                    <label for="facility_id" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Facility</label>
                    <select id="facility_id" name="facility_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="">All Facilities</option>
                        <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($facility->facility_id); ?>" <?php echo e($facilityId == $facility->facility_id ? 'selected' : ''); ?>>
                                <?php echo e($facility->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label for="date_filter" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Time Period</label>
                    <select id="date_filter" name="date_filter" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="future" <?php echo e($dateFilter == 'future' ? 'selected' : ''); ?>>Future Conflicts</option>
                        <option value="all" <?php echo e($dateFilter == 'all' ? 'selected' : ''); ?>>All Time</option>
                        <option value="past" <?php echo e($dateFilter == 'past' ? 'selected' : ''); ?>>Past Conflicts</option>
                    </select>
                </div>

                
                <div class="flex items-end gap-gr-sm">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                        <i data-lucide="filter" class="w-5 h-5 mr-gr-xs"></i>
                        Apply Filters
                    </button>
                    <a href="<?php echo e(route('admin.schedule-conflicts.index')); ?>" class="inline-flex items-center px-gr-md py-gr-sm bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    
    <?php if(count($conflictDetails) > 0): ?>
        <div class="space-y-gr-md">
            <?php $__currentLoopData = $conflictDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conflict): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl shadow-sm border border-lgu-tertiary p-gr-lg">
                    
                    <div class="flex items-start justify-between mb-gr-md pb-gr-md border-b border-gray-200">
                        <div class="flex-1">
                            <div class="flex items-center gap-gr-sm mb-gr-sm">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    <i data-lucide="alert-triangle" class="w-3 h-3 mr-1"></i>
                                    <?php echo e($conflict['conflict_count']); ?> Conflict(s)
                                </span>
                                <span class="text-small text-gray-600">Booking #<?php echo e($conflict['main_booking']->id); ?></span>
                            </div>
                            <h3 class="text-h3 font-bold text-lgu-headline mb-gr-xs"><?php echo e($conflict['main_booking']->facility->name); ?></h3>
                            <div class="grid grid-cols-2 gap-gr-md text-small">
                                <div class="flex items-center text-gray-600">
                                    <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                                    <span><?php echo e(\Carbon\Carbon::parse($conflict['main_booking']->event_date)->format('F j, Y')); ?></span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i data-lucide="clock" class="w-4 h-4 mr-2"></i>
                                    <span><?php echo e(\Carbon\Carbon::parse($conflict['main_booking']->start_time)->format('g:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($conflict['main_booking']->end_time)->format('g:i A')); ?></span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i data-lucide="user" class="w-4 h-4 mr-2"></i>
                                    <span><?php echo e($conflict['main_booking']->user_name ?? 'N/A'); ?></span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i data-lucide="tag" class="w-4 h-4 mr-2"></i>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold 
                                        <?php if($conflict['main_booking']->status == 'confirmed'): ?> bg-green-100 text-green-800
                                        <?php elseif($conflict['main_booking']->status == 'paid'): ?> bg-blue-100 text-blue-800
                                        <?php else: ?> bg-gray-100 text-gray-800
                                        <?php endif; ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $conflict['main_booking']->status))); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo e(route('admin.schedule-conflicts.show', $conflict['main_booking']->id)); ?>" class="inline-flex items-center px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                            <i data-lucide="eye" class="w-5 h-5 mr-gr-xs"></i>
                            View Details
                        </a>
                    </div>

                    
                    <div>
                        <h4 class="text-small font-semibold text-lgu-headline uppercase mb-gr-sm">Conflicting Bookings:</h4>
                        <div class="space-y-gr-sm">
                            <?php $__currentLoopData = $conflict['conflicts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conflicting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-gray-50 rounded-lg p-gr-sm border border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-gr-sm mb-1">
                                                <span class="text-small font-semibold text-gray-900">Booking #<?php echo e($conflicting->id); ?></span>
                                                <span class="text-xs text-gray-600"><?php echo e($conflicting->user_name ?? 'N/A'); ?></span>
                                            </div>
                                            <div class="flex items-center gap-gr-md text-xs text-gray-600">
                                                <span><?php echo e(\Carbon\Carbon::parse($conflicting->start_time)->format('g:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($conflicting->end_time)->format('g:i A')); ?></span>
                                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold 
                                                    <?php if($conflicting->status == 'confirmed'): ?> bg-green-100 text-green-800
                                                    <?php elseif($conflicting->status == 'paid'): ?> bg-blue-100 text-blue-800
                                                    <?php else: ?> bg-gray-100 text-gray-800
                                                    <?php endif; ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $conflicting->status))); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <a href="<?php echo e(route('admin.bookings.review', $conflicting->id)); ?>" class="text-lgu-button hover:underline text-small">
                                            View →
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php if($conflicts->hasPages()): ?>
            <div class="mt-gr-lg">
                <?php echo e($conflicts->links()); ?>

            </div>
        <?php endif; ?>
    <?php else: ?>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <i data-lucide="check-circle" class="w-16 h-16 text-green-500 mb-gr-md"></i>
                <h3 class="text-h3 font-bold text-lgu-headline mb-gr-xs">No Schedule Conflicts Found</h3>
                <p class="text-body text-gray-600">All bookings are scheduled without conflicts.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\local-government-unit-1-ph.com\resources\views/admin/schedule-conflicts/index.blade.php ENDPATH**/ ?>