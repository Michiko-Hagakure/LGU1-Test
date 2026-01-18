

<?php $__env->startSection('page-title', 'Create City Event'); ?>
<?php $__env->startSection('page-subtitle', 'Schedule a new government event or priority booking'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?php echo e(route('admin.city-events.index')); ?>" class="p-2 hover:bg-lgu-bg rounded-lg transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5 text-lgu-headline"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-lgu-headline">Create City Event</h2>
            <p class="text-sm text-lgu-paragraph mt-1">Schedule a government event and manage conflicting bookings</p>
        </div>
    </div>

    <!-- Error Messages -->
    <?php if(session('error')): ?>
    <div class="bg-red-50 border-l-4 border-red-600 p-4 rounded-lg">
        <div class="flex items-start">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="font-semibold text-red-900">Error</p>
                <p class="text-sm text-red-800 mt-1"><?php echo e(session('error')); ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="bg-red-50 border-l-4 border-red-600 p-4 rounded-lg">
        <div class="flex items-start">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="font-semibold text-red-900">Validation Errors</p>
                <ul class="list-disc list-inside text-sm text-red-800 mt-2 space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.city-events.store')); ?>" method="POST" id="cityEventForm" class="space-y-6" onsubmit="return prepareFormSubmit()">
        <?php echo csrf_field(); ?>

        <!-- Event Details Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
            <h3 class="text-xl font-semibold text-lgu-headline">Event Details</h3>

            <!-- Event Title Dropdown with Custom Option -->
            <div>
                <label class="block text-sm font-medium text-lgu-headline mb-2">
                    Event Title <span class="text-lgu-tertiary">*</span>
                </label>
                <select 
                    id="event_title_select"
                    onchange="toggleCustomTitle()"
                    required
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph <?php $__errorArgs = ['event_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                >
                    <option value="">Select event title</option>
                    <option value="Annual City Anniversary Celebration" <?php echo e(old('event_title') === 'Annual City Anniversary Celebration' ? 'selected' : ''); ?>>Annual City Anniversary Celebration</option>
                    <option value="Independence Day Celebration" <?php echo e(old('event_title') === 'Independence Day Celebration' ? 'selected' : ''); ?>>Independence Day Celebration</option>
                    <option value="Founding Anniversary" <?php echo e(old('event_title') === 'Founding Anniversary' ? 'selected' : ''); ?>>Founding Anniversary</option>
                    <option value="National Heroes Day" <?php echo e(old('event_title') === 'National Heroes Day' ? 'selected' : ''); ?>>National Heroes Day</option>
                    <option value="Christmas Community Event" <?php echo e(old('event_title') === 'Christmas Community Event' ? 'selected' : ''); ?>>Christmas Community Event</option>
                    <option value="New Year's Celebration" <?php echo e(old('event_title') === 'New Year\'s Celebration' ? 'selected' : ''); ?>>New Year's Celebration</option>
                    <option value="Barangay Assembly Meeting" <?php echo e(old('event_title') === 'Barangay Assembly Meeting' ? 'selected' : ''); ?>>Barangay Assembly Meeting</option>
                    <option value="Emergency Evacuation Center" <?php echo e(old('event_title') === 'Emergency Evacuation Center' ? 'selected' : ''); ?>>Emergency Evacuation Center</option>
                    <option value="Disaster Response Operations" <?php echo e(old('event_title') === 'Disaster Response Operations' ? 'selected' : ''); ?>>Disaster Response Operations</option>
                    <option value="Medical Mission" <?php echo e(old('event_title') === 'Medical Mission' ? 'selected' : ''); ?>>Medical Mission</option>
                    <option value="Vaccination Drive" <?php echo e(old('event_title') === 'Vaccination Drive' ? 'selected' : ''); ?>>Vaccination Drive</option>
                    <option value="Facility Maintenance" <?php echo e(old('event_title') === 'Facility Maintenance' ? 'selected' : ''); ?>>Facility Maintenance</option>
                    <option value="Equipment Repair" <?php echo e(old('event_title') === 'Equipment Repair' ? 'selected' : ''); ?>>Equipment Repair</option>
                    <option value="Building Renovation" <?php echo e(old('event_title') === 'Building Renovation' ? 'selected' : ''); ?>>Building Renovation</option>
                    <option value="custom">Other (Custom Title)...</option>
                </select>
                
                <!-- Hidden input that actually gets submitted -->
                <input type="hidden" name="event_title" id="event_title_value" value="<?php echo e(old('event_title')); ?>">
                
                <!-- Custom Title Input (Hidden by default) -->
                <div id="custom_title_wrapper" class="hidden mt-3">
                    <input 
                        type="text" 
                        id="custom_title_input"
                        placeholder="Enter custom event title"
                        class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph"
                    />
                </div>
                
                <p class="text-xs text-lgu-paragraph mt-1">Select a predefined title or choose "Other" for custom input</p>
                <?php $__errorArgs = ['event_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-lgu-tertiary text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Event Description -->
            <div>
                <label class="block text-sm font-medium text-lgu-headline mb-2">
                    Event Description
                </label>
                <textarea 
                    name="event_description" 
                    rows="4"
                    placeholder="Provide details about the event..."
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph <?php $__errorArgs = ['event_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                ><?php echo e(old('event_description')); ?></textarea>
                <?php $__errorArgs = ['event_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-lgu-tertiary text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Event Type -->
            <div>
                <label class="block text-sm font-medium text-lgu-headline mb-2">
                    Event Type <span class="text-lgu-tertiary">*</span>
                </label>
                <select 
                    name="event_type" 
                    required
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph <?php $__errorArgs = ['event_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                >
                    <option value="">Select event type</option>
                    <option value="government" <?php echo e(old('event_type') === 'government' ? 'selected' : ''); ?>>Government Event</option>
                    <option value="emergency" <?php echo e(old('event_type') === 'emergency' ? 'selected' : ''); ?>>Emergency</option>
                    <option value="maintenance" <?php echo e(old('event_type') === 'maintenance' ? 'selected' : ''); ?>>Maintenance</option>
                </select>
                <?php $__errorArgs = ['event_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-lgu-tertiary text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <!-- Facility & Schedule Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
            <h3 class="text-xl font-semibold text-lgu-headline">Facility & Schedule</h3>

            <!-- Facility Selection -->
            <div>
                <label class="block text-sm font-medium text-lgu-headline mb-2">
                    Facility <span class="text-lgu-tertiary">*</span>
                </label>
                <select 
                    name="facility_id" 
                    id="facility_id"
                    required
                    onchange="checkConflicts()"
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph <?php $__errorArgs = ['facility_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                >
                    <option value="">Select facility</option>
                    <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($facility->id); ?>" <?php echo e(old('facility_id') == $facility->id ? 'selected' : ''); ?>>
                            <?php echo e($facility->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['facility_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-lgu-tertiary text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Date & Time with Modal Picker -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-lgu-headline mb-2">
                        Start Date & Time <span class="text-lgu-tertiary">*</span>
                    </label>
                    <button 
                        type="button"
                        onclick="openDateTimeModal('start')"
                        class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg hover:border-lgu-highlight focus:border-lgu-highlight focus:outline-none text-left text-lgu-paragraph flex items-center justify-between <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    >
                        <span id="start_time_display" class="text-gray-400">Select start date & time</span>
                        <i data-lucide="calendar-clock" class="w-5 h-5 text-lgu-headline"></i>
                    </button>
                    <input type="hidden" name="start_time" id="start_time" value="<?php echo e(old('start_time')); ?>" required>
                    <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-lgu-tertiary text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-lgu-headline mb-2">
                        End Date & Time <span class="text-lgu-tertiary">*</span>
                    </label>
                    <button 
                        type="button"
                        onclick="openDateTimeModal('end')"
                        class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg hover:border-lgu-highlight focus:border-lgu-highlight focus:outline-none text-left text-lgu-paragraph flex items-center justify-between <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    >
                        <span id="end_time_display" class="text-gray-400">Select end date & time</span>
                        <i data-lucide="calendar-clock" class="w-5 h-5 text-lgu-headline"></i>
                    </button>
                    <input type="hidden" name="end_time" id="end_time" value="<?php echo e(old('end_time')); ?>" required>
                    <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-lgu-tertiary text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Conflict Preview Modal (Hidden by default) -->
        <div id="conflictModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-orange-50 border-b-2 border-orange-200 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                            <i data-lucide="alert-triangle" class="w-6 h-6 text-orange-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-lgu-headline">Conflicting Bookings Detected</h3>
                            <p class="text-sm text-lgu-paragraph" id="conflictCount"></p>
                        </div>
                    </div>
                    <button type="button" onclick="closeConflictModal()" class="p-2 hover:bg-orange-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-lgu-headline"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto max-h-96">
                    <div id="conflictList" class="space-y-3"></div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="closeConflictModal()" class="btn-secondary flex items-center gap-2">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        <span>Close</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Date & Time Picker Modal -->
        <div id="dateTimeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
                <!-- Modal Header -->
                <div class="bg-lgu-highlight bg-opacity-10 border-b-2 border-lgu-highlight px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-lgu-highlight bg-opacity-20 rounded-full flex items-center justify-center">
                            <i data-lucide="calendar-clock" class="w-6 h-6 text-lgu-headline"></i>
                        </div>
                        <h3 class="text-lg font-bold text-lgu-headline" id="modalTitle">Select Date & Time</h3>
                    </div>
                    <button type="button" onclick="closeDateTimeModal()" class="p-2 hover:bg-lgu-highlight hover:bg-opacity-10 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-lgu-headline"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    <!-- Date Input -->
                    <div>
                        <label class="block text-sm font-medium text-lgu-headline mb-2">
                            Date <span class="text-lgu-tertiary">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="modal_date"
                            class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph"
                        />
                    </div>

                    <!-- Time Input -->
                    <div>
                        <label class="block text-sm font-medium text-lgu-headline mb-2">
                            Time <span class="text-lgu-tertiary">*</span>
                        </label>
                        <input 
                            type="time" 
                            id="modal_time"
                            class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph"
                        />
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="closeDateTimeModal()" class="btn-secondary flex items-center gap-2">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        <span>Cancel</span>
                    </button>
                    <button type="button" onclick="saveDateTimeSelection()" class="btn-primary flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>Confirm</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <i data-lucide="check" class="w-5 h-5"></i>
                <span>Create City Event</span>
            </button>
            <a href="<?php echo e(route('admin.city-events.index')); ?>" class="btn-secondary flex items-center gap-2">
                <i data-lucide="x" class="w-5 h-5"></i>
                <span>Cancel</span>
            </a>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}

// Toggle custom title input
function toggleCustomTitle() {
    const select = document.getElementById('event_title_select');
    const customWrapper = document.getElementById('custom_title_wrapper');
    const customInput = document.getElementById('custom_title_input');
    const hiddenInput = document.getElementById('event_title_value');
    
    if (select.value === 'custom') {
        // Show custom input field
        customWrapper.classList.remove('hidden');
        customInput.required = true;
        customInput.focus();
        hiddenInput.value = ''; // Clear hidden input
    } else {
        // Hide custom input field
        customWrapper.classList.add('hidden');
        customInput.required = false;
        customInput.value = '';
        // Update hidden input with selected value
        hiddenInput.value = select.value;
    }
}

// Prepare form before submission
function prepareFormSubmit() {
    const select = document.getElementById('event_title_select');
    const customInput = document.getElementById('custom_title_input');
    const hiddenInput = document.getElementById('event_title_value');
    
    if (select.value === 'custom') {
        // Use custom input value
        if (customInput.value.trim() === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Custom Title Required',
                text: 'Please enter a custom event title.',
                confirmButtonColor: '#faae2b'
            });
            customInput.focus();
            return false;
        }
        // Set hidden input to custom value
        hiddenInput.value = customInput.value.trim();
    }
    
    return true;
}

// Check for conflicting bookings
let conflictTimeout;
function checkConflicts() {
    clearTimeout(conflictTimeout);
    conflictTimeout = setTimeout(() => {
        const facilityId = document.getElementById('facility_id').value;
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;

        if (!facilityId || !startTime || !endTime) {
            return;
        }

        fetch('<?php echo e(route('admin.city-events.preview-conflicts')); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                facility_id: facilityId,
                start_time: startTime,
                end_time: endTime
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.count > 0) {
                showConflictModal(data);
            }
        })
        .catch(error => {
            console.error('Error checking conflicts:', error);
        });
    }, 500);
}

// Show conflict modal
function showConflictModal(data) {
    const modal = document.getElementById('conflictModal');
    const count = document.getElementById('conflictCount');
    const list = document.getElementById('conflictList');

    count.textContent = `${data.count} existing booking(s) will be affected. Citizens will be notified and given the option to reschedule or request a refund.`;
    
    list.innerHTML = data.bookings.map(booking => `
        <div class="bg-orange-50 border-l-4 border-orange-500 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <i data-lucide="calendar-x" class="w-5 h-5 text-orange-600"></i>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-lgu-headline mb-1">${booking.citizen?.full_name || 'Unknown Citizen'}</div>
                    <div class="text-sm text-lgu-paragraph">
                        <div class="flex items-center gap-2 mb-1">
                            <i data-lucide="mail" class="w-3 h-3"></i>
                            <span>${booking.citizen?.email || 'No email'}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            <span>${new Date(booking.start_time).toLocaleString('en-US', { 
                                month: 'short', 
                                day: 'numeric', 
                                year: 'numeric',
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            })} - ${new Date(booking.end_time).toLocaleString('en-US', { 
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            })}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    
    modal.classList.remove('hidden');
    lucide.createIcons();
}

// Close conflict modal
function closeConflictModal() {
    const modal = document.getElementById('conflictModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('conflictModal');
    if (event.target === modal) {
        closeConflictModal();
    }
    
    const dateTimeModal = document.getElementById('dateTimeModal');
    if (event.target === dateTimeModal) {
        closeDateTimeModal();
    }
});

// Date-Time Modal Functions
let currentDateTimeField = '';

function openDateTimeModal(field) {
    currentDateTimeField = field;
    const modal = document.getElementById('dateTimeModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalDate = document.getElementById('modal_date');
    const modalTime = document.getElementById('modal_time');
    
    // Set modal title
    modalTitle.textContent = field === 'start' ? 'Select Start Date & Time' : 'Select End Date & Time';
    
    // Get current value if exists
    const currentValue = document.getElementById(field + '_time').value;
    if (currentValue) {
        const dateTime = new Date(currentValue);
        modalDate.value = dateTime.toISOString().split('T')[0];
        modalTime.value = dateTime.toTimeString().slice(0, 5);
    } else {
        // Set to current date/time as default
        const now = new Date();
        modalDate.value = now.toISOString().split('T')[0];
        modalTime.value = now.toTimeString().slice(0, 5);
    }
    
    modal.classList.remove('hidden');
    lucide.createIcons();
}

function closeDateTimeModal() {
    const modal = document.getElementById('dateTimeModal');
    modal.classList.add('hidden');
    currentDateTimeField = '';
}

function saveDateTimeSelection() {
    const modalDate = document.getElementById('modal_date');
    const modalTime = document.getElementById('modal_time');
    
    if (!modalDate.value || !modalTime.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Selection',
            text: 'Please select both date and time.',
            confirmButtonColor: '#faae2b'
        });
        return;
    }
    
    // Combine date and time
    const dateTimeValue = modalDate.value + 'T' + modalTime.value;
    
    // Set hidden input value
    document.getElementById(currentDateTimeField + '_time').value = dateTimeValue;
    
    // Format display text
    const dateTime = new Date(dateTimeValue);
    const displayText = dateTime.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
    
    // Update display button
    const displayElement = document.getElementById(currentDateTimeField + '_time_display');
    displayElement.textContent = displayText;
    displayElement.classList.remove('text-gray-400');
    displayElement.classList.add('text-lgu-headline', 'font-medium');
    
    // Close modal
    closeDateTimeModal();
    
    // Check for conflicts
    checkConflicts();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/city-events/create.blade.php ENDPATH**/ ?>