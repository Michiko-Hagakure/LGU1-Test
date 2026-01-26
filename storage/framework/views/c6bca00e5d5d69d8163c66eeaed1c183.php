

<?php $__env->startSection('title', 'Facility Calendar'); ?>
<?php $__env->startSection('page-title', 'Facility Calendar'); ?>
<?php $__env->startSection('page-subtitle', 'View available dates and existing reservations'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-6">
    <!-- Calendar Header with Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Month/Year Navigation -->
            <div class="flex items-center space-x-4">
                <a href="<?php echo e(route('citizen.facility-calendar', ['month' => $prevMonth->month, 'year' => $prevMonth->year, 'facility_id' => $selectedFacilityId])); ?>" 
                   class="p-2 text-gray-600 hover:text-lgu-headline hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <h2 class="text-2xl font-bold text-gray-900">
                    <?php echo e($currentDate->format('F Y')); ?>

                </h2>
                
                <a href="<?php echo e(route('citizen.facility-calendar', ['month' => $nextMonth->month, 'year' => $nextMonth->year, 'facility_id' => $selectedFacilityId])); ?>" 
                   class="p-2 text-gray-600 hover:text-lgu-headline hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
                
                <a href="<?php echo e(route('citizen.facility-calendar')); ?>" 
                   class="px-4 py-2 text-sm text-lgu-button-text bg-lgu-button hover:bg-lgu-highlight rounded-lg transition font-medium">
                    Today
                </a>
            </div>
            
            <!-- Facility Filter -->
            <div class="flex items-center space-x-3">
                <label for="facility_filter" class="text-sm font-medium text-gray-700">Filter by Facility:</label>
                <select id="facility_filter" 
                        onchange="window.location.href='<?php echo e(route('citizen.facility-calendar', ['month' => $currentDate->month, 'year' => $currentDate->year])); ?>&facility_id=' + this.value"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-lgu-button focus:border-lgu-button">
                    <option value="">All Facilities</option>
                    <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($facility->facility_id); ?>" <?php echo e($selectedFacilityId == $facility->facility_id ? 'selected' : ''); ?>>
                            <?php echo e($facility->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        
        <!-- Legend -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Status Legend:</h3>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-green-500 rounded"></div>
                    <span class="text-sm text-gray-600">Available</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-blue-500 rounded"></div>
                    <span class="text-sm text-gray-600">Reserved (Paid)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                    <span class="text-sm text-gray-600">Tentative (Unpaid)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-red-500 rounded"></div>
                    <span class="text-sm text-gray-600">Confirmed</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-purple-500 rounded"></div>
                    <span class="text-sm text-gray-600">Under Verification</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Day Headers -->
        <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
            <div class="p-3 text-center text-sm font-semibold text-gray-700">Sunday</div>
            <div class="p-3 text-center text-sm font-semibold text-gray-700">Monday</div>
            <div class="p-3 text-center text-sm font-semibold text-gray-700">Tuesday</div>
            <div class="p-3 text-center text-sm font-semibold text-gray-700">Wednesday</div>
            <div class="p-3 text-center text-sm font-semibold text-gray-700">Thursday</div>
            <div class="p-3 text-center text-sm font-semibold text-gray-700">Friday</div>
            <div class="p-3 text-center text-sm font-semibold text-gray-700">Saturday</div>
        </div>
        
        <!-- Calendar Days -->
        <div class="grid grid-cols-7">
            <?php $__currentLoopData = $calendarData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($day['date']): ?>
                    <div class="min-h-[120px] p-2 border border-gray-200 <?php echo e($day['isToday'] ? 'bg-blue-50' : ($day['isPast'] ? 'bg-gray-50' : 'bg-white')); ?> hover:bg-gray-50 transition-colors cursor-pointer"
                         onclick="showDayDetails('<?php echo e($day['dateString']); ?>')">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-sm font-semibold <?php echo e($day['isToday'] ? 'text-blue-600' : ($day['isPast'] ? 'text-gray-400' : 'text-gray-900')); ?>">
                                <?php echo e($day['date']->format('j')); ?>

                            </span>
                            <?php if($day['hasBookings']): ?>
                                <span class="px-2 py-1 text-xs font-bold bg-lgu-button text-lgu-button-text rounded-full">
                                    <?php echo e($day['bookingCount']); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if($day['hasBookings']): ?>
                            <div class="space-y-1">
                                <?php $__currentLoopData = $day['bookings']->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $statusColors = [
                                            'reserved' => 'bg-blue-100 text-blue-800 border-blue-300',
                                            'tentative' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                            'confirmed' => 'bg-red-100 text-red-800 border-red-300',
                                            'staff_verified' => 'bg-purple-100 text-purple-800 border-purple-300',
                                            'payment_pending' => 'bg-orange-100 text-orange-800 border-orange-300',
                                        ];
                                        $colorClass = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                                    ?>
                                    <div class="p-1.5 <?php echo e($colorClass); ?> border rounded text-xs">
                                        <div class="font-semibold truncate"><?php echo e(\Carbon\Carbon::parse($booking->start_time)->format('g:i A')); ?></div>
                                        <div class="truncate text-xs"><?php echo e($booking->facility_name); ?></div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if($day['bookingCount'] > 2): ?>
                                    <div class="text-xs text-gray-500 text-center mt-1">
                                        +<?php echo e($day['bookingCount'] - 2); ?> more
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="min-h-[120px] p-2 border border-gray-200 bg-gray-50"></div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- All Reservations List (Highly Visible) -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                <?php echo e($currentDate->format('F Y')); ?> Reservations
            </h2>
            <span class="px-4 py-2 bg-lgu-button text-lgu-button-text font-bold rounded-lg text-lg">
                <?php echo e($bookings->count()); ?> Total
            </span>
        </div>

        <?php if($bookings->isEmpty()): ?>
            <div class="text-center py-12">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Reservations Found</h3>
                <p class="text-gray-600">There are no reservations for this month<?php echo e($selectedFacilityId ? ' and facility' : ''); ?>.</p>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $statusConfig = [
                            'reserved' => ['color' => 'blue', 'label' => 'Reserved'],
                            'tentative' => ['color' => 'yellow', 'label' => 'Tentative'],
                            'confirmed' => ['color' => 'green', 'label' => 'Confirmed'],
                            'staff_verified' => ['color' => 'purple', 'label' => 'Verified'],
                            'payment_pending' => ['color' => 'orange', 'label' => 'Payment Pending'],
                        ];
                        $status = $statusConfig[$booking->status] ?? ['color' => 'gray', 'label' => ucfirst($booking->status)];
                    ?>
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow bg-gray-50">
                        <div class="flex items-center space-x-4 flex-1">
                            <!-- Date Badge -->
                            <div class="flex-shrink-0 text-center bg-lgu-headline text-white rounded-lg p-3 min-w-[80px]">
                                <div class="text-2xl font-bold"><?php echo e(\Carbon\Carbon::parse($booking->start_time)->format('d')); ?></div>
                                <div class="text-xs uppercase"><?php echo e(\Carbon\Carbon::parse($booking->start_time)->format('M')); ?></div>
                            </div>
                            
                            <!-- Booking Details -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-900 truncate"><?php echo e($booking->facility_name); ?></h3>
                                <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-semibold"><?php echo e(\Carbon\Carbon::parse($booking->start_time)->format('g:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($booking->end_time)->format('g:i A')); ?></span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                        <span><?php echo e($booking->facility_location); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Badge -->
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-<?php echo e($status['color']); ?>-100 text-<?php echo e($status['color']); ?>-800 border-2 border-<?php echo e($status['color']); ?>-300">
                                    <?php echo e($status['label']); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Day Details Modal -->
<div id="dayDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-900"></h3>
            <button onclick="closeDayDetails()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="modalContent" class="p-6">
            <!-- Content will be loaded dynamically -->
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function showDayDetails(dateString) {
    const modal = document.getElementById('dayDetailsModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    
    // Format date for display
    const date = new Date(dateString + 'T00:00:00');
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    modalTitle.textContent = date.toLocaleDateString('en-US', options);
    
    // Show loading
    modalContent.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-lgu-headline mx-auto"></div></div>';
    modal.classList.remove('hidden');
    
    // Fetch bookings for this day
    const facilityId = document.getElementById('facility_filter').value;
    fetch(`<?php echo e(route('citizen.facility-calendar.bookings')); ?>?date=${dateString}&facility_id=${facilityId}`)
        .then(response => response.json())
        .then(data => {
            if (data.bookings.length === 0) {
                modalContent.innerHTML = `
                    <div class="text-center py-8">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Reservations</h3>
                        <p class="text-gray-600">There are no reservations for this date.</p>
                    </div>
                `;
            } else {
                let html = '<div class="space-y-3">';
                data.bookings.forEach(booking => {
                    const statusColors = {
                        'reserved': 'bg-blue-100 text-blue-800 border-blue-300',
                        'tentative': 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'confirmed': 'bg-red-100 text-red-800 border-red-300',
                        'staff_verified': 'bg-purple-100 text-purple-800 border-purple-300',
                        'payment_pending': 'bg-orange-100 text-orange-800 border-orange-300',
                    };
                    const colorClass = statusColors[booking.status] || 'bg-gray-100 text-gray-800 border-gray-300';
                    const startTime = new Date(booking.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                    const endTime = new Date(booking.end_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                    
                    html += `
                        <div class="p-4 border-2 ${colorClass} rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-bold text-lg">${booking.facility_name}</h4>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase">${booking.status}</span>
                            </div>
                            <div class="text-sm space-y-1">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-semibold">${startTime} - ${endTime}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    <span>${booking.facility_location}</span>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                modalContent.innerHTML = html;
            }
        })
        .catch(error => {
            modalContent.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-600">Error loading bookings. Please try again.</p>
                </div>
            `;
        });
}

function closeDayDetails() {
    document.getElementById('dayDetailsModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('dayDetailsModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDayDetails();
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.citizen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/citizen/facility-calendar.blade.php ENDPATH**/ ?>