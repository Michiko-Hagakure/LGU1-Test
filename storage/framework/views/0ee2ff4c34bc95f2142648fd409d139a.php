

<?php $__env->startSection('title', 'New Booking - Select Equipment'); ?>
<?php $__env->startSection('page-title', 'New Booking'); ?>
<?php $__env->startSection('page-subtitle', 'Step 2 of 3: Select Equipment & Add-ons'); ?>

<?php
use Illuminate\Support\Facades\Storage;
?>

<?php $__env->startSection('page-content'); ?>
<div class="max-w-6xl mx-auto">
    <!-- Progress Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </div>
                <span class="mt-2 text-sm font-medium text-green-600">Date & Time</span>
            </div>
            <div class="flex-1 h-1 bg-lgu-highlight"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 bg-lgu-button text-lgu-button-text rounded-full flex items-center justify-center font-bold">
                    2
                </div>
                <span class="mt-2 text-sm font-medium text-lgu-button">Equipment</span>
            </div>
            <div class="flex-1 h-1 bg-gray-300"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">
                    3
                </div>
                <span class="mt-2 text-sm text-gray-500">Review</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Equipment Selection -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Available Equipment & Add-ons</h2>
                <p class="text-sm text-gray-600 mb-6">Select additional equipment to enhance your event. You can skip this step if you don't need any equipment.</p>

                <form action="<?php echo e(route('citizen.booking.step3')); ?>" method="POST" id="equipmentForm">
                    <?php echo csrf_field(); ?>

                    <?php $__currentLoopData = $equipment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 capitalize border-b pb-2">
                                <?php echo e(str_replace('_', ' ', $category)); ?>

                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="bg-white border-2 border-gray-200 rounded-xl overflow-hidden hover:border-lgu-button hover:shadow-lg transition-all duration-300 group">
                                        <!-- Equipment Image -->
                                        <div class="relative h-48 bg-gray-100 overflow-hidden">
                                            <?php if($item->image_path && Storage::disk('public')->exists($item->image_path)): ?>
                                                <img src="<?php echo e(asset('storage/' . $item->image_path)); ?>" 
                                                     alt="<?php echo e($item->name); ?>" 
                                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center bg-lgu-button">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white opacity-50">
                                                        <rect width="18" height="18" x="3" y="3" rx="2"/>
                                                        <path d="M7 7h.01"/>
                                                        <path d="M3 16l4-4c.928-.893 2.072-.893 3 0l5 5"/>
                                                        <path d="m14 14 1-1c.928-.893 2.072-.893 3 0l3 3"/>
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Stock Badge -->
                                            <div class="absolute top-3 right-3">
                                                <span class="px-3 py-1 bg-white bg-opacity-90 backdrop-blur-sm rounded-full text-xs font-semibold text-gray-700 shadow">
                                                    <?php echo e($item->quantity_available_now); ?> available
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Equipment Details -->
                                        <div class="p-4">
                                            <h4 class="font-bold text-gray-900 text-lg mb-2"><?php echo e($item->name); ?></h4>
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2"><?php echo e($item->description); ?></p>
                                            
                                            <div class="flex items-center justify-between mb-3 pb-3 border-b">
                                                <div>
                                                    <span class="text-xs text-gray-500 block">Price per unit</span>
                                                    <span class="text-xl font-bold text-lgu-button">₱<?php echo e(number_format($item->price_per_unit, 2)); ?></span>
                                                </div>
                                                <div class="text-right">
                                                    <label for="equipment_<?php echo e($item->id); ?>" class="block text-xs font-medium text-gray-700 mb-1">Quantity</label>
                                                    <input type="number" 
                                                           name="equipment[<?php echo e($item->id); ?>]" 
                                                           id="equipment_<?php echo e($item->id); ?>"
                                                           min="0" 
                                                           max="<?php echo e($item->quantity_available_now); ?>" 
                                                           value="0"
                                                           data-price="<?php echo e($item->price_per_unit); ?>"
                                                           class="equipment-input w-20 px-3 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-lgu-button text-center font-semibold cursor-pointer">
                                                </div>
                                            </div>
                                            
                                            <!-- Item Total -->
                                            <div class="text-right">
                                                <span class="text-xs text-gray-500">Item Total:</span>
                                                <span class="text-sm font-bold text-gray-900 ml-1 item-total" data-item-id="<?php echo e($item->id); ?>">₱0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t">
                        <a href="<?php echo e(route('citizen.booking.create')); ?>" 
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left inline-block mr-2">
                                <path d="m12 19-7-7 7-7"/>
                                <path d="M19 12H5"/>
                            </svg>
                            Back
                        </a>
                        <button type="submit"
                                class="px-8 py-3 bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-highlight transition shadow cursor-pointer">
                            Next Step: Review & Submit
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right inline-block ml-2">
                                <path d="M5 12h14"/>
                                <path d="m12 5 7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Pricing Summary Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-lg rounded-lg p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Pricing Summary</h3>

                <div class="space-y-3 mb-4 pb-4 border-b">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Base Rate (3 hours)</span>
                        <span class="font-medium">₱<?php echo e(number_format($pricing['base_rate'], 2)); ?></span>
                    </div>

                    <?php if($pricing['extension_hours'] > 0): ?>
                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Time Extension (<?php echo e($pricing['extension_hours']); ?> <?php echo e($pricing['extension_hours'] == 1 ? 'hour' : 'hours'); ?>)</span>
                                <span class="font-medium">₱<?php echo e(number_format($pricing['extension_rate'], 2)); ?></span>
                            </div>
                            <?php if($pricing['pricing_model'] == 'per_person' && isset($pricing['extension_rate_per_block'])): ?>
                                <div class="text-xs text-gray-500 mt-1">
                                    ₱<?php echo e(number_format($pricing['extension_rate_per_block'], 2)); ?> per person per 2-hour block × <?php echo e(number_format($pricing['expected_attendees'])); ?> people × <?php echo e($pricing['extension_blocks']); ?> <?php echo e($pricing['extension_blocks'] == 1 ? 'block' : 'blocks'); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Equipment Total</span>
                        <span class="font-medium" id="equipmentTotal">₱0.00</span>
                    </div>
                </div>

                <div class="flex justify-between text-lg font-bold text-gray-900 mb-4">
                    <span>Subtotal</span>
                    <span id="subtotal">₱<?php echo e(number_format($pricing['subtotal'], 2)); ?></span>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-xs text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info inline-block mr-1">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 16v-4"/>
                            <path d="M12 8h.01"/>
                        </svg>
                        Discounts will be applied in the next step based on your residency and eligibility.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const equipmentInputs = document.querySelectorAll('.equipment-input');
    const equipmentTotalEl = document.getElementById('equipmentTotal');
    const subtotalEl = document.getElementById('subtotal');
    const basePricing = <?php echo e($pricing['subtotal'] - $pricing['equipment_total']); ?>;

    function updatePricing() {
        let equipmentTotal = 0;

        equipmentInputs.forEach(input => {
            const quantity = parseInt(input.value) || 0;
            const price = parseFloat(input.dataset.price) || 0;
            const itemTotal = quantity * price;
            equipmentTotal += itemTotal;
            
            // Update individual item total
            const itemId = input.id.replace('equipment_', '');
            const itemTotalEl = document.querySelector('.item-total[data-item-id="' + itemId + '"]');
            if (itemTotalEl) {
                itemTotalEl.textContent = '₱' + itemTotal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                
                // Highlight if selected
                if (quantity > 0) {
                    itemTotalEl.classList.add('text-lgu-button');
                    itemTotalEl.classList.remove('text-gray-900');
                } else {
                    itemTotalEl.classList.add('text-gray-900');
                    itemTotalEl.classList.remove('text-lgu-button');
                }
            }
        });

        const subtotal = basePricing + equipmentTotal;

        equipmentTotalEl.textContent = '₱' + equipmentTotal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        subtotalEl.textContent = '₱' + subtotal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    equipmentInputs.forEach(input => {
        input.addEventListener('input', updatePricing);
    });

    // Auto-save equipment selections to localStorage
    const form = document.getElementById('equipmentForm');
    
    // Restore saved equipment data
    function restoreEquipmentData() {
        const savedData = localStorage.getItem('booking_step2_data');
        if (savedData) {
            const data = JSON.parse(savedData);
            
            // Restore equipment quantities
            Object.keys(data).forEach(equipmentId => {
                const input = document.getElementById(`equipment_${equipmentId}`);
                if (input) {
                    input.value = data[equipmentId];
                }
            });
            
            // Update pricing after restoring
            updatePricing();
        }
    }
    
    // Save equipment selections
    function saveEquipmentData() {
        const data = {};
        equipmentInputs.forEach(input => {
            const equipmentId = input.id.replace('equipment_', '');
            const quantity = parseInt(input.value) || 0;
            if (quantity > 0) {
                data[equipmentId] = quantity;
            }
        });
        localStorage.setItem('booking_step2_data', JSON.stringify(data));
    }
    
    // Attach save listeners
    equipmentInputs.forEach(input => {
        input.addEventListener('input', saveEquipmentData);
        input.addEventListener('change', saveEquipmentData);
    });
    
    // Restore data on page load
    restoreEquipmentData();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.citizen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/citizen/booking/step2-select-equipment.blade.php ENDPATH**/ ?>