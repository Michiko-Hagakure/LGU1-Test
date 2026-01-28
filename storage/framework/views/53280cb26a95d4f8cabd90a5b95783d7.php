

<?php $__env->startSection('title', 'Events Management'); ?>
<?php $__env->startSection('page-title', 'Events Management'); ?>
<?php $__env->startSection('page-subtitle', 'Manage city events and activities'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Events Management</h1>
        <p class="text-gray-600 mt-1">Manage city events and activities</p>
    </div>
    <div class="flex gap-3">
        <a href="<?php echo e(route('admin.events.trash')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> View Trash
        </a>
        <a href="<?php echo e(route('admin.events.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Event
        </a>
    </div>
</div>

<?php if(session('success')): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <?php if($events->count() > 0): ?>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attendees</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900"><?php echo e($event->title); ?></div>
                    <div class="text-sm text-gray-500"><?php echo e(Str::limit($event->description, 60)); ?></div>
                    <?php if($event->is_featured): ?>
                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded mt-1 inline-block">Featured</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-sm">
                    <div class="text-gray-900"><?php echo e($event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : 'TBA'); ?></div>
                    <?php if($event->start_time): ?>
                    <div class="text-gray-500"><?php echo e(\Carbon\Carbon::parse($event->start_time)->format('g:i A')); ?> - <?php echo e($event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('g:i A') : 'TBA'); ?></div>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($event->location ?? 'TBA'); ?></td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <?php echo e($event->registered_count ?? 0); ?><?php echo e($event->max_attendees ? ' / ' . $event->max_attendees : ''); ?>

                </td>
                <td class="px-6 py-4">
                    <?php if($event->is_active): ?>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    <?php else: ?>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex gap-2">
                        <a href="<?php echo e(route('admin.events.edit', $event->id)); ?>" class="text-blue-600 hover:text-blue-800">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </a>
                        <form action="<?php echo e(route('admin.events.destroy', $event->id)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="button" onclick="confirmDelete(this)" class="text-red-600 hover:text-red-800">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="px-6 py-4">
        <?php echo e($events->links()); ?>

    </div>
    <?php else: ?>
    <div class="p-12 text-center text-gray-500">
        <i data-lucide="calendar" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
        <p class="text-lg">No events yet.</p>
        <a href="<?php echo e(route('admin.events.create')); ?>" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
            Create your first event
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
function confirmDelete(button) {
    Swal.fire({
        title: 'Delete Event?',
        text: 'This will move the event to trash.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/events/index.blade.php ENDPATH**/ ?>