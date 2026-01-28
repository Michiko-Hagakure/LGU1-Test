<?php $__env->startSection('page-title', 'Edit City Event'); ?>
<?php $__env->startSection('page-subtitle', 'Modify city event details'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="<?php echo e(route('admin.city-events.show', $cityEvent)); ?>" class="inline-flex items-center gap-2 text-lgu-paragraph hover:text-lgu-headline transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Event Details</span>
        </a>
    </div>

    <!-- Page Header -->
    <div>
        <h2 class="text-2xl font-bold text-lgu-headline">Edit City Event</h2>
        <p class="text-sm text-lgu-paragraph mt-1">Update the city event information</p>
    </div>

    <!-- Edit Form -->
    <form method="POST" action="<?php echo e(route('admin.city-events.update', $cityEvent)); ?>" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Event Details Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
            <h3 class="text-xl font-semibold text-lgu-headline">Event Details</h3>

            <!-- Event Title Dropdown with Custom Option -->
            <?php
                $predefinedTitles = [
                    'Annual City Anniversary Celebration',
                    'Independence Day Celebration',
                    'Founding Anniversary',
                    'National Heroes Day',
                    'Christmas Community Event',
                    "New Year's Celebration",
                    'Barangay Assembly Meeting',
                    'Emergency Evacuation Center',
                    'Disaster Response Operations',
                    'Medical Mission',
                    'Vaccination Drive',
                    'Facility Maintenance',
                    'Equipment Repair',
                    'Building Renovation',
                ];
                $currentTitle = old('event_title', $cityEvent->event_title);
                $isCustomTitle = !in_array($currentTitle, $predefinedTitles);
            ?>
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
                    <?php $__currentLoopData = $predefinedTitles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $title): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($title); ?>" <?php echo e($currentTitle === $title ? 'selected' : ''); ?>><?php echo e($title); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <option value="custom" <?php echo e($isCustomTitle && $currentTitle ? 'selected' : ''); ?>>Other (Custom Title)...</option>
                </select>
                
                <!-- Hidden input that actually gets submitted -->
                <input type="hidden" name="event_title" id="event_title_value" value="<?php echo e($currentTitle); ?>">
                
                <!-- Custom Title Input -->
                <div id="custom_title_wrapper" class="<?php echo e($isCustomTitle && $currentTitle ? '' : 'hidden'); ?> mt-3">
                    <input 
                        type="text" 
                        id="custom_title_input"
                        value="<?php echo e($isCustomTitle ? $currentTitle : ''); ?>"
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
                    Description
                </label>
                <textarea 
                    name="event_description" 
                    rows="3"
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph <?php $__errorArgs = ['event_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    placeholder="Enter event description (optional)"
                ><?php echo e(old('event_description', $cityEvent->event_description)); ?></textarea>
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
                    <option value="government" <?php echo e(old('event_type', $cityEvent->event_type) === 'government' ? 'selected' : ''); ?>>Government Event</option>
                    <option value="emergency" <?php echo e(old('event_type', $cityEvent->event_type) === 'emergency' ? 'selected' : ''); ?>>Emergency</option>
                    <option value="maintenance" <?php echo e(old('event_type', $cityEvent->event_type) === 'maintenance' ? 'selected' : ''); ?>>Maintenance</option>
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

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-lgu-headline mb-2">
                    Status <span class="text-lgu-tertiary">*</span>
                </label>
                <select 
                    name="status" 
                    required
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                >
                    <option value="scheduled" <?php echo e(old('status', $cityEvent->status) === 'scheduled' ? 'selected' : ''); ?>>Scheduled</option>
                    <option value="ongoing" <?php echo e(old('status', $cityEvent->status) === 'ongoing' ? 'selected' : ''); ?>>Ongoing</option>
                    <option value="completed" <?php echo e(old('status', $cityEvent->status) === 'completed' ? 'selected' : ''); ?>>Completed</option>
                    <option value="cancelled" <?php echo e(old('status', $cityEvent->status) === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                </select>
                <?php $__errorArgs = ['status'];
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
                        <option value="<?php echo e($facility->id); ?>" <?php echo e(old('facility_id', $cityEvent->facility_id) == $facility->id ? 'selected' : ''); ?>>
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

            <!-- Date Picker -->
            <div>
                <label class="block text-sm font-medium text-lgu-headline mb-2">
                    Event Date <span class="text-lgu-tertiary">*</span>
                </label>
                <button 
                    type="button"
                    onclick="openDatePicker()"
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg hover:border-lgu-highlight focus:border-lgu-highlight focus:outline-none text-left text-lgu-paragraph flex items-center justify-between"
                >
                    <span id="event_date_display" class="text-lgu-headline font-medium">
                        <?php echo e(\Carbon\Carbon::parse($cityEvent->start_time)->format('l, F d, Y')); ?>

                    </span>
                    <i data-lucide="calendar" class="w-5 h-5 text-lgu-headline"></i>
                </button>
                <input type="hidden" id="event_date" value="<?php echo e(\Carbon\Carbon::parse($cityEvent->start_time)->format('Y-m-d')); ?>">
            </div>

            <!-- Time Pickers (Start & End) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-lgu-headline mb-2">
                        Start Time <span class="text-lgu-tertiary">*</span>
                    </label>
                    <button 
                        type="button"
                        onclick="openTimePicker('start')"
                        class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg hover:border-lgu-highlight focus:border-lgu-highlight focus:outline-none text-left text-lgu-paragraph flex items-center justify-between <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    >
                        <span id="start_time_display" class="text-lgu-headline font-medium">
                            <?php echo e(\Carbon\Carbon::parse($cityEvent->start_time)->format('g:i A')); ?>

                        </span>
                        <i data-lucide="clock" class="w-5 h-5 text-lgu-headline"></i>
                    </button>
                    <input type="hidden" name="start_time" id="start_time" value="<?php echo e(old('start_time', $cityEvent->start_time)); ?>" required>
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
                        End Time <span class="text-lgu-tertiary">*</span>
                    </label>
                    <button 
                        type="button"
                        onclick="openTimePicker('end')"
                        class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg hover:border-lgu-highlight focus:border-lgu-highlight focus:outline-none text-left text-lgu-paragraph flex items-center justify-between <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    >
                        <span id="end_time_display" class="text-lgu-headline font-medium">
                            <?php echo e(\Carbon\Carbon::parse($cityEvent->end_time)->format('g:i A')); ?>

                        </span>
                        <i data-lucide="clock" class="w-5 h-5 text-lgu-headline"></i>
                    </button>
                    <input type="hidden" name="end_time" id="end_time" value="<?php echo e(old('end_time', $cityEvent->end_time)); ?>" required>
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

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <i data-lucide="save" class="w-5 h-5"></i>
                <span>Save Changes</span>
            </button>
            <a href="<?php echo e(route('admin.city-events.show', $cityEvent)); ?>" class="btn-secondary flex items-center gap-2">
                <i data-lucide="x" class="w-5 h-5"></i>
                <span>Cancel</span>
            </a>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
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
        customWrapper.classList.remove('hidden');
        customInput.required = true;
        customInput.focus();
        hiddenInput.value = customInput.value;
    } else {
        customWrapper.classList.add('hidden');
        customInput.required = false;
        hiddenInput.value = select.value;
    }
}

