

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
            <form method="POST" action="<?php echo e(route('citizen.payment-methods.store')); ?>" novalidate>
                <?php echo csrf_field(); ?>

                <!-- Payment Type -->
                <div class="mb-gr-lg">
                    <label class="block text-body font-semibold text-gray-900 mb-gr-sm">Payment Type <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-gr-md" id="paymentTypeSelector">
                        <div class="payment-type-option relative cursor-pointer" data-value="gcash" onclick="selectPaymentType('gcash')">
                            <input type="radio" name="payment_type" value="gcash" <?php echo e(old('payment_type') == 'gcash' ? 'checked' : ''); ?> class="hidden" required>
                            <div class="payment-type-card border-2 border-gray-200 rounded-lg p-gr-md text-center hover:border-gray-400 transition-all <?php echo e(old('payment_type') == 'gcash' ? 'border-lgu-green bg-green-50' : ''); ?>">
                                <div class="w-12 h-12 bg-blue-100 rounded-full mx-auto mb-gr-sm flex items-center justify-center">
                                    <span class="text-h5 font-bold text-blue-600">G</span>
                                </div>
                                <p class="font-semibold text-gray-900">GCash</p>
                            </div>
                        </div>

                        <div class="payment-type-option relative cursor-pointer" data-value="paymaya" onclick="selectPaymentType('paymaya')">
                            <input type="radio" name="payment_type" value="paymaya" <?php echo e(old('payment_type') == 'paymaya' ? 'checked' : ''); ?> class="hidden" required>
                            <div class="payment-type-card border-2 border-gray-200 rounded-lg p-gr-md text-center hover:border-gray-400 transition-all <?php echo e(old('payment_type') == 'paymaya' ? 'border-lgu-green bg-green-50' : ''); ?>">
                                <div class="w-12 h-12 bg-green-100 rounded-full mx-auto mb-gr-sm flex items-center justify-center">
                                    <span class="text-h5 font-bold text-green-600">P</span>
                                </div>
                                <p class="font-semibold text-gray-900">PayMaya</p>
                            </div>
                        </div>

                        <div class="payment-type-option relative cursor-pointer" data-value="bank_transfer" onclick="selectPaymentType('bank_transfer')">
                            <input type="radio" name="payment_type" value="bank_transfer" <?php echo e(old('payment_type') == 'bank_transfer' ? 'checked' : ''); ?> class="hidden" required>
                            <div class="payment-type-card border-2 border-gray-200 rounded-lg p-gr-md text-center hover:border-gray-400 transition-all <?php echo e(old('payment_type') == 'bank_transfer' ? 'border-lgu-green bg-green-50' : ''); ?>">
                                <div class="w-12 h-12 bg-purple-100 rounded-full mx-auto mb-gr-sm flex items-center justify-center">
                                    <i data-lucide="building-2" class="w-6 h-6 text-purple-600"></i>
                                </div>
                                <p class="font-semibold text-gray-900">Bank Transfer</p>
                            </div>
                        </div>
                    </div>
                    <?php $__errorArgs = ['payment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
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
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
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
                           placeholder="09171234567 or Account Number" 
                           pattern="[0-9]+" 
                           inputmode="numeric"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                           required>
                    <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
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
                    <a href="<?php echo e(route('citizen.payment-methods.index')); ?>" class="btn-secondary flex-1 flex items-center justify-center">
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

// Payment type selection handler
function selectPaymentType(value) {
    // Remove selection from all options
    document.querySelectorAll('.payment-type-option').forEach(option => {
        const card = option.querySelector('.payment-type-card');
        const radio = option.querySelector('input[type="radio"]');
        card.classList.remove('border-lgu-green', 'bg-green-50', 'border-green-500', 'border-red-500');
        card.classList.add('border-gray-200');
        radio.checked = false;
    });
    
    // Select the clicked option
    const selectedOption = document.querySelector(`.payment-type-option[data-value="${value}"]`);
    if (selectedOption) {
        const card = selectedOption.querySelector('.payment-type-card');
        const radio = selectedOption.querySelector('input[type="radio"]');
        card.classList.remove('border-gray-200');
        card.classList.add('border-lgu-green', 'bg-green-50');
        radio.checked = true;
        
        // Clear error message if exists
        const errorMsg = document.getElementById('payment-type-error');
        if (errorMsg) errorMsg.remove();
    }
}

// Initialize selection on page load if there's a pre-selected value
document.addEventListener('DOMContentLoaded', function() {
    const checkedRadio = document.querySelector('input[name="payment_type"]:checked');
    if (checkedRadio) {
        selectPaymentType(checkedRadio.value);
    }
    
    // Add form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate payment type
        const paymentTypeSelected = document.querySelector('input[name="payment_type"]:checked');
        const paymentTypeContainer = document.getElementById('paymentTypeSelector');
        let existingError = document.getElementById('payment-type-error');
        
        if (!paymentTypeSelected) {
            isValid = false;
            // Add red border to all payment type cards
            document.querySelectorAll('.payment-type-card').forEach(card => {
                card.classList.add('border-red-500');
                card.classList.remove('border-gray-200');
            });
            // Show error message
            if (!existingError) {
                const errorDiv = document.createElement('p');
                errorDiv.id = 'payment-type-error';
                errorDiv.className = 'mt-1 text-sm text-red-600';
                errorDiv.textContent = 'Please select a payment type.';
                paymentTypeContainer.parentNode.appendChild(errorDiv);
            }
        } else if (existingError) {
            existingError.remove();
        }
        
        // Validate account name
        const accountName = document.getElementById('account_name');
        if (!accountName.value.trim()) {
            isValid = false;
            accountName.classList.add('border-red-500');
            showFieldError(accountName, 'Please enter account name.');
        } else {
            accountName.classList.remove('border-red-500');
            clearFieldError(accountName);
        }
        
        // Validate account number
        const accountNumber = document.getElementById('account_number');
        const accountValue = accountNumber.value.trim();
        if (!accountValue) {
            isValid = false;
            accountNumber.classList.add('border-red-500');
            showFieldError(accountNumber, 'Please enter account/mobile number.');
        } else if (!/^[0-9]+$/.test(accountValue)) {
            isValid = false;
            accountNumber.classList.add('border-red-500');
            showFieldError(accountNumber, 'Account number must contain only digits.');
        } else if (accountValue.length < 10) {
            isValid = false;
            accountNumber.classList.add('border-red-500');
            showFieldError(accountNumber, 'Account number must be at least 10 digits.');
        } else {
            accountNumber.classList.remove('border-red-500');
            clearFieldError(accountNumber);
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Clear errors on input
    document.querySelectorAll('input[type="text"]').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('border-red-500');
            clearFieldError(this);
        });
    });
});

function showFieldError(field, message) {
    let errorEl = field.parentNode.querySelector('.field-error');
    if (!errorEl) {
        errorEl = document.createElement('p');
        errorEl.className = 'field-error mt-1 text-sm text-red-600';
        field.parentNode.appendChild(errorEl);
    }
    errorEl.textContent = message;
}

function clearFieldError(field) {
    const errorEl = field.parentNode.querySelector('.field-error');
    if (errorEl) errorEl.remove();
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.citizen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/citizen/payment-methods/create.blade.php ENDPATH**/ ?>