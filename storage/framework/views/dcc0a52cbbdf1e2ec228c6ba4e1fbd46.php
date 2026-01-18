

<?php $__env->startSection('page-content'); ?>
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <a href="<?php echo e(route('admin.audit-trail.index')); ?>" class="inline-flex items-center text-lgu-paragraph hover:text-lgu-headline mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to Audit Trail
        </a>
        <h1 class="text-3xl font-bold text-lgu-headline mb-2">Audit Log Details</h1>
        <p class="text-lgu-paragraph">Complete information about this audit log entry</p>
    </div>

    <!-- Log Details Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div>
                <h2 class="text-xl font-bold text-lgu-headline mb-4 flex items-center">
                    <i data-lucide="info" class="w-5 h-5 mr-2"></i>
                    Basic Information
                </h2>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">Log ID</p>
                        <p class="text-lgu-headline font-bold">#<?php echo e($log->id); ?></p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">Date & Time</p>
                        <p class="text-lgu-headline font-bold"><?php echo e($log->created_at->format('F d, Y - h:i:s A')); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($log->created_at->diffForHumans()); ?></p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">Action Type</p>
                        <?php if($log->event === 'created'): ?>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                <i data-lucide="plus-circle" class="w-4 h-4 inline mr-1"></i>
                                Created
                            </span>
                        <?php elseif($log->event === 'updated'): ?>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                <i data-lucide="edit" class="w-4 h-4 inline mr-1"></i>
                                Updated
                            </span>
                        <?php elseif($log->event === 'deleted'): ?>
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                <i data-lucide="trash-2" class="w-4 h-4 inline mr-1"></i>
                                Deleted
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold">
                                <?php echo e(ucfirst($log->event ?? 'N/A')); ?>

                            </span>
                        <?php endif; ?>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">Module</p>
                        <span class="px-3 py-1 bg-lgu-bg text-lgu-headline rounded text-sm font-semibold">
                            <?php echo e(ucfirst($log->log_name ?? 'N/A')); ?>

                        </span>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">Description</p>
                        <p class="text-lgu-headline"><?php echo e($log->description); ?></p>
                    </div>
                </div>
            </div>

            <!-- User & System Information -->
            <div>
                <h2 class="text-xl font-bold text-lgu-headline mb-4 flex items-center">
                    <i data-lucide="user" class="w-5 h-5 mr-2"></i>
                    User & System Information
                </h2>

                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">Performed By</p>
                        <p class="text-lgu-headline font-bold"><?php echo e($log->causer?->name ?? 'System'); ?></p>
                        <?php if($log->causer): ?>
                            <p class="text-xs text-gray-500"><?php echo e($log->causer->email); ?></p>
                            <p class="text-xs text-gray-500">Role: <?php echo e(ucfirst($log->causer->role)); ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">IP Address</p>
                        <p class="text-lgu-headline font-mono"><?php echo e($log->ip_address ?? 'N/A'); ?></p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">User Agent</p>
                        <p class="text-sm text-lgu-paragraph break-all"><?php echo e($log->user_agent ?? 'N/A'); ?></p>
                    </div>

                    <?php if($log->subject): ?>
                        <div>
                            <p class="text-sm font-semibold text-lgu-paragraph mb-1">Subject Type</p>
                            <p class="text-lgu-headline"><?php echo e(class_basename($log->subject_type)); ?></p>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-lgu-paragraph mb-1">Subject ID</p>
                            <p class="text-lgu-headline font-mono">#<?php echo e($log->subject_id); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Changed Properties -->
    <?php if($log->properties && count($log->properties) > 0): ?>
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-lgu-headline mb-4 flex items-center">
                <i data-lucide="file-json" class="w-5 h-5 mr-2"></i>
                Changed Properties
            </h2>

            <div class="bg-gray-50 rounded-lg p-4 overflow-x-auto">
                <pre class="text-sm text-lgu-paragraph"><code><?php echo e(json_encode($log->properties, JSON_PRETTY_PRINT)); ?></code></pre>
            </div>

            <?php if(isset($log->properties['attributes']) && $log->event === 'updated'): ?>
                <div class="mt-4">
                    <h3 class="text-lg font-bold text-lgu-headline mb-3">Changes Summary</h3>
                    <div class="space-y-2">
                        <?php $__currentLoopData = $log->properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($key !== 'attributes' && $key !== 'old'): ?>
                                <div class="flex items-start border-b border-gray-200 pb-2">
                                    <span class="text-sm font-semibold text-lgu-paragraph w-1/3"><?php echo e(ucfirst(str_replace('_', ' ', $key))); ?></span>
                                    <span class="text-sm text-lgu-headline w-2/3">
                                        <?php if(is_array($value)): ?>
                                            <?php echo e(json_encode($value)); ?>

                                        <?php else: ?>
                                            <?php echo e($value ?? 'N/A'); ?>

                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    lucide.createIcons();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/audit-trail/show.blade.php ENDPATH**/ ?>