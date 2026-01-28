

<?php $__env->startSection('title', 'Manage Facilities'); ?>
<?php $__env->startSection('page-title', 'Manage Facilities'); ?>
<?php $__env->startSection('page-subtitle', 'Add, edit, and manage all public facilities'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="container mx-auto px-gr-md py-gr-lg">
    
    <div class="flex items-center justify-between mb-gr-lg">
        <div>
            <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">Manage Facilities</h1>
            <p class="text-body text-lgu-paragraph">Add, edit, and manage all public facilities</p>
        </div>
        <a href="<?php echo e(route('admin.facilities.create')); ?>" class="inline-flex items-center px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
            <i data-lucide="plus" class="w-5 h-5 mr-gr-xs"></i>
            Add New Facility
        </a>
    </div>

    
    <?php if(session('success')): ?>
        <div id="success-message" class="bg-green-50 border-2 border-green-200 rounded-lg p-gr-md mb-gr-lg flex items-start gap-gr-sm">
            <i data-lucide="check-circle" class="w-6 h-6 text-green-600 flex-shrink-0 mt-1"></i>
            <div class="flex-1">
                <p class="text-body font-semibold text-green-900"><?php echo e(session('success')); ?></p>
            </div>
            <button onclick="document.getElementById('success-message').remove()" class="text-green-600 hover:text-green-800">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div id="error-message" class="bg-red-50 border-2 border-red-200 rounded-lg p-gr-md mb-gr-lg flex items-start gap-gr-sm">
            <i data-lucide="alert-circle" class="w-6 h-6 text-red-600 flex-shrink-0 mt-1"></i>
            <div class="flex-1">
                <p class="text-body font-semibold text-red-900"><?php echo e(session('error')); ?></p>
            </div>
            <button onclick="document.getElementById('error-message').remove()" class="text-red-600 hover:text-red-800">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-gr-lg">
        <div class="flex">
            <a href="<?php echo e(route('admin.facilities.index', request()->except('show_deleted'))); ?>" 
                style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 1rem 1.5rem; font-weight: 600; transition: all 0.2s; border-bottom: 4px solid <?php echo e(!$showDeleted ? '#00473e' : 'transparent'); ?>; background-color: <?php echo e(!$showDeleted ? '#00473e' : '#f9fafb'); ?>; color: <?php echo e(!$showDeleted ? 'white' : '#4b5563'); ?>;">
                <i data-lucide="building-2" style="width: 20px; height: 20px; margin-right: 0.5rem;"></i>
                <span>Active Facilities</span>
                <span style="margin-left: 0.5rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background-color: <?php echo e(!$showDeleted ? 'white' : '#e5e7eb'); ?>; color: <?php echo e(!$showDeleted ? '#00473e' : '#374151'); ?>;">
                    <?php echo e($activeFacilitiesCount ?? 0); ?>

                </span>
            </a>
            <a href="<?php echo e(route('admin.facilities.index', array_merge(request()->except('show_deleted'), ['show_deleted' => '1']))); ?>" 
                style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 1rem 1.5rem; font-weight: 600; transition: all 0.2s; border-bottom: 4px solid <?php echo e($showDeleted ? '#f59e0b' : 'transparent'); ?>; background-color: <?php echo e($showDeleted ? '#f59e0b' : '#f9fafb'); ?>; color: <?php echo e($showDeleted ? 'white' : '#4b5563'); ?>;">
                <i data-lucide="archive" style="width: 20px; height: 20px; margin-right: 0.5rem;"></i>
                <span>Archived Facilities</span>
                <span style="margin-left: 0.5rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background-color: <?php echo e($showDeleted ? 'white' : '#e5e7eb'); ?>; color: <?php echo e($showDeleted ? '#f59e0b' : '#374151'); ?>;">
                    <?php echo e($archivedFacilitiesCount ?? 0); ?>

                </span>
            </a>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-lg">
        <form method="GET" action="<?php echo e(route('admin.facilities.index')); ?>" class="space-y-gr-md">
            <input type="hidden" name="show_deleted" value="<?php echo e($showDeleted ? '1' : ''); ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gr-md">
                
                <div>
                    <label for="search" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Search</label>
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="text" id="search" name="search" value="<?php echo e($search); ?>" 
                            placeholder="Search facilities..." 
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                    </div>
                </div>

                
                <div>
                    <label for="city_id" class="block text-small font-semibold text-lgu-headline mb-gr-xs">City</label>
                    <select id="city_id" name="city_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="">All Cities</option>
                        <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($city->id); ?>" <?php echo e($cityId == $city->id ? 'selected' : ''); ?>>
                                <?php echo e($city->city_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label for="facility_type" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Type</label>
                    <select id="facility_type" name="facility_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="">All Types</option>
                        <?php $__currentLoopData = $facilityTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e($facilityType == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label for="status" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Status</label>
                    <select id="status" name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="">All Statuses</option>
                        <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e($status == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-gr-sm">
                <button type="submit" class="inline-flex items-center px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                    <i data-lucide="filter" class="w-5 h-5 mr-gr-xs"></i>
                    Apply Filters
                </button>
                <a href="<?php echo e(route('admin.facilities.index', $showDeleted ? ['show_deleted' => '1'] : [])); ?>" class="inline-flex items-center px-gr-md py-gr-sm bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <i data-lucide="x" class="w-5 h-5 mr-gr-xs"></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-lgu-headline text-white">
                        <th class="px-gr-md py-gr-sm text-left text-small font-semibold uppercase tracking-wider">Facility</th>
                        <th class="px-gr-md py-gr-sm text-left text-small font-semibold uppercase tracking-wider">City</th>
                        <th class="px-gr-md py-gr-sm text-left text-small font-semibold uppercase tracking-wider">Type</th>
                        <th class="px-gr-md py-gr-sm text-left text-small font-semibold uppercase tracking-wider">Capacity</th>
                        <th class="px-gr-md py-gr-sm text-left text-small font-semibold uppercase tracking-wider">Rate Per Person</th>
                        <th class="px-gr-md py-gr-sm text-left text-small font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-gr-md py-gr-sm text-right text-small font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150 <?php echo e($facility->deleted_at ? 'opacity-60' : ''); ?>">
                            <td class="px-gr-md py-gr-sm">
                                <div class="flex items-center gap-gr-sm">
                                    <?php if($facility->image_path): ?>
                                        <img src="<?php echo e(Storage::url($facility->image_path)); ?>" alt="<?php echo e($facility->name); ?>" 
                                            class="w-12 h-12 object-cover rounded-lg">
                                    <?php else: ?>
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i data-lucide="building-2" class="w-6 h-6 text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-semibold text-lgu-headline"><?php echo e($facility->name); ?></p>
                                        <p class="text-small text-gray-600"><?php echo e(Str::limit($facility->address, 40)); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-gr-md py-gr-sm">
                                <span class="text-body text-lgu-paragraph"><?php echo e($facility->city_name ?? 'N/A'); ?></span>
                            </td>
                            <td class="px-gr-md py-gr-sm">
                                <span class="text-body text-lgu-paragraph"><?php echo e($facility->name); ?></span>
                            </td>
                            <td class="px-gr-md py-gr-sm">
                                <span class="text-body text-lgu-paragraph"><?php echo e(number_format($facility->capacity)); ?> pax</span>
                            </td>
                            <td class="px-gr-md py-gr-sm">
                                <span class="font-semibold text-lgu-headline">₱<?php echo e(number_format($facility->per_person_rate ?? 0, 2)); ?></span>
                            </td>
                            <td class="px-gr-md py-gr-sm">
                                <?php if($facility->deleted_at): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                        <i data-lucide="archive" class="w-3 h-3 mr-1"></i>
                                        Archived
                                    </span>
                                <?php elseif($facility->is_available): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                        Available
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>
                                        Unavailable
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-gr-md py-gr-sm text-right">
                                <div class="flex items-center justify-end gap-gr-xs">
                                    <?php if($facility->deleted_at): ?>
                                        <button onclick="restoreFacility(<?php echo e($facility->facility_id); ?>)" 
                                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-200"
                                            title="Restore">
                                            <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
                                        </button>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('admin.facilities.edit', $facility->facility_id)); ?>" 
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                            title="Edit">
                                            <i data-lucide="edit" class="w-5 h-5"></i>
                                        </a>
                                        <button onclick="archiveFacility(<?php echo e($facility->facility_id); ?>)" 
                                            class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors duration-200"
                                            title="Archive">
                                            <i data-lucide="archive" class="w-5 h-5"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-gr-md py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <?php if($showDeleted): ?>
                                        <i data-lucide="archive" class="w-16 h-16 text-amber-300 mb-gr-md"></i>
                                        <p class="text-body font-semibold text-gray-600 mb-gr-xs">No archived facilities</p>
                                        <p class="text-small text-gray-500">Archived facilities will appear here</p>
                                    <?php else: ?>
                                        <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mb-gr-md"></i>
                                        <p class="text-body font-semibold text-gray-600 mb-gr-xs">No facilities found</p>
                                        <p class="text-small text-gray-500 mb-gr-md">Try adjusting your search or filters</p>
                                        <a href="<?php echo e(route('admin.facilities.create')); ?>" class="inline-flex items-center px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                                            <i data-lucide="plus" class="w-5 h-5 mr-gr-xs"></i>
                                            Add Your First Facility
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($facilities->hasPages()): ?>
            <div class="px-gr-md py-gr-sm border-t border-gray-200">
                <?php echo e($facilities->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>


<form id="delete-form" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>


<form id="restore-form" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
function archiveFacility(id) {
    Swal.fire({
        title: 'Archive Facility?',
        text: "This will archive the facility and hide it from active listings. You can restore it anytime from the archived facilities list.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#475d5b',
        confirmButtonText: 'Yes, Archive',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-form');
            form.action = `/admin/facilities/${id}`;
            form.submit();
        }
    });
}

function restoreFacility(id) {
    Swal.fire({
        title: 'Restore Facility?',
        text: "This will restore the facility and make it available again.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#00473e',
        cancelButtonColor: '#475d5b',
        confirmButtonText: 'Yes, Restore',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('restore-form');
            form.action = `/admin/facilities/${id}/restore`;
            form.submit();
        }
    });
}

// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/facilities/index.blade.php ENDPATH**/ ?>