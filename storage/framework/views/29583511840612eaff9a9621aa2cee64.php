

<?php $__env->startSection('title', 'Edit Equipment'); ?>
<?php $__env->startSection('page-title', 'Edit Equipment'); ?>
<?php $__env->startSection('page-subtitle', 'Update equipment information'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="container mx-auto px-gr-md py-gr-lg max-w-3xl">
    
    <div class="mb-gr-md">
        <a href="<?php echo e(route('admin.equipment.index')); ?>" class="inline-flex items-center text-lgu-paragraph hover:text-lgu-headline transition-colors duration-200">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-gr-xs"></i>
            Back to Equipment
        </a>
    </div>

    
    <div class="mb-gr-lg">
        <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">Edit Equipment</h1>
        <p class="text-body text-lgu-paragraph">Update equipment information</p>
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

    
    <form action="<?php echo e(route('admin.equipment.update', $equipment->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-md">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center">
                <i data-lucide="package" class="w-6 h-6 mr-gr-sm"></i>
                Equipment Information
            </h2>

            <div class="space-y-gr-md">
                
                <div>
                    <label for="name" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Equipment Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="<?php echo e(old('name', $equipment->name)); ?>" required
                        placeholder="e.g., Monobloc Chair, Round Table, Sound System"
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
                    <label for="category" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select id="category" name="category" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Select Category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(old('category', $equipment->category) == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['category'];
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
                    <label for="description" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="3"
                        placeholder="Describe the equipment, its condition, and specifications..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description', $equipment->description)); ?></textarea>
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
                <i data-lucide="peso-sign" class="w-6 h-6 mr-gr-sm"></i>
                Pricing & Inventory
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-md">
                
                <div>
                    <label for="price_per_unit" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Rental Price Per Unit <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-lgu-paragraph font-semibold">₱</span>
                        <input type="number" id="price_per_unit" name="price_per_unit" value="<?php echo e(old('price_per_unit', $equipment->price_per_unit)); ?>" min="0" step="0.01" required
                            class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent <?php $__errorArgs = ['price_per_unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Price per unit for the entire booking duration</p>
                    <?php $__errorArgs = ['price_per_unit'];
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
                    <label for="quantity_available" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Quantity Available <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="quantity_available" name="quantity_available" value="<?php echo e(old('quantity_available', $equipment->quantity_available)); ?>" min="0" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent <?php $__errorArgs = ['quantity_available'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <p class="mt-1 text-xs text-gray-500">Total units available for rent</p>
                    <?php $__errorArgs = ['quantity_available'];
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
                    <label class="flex items-center">
                        <input type="checkbox" name="is_available" value="1" <?php echo e(old('is_available', $equipment->is_available) ? 'checked' : ''); ?>

                            class="rounded border-gray-300 text-lgu-button focus:ring-lgu-highlight">
                        <span class="ml-2 text-small font-semibold text-lgu-headline">
                            Available for Booking
                        </span>
                    </label>
                    <p class="ml-6 mt-1 text-xs text-gray-500">Uncheck to temporarily disable this equipment from bookings</p>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-md">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center">
                <i data-lucide="image" class="w-6 h-6 mr-gr-sm"></i>
                Equipment Photo
            </h2>

            <div class="space-y-gr-md">
                
                <?php if($equipment->image_path): ?>
                    <div>
                        <label class="block text-small font-semibold text-lgu-headline mb-gr-xs">Current Photo</label>
                        <img src="<?php echo e(Storage::url($equipment->image_path)); ?>" alt="<?php echo e($equipment->name); ?>" 
                            class="w-48 h-32 object-cover rounded-lg border border-gray-200">
                    </div>
                <?php endif; ?>

                
                <div>
                    <label for="image_path" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        <?php echo e($equipment->image_path ? 'Replace Photo' : 'Upload Photo'); ?>

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
            </div>
        </div>

        
        <div class="flex items-center justify-end gap-gr-sm">
            <a href="<?php echo e(route('admin.equipment.index')); ?>" class="inline-flex items-center px-gr-lg py-gr-sm bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-gr-lg py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                <i data-lucide="save" class="w-5 h-5 mr-gr-xs"></i>
                Update Equipment
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
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/admin/equipment/edit.blade.php ENDPATH**/ ?>