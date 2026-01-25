

<?php $__env->startSection('title', $facility->name); ?>
<?php $__env->startSection('page-title', $facility->name); ?>
<?php $__env->startSection('page-subtitle', 'Facility Details'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="<?php echo e(route('citizen.browse-facilities')); ?>" 
           class="inline-flex items-center text-lgu-button hover:text-lgu-highlight font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
            </svg>
            Back to Browse Facilities
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Facility Image Gallery / Virtual Tour -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="relative">
                    <?php if($facility->image_path): ?>
                        <img id="mainImage" src="<?php echo e(asset('storage/' . $facility->image_path)); ?>" 
                             alt="<?php echo e($facility->name); ?>" 
                             class="w-full h-96 object-cover">
                        
                        <!-- Virtual Tour Badge -->
                        <div class="absolute top-4 left-4 bg-lgu-button text-lgu-button-text px-4 py-2 rounded-lg shadow-lg font-semibold text-sm flex items-center gap-2">
                            <i data-lucide="camera" class="w-4 h-4"></i>
                            <span>Virtual Tour</span>
                        </div>
                        
                        <!-- Navigation Controls -->
                        <button onclick="previousImage()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 rounded-full p-3 shadow-lg transition-all">
                            <i data-lucide="chevron-left" class="w-6 h-6"></i>
                        </button>
                        <button onclick="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 rounded-full p-3 shadow-lg transition-all">
                            <i data-lucide="chevron-right" class="w-6 h-6"></i>
                        </button>
                        
                        <!-- Fullscreen Button -->
                        <button onclick="openFullscreen()" class="absolute top-4 right-4 bg-white/90 hover:bg-white text-gray-800 rounded-lg px-3 py-2 shadow-lg transition-all flex items-center gap-2">
                            <i data-lucide="maximize" class="w-4 h-4"></i>
                            <span class="text-sm font-semibold">Fullscreen</span>
                        </button>
                    <?php else: ?>
                        <div class="w-full h-96 bg-lgu-bg flex items-center justify-center">
                            <div class="text-center">
                                <i data-lucide="image-off" class="w-32 h-32 text-gray-300 mx-auto mb-4"></i>
                                <p class="text-gray-500 font-medium">No photos available</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Photo Thumbnails -->
                <?php if($facility->image_path): ?>
                <div class="bg-gray-50 p-4">
                    <div class="flex gap-2 overflow-x-auto">
                        <button onclick="changeImage(0)" class="thumbnail-btn flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 border-lgu-button transition-all">
                            <img src="<?php echo e(asset('storage/' . $facility->image_path)); ?>" alt="View 1" class="w-full h-full object-cover">
                        </button>
                        <!-- Additional photos would go here if available -->
                        <?php for($i = 1; $i < 4; $i++): ?>
                        <button onclick="changeImage(<?php echo e($i); ?>)" class="thumbnail-btn flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 border-gray-300 hover:border-lgu-highlight transition-all opacity-50">
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i data-lucide="camera" class="w-6 h-6 text-gray-400"></i>
                            </div>
                        </button>
                        <?php endfor; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <?php $__env->startPush('scripts'); ?>
            <script>
            // Virtual Tour / Photo Gallery
            let currentImageIndex = 0;
            const images = ['<?php echo e(asset('storage/' . $facility->image_path)); ?>']; // In production, this would be an array of all facility photos

            function changeImage(index) {
                currentImageIndex = index;
                const mainImage = document.getElementById('mainImage');
                if (mainImage && images[index]) {
                    mainImage.src = images[index];
                    
                    // Update thumbnail borders
                    document.querySelectorAll('.thumbnail-btn').forEach((btn, i) => {
                        if (i === index) {
                            btn.classList.remove('border-gray-300');
                            btn.classList.add('border-lgu-button');
                            btn.classList.remove('opacity-50');
                        } else {
                            btn.classList.add('border-gray-300');
                            btn.classList.remove('border-lgu-button');
                            btn.classList.add('opacity-50');
                        }
                    });
                }
            }

            function previousImage() {
                currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
                changeImage(currentImageIndex);
            }

            function nextImage() {
                currentImageIndex = (currentImageIndex + 1) % images.length;
                changeImage(currentImageIndex);
            }

            function openFullscreen() {
                const mainImage = document.getElementById('mainImage');
                if (mainImage) {
                    if (mainImage.requestFullscreen) {
                        mainImage.requestFullscreen();
                    } else if (mainImage.webkitRequestFullscreen) {
                        mainImage.webkitRequestFullscreen();
                    } else if (mainImage.msRequestFullscreen) {
                        mainImage.msRequestFullscreen();
                    }
                }
            }
            </script>
            <?php $__env->stopPush(); ?>

            <!-- Facility Description -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Facility</h2>
                <p class="text-gray-700 leading-relaxed"><?php echo e($facility->description); ?></p>
            </div>

            <!-- Facility Details -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Facility Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lgu-button mr-3 mt-1 flex-shrink-0">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Location</p>
                            <p class="text-base font-semibold text-gray-900"><?php echo e($facility->address); ?></p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lgu-button mr-3 mt-1 flex-shrink-0">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Capacity</p>
                            <p class="text-base font-semibold text-gray-900"><?php echo e($facility->capacity); ?> people</p>
                        </div>
                    </div>

                    <?php if($facility->per_person_rate): ?>
                        <div class="flex items-start">
                            <div class="w-5 h-5 flex items-center justify-center text-lgu-button font-bold text-lg mr-3 mt-1 flex-shrink-0">
                                ₱
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Per Person Rate</p>
                                <p class="text-base font-semibold text-gray-900">₱<?php echo e(number_format($facility->per_person_rate, 2)); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($facility->hourly_rate): ?>
                        <div class="flex items-start">
                            <div class="w-5 h-5 flex items-center justify-center text-lgu-button font-bold text-lg mr-3 mt-1 flex-shrink-0">
                                ₱
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Hourly Rate</p>
                                <p class="text-base font-semibold text-gray-900">₱<?php echo e(number_format($facility->hourly_rate, 2)); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($facility->deposit_amount): ?>
                        <div class="flex items-start">
                            <div class="w-5 h-5 flex items-center justify-center text-lgu-button font-bold text-lg mr-3 mt-1 flex-shrink-0">
                                ₱
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Deposit Required</p>
                                <p class="text-base font-semibold text-gray-900">₱<?php echo e(number_format($facility->deposit_amount, 2)); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Availability Notice -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Check Availability</h2>
                <p class="text-gray-600 mb-4">View the facility calendar to see available dates and times for booking.</p>
                <a href="<?php echo e(route('citizen.facility-calendar', ['facility_id' => $facility->facility_id])); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-highlight transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                    </svg>
                    View Calendar
                </a>
            </div>
        </div>

        <!-- Sidebar: Booking Information -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6 sticky top-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Book This Facility</h3>
                
                <!-- Pricing Summary -->
                <div class="space-y-3 mb-6">
                    <?php if($facility->per_person_rate): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Per Person:</span>
                            <span class="font-bold text-gray-900">₱<?php echo e(number_format($facility->per_person_rate, 2)); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Payment Type:</span>
                            <span class="text-gray-900">Per attendee</span>
                        </div>
                    <?php elseif($facility->hourly_rate): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Hourly Rate:</span>
                            <span class="font-bold text-gray-900">₱<?php echo e(number_format($facility->hourly_rate, 2)); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Min Duration:</span>
                            <span class="text-gray-900"><?php echo e($facility->min_booking_hours); ?> hours</span>
                        </div>
                    <?php endif; ?>
                    <?php if($facility->deposit_amount): ?>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Deposit:</span>
                                <span class="text-gray-900">₱<?php echo e(number_format($facility->deposit_amount, 2)); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Discount Information -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="text-sm font-bold text-blue-900 mb-2">Available Discounts</h4>
                    <ul class="text-xs text-blue-800 space-y-1">
                        <li>• Senior Citizen: 20% off (with valid ID)</li>
                        <li>• PWD: 20% off (with valid ID)</li>
                        <li>• Student: 10% off (with valid school ID)</li>
                    </ul>
                </div>

                <!-- Booking Requirements -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h4 class="text-sm font-bold text-yellow-900 mb-2">Requirements</h4>
                    <ul class="text-xs text-yellow-800 space-y-1">
                        <li>• Book 7 days in advance</li>
                        <li>• Valid government ID required</li>
                        <li>• Staff approval needed</li>
                    </ul>
                </div>

                <!-- Book Now Button -->
                <a href="<?php echo e(route('citizen.booking.create', $facility->facility_id)); ?>" 
                   class="block w-full px-6 py-3 bg-lgu-button text-lgu-button-text text-center font-semibold rounded-lg hover:bg-lgu-highlight transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block mr-2">
                        <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/>
                    </svg>
                    Book Now
                </a>

                <a href="<?php echo e(route('citizen.facility-calendar', ['facility_id' => $facility->facility_id])); ?>" 
                   class="block w-full mt-3 px-6 py-3 bg-gray-200 text-gray-700 text-center font-semibold rounded-lg hover:bg-gray-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block mr-2">
                        <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                    </svg>
                    Check Availability
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.citizen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/citizen/facility-details.blade.php ENDPATH**/ ?>