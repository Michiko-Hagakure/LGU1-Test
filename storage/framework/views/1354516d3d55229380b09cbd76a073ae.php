

<?php $__env->startSection('page-title', 'Infrastructure Project Requests'); ?>
<?php $__env->startSection('page-subtitle', 'View and track your submitted infrastructure project requests'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="max-w-7xl mx-auto">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <p class="text-gray-600">Track the status of your infrastructure project requests submitted to the Infrastructure PM system.</p>
        </div>
        <a href="<?php echo e(route('admin.infrastructure.project-request')); ?>" class="px-4 py-2 bg-lgu-highlight text-white rounded-lg hover:bg-lgu-stroke transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Project Request
        </a>
    </div>

    
    <?php if(session('success')): ?>
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <p class="text-green-800"><?php echo e(session('success')); ?></p>
    </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <?php if($requests instanceof \Illuminate\Pagination\LengthAwarePaginator && $requests->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Budget</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">External ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900"><?php echo e($request->project_title); ?></p>
                                <p class="text-sm text-gray-500"><?php echo e($request->requesting_office); ?></p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($request->project_category); ?></td>
                        <td class="px-6 py-4">
                            <?php
                                $priorityColors = [
                                    'low' => 'bg-green-100 text-green-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'high' => 'bg-red-100 text-red-800',
                                ];
                            ?>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?php echo e($priorityColors[$request->priority_level] ?? 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e(ucfirst($request->priority_level)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?php if($request->estimated_budget): ?>
                            ₱<?php echo e(number_format($request->estimated_budget, 2)); ?>

                            <?php else: ?>
                            <span class="text-gray-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'submitted' => 'bg-blue-100 text-blue-800',
                                    'received' => 'bg-indigo-100 text-indigo-800',
                                    'under_review' => 'bg-purple-100 text-purple-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'in_progress' => 'bg-orange-100 text-orange-800',
                                    'completed' => 'bg-emerald-100 text-emerald-800',
                                ];
                            ?>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?php echo e($statusColors[$request->status] ?? 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e(ucwords(str_replace('_', ' ', $request->status))); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?php echo e(\Carbon\Carbon::parse($request->created_at)->format('M d, Y')); ?>

                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <?php if($request->external_project_id): ?>
                            <span class="font-mono text-gray-900">#<?php echo e($request->external_project_id); ?></span>
                            <?php else: ?>
                            <span class="text-gray-400">Pending</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        
        <?php if($requests->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($requests->links()); ?>

        </div>
        <?php endif; ?>

        <?php else: ?>
        
        <div class="p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No project requests yet</h3>
            <p class="mt-2 text-gray-500">Get started by submitting your first infrastructure project request.</p>
            <a href="<?php echo e(route('admin.infrastructure.project-request')); ?>" class="mt-6 inline-flex items-center gap-2 px-4 py-2 bg-lgu-highlight text-white rounded-lg hover:bg-lgu-stroke transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Submit Project Request
            </a>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex gap-4">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h4 class="font-medium text-blue-900">About Infrastructure PM Integration</h4>
                <p class="mt-1 text-sm text-blue-700">
                    Project requests submitted here are sent to the Infrastructure Project Management system for review and processing. 
                    Once approved, you'll receive updates on contractor assignment, construction progress, and project completion.
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/infrastructure/index.blade.php ENDPATH**/ ?>