// Sync custom input to hidden field
document.getElementById('custom_title_input')?.addEventListener('input', function() {
    document.getElementById('event_title_value').value = this.value;
});

// Initialize with existing values
let selectedDate = '<?php echo e(\Carbon\Carbon::parse($cityEvent->start_time)->format('Y-m-d')); ?>';
let selectedStartTime = '<?php echo e(\Carbon\Carbon::parse($cityEvent->start_time)->format('H:i')); ?>';
let selectedEndTime = '<?php echo e(\Carbon\Carbon::parse($cityEvent->end_time)->format('H:i')); ?>';

// Generate calendar HTML for SweetAlert2
function generateCalendarHTML(year, month, selectedDate) {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startingDay = firstDay.getDay();
    const monthLength = lastDay.getDate();
    
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                        'July', 'August', 'September', 'October', 'November', 'December'];
    
    let html = `
        <div class="calendar-container">
            <div class="flex items-center justify-between mb-4">
                <button type="button" onclick="changeMonth(-1)" class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <span class="text-lg font-bold text-gray-800">${monthNames[month]} ${year}</span>
                <button type="button" onclick="changeMonth(1)" class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-7 gap-1 mb-2">
                <div class="text-center text-xs font-semibold text-gray-500 py-2">Sun</div>
                <div class="text-center text-xs font-semibold text-gray-500 py-2">Mon</div>
                <div class="text-center text-xs font-semibold text-gray-500 py-2">Tue</div>
                <div class="text-center text-xs font-semibold text-gray-500 py-2">Wed</div>
                <div class="text-center text-xs font-semibold text-gray-500 py-2">Thu</div>
                <div class="text-center text-xs font-semibold text-gray-500 py-2">Fri</div>
                <div class="text-center text-xs font-semibold text-gray-500 py-2">Sat</div>
            </div>
            <div class="grid grid-cols-7 gap-1">
    `;
    
    for (let i = 0; i < startingDay; i++) {
        html += '<div class="p-2"></div>';
    }
    
    for (let day = 1; day <= monthLength; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const currentDate = new Date(year, month, day);
        const isPast = currentDate < today;
        const isSelected = selectedDate === dateStr;
        const isToday = currentDate.getTime() === today.getTime();
        
        let classes = 'p-2 text-center rounded-lg cursor-pointer transition-colors ';
        if (isPast) {
            classes += 'text-gray-300 cursor-not-allowed';
        } else if (isSelected) {
            classes += 'bg-amber-500 text-white font-bold';
        } else if (isToday) {
            classes += 'bg-amber-100 text-amber-800 font-semibold hover:bg-amber-200';
        } else {
            classes += 'hover:bg-gray-100 text-gray-700';
        }
        
        const onclick = isPast ? '' : `onclick="selectDate('${dateStr}')"`;
        html += `<div class="${classes}" ${onclick}>${day}</div>`;
    }
    
    html += '</div></div>';
    return html;
}

let currentCalendarYear, currentCalendarMonth;

function openDatePicker() {
    const existingDate = new Date(selectedDate);
    currentCalendarYear = existingDate.getFullYear();
    currentCalendarMonth = existingDate.getMonth();
    
    Swal.fire({
        title: 'Select Event Date',
        html: generateCalendarHTML(currentCalendarYear, currentCalendarMonth, selectedDate),
        showCancelButton: true,
        showConfirmButton: false,
        cancelButtonText: 'Cancel',
        cancelButtonColor: '#6b7280',
        width: 400,
        customClass: { popup: 'rounded-xl' }
    });
}

function changeMonth(delta) {
    currentCalendarMonth += delta;
    if (currentCalendarMonth > 11) {
        currentCalendarMonth = 0;
        currentCalendarYear++;
    } else if (currentCalendarMonth < 0) {
        currentCalendarMonth = 11;
        currentCalendarYear--;
    }
    
    Swal.update({
        html: generateCalendarHTML(currentCalendarYear, currentCalendarMonth, selectedDate)
    });
}

function selectDate(dateStr) {
    selectedDate = dateStr;
    document.getElementById('event_date').value = dateStr;
    
    const date = new Date(dateStr + 'T00:00:00');
    const displayText = date.toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    });
    
    document.getElementById('event_date_display').textContent = displayText;
    Swal.close();
    updateHiddenDateTimeFields();
}

function generateTimePickerHTML(type, selectedTime) {
    const times = [];
    for (let hour = 8; hour <= 21; hour++) {
        const hour24 = String(hour).padStart(2, '0');
        const hour12 = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        times.push({
            value: `${hour24}:00`,
            display: `${hour12}:00 ${ampm}`
        });
    }
    
    let html = `
        <div class="time-picker-container">
            <p class="text-sm text-gray-500 mb-4">Select ${type === 'start' ? 'start' : 'end'} time (8:00 AM - 9:00 PM)</p>
            <div class="grid grid-cols-3 gap-2 max-h-64 overflow-y-auto">
    `;
    
    times.forEach(time => {
        const isSelected = selectedTime === time.value;
        const classes = isSelected 
            ? 'bg-amber-500 text-white font-bold' 
            : 'bg-gray-100 hover:bg-amber-100 text-gray-700';
        html += `
            <button type="button" 
                onclick="selectTime('${type}', '${time.value}', '${time.display}')"
                class="px-3 py-3 rounded-lg text-sm transition-colors ${classes}">
                ${time.display}
            </button>
        `;
    });
    
    html += '</div></div>';
    return html;
}

function openTimePicker(type) {
    const currentTime = type === 'start' ? selectedStartTime : selectedEndTime;
    
    Swal.fire({
        title: type === 'start' ? 'Select Start Time' : 'Select End Time',
        html: generateTimePickerHTML(type, currentTime),
        showCancelButton: true,
        showConfirmButton: false,
        cancelButtonText: 'Cancel',
        cancelButtonColor: '#6b7280',
        width: 380,
        customClass: { popup: 'rounded-xl' }
    });
}

function selectTime(type, timeValue, displayText) {
    if (type === 'start') {
        selectedStartTime = timeValue;
        document.getElementById('start_time_display').textContent = displayText;
    } else {
        selectedEndTime = timeValue;
        document.getElementById('end_time_display').textContent = displayText;
    }
    
    Swal.close();
    updateHiddenDateTimeFields();
}

function updateHiddenDateTimeFields() {
    const eventDate = document.getElementById('event_date').value;
    
    if (eventDate && selectedStartTime) {
        document.getElementById('start_time').value = eventDate + 'T' + selectedStartTime;
    }
    
    if (eventDate && selectedEndTime) {
        document.getElementById('end_time').value = eventDate + 'T' + selectedEndTime;
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/city-events/edit.blade.php ENDPATH**/ ?>