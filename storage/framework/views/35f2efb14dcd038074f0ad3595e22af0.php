

<?php $__env->startSection('page-title', 'Add Payment Method'); ?>
<?php $__env->startSection('page-subtitle', 'Save a new payment method for faster checkout'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="pb-gr-2xl">
    <!-- Back Button -->
    <div class="mb-gr-md">
        <a href="<?php echo e(route('citizen.payment-methods.index')); ?>" class="inline-flex items-center text-small font-medium text-gray-600 hover:text-gray-900">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-gr-xs"></i>
            Back to Payment Methods
        </a>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
            <form method="POST" action="<?php echo e(route('citizen.payment-methods.store')); ?>">
                <?php echo csrf_field(); ?>

                <!-- Payment Type -->
                <div class="mb-gr-lg">
                    <label class="block text-body font-semibold text-gray-900 mb-gr-sm">Payment Type <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-gr-md">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_type" value="gcash" <?php echo e(old('payment_type') == 'gcash' ? 'checked' : ''); ?> class="sr-only peer" required>
                            <div class="border-2 border-gray-200 rounded-lg p-gr-md text-center peer-checked:border-lgu-green peer-checked:bg-lgu-green peer-checked:bg-opacity-5 transition-all">
                                <div class="w-12 h-12 bg-blue-100 rounded-full mx-auto mb-gr-sm flex items-center justify-center">
                                    <span class="text-h5 font-bold text-blue-600">G</span>
                                </div>
                                <p class="font-semibold text-gray-900">GCash</p>
                            </div>
                        </label>

                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_type" value="paymaya" <?php echo e(old('payment_type') == 'paymaya' ? 'checked' : ''); ?> class="sr-only peer" required>
                            <div class="border-2 border-gray-200 rounded-lg p-gr-md text-center peer-checked:border-lgu-green peer-checked:bg-lgu-green peer-checked:bg-opacity-5 transition-all">
                                <div class="w-12 h-12 bg-green-100 rounded-full mx-auto mb-gr-sm flex items-center justify-center">
                                    <span class="text-h5 font-bold text-green-600">P</span>
                                </div>
                                <p class="font-semibold text-gray-900">PayMaya</p>
                            </div>
                        </label>

                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_type" value="bank_transfer" <?php echo e(old('payment_type') == 'bank_transfer' ? 'checked' : ''); ?> class="sr-only peer" required>
                            <div class="border-2 border-gray-200 rounded-lg p-gr-md text-center peer-checked:border-lgu-green peer-checked:bg-lgu-green peer-checked:bg-opacity-5 transition-all">
                                <div class="w-12 h-12 bg-purple-100 rounded-full mx-auto mb-gr-sm flex items-center justify-center">
                                    <i data-lucide="building-2" class="w-6 h-6 text-purple-600"></i>
                                </div>
                                <p class="font-semibold text-gray-900">Bank Transfer</p>
                            </div>
                        </label>
                    </div>
                    <?php $__errorArgs = ['payment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-caption text-red-600 mt-gr-xs"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Account Name -->
                <div class="mb-gr-md">
                    <label for="account_name" class="block text-body font-semibold text-gray-900 mb-gr-sm">Account Name <span class="text-red-500">*</span></label>
                    <input type="text" id="account_name" name="account_name" value="<?php echo e(old('account_name')); ?>" 
                           class="input-field <?php $__errorArgs = ['account_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           placeholder="Juan Dela Cruz" required>
                    <?php $__errorArgs = ['account_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-caption text-red-600 mt-gr-xs"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Account Number -->
                <div class="mb-gr-md">
                    <label for="account_number" class="block text-body font-semibold text-gray-900 mb-gr-sm">Account/Mobile Number <span class="text-red-500">*</span></label>
                    <input type="text" id="account_number" name="account_number" value="<?php echo e(old('account_number')); ?>" 
                           class="input-field <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           placeholder="09171234567 or Account Number" required>
                    <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-caption text-red-600 mt-gr-xs"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Set as Default -->
                <div class="mb-gr-lg">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_default" value="1" <?php echo e(old('is_default') ? 'checked' : ''); ?> class="w-4 h-4 text-lgu-green border-gray-300 rounded focus:ring-lgu-green">
                        <span class="ml-gr-xs text-body text-gray-900">Set as default payment method</span>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-gr-sm pt-gr-md border-t border-gray-200">
                    <button type="submit" class="btn-primary flex-1">
                        <i data-lucide="plus" class="w-4 h-4 mr-gr-xs"></i>
                        Add Payment Method
                    </button>
                    <a href="<?php echo e(route('citizen.payment-methods.index')); ?>" class="btn-secondary flex-1 text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Initialize Lucide icons
lucide.createIcons();
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.citizen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/citizen/payment-methods/create.blade.php ENDPATH**/ ?>