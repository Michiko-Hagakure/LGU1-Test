

<?php $__env->startSection('page-title', 'Booking Conflicts'); ?>
<?php $__env->startSection('page-subtitle', 'Manage conflicts with city events'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-6">
    <!-- Success Message -->
    <?php if(session('success')): ?>
    <div class="bg-green-50 border-l-4 border-green-600 p-4 rounded-lg">
        <div class="flex items-start">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mr-3 flex-shrink-0 mt-0.5"></i>
            <p class="text-sm text-green-800"><?php echo e(session('success')); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Error Messages -->
    <?php if($errors->any()): ?>
    <div class="bg-red-50 border-l-4 border-red-600 p-4 rounded-lg">
        <div class="flex items-start">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="font-semibold text-red-900">Error</p>
                <ul class="list-disc list-inside text-sm text-red-800 mt-2 space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div>
        <h2 class="text-2xl font-bold text-lgu-headline">Booking Conflicts</h2>
        <p class="text-sm text-lgu-paragraph mt-1">Your bookings that conflict with city events</p>
    </div>

    <!-- Filter Tabs -->
    <div class="flex gap-2 border-b border-gray-200">
        <a href="<?php echo e(route('citizen.conflicts.index', ['filter' => 'pending'])); ?>" 
           class="px-4 py-2 -mb-px <?php echo e(request('filter', 'pending') === 'pending' ? 'border-b-2 border-lgu-highlight text-lgu-highlight font-semibold' : 'text-gray-600 hover:text-gray-800'); ?>">
            <i data-lucide="alert-circle" class="w-4 h-4 inline-block mr-1"></i>
            Pending
        </a>
        <a href="<?php echo e(route('citizen.conflicts.index', ['filter' => 'resolved'])); ?>" 
           class="px-4 py-2 -mb-px <?php echo e(request('filter') === 'resolved' ? 'border-b-2 border-lgu-highlight text-lgu-highlight font-semibold' : 'text-gray-600 hover:text-gray-800'); ?>">
            <i data-lucide="check-circle" class="w-4 h-4 inline-block mr-1"></i>
            Resolved
        </a>
        <a href="<?php echo e(route('citizen.conflicts.index', ['filter' => 'all'])); ?>" 
           class="px-4 py-2 -mb-px <?php echo e(request('filter') === 'all' ? 'border-b-2 border-lgu-highlight text-lgu-highlight font-semibold' : 'text-gray-600 hover:text-gray-800'); ?>">
            <i data-lucide="list" class="w-4 h-4 inline-block mr-1"></i>
            All
        </a>
    </div>

    <?php if($conflicts->count() > 0): ?>
        <div class="space-y-4">
            <?php $__currentLoopData = $conflicts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conflict): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl shadow-sm overflow-hidden
                <?php if($conflict->status === 'pending'): ?> border-l-4 border-orange-500 <?php else: ?> border-l-4 border-green-500 <?php endif; ?>">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <?php if($conflict->status === 'pending'): ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                        Action Required
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i data-lucide="check-circle" class="w-3 h-3"></i>
                                        Resolved
                                    </span>
                                <?php endif; ?>
                                <span class="text-sm text-lgu-paragraph">
                                    <?php if($conflict->status === 'pending'): ?>
                                        Respond by <?php echo e($conflict->response_deadline->format('M d, Y g:i A')); ?>

                                    <?php else: ?>
                                        Resolved on <?php echo e($conflict->resolved_at->format('M d, Y')); ?>

                                    <?php endif; ?>
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-lgu-headline mb-2">
                                <?php echo e($conflict->cityEvent->event_title); ?>

                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-lgu-paragraph uppercase tracking-wide mb-1">Your Booking</p>
                                    <p class="text-sm font-semibold text-lgu-headline"><?php echo e($conflict->facilityDetails->name ?? 'N/A'); ?></p>
                                    <p class="text-sm text-lgu-paragraph">
                                        <?php echo e(\Carbon\Carbon::parse($conflict->bookingDetails->start_time)->format('M d, Y g:i A')); ?>

                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-lgu-paragraph uppercase tracking-wide mb-1">City Event</p>
                                    <p class="text-sm font-semibold text-lgu-headline"><?php echo e(ucfirst($conflict->cityEvent->event_type)); ?></p>
                                    <p class="text-sm text-lgu-paragraph">
                                        <?php echo e($conflict->cityEvent->start_time->format('M d, Y g:i A')); ?>

                                    </p>
                                </div>
                            </div>

                            <?php if($conflict->status === 'resolved'): ?>
                                <div class="bg-green-50 rounded-lg p-3">
                                    <p class="text-sm text-green-800">
                                        <span class="font-semibold">Choice: </span>
                                        <?php echo e(ucfirst(str_replace('_', ' ', $conflict->citizen_choice))); ?>

                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if($conflict->status === 'pending'): ?>
                        <div class="flex-shrink-0 ml-4">
                            <a href="<?php echo e(route('citizen.conflicts.show', $conflict)); ?>" 
                               class="btn-primary inline-flex items-center gap-2">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                                <span>Resolve</span>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-6">
            <?php echo e($conflicts->links()); ?>

        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <i data-lucide="check-circle" class="w-16 h-16 mx-auto text-green-500 mb-4"></i>
            <h3 class="text-lg font-semibold text-lgu-headline mb-2">
                <?php if(request('filter') === 'resolved'): ?>
                    No Resolved Conflicts
                <?php elseif(request('filter') === 'all'): ?>
                    No Conflicts
                <?php else: ?>
                    No Pending Conflicts
                <?php endif; ?>
            </h3>
            <p class="text-sm text-lgu-paragraph">
                <?php if(request('filter') === 'resolved'): ?>
                    You don't have any resolved conflicts in your history.
                <?php elseif(request('filter') === 'all'): ?>
                    You don't have any booking conflicts at this time.
                <?php else: ?>
                    You don't have any pending booking conflicts that require action.
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.citizen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/citizen/conflicts/index.blade.php ENDPATH**/ ?>