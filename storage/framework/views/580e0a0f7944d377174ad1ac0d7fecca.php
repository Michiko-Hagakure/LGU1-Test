

<?php $__env->startSection('page-content'); ?>
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-lgu-headline mb-2">Audit Trail</h1>
        <p class="text-lgu-paragraph">Track all system activities and user actions for compliance and security monitoring</p>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <form method="GET" action="<?php echo e(route('admin.audit-trail.index')); ?>" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-semibold text-lgu-headline mb-2">
                        <i data-lucide="search" class="w-4 h-4 inline mr-1"></i>
                        Search
                    </label>
                    <input type="text" 
                           name="search" 
                           value="<?php echo e(request('search')); ?>"
                           placeholder="Search description..."
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                </div>

                <!-- Event Filter -->
                <div>
                    <label class="block text-sm font-semibold text-lgu-headline mb-2">
                        <i data-lucide="activity" class="w-4 h-4 inline mr-1"></i>
                        Action Type
                    </label>
                    <select name="event" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                        <option value="">All Actions</option>
                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($event); ?>" <?php echo e(request('event') == $event ? 'selected' : ''); ?>>
                                <?php echo e(ucfirst($event)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Module Filter -->
                <div>
                    <label class="block text-sm font-semibold text-lgu-headline mb-2">
                        <i data-lucide="layers" class="w-4 h-4 inline mr-1"></i>
                        Module
                    </label>
                    <select name="log_name" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                        <option value="">All Modules</option>
                        <?php $__currentLoopData = $logNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($logName); ?>" <?php echo e(request('log_name') == $logName ? 'selected' : ''); ?>>
                                <?php echo e(ucfirst($logName)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-semibold text-lgu-headline mb-2">
                        <i data-lucide="calendar" class="w-4 h-4 inline mr-1"></i>
                        Date From
                    </label>
                    <input type="date" 
                           name="date_from" 
                           value="<?php echo e(request('date_from')); ?>"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-semibold text-lgu-headline mb-2">
                        <i data-lucide="calendar" class="w-4 h-4 inline mr-1"></i>
                        Date To
                    </label>
                    <input type="date" 
                           name="date_to" 
                           value="<?php echo e(request('date_to')); ?>"
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2 bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:opacity-90 transition">
                    <i data-lucide="filter" class="w-4 h-4 inline mr-2"></i>
                    Apply Filters
                </button>
                <a href="<?php echo e(route('admin.audit-trail.index')); ?>" class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    <i data-lucide="x" class="w-4 h-4 inline mr-2"></i>
                    Clear
                </a>
                <div class="ml-auto flex gap-2">
                    <button type="button" onclick="exportCsv()" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                        <i data-lucide="file-spreadsheet" class="w-4 h-4 inline mr-2"></i>
                        Export CSV
                    </button>
                    <button type="button" onclick="exportPdf()" class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                        <i data-lucide="file-text" class="w-4 h-4 inline mr-2"></i>
                        Export PDF
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Summary -->
    <div class="bg-white rounded-2xl shadow-lg p-4 mb-6">
        <div class="flex items-center justify-between">
            <p class="text-sm text-lgu-paragraph">
                Showing <?php echo e($logs->firstItem() ?? 0); ?> to <?php echo e($logs->lastItem() ?? 0); ?> of <?php echo e($logs->total()); ?> audit logs
            </p>
            <?php if(request()->hasAny(['search', 'event', 'log_name', 'date_from', 'date_to'])): ?>
                <span class="px-3 py-1 bg-lgu-button text-lgu-button-text text-sm font-semibold rounded-full">
                    <i data-lucide="filter" class="w-3 h-3 inline mr-1"></i>
                    Filters Active
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-lgu-stroke text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Date & Time</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">User</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Action</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Module</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Description</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">IP Address</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-lgu-paragraph whitespace-nowrap">
                                <?php echo e($log->created_at->format('M d, Y h:i A')); ?>

                            </td>
                            <td class="px-4 py-3 text-sm font-semibold text-lgu-headline">
                                <?php echo e($log->causer?->name ?? 'System'); ?>

                            </td>
                            <td class="px-4 py-3 text-sm">
                                <?php if($log->event === 'created'): ?>
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        <i data-lucide="plus-circle" class="w-3 h-3 inline mr-1"></i>
                                        Created
                                    </span>
                                <?php elseif($log->event === 'updated'): ?>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                        <i data-lucide="edit" class="w-3 h-3 inline mr-1"></i>
                                        Updated
                                    </span>
                                <?php elseif($log->event === 'deleted'): ?>
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                        <i data-lucide="trash-2" class="w-3 h-3 inline mr-1"></i>
                                        Deleted
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                        <?php echo e(ucfirst($log->event ?? 'N/A')); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-lgu-paragraph">
                                <span class="px-2 py-1 bg-lgu-bg text-lgu-headline rounded text-xs font-semibold">
                                    <?php echo e(ucfirst($log->log_name ?? 'N/A')); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-lgu-paragraph">
                                <?php echo e(Str::limit($log->description, 50)); ?>

                            </td>
                            <td class="px-4 py-3 text-sm text-lgu-paragraph">
                                <?php echo e($log->ip_address ?? 'N/A'); ?>

                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="<?php echo e(route('admin.audit-trail.show', $log->id)); ?>" 
                                   class="inline-flex items-center px-3 py-1 bg-lgu-button text-lgu-button-text text-sm font-semibold rounded hover:opacity-90 transition">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                    View Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <i data-lucide="inbox" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
                                <p class="text-lgu-paragraph font-semibold">No audit logs found</p>
                                <p class="text-sm text-gray-500">Try adjusting your filters or search criteria</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($logs->hasPages()): ?>
            <div class="px-6 py-4 border-t border-gray-200">
                <?php echo e($logs->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function exportCsv() {
        const params = new URLSearchParams(window.location.search);
        window.location.href = '<?php echo e(route("admin.audit-trail.export-csv")); ?>?' + params.toString();
    }

    function exportPdf() {
        const params = new URLSearchParams(window.location.search);
        window.location.href = '<?php echo e(route("admin.audit-trail.export-pdf")); ?>?' + params.toString();
    }

    lucide.createIcons();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/audit-trail/index.blade.php ENDPATH**/ ?>