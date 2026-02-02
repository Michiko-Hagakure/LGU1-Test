

<?php $__env->startSection('title', 'My Inquiries'); ?>
<?php $__env->startSection('page-title', 'My Inquiries'); ?>
<?php $__env->startSection('page-subtitle', 'Track your support tickets and inquiries'); ?>

<?php $__env->startSection('page-content'); ?>
<!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">My Inquiries</h1>
        <p class="text-gray-600">Track your support tickets and inquiries</p>
    </div>

    <?php if($inquiries->count() > 0): ?>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $inquiries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inquiry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm text-blue-600 font-semibold"><?php echo e($inquiry->ticket_number); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($inquiry->subject); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600"><?php echo e(ucfirst(str_replace('_', ' ', $inquiry->category))); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php switch($inquiry->status):
                                case ('new'): ?>
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">New</span>
                                    <?php break; ?>
                                <?php case ('open'): ?>
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Open</span>
                                    <?php break; ?>
                                <?php case ('pending'): ?>
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Pending</span>
                                    <?php break; ?>
                                <?php case ('resolved'): ?>
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Resolved</span>
                                    <?php break; ?>
                                <?php case ('closed'): ?>
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Closed</span>
                                    <?php break; ?>
                            <?php endswitch; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php switch($inquiry->priority):
                                case ('urgent'): ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-red-100 text-red-800">Urgent</span>
                                    <?php break; ?>
                                <?php case ('high'): ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-orange-100 text-orange-800">High</span>
                                    <?php break; ?>
                                <?php case ('normal'): ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-blue-100 text-blue-800">Normal</span>
                                    <?php break; ?>
                                <?php case ('low'): ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-gray-100 text-gray-800">Low</span>
                                    <?php break; ?>
                            <?php endswitch; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($inquiry->created_at->format('M j, Y')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="<?php echo e(route('citizen.contact.show-inquiry', $inquiry->ticket_number)); ?>" 
                                class="text-blue-600 hover:text-blue-800 font-medium">View Details</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        <?php echo e($inquiries->links()); ?>

    </div>
    <?php else: ?>
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">No Inquiries Yet</h3>
        <p class="text-gray-600 mb-6">You haven't submitted any inquiries.</p>
        <a href="<?php echo e(route('citizen.contact.index')); ?>" 
            class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
            Submit an Inquiry
        </a>
    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.citizen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/citizen/contact/my-inquiries.blade.php ENDPATH**/ ?>