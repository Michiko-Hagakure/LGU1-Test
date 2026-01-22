

<?php $__env->startSection('page-title', 'Add Staff Member'); ?>
<?php $__env->startSection('page-subtitle', 'Create a new staff account for booking verification'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-gr-lg border-2 border-lgu-stroke">
        
        <div class="flex items-center gap-gr-sm mb-gr-lg">
            <a href="<?php echo e(route('admin.staff.index')); ?>" class="p-gr-xs rounded-lg hover:bg-gray-100">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h2 class="text-h2 font-bold text-lgu-headline">Add Staff Member</h2>
                <p class="text-sm text-lgu-paragraph">Fill in the details to create a new staff account</p>
            </div>
        </div>

        
        <form method="POST" action="<?php echo e(route('admin.staff.store')); ?>" class="space-y-gr-md">
            <?php echo csrf_field(); ?>

            
            <div>
                <label for="name" class="block text-sm font-semibold mb-gr-xs text-lgu-headline">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" required
                       class="w-full px-gr-sm py-gr-xs rounded-lg border-2 border-lgu-stroke focus:border-lgu-green focus:ring-0
                       <?php $__errorArgs = ['name'];
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
                    <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label for="email" class="block text-sm font-semibold mb-gr-xs text-lgu-headline">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required
                       class="w-full px-gr-sm py-gr-xs rounded-lg border-2 border-lgu-stroke focus:border-lgu-green focus:ring-0
                       <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label for="phone" class="block text-sm font-semibold mb-gr-xs text-lgu-headline">
                    Phone Number <span class="text-red-500">*</span>
                </label>
                <input type="tel" name="phone" id="phone" value="<?php echo e(old('phone')); ?>" required
                       placeholder="+63 XXX XXX XXXX"
                       class="w-full px-gr-sm py-gr-xs rounded-lg border-2 border-lgu-stroke focus:border-lgu-green focus:ring-0
                       <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label for="city_id" class="block text-sm font-semibold mb-gr-xs text-lgu-headline">
                    City Assignment <span class="text-red-500">*</span>
                </label>
                <select name="city_id" id="city_id" required
                        class="w-full px-gr-sm py-gr-xs rounded-lg border-2 border-lgu-stroke focus:border-lgu-green focus:ring-0
                        <?php $__errorArgs = ['city_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">-- Select City --</option>
                    <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($city->id); ?>" <?php echo e(old('city_id') == $city->id ? 'selected' : ''); ?>>
                            <?php echo e($city->city_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['city_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <p class="mt-1 text-sm text-lgu-paragraph">Staff will verify bookings for this city</p>
            </div>

            
            <div>
                <label for="password" class="block text-sm font-semibold mb-gr-xs text-lgu-headline">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password" id="password" required
                       class="w-full px-gr-sm py-gr-xs rounded-lg border-2 border-lgu-stroke focus:border-lgu-green focus:ring-0
                       <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <p class="mt-1 text-sm text-lgu-paragraph">Minimum 8 characters</p>
            </div>

            
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold mb-gr-xs text-lgu-headline">
                    Confirm Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-gr-sm py-gr-xs rounded-lg border-2 border-lgu-stroke focus:border-lgu-green focus:ring-0">
            </div>

            
            <div class="flex gap-gr-sm pt-gr-sm border-t-2 border-lgu-stroke">
                <button type="submit" class="flex-1 btn-primary">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                    Add Staff Member
                </button>
                <a href="<?php echo e(route('admin.staff.index')); ?>" class="flex-1 btn-secondary">
                    <i data-lucide="x" class="w-5 h-5"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
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


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/admin/staff/create.blade.php ENDPATH**/ ?>