

<?php $__env->startSection('title', 'Pricing Management'); ?>
<?php $__env->startSection('page-title', 'Pricing Management'); ?>
<?php $__env->startSection('page-subtitle', 'Configure facility pricing and rates'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="container mx-auto px-gr-md py-gr-lg">
    
    <div class="flex items-center justify-between mb-gr-lg">
        <div>
            <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">Pricing Management</h1>
            <p class="text-body text-lgu-paragraph">Update facility rental rates and pricing</p>
        </div>
        <button onclick="openBulkUpdateModal()" class="inline-flex items-center px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
            <i data-lucide="percent" class="w-5 h-5 mr-gr-xs"></i>
            Bulk Price Adjustment
        </button>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-lg">
        <form method="GET" action="<?php echo e(route('admin.pricing.index')); ?>" class="space-y-gr-md">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-md">
                
                <div>
                    <label for="search" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Search Facility</label>
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
            </div>

            <div class="flex items-center gap-gr-sm">
                <button type="submit" class="inline-flex items-center px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                    <i data-lucide="filter" class="w-5 h-5 mr-gr-xs"></i>
                    Apply Filters
                </button>
                <a href="<?php echo e(route('admin.pricing.index')); ?>" class="inline-flex items-center px-gr-md py-gr-sm bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
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
                        <th class="px-gr-md py-gr-sm text-left text-small font-semibold uppercase tracking-wider">Rate Per Person</th>
                        <th class="px-gr-md py-gr-sm text-right text-small font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150" id="row-<?php echo e($facility->facility_id); ?>">
                            <td class="px-gr-md py-gr-sm">
                                <div>
                                    <p class="font-semibold text-lgu-headline"><?php echo e($facility->name); ?></p>
                                </div>
                            </td>
                            <td class="px-gr-md py-gr-sm">
                                <span class="text-body text-lgu-paragraph"><?php echo e($facility->city_name ?? 'N/A'); ?></span>
                            </td>
                            <td class="px-gr-md py-gr-sm">
                                <div class="view-mode" id="per-person-rate-view-<?php echo e($facility->facility_id); ?>">
                                    <p class="font-semibold text-lgu-headline">₱<?php echo e(number_format($facility->per_person_rate ?? 0, 2)); ?></p>
                                    <p class="text-xs text-gray-500">per person</p>
                                </div>
                                <div class="edit-mode hidden" id="per-person-rate-edit-<?php echo e($facility->facility_id); ?>">
                                    <input type="number" step="0.01" min="0" value="<?php echo e($facility->per_person_rate ?? 0); ?>" 
                                        class="w-full px-2 py-1 border border-gray-300 rounded text-small"
                                        id="per-person-rate-input-<?php echo e($facility->facility_id); ?>">
                                </div>
                            </td>
                            <td class="px-gr-md py-gr-sm text-right">
                                <div class="flex items-center justify-end gap-gr-xs">
                                    <button onclick="toggleEditMode(<?php echo e($facility->facility_id); ?>)" 
                                        id="edit-btn-<?php echo e($facility->facility_id); ?>"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                        title="Edit">
                                        <i data-lucide="edit" class="w-5 h-5"></i>
                                    </button>
                                    <button onclick="savePricing(<?php echo e($facility->facility_id); ?>)" 
                                        id="save-btn-<?php echo e($facility->facility_id); ?>"
                                        class="hidden p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-200"
                                        title="Save">
                                        <i data-lucide="check" class="w-5 h-5"></i>
                                    </button>
                                    <button onclick="cancelEdit(<?php echo e($facility->facility_id); ?>)" 
                                        id="cancel-btn-<?php echo e($facility->facility_id); ?>"
                                        class="hidden p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                        title="Cancel">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-gr-md py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mb-gr-md"></i>
                                    <p class="text-body font-semibold text-gray-600 mb-gr-xs">No facilities found</p>
                                    <p class="text-small text-gray-500">Try adjusting your search or filters</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div id="bulkUpdateModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-gr-lg">
            <div class="flex items-center justify-between mb-gr-md">
                <h3 class="text-h3 font-bold text-lgu-headline">Bulk Price Adjustment</h3>
                <button onclick="closeBulkUpdateModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form id="bulkUpdateForm" class="space-y-gr-md">
                
                <div>
                    <label class="block text-small font-semibold text-lgu-headline mb-gr-xs">Adjustment Type</label>
                    <select id="adjustment_type" name="adjustment_type" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight">
                        <option value="increase">Increase Prices</option>
                        <option value="decrease">Decrease Prices</option>
                    </select>
                </div>

                
                <div>
                    <label class="block text-small font-semibold text-lgu-headline mb-gr-xs">Percentage</label>
                    <input type="number" id="adjustment_percentage" name="adjustment_percentage" min="0" max="100" step="0.1" required
                        placeholder="e.g., 10"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight">
                    <p class="mt-1 text-xs text-gray-500">Enter percentage (e.g., 10 for 10%)</p>
                </div>

                
                <div>
                    <label class="block text-small font-semibold text-lgu-headline mb-gr-xs">Apply To</label>
                    <select id="apply_to" name="apply_to" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight"
                        onchange="toggleCitySelect()">
                        <option value="all">All Facilities</option>
                        <option value="city">Specific City</option>
                    </select>
                </div>

                
                <div id="citySelectDiv" class="hidden">
                    <label class="block text-small font-semibold text-lgu-headline mb-gr-xs">Select City</label>
                    <select id="city_id_bulk" name="city_id"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight">
                        <option value="">Select City</option>
                        <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($city->id); ?>"><?php echo e($city->city_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-gr-sm pt-gr-md border-t">
                    <button type="button" onclick="closeBulkUpdateModal()" 
                        class="px-gr-lg py-gr-sm bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-gr-lg py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                        Apply Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Store original values for cancel functionality
const originalValues = {};

function toggleEditMode(facilityId) {
    // Store original values
    originalValues[facilityId] = {
        hourly_rate: document.getElementById(`hourly-rate-input-${facilityId}`).value,
        per_person_rate: document.getElementById(`per-person-rate-input-${facilityId}`).value
    };

    // Hide view mode, show edit mode
    document.querySelectorAll(`#row-${facilityId} .view-mode`).forEach(el => el.classList.add('hidden'));
    document.querySelectorAll(`#row-${facilityId} .edit-mode`).forEach(el => el.classList.remove('hidden'));
    
    // Toggle buttons
    document.getElementById(`edit-btn-${facilityId}`).classList.add('hidden');
    document.getElementById(`save-btn-${facilityId}`).classList.remove('hidden');
    document.getElementById(`cancel-btn-${facilityId}`).classList.remove('hidden');
}

function cancelEdit(facilityId) {
    // Restore original values
    if (originalValues[facilityId]) {
        document.getElementById(`hourly-rate-input-${facilityId}`).value = originalValues[facilityId].hourly_rate;
        document.getElementById(`per-person-rate-input-${facilityId}`).value = originalValues[facilityId].per_person_rate;
    }

    // Hide edit mode, show view mode
    document.querySelectorAll(`#row-${facilityId} .edit-mode`).forEach(el => el.classList.add('hidden'));
    document.querySelectorAll(`#row-${facilityId} .view-mode`).forEach(el => el.classList.remove('hidden'));
    
    // Toggle buttons
    document.getElementById(`edit-btn-${facilityId}`).classList.remove('hidden');
    document.getElementById(`save-btn-${facilityId}`).classList.add('hidden');
    document.getElementById(`cancel-btn-${facilityId}`).classList.add('hidden');
}

async function savePricing(facilityId) {
    const perPersonRate = document.getElementById(`per-person-rate-input-${facilityId}`).value;

    try {
        const response = await fetch(`/admin/pricing/${facilityId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                per_person_rate: perPersonRate
            })
        });

        const data = await response.json();

        if (data.success) {
            // Update view mode with new values
            document.querySelector(`#per-person-rate-view-${facilityId} p:first-child`).textContent = `₱${parseFloat(perPersonRate).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;

            // Exit edit mode
            cancelEdit(facilityId);

            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to update pricing'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to update pricing. Please try again.'
        });
    }
}

function openBulkUpdateModal() {
    document.getElementById('bulkUpdateModal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function closeBulkUpdateModal() {
    document.getElementById('bulkUpdateModal').classList.add('hidden');
    document.getElementById('bulkUpdateForm').reset();
    document.getElementById('citySelectDiv').classList.add('hidden');
}

function toggleCitySelect() {
    const applyTo = document.getElementById('apply_to').value;
    const cityDiv = document.getElementById('citySelectDiv');
    
    if (applyTo === 'city') {
        cityDiv.classList.remove('hidden');
    } else {
        cityDiv.classList.add('hidden');
    }
}

document.getElementById('bulkUpdateForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = {
        adjustment_type: document.getElementById('adjustment_type').value,
        adjustment_percentage: document.getElementById('adjustment_percentage').value,
        apply_to: document.getElementById('apply_to').value,
        city_id: document.getElementById('city_id_bulk').value
    };

    try {
        const response = await fetch('/admin/pricing/bulk-update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            closeBulkUpdateModal();
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                confirmButtonColor: '#00473e'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to apply bulk update'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to apply bulk update. Please try again.'
        });
    }
});

// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/pricing/index.blade.php ENDPATH**/ ?>