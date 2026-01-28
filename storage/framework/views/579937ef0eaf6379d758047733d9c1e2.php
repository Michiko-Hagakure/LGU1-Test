<?php $__env->startSection('title', 'Create Event'); ?>
<?php $__env->startSection('page-title', 'Create Event'); ?>
<?php $__env->startSection('page-subtitle', 'Add a new city event'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="mb-6">
    <a href="<?php echo e(route('admin.events.index')); ?>" class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors duration-200">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
        Back to Events
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Create New Event</h1>

    <?php if($errors->any()): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <ul class="list-disc list-inside">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.events.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                    Event Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="<?php echo e(old('title')); ?>" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['title'];
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

            
            <div class="md:col-span-2">
                <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">
                    URL Slug <span class="text-red-500">*</span>
                </label>
                <input type="text" name="slug" id="slug" value="<?php echo e(old('slug')); ?>" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    placeholder="event-url-slug">
                <?php $__errorArgs = ['slug'];
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

            
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="description" rows="4" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description')); ?></textarea>
                <?php $__errorArgs = ['description'];
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
                <label for="event_date_display" class="block text-sm font-semibold text-gray-700 mb-2">
                    Event Date <span class="text-red-500">*</span>
                </label>
                <input type="hidden" name="event_date" id="event_date" value="<?php echo e(old('event_date')); ?>" required>
                <div id="event_date_display" onclick="openDatePicker()"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-colors flex items-center justify-between <?php $__errorArgs = ['event_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <span id="event_date_text" class="text-gray-500">Select event date...</span>
                    <i data-lucide="calendar" class="w-5 h-5 text-gray-400"></i>
                </div>
                <?php $__errorArgs = ['event_date'];
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
                <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">
                    Location
                </label>
                <input type="text" name="location" id="location" value="<?php echo e(old('location')); ?>"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['location'];
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
                <label for="start_time_display" class="block text-sm font-semibold text-gray-700 mb-2">
                    Start Time
                </label>
                <input type="hidden" name="start_time" id="start_time" value="<?php echo e(old('start_time')); ?>">
                <div id="start_time_display" onclick="openTimePicker('start_time')"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-colors flex items-center justify-between">
                    <span id="start_time_text" class="text-gray-500">Select start time...</span>
                    <i data-lucide="clock" class="w-5 h-5 text-gray-400"></i>
                </div>
                <?php $__errorArgs = ['start_time'];
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
                <label for="end_time_display" class="block text-sm font-semibold text-gray-700 mb-2">
                    End Time
                </label>
                <input type="hidden" name="end_time" id="end_time" value="<?php echo e(old('end_time')); ?>">
                <div id="end_time_display" onclick="openTimePicker('end_time')"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-colors flex items-center justify-between">
                    <span id="end_time_text" class="text-gray-500">Select end time...</span>
                    <i data-lucide="clock" class="w-5 h-5 text-gray-400"></i>
                </div>
                <?php $__errorArgs = ['end_time'];
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
                <label for="organizer" class="block text-sm font-semibold text-gray-700 mb-2">
                    Organizer
                </label>
                <input type="text" name="organizer" id="organizer" value="<?php echo e(old('organizer')); ?>"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['organizer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['organizer'];
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
                <label for="max_attendees" class="block text-sm font-semibold text-gray-700 mb-2">
                    Max Attendees
                </label>
                <input type="number" name="max_attendees" id="max_attendees" value="<?php echo e(old('max_attendees')); ?>" min="1"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['max_attendees'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['max_attendees'];
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
                <label for="contact_email" class="block text-sm font-semibold text-gray-700 mb-2">
                    Contact Email
                </label>
                <input type="email" name="contact_email" id="contact_email" value="<?php echo e(old('contact_email')); ?>"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['contact_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['contact_email'];
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
                <label for="contact_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                    Contact Phone
                </label>
                <input type="tel" name="contact_phone" id="contact_phone" value="<?php echo e(old('contact_phone')); ?>"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['contact_phone'];
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

            
            <div class="md:col-span-2">
                <label for="registration_link" class="block text-sm font-semibold text-gray-700 mb-2">
                    Registration Link
                </label>
                <input type="url" name="registration_link" id="registration_link" value="<?php echo e(old('registration_link')); ?>"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['registration_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    placeholder="https://...">
                <?php $__errorArgs = ['registration_link'];
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

            
            <div class="md:col-span-2">
                <label for="image_path" class="block text-sm font-semibold text-gray-700 mb-2">
                    Event Image
                </label>
                <input type="file" name="image_path" id="image_path" accept="image/jpeg,image/png,image/jpg"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['image_path'];
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
                    <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="md:col-span-2 flex flex-wrap gap-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" <?php echo e(old('is_featured') ? 'checked' : ''); ?>

                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Featured Event</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>

                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
            </div>
        </div>

        
        <div class="mt-8 flex justify-end gap-4">
            <a href="<?php echo e(route('admin.events.index')); ?>" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200">
                Create Event
            </button>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('title').addEventListener('input', function() {
    const slug = this.value
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
    document.getElementById('slug').value = slug;
});

if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}

// Beautiful Date Picker with SweetAlert2
function openDatePicker() {
    const today = new Date();
    const currentYear = today.getFullYear();
    const currentMonth = today.getMonth();
    
    let selectedYear = currentYear;
    let selectedMonth = currentMonth;
    let selectedDay = null;
    
    // Check if there's an existing value
    const existingValue = document.getElementById('event_date').value;
    if (existingValue) {
        const parts = existingValue.split('-');
        selectedYear = parseInt(parts[0]);
        selectedMonth = parseInt(parts[1]) - 1;
        selectedDay = parseInt(parts[2]);
    }
    
    function renderCalendar() {
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                           'July', 'August', 'September', 'October', 'November', 'December'];
        const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        
        const firstDay = new Date(selectedYear, selectedMonth, 1).getDay();
        const daysInMonth = new Date(selectedYear, selectedMonth + 1, 0).getDate();
        const prevMonthDays = new Date(selectedYear, selectedMonth, 0).getDate();
        
        let calendarHTML = `
            <div class="swal2-calendar-container">
                <div class="flex items-center justify-between mb-4">
                    <button type="button" onclick="navigateMonth(-1)" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <div class="text-lg font-semibold text-gray-800">${monthNames[selectedMonth]} ${selectedYear}</div>
                    <button type="button" onclick="navigateMonth(1)" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-7 gap-1 mb-2">
                    ${dayNames.map(d => `<div class="text-center text-xs font-medium text-gray-500 py-2">${d}</div>`).join('')}
                </div>
                <div class="grid grid-cols-7 gap-1">
        `;
        
        // Previous month days
        for (let i = firstDay - 1; i >= 0; i--) {
            calendarHTML += `<div class="text-center py-2 text-gray-300 text-sm">${prevMonthDays - i}</div>`;
        }
        
        // Current month days
        for (let day = 1; day <= daysInMonth; day++) {
            const isToday = day === today.getDate() && selectedMonth === currentMonth && selectedYear === currentYear;
            const isSelected = day === selectedDay;
            const isPast = new Date(selectedYear, selectedMonth, day) < new Date(today.getFullYear(), today.getMonth(), today.getDate());
            
            let classes = 'text-center py-2 text-sm rounded-lg cursor-pointer transition-all duration-200 ';
            if (isPast) {
                classes += 'text-gray-300 cursor-not-allowed';
            } else if (isSelected) {
                classes += 'bg-blue-600 text-white font-semibold shadow-md';
            } else if (isToday) {
                classes += 'bg-blue-100 text-blue-600 font-semibold hover:bg-blue-200';
            } else {
                classes += 'hover:bg-gray-100 text-gray-700';
            }
            
            calendarHTML += `<div class="${classes}" ${!isPast ? `onclick="selectDate(${day})"` : ''}>${day}</div>`;
        }
        
        // Next month days
        const remainingDays = 42 - (firstDay + daysInMonth);
        for (let i = 1; i <= remainingDays; i++) {
            calendarHTML += `<div class="text-center py-2 text-gray-300 text-sm">${i}</div>`;
        }
        
        calendarHTML += '</div></div>';
        
        return calendarHTML;
    }
    
    window.navigateMonth = function(direction) {
        selectedMonth += direction;
        if (selectedMonth > 11) {
            selectedMonth = 0;
            selectedYear++;
        } else if (selectedMonth < 0) {
            selectedMonth = 11;
            selectedYear--;
        }
        Swal.update({ html: renderCalendar() });
    };
    
    window.selectDate = function(day) {
        selectedDay = day;
        const formattedDate = `${selectedYear}-${String(selectedMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        document.getElementById('event_date').value = formattedDate;
        
        const displayDate = new Date(selectedYear, selectedMonth, day);
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('event_date_text').textContent = displayDate.toLocaleDateString('en-US', options);
        document.getElementById('event_date_text').classList.remove('text-gray-500');
        document.getElementById('event_date_text').classList.add('text-gray-800');
        
        Swal.close();
    };
    
    Swal.fire({
        title: '<span class="text-xl font-bold text-gray-800">Select Event Date</span>',
        html: renderCalendar(),
        showConfirmButton: false,
        showCloseButton: true,
        customClass: {
            popup: 'rounded-2xl',
            closeButton: 'text-gray-400 hover:text-gray-600'
        },
        width: '380px',
        padding: '1.5rem'
    });
}

// Beautiful Time Picker with SweetAlert2
function openTimePicker(fieldId) {
    let selectedHour = 9;
    let selectedMinute = 0;
    let selectedPeriod = 'AM';
    
    // Check if there's an existing value
    const existingValue = document.getElementById(fieldId).value;
    if (existingValue) {
        const parts = existingValue.split(':');
        let hour = parseInt(parts[0]);
        selectedMinute = parseInt(parts[1]);
        if (hour >= 12) {
            selectedPeriod = 'PM';
            if (hour > 12) hour -= 12;
        } else if (hour === 0) {
            hour = 12;
        }
        selectedHour = hour;
    }
    
    function renderTimePicker() {
        const hours = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        const minutes = ['00', '15', '30', '45'];
        
        return `
            <div class="time-picker-container py-4">
                <div class="flex justify-center gap-4 mb-6">
                    <div class="text-center">
                        <div class="text-xs font-medium text-gray-500 mb-2 uppercase tracking-wider">Hour</div>
                        <div class="grid grid-cols-4 gap-2">
                            ${hours.map(h => `
                                <button type="button" onclick="selectHour(${h})" 
                                    class="w-12 h-12 rounded-xl text-lg font-medium transition-all duration-200 
                                    ${h === selectedHour ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}">
                                    ${h}
                                </button>
                            `).join('')}
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-center gap-4 mb-6">
                    <div class="text-center">
                        <div class="text-xs font-medium text-gray-500 mb-2 uppercase tracking-wider">Minute</div>
                        <div class="flex gap-2 justify-center">
                            ${minutes.map(m => `
                                <button type="button" onclick="selectMinute(${parseInt(m)})" 
                                    class="w-14 h-12 rounded-xl text-lg font-medium transition-all duration-200 
                                    ${parseInt(m) === selectedMinute ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}">
                                    :${m}
                                </button>
                            `).join('')}
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-center gap-3 mb-6">
                    <button type="button" onclick="selectPeriod('AM')" 
                        class="px-8 py-3 rounded-xl text-lg font-semibold transition-all duration-200 
                        ${selectedPeriod === 'AM' ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}">
                        AM
                    </button>
                    <button type="button" onclick="selectPeriod('PM')" 
                        class="px-8 py-3 rounded-xl text-lg font-semibold transition-all duration-200 
                        ${selectedPeriod === 'PM' ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}">
                        PM
                    </button>
                </div>
                
                <div class="text-center mb-4">
                    <div class="inline-flex items-center gap-2 bg-gray-50 px-6 py-3 rounded-xl">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-2xl font-bold text-gray-800">${selectedHour}:${String(selectedMinute).padStart(2, '0')} ${selectedPeriod}</span>
                    </div>
                </div>
            </div>
        `;
    }
    
    window.selectHour = function(hour) {
        selectedHour = hour;
        Swal.update({ html: renderTimePicker() });
    };
    
    window.selectMinute = function(minute) {
        selectedMinute = minute;
        Swal.update({ html: renderTimePicker() });
    };
    
    window.selectPeriod = function(period) {
        selectedPeriod = period;
        Swal.update({ html: renderTimePicker() });
    };
    
    Swal.fire({
        title: `<span class="text-xl font-bold text-gray-800">Select ${fieldId === 'start_time' ? 'Start' : 'End'} Time</span>`,
        html: renderTimePicker(),
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg',
            cancelButton: 'bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2.5 rounded-lg'
        },
        buttonsStyling: false,
        width: '420px',
        padding: '1.5rem'
    }).then((result) => {
        if (result.isConfirmed) {
            // Convert to 24-hour format for the hidden input
            let hour24 = selectedHour;
            if (selectedPeriod === 'PM' && selectedHour !== 12) {
                hour24 += 12;
            } else if (selectedPeriod === 'AM' && selectedHour === 12) {
                hour24 = 0;
            }
            
            const formattedTime = `${String(hour24).padStart(2, '0')}:${String(selectedMinute).padStart(2, '0')}`;
            document.getElementById(fieldId).value = formattedTime;
            
            const displayTime = `${selectedHour}:${String(selectedMinute).padStart(2, '0')} ${selectedPeriod}`;
            document.getElementById(fieldId + '_text').textContent = displayTime;
            document.getElementById(fieldId + '_text').classList.remove('text-gray-500');
            document.getElementById(fieldId + '_text').classList.add('text-gray-800');
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/events/create.blade.php ENDPATH**/ ?>