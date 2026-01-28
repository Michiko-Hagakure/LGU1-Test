

<?php $__env->startSection('title', 'Edit Facility'); ?>
<?php $__env->startSection('page-title', 'Edit Facility'); ?>
<?php $__env->startSection('page-subtitle', 'Update facility information'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="container mx-auto px-gr-md py-gr-lg max-w-4xl">
    
    <div class="mb-gr-md">
        <a href="<?php echo e(route('admin.facilities.index')); ?>" class="inline-flex items-center text-lgu-paragraph hover:text-lgu-headline transition-colors duration-200">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-gr-xs"></i>
            Back to Facilities
        </a>
    </div>

    
    <div class="mb-gr-lg">
        <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">Edit Facility</h1>
        <p class="text-body text-lgu-paragraph">Update facility information</p>
    </div>

    
    <?php if($errors->any()): ?>
        <div class="bg-red-50 border-2 border-red-200 rounded-lg p-gr-md mb-gr-lg">
            <div class="flex items-start gap-gr-sm">
                <i data-lucide="alert-circle" class="w-6 h-6 text-red-600 flex-shrink-0 mt-1"></i>
                <div class="flex-1">
                    <p class="text-body font-semibold text-red-900 mb-2">Please fix the following errors:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="text-small text-red-700"><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
    <form action="<?php echo e(route('admin.facilities.update', $facility->facility_id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-md">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center">
                <i data-lucide="info" class="w-6 h-6 mr-gr-sm"></i>
                Basic Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-md">
                
                <div class="md:col-span-2">
                    <label for="name" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Facility Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="<?php echo e(old('name', $facility->name)); ?>" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="city_id" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        City <span class="text-red-500">*</span>
                    </label>
                    <select id="city_id" name="city_id" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent <?php $__errorArgs = ['city_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Select City</option>
                        <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($city->id); ?>" <?php echo e(old('city_id', $facility->lgu_city_id) == $city->id ? 'selected' : ''); ?>>
                                <?php echo e($city->city_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['city_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="is_available" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Availability
                    </label>
                    <select id="is_available" name="is_available"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="1" <?php echo e(old('is_available', $facility->is_available) == 1 ? 'selected' : ''); ?>>Available</option>
                        <option value="0" <?php echo e(old('is_available', $facility->is_available) == 0 ? 'selected' : ''); ?>>Not Available</option>
                    </select>
                </div>

                
                <div>
                    <label for="capacity" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Capacity (persons) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="capacity" name="capacity" value="<?php echo e(old('capacity', $facility->capacity)); ?>" min="1" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description', $facility->description)); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-md">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center">
                <i data-lucide="map-pin" class="w-6 h-6 mr-gr-sm"></i>
                Location Information
            </h2>

            <div class="space-y-gr-md">
                
                <div>
                    <label for="address" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Address <span class="text-red-500">*</span>
                    </label>
                    <textarea id="address" name="address" rows="3" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('address', $facility->address)); ?></textarea>
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                            </div>
        </div>

        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-md">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center">
                <i data-lucide="peso-sign" class="w-6 h-6 mr-gr-sm"></i>
                Pricing Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-md">
                
                <div>
                    <label for="per_person_rate" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Per Person Rate <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-lgu-paragraph font-semibold">₱</span>
                        <input type="number" id="per_person_rate" name="per_person_rate" value="<?php echo e(old('per_person_rate', $facility->per_person_rate)); ?>" min="0" step="0.01" required
                            class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent <?php $__errorArgs = ['per_person_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    </div>
                    <?php $__errorArgs = ['per_person_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-md">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center">
                <i data-lucide="images" class="w-6 h-6 mr-gr-sm"></i>
                Facility Photos
            </h2>

            <div class="space-y-gr-md">
                
                
                <div>
                    <label class="block text-small font-semibold text-lgu-headline mb-gr-xs">Primary Photo</label>
                    <?php if($facility->image_path): ?>
                        <div class="flex items-start gap-3" id="primary-photo-container">
                            <div class="relative inline-block">
                                <img src="<?php echo e(Storage::url($facility->image_path)); ?>" alt="<?php echo e($facility->name); ?>" 
                                    class="w-48 h-32 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition"
                                    onclick="showFullImage('<?php echo e(Storage::url($facility->image_path)); ?>', '<?php echo e($facility->name); ?>')">
                                <span class="absolute top-2 left-2 bg-lgu-green text-white text-xs px-2 py-1 rounded">Primary</span>
                            </div>
                            <button type="button" 
                                onclick="deletePrimaryImage(<?php echo e($facility->facility_id); ?>)"
                                class="flex items-center gap-1 px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                Remove
                            </button>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">No primary photo uploaded</p>
                    <?php endif; ?>
                </div>

                
                <div>
                    <label for="image_path" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        <?php echo e($facility->image_path ? 'Replace Primary Photo' : 'Upload Primary Photo'); ?>

                    </label>
                    <input type="file" id="image_path" name="image_path" accept="image/jpeg,image/png,image/jpg"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent <?php $__errorArgs = ['image_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <p class="mt-1 text-xs text-gray-500">Accepted formats: JPG, JPEG, PNG. Max size: 2MB</p>
                    <?php $__errorArgs = ['image_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="border-t pt-gr-md">
                    <label class="block text-small font-semibold text-lgu-headline mb-gr-xs">Additional Photos</label>
                    
                    <?php if($facilityImages->count() > 0): ?>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <?php $__currentLoopData = $facilityImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="relative group" id="image-<?php echo e($image->id); ?>">
                                    <img src="<?php echo e(Storage::url($image->image_path)); ?>" alt="Facility image" 
                                        class="w-full h-24 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition"
                                        onclick="showFullImage('<?php echo e(Storage::url($image->image_path)); ?>', 'Facility Image')">
                                    <button type="button" 
                                        onclick="deleteImage(<?php echo e($facility->facility_id); ?>, <?php echo e($image->id); ?>)"
                                        class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                                        title="Remove image">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm mb-4">No additional photos uploaded</p>
                    <?php endif; ?>

                    
                    <div>
                        <label for="additional_images" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                            Add More Photos
                        </label>
                        <input type="file" id="additional_images" name="additional_images[]" accept="image/jpeg,image/png,image/jpg" multiple
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">You can select multiple images. Accepted formats: JPG, JPEG, PNG. Max size: 2MB each</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="flex items-center justify-end gap-gr-sm">
            <a href="<?php echo e(route('admin.facilities.index')); ?>" class="inline-flex items-center px-gr-lg py-gr-sm bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-gr-lg py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                <i data-lucide="save" class="w-5 h-5 mr-gr-xs"></i>
                Update Facility
            </button>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}

// Show full image in SweetAlert2 modal
function showFullImage(imageUrl, title) {
    Swal.fire({
        title: title,
        imageUrl: imageUrl,
        imageAlt: title,
        showCloseButton: true,
        showConfirmButton: false,
        width: 'auto',
        customClass: {
            image: 'max-h-[80vh] object-contain rounded-lg'
        }
    });
}

// Delete facility image
function deleteImage(facilityId, imageId) {
    Swal.fire({
        title: 'Remove Image?',
        text: 'This image will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, remove it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/facilities/${facilityId}/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the image element from DOM
                    const imageElement = document.getElementById(`image-${imageId}`);
                    if (imageElement) {
                        imageElement.remove();
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Image has been removed.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to delete image.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to delete image. Please try again.'
                });
            });
        }
    });
}

// Delete primary image
function deletePrimaryImage(facilityId) {
    Swal.fire({
        title: 'Remove Primary Photo?',
        text: 'This photo will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, remove it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/facilities/${facilityId}/primary-image`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Replace photo container with "no photo" message
                    const container = document.getElementById('primary-photo-container');
                    if (container) {
                        container.outerHTML = '<p class="text-gray-500 text-sm">No primary photo uploaded</p>';
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Primary photo has been removed.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to delete photo.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to delete photo. Please try again.'
                });
            });
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/facilities/edit.blade.php ENDPATH**/ ?>