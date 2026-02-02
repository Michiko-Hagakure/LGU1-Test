@extends('layouts.admin')

@section('page-title', 'Edit City Event')
@section('page-subtitle', 'Modify city event details')

@section('page-content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.city-events.show', $cityEvent) }}" class="inline-flex items-center gap-2 text-lgu-paragraph hover:text-lgu-headline transition-colors">
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
    <form method="POST" action="{{ route('admin.city-events.update', $cityEvent) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Event Details Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
            <h3 class="text-xl font-semibold text-lgu-headline">Event Details</h3>

            <!-- Event Title Dropdown with Custom Option -->
            @php
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
            @endphp
            <div>
                <label class="block text-sm font-medium text-lgu-headline mb-2">
                    Event Title <span class="text-lgu-tertiary">*</span>
                </label>
                <select 
                    id="event_title_select"
                    onchange="toggleCustomTitle()"
                    required
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph @error('event_title') border-lgu-tertiary @enderror"
                >
                    <option value="">Select event title</option>
                    @foreach($predefinedTitles as $title)
                        <option value="{{ $title }}" {{ $currentTitle === $title ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                    <option value="custom" {{ $isCustomTitle && $currentTitle ? 'selected' : '' }}>Other (Custom Title)...</option>
                </select>
                
                <!-- Hidden input that actually gets submitted -->
                <input type="hidden" name="event_title" id="event_title_value" value="{{ $currentTitle }}">
                
                <!-- Custom Title Input -->
                <div id="custom_title_wrapper" class="{{ $isCustomTitle && $currentTitle ? '' : 'hidden' }} mt-3">
                    <input 
                        type="text" 
                        id="custom_title_input"
                        value="{{ $isCustomTitle ? $currentTitle : '' }}"
                        placeholder="Enter custom event title"
                        class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph"
                    />
                </div>
                
                <p class="text-xs text-lgu-paragraph mt-1">Select a predefined title or choose "Other" for custom input</p>
                @error('event_title')
                    <p class="text-lgu-tertiary text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Event Description -->
            <div>
                <label class="block text-sm font-medium text-lgu-headline mb-2">
                    Description
                </label>
                <textarea 
                    name="event_description" 
                    rows="3"
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph @error('event_description') border-lgu-tertiary @enderror"
                    placeholder="Enter event description (optional)"
                >{{ old('event_description', $cityEvent->event_description) }}</textarea>
                @error('event_description')
                    <p class="text-lgu-tertiary text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Event Type -->
            <div>
                <label class="block text-sm font-medium text-lgu-headline mb-2">
                    Event Type <span class="text-lgu-tertiary">*</span>
                </label>
                <select 
                    name="event_type" 
                    required
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph @error('event_type') border-lgu-tertiary @enderror"
                >
                    <option value="government" {{ old('event_type', $cityEvent->event_type) === 'government' ? 'selected' : '' }}>Government Event</option>
                    <option value="emergency" {{ old('event_type', $cityEvent->event_type) === 'emergency' ? 'selected' : '' }}>Emergency</option>
                    <option value="maintenance" {{ old('event_type', $cityEvent->event_type) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                @error('event_type')
                    <p class="text-lgu-tertiary text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-lgu-headline mb-2">
                    Status <span class="text-lgu-tertiary">*</span>
                </label>
                <select 
                    name="status" 
                    required
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph @error('status') border-lgu-tertiary @enderror"
                >
                    <option value="scheduled" {{ old('status', $cityEvent->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="ongoing" {{ old('status', $cityEvent->status) === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ old('status', $cityEvent->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status', $cityEvent->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <p class="text-lgu-tertiary text-sm mt-1">{{ $message }}</p>
                @enderror
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
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph @error('facility_id') border-lgu-tertiary @enderror"
                >
                    <option value="">Select facility</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}" {{ old('facility_id', $cityEvent->facility_id) == $facility->id ? 'selected' : '' }}>
                            {{ $facility->name }}
                        </option>
                    @endforeach
                </select>
                @error('facility_id')
                    <p class="text-lgu-tertiary text-sm mt-1">{{ $message }}</p>
                @enderror
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
                        {{ \Carbon\Carbon::parse($cityEvent->start_time)->format('l, F d, Y') }}
                    </span>
                    <i data-lucide="calendar" class="w-5 h-5 text-lgu-headline"></i>
                </button>
                <input type="hidden" id="event_date" value="{{ \Carbon\Carbon::parse($cityEvent->start_time)->format('Y-m-d') }}">
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
                        class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg hover:border-lgu-highlight focus:border-lgu-highlight focus:outline-none text-left text-lgu-paragraph flex items-center justify-between @error('start_time') border-lgu-tertiary @enderror"
                    >
                        <span id="start_time_display" class="text-lgu-headline font-medium">
                            {{ \Carbon\Carbon::parse($cityEvent->start_time)->format('g:i A') }}
                        </span>
                        <i data-lucide="clock" class="w-5 h-5 text-lgu-headline"></i>
                    </button>
                    <input type="hidden" name="start_time" id="start_time" value="{{ old('start_time', $cityEvent->start_time) }}" required>
                    @error('start_time')
                        <p class="text-lgu-tertiary text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-lgu-headline mb-2">
                        End Time <span class="text-lgu-tertiary">*</span>
                    </label>
                    <button 
                        type="button"
                        onclick="openTimePicker('end')"
                        class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg hover:border-lgu-highlight focus:border-lgu-highlight focus:outline-none text-left text-lgu-paragraph flex items-center justify-between @error('end_time') border-lgu-tertiary @enderror"
                    >
                        <span id="end_time_display" class="text-lgu-headline font-medium">
                            {{ \Carbon\Carbon::parse($cityEvent->end_time)->format('g:i A') }}
                        </span>
                        <i data-lucide="clock" class="w-5 h-5 text-lgu-headline"></i>
                    </button>
                    <input type="hidden" name="end_time" id="end_time" value="{{ old('end_time', $cityEvent->end_time) }}" required>
                    @error('end_time')
                        <p class="text-lgu-tertiary text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <i data-lucide="save" class="w-5 h-5"></i>
                <span>Save Changes</span>
            </button>
            <a href="{{ route('admin.city-events.show', $cityEvent) }}" class="btn-secondary flex items-center gap-2">
                <i data-lucide="x" class="w-5 h-5"></i>
                <span>Cancel</span>
            </a>
        </div>
    </form>
</div>

@push('scripts')
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
let selectedDate = '{{ \Carbon\Carbon::parse($cityEvent->start_time)->format('Y-m-d') }}';
let selectedStartTime = '{{ \Carbon\Carbon::parse($cityEvent->start_time)->format('H:i') }}';
let selectedEndTime = '{{ \Carbon\Carbon::parse($cityEvent->end_time)->format('H:i') }}';

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
@endpush
@endsection
