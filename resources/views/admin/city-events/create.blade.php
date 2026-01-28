@extends('layouts.admin')

@section('page-title', 'Create City Event')
@section('page-subtitle', 'Schedule a new government event or priority booking')

@section('page-content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ URL::signedRoute('admin.city-events.index') }}" class="p-2 hover:bg-lgu-bg rounded-lg transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5 text-lgu-headline"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-lgu-headline">Create City Event</h2>
            <p class="text-sm text-lgu-paragraph mt-1">Schedule a government event and manage conflicting bookings</p>
        </div>
    </div>

    <!-- Error Messages -->
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-600 p-4 rounded-lg">
        <div class="flex items-start">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="font-semibold text-red-900">Error</p>
                <p class="text-sm text-red-800 mt-1">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-600 p-4 rounded-lg">
        <div class="flex items-start">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="font-semibold text-red-900">Validation Errors</p>
                <ul class="list-disc list-inside text-sm text-red-800 mt-2 space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ URL::signedRoute('admin.city-events.store') }}" method="POST" id="cityEventForm" class="space-y-6" onsubmit="return prepareFormSubmit()">
        @csrf

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
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph @error('event_title') border-lgu-tertiary @enderror"
                >
                    <option value="">Select event title</option>
                    <option value="Annual City Anniversary Celebration" {{ old('event_title') === 'Annual City Anniversary Celebration' ? 'selected' : '' }}>Annual City Anniversary Celebration</option>
                    <option value="Independence Day Celebration" {{ old('event_title') === 'Independence Day Celebration' ? 'selected' : '' }}>Independence Day Celebration</option>
                    <option value="Founding Anniversary" {{ old('event_title') === 'Founding Anniversary' ? 'selected' : '' }}>Founding Anniversary</option>
                    <option value="National Heroes Day" {{ old('event_title') === 'National Heroes Day' ? 'selected' : '' }}>National Heroes Day</option>
                    <option value="Christmas Community Event" {{ old('event_title') === 'Christmas Community Event' ? 'selected' : '' }}>Christmas Community Event</option>
                    <option value="New Year's Celebration" {{ old('event_title') === 'New Year\'s Celebration' ? 'selected' : '' }}>New Year's Celebration</option>
                    <option value="Barangay Assembly Meeting" {{ old('event_title') === 'Barangay Assembly Meeting' ? 'selected' : '' }}>Barangay Assembly Meeting</option>
                    <option value="Emergency Evacuation Center" {{ old('event_title') === 'Emergency Evacuation Center' ? 'selected' : '' }}>Emergency Evacuation Center</option>
                    <option value="Disaster Response Operations" {{ old('event_title') === 'Disaster Response Operations' ? 'selected' : '' }}>Disaster Response Operations</option>
                    <option value="Medical Mission" {{ old('event_title') === 'Medical Mission' ? 'selected' : '' }}>Medical Mission</option>
                    <option value="Vaccination Drive" {{ old('event_title') === 'Vaccination Drive' ? 'selected' : '' }}>Vaccination Drive</option>
                    <option value="Facility Maintenance" {{ old('event_title') === 'Facility Maintenance' ? 'selected' : '' }}>Facility Maintenance</option>
                    <option value="Equipment Repair" {{ old('event_title') === 'Equipment Repair' ? 'selected' : '' }}>Equipment Repair</option>
                    <option value="Building Renovation" {{ old('event_title') === 'Building Renovation' ? 'selected' : '' }}>Building Renovation</option>
                    <option value="custom">Other (Custom Title)...</option>
                </select>
                
                <!-- Hidden input that actually gets submitted -->
                <input type="hidden" name="event_title" id="event_title_value" value="{{ old('event_title') }}">
                
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
                @error('event_title')
                    <p class="text-lgu-tertiary text-sm mt-1">{{ $message }}</p>
                @enderror
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
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph @error('event_description') border-lgu-tertiary @enderror"
                >{{ old('event_description') }}</textarea>
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
                    <option value="">Select event type</option>
                    <option value="government" {{ old('event_type') === 'government' ? 'selected' : '' }}>Government Event</option>
                    <option value="emergency" {{ old('event_type') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                    <option value="maintenance" {{ old('event_type') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                @error('event_type')
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
                    onchange="checkConflicts()"
                    class="w-full px-4 py-3 border-2 border-lgu-stroke rounded-lg focus:border-lgu-highlight focus:outline-none text-lgu-paragraph @error('facility_id') border-lgu-tertiary @enderror"
                >
                    <option value="">Select facility</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
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
                    <span id="event_date_display" class="text-gray-400">Select event date</span>
                    <i data-lucide="calendar" class="w-5 h-5 text-lgu-headline"></i>
                </button>
                <input type="hidden" id="event_date" value="{{ old('event_date') }}">
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
                        <span id="start_time_display" class="text-gray-400">Select start time</span>
                        <i data-lucide="clock" class="w-5 h-5 text-lgu-headline"></i>
                    </button>
                    <input type="hidden" name="start_time" id="start_time" value="{{ old('start_time') }}" required>
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
                        <span id="end_time_display" class="text-gray-400">Select end time</span>
                        <i data-lucide="clock" class="w-5 h-5 text-lgu-headline"></i>
                    </button>
                    <input type="hidden" name="end_time" id="end_time" value="{{ old('end_time') }}" required>
                    @error('end_time')
                        <p class="text-lgu-tertiary text-sm mt-1">{{ $message }}</p>
                    @enderror
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

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <i data-lucide="check" class="w-5 h-5"></i>
                <span>Create City Event</span>
            </button>
            <a href="{{ URL::signedRoute('admin.city-events.index') }}" class="btn-secondary flex items-center gap-2">
                <i data-lucide="x" class="w-5 h-5"></i>
                <span>Cancel</span>
            </a>
        </div>
    </form>
</div>

@push('scripts')
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

        fetch('{{ URL::signedRoute('admin.city-events.preview-conflicts') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
});

// Selected date and times storage
let selectedDate = null;
let selectedStartTime = null;
let selectedEndTime = null;

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
    
    // Empty cells before first day
    for (let i = 0; i < startingDay; i++) {
        html += '<div class="p-2"></div>';
    }
    
    // Days of the month
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
    const now = new Date();
    currentCalendarYear = now.getFullYear();
    currentCalendarMonth = now.getMonth();
    
    const currentSelectedDate = document.getElementById('event_date').value || null;
    
    Swal.fire({
        title: 'Select Event Date',
        html: generateCalendarHTML(currentCalendarYear, currentCalendarMonth, currentSelectedDate),
        showCancelButton: true,
        showConfirmButton: false,
        cancelButtonText: 'Cancel',
        cancelButtonColor: '#6b7280',
        width: 400,
        customClass: {
            popup: 'rounded-xl'
        }
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
    
    const currentSelectedDate = document.getElementById('event_date').value || null;
    Swal.update({
        html: generateCalendarHTML(currentCalendarYear, currentCalendarMonth, currentSelectedDate)
    });
}

function selectDate(dateStr) {
    selectedDate = dateStr;
    document.getElementById('event_date').value = dateStr;
    
    // Format display
    const date = new Date(dateStr + 'T00:00:00');
    const displayText = date.toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    });
    
    const displayEl = document.getElementById('event_date_display');
    displayEl.textContent = displayText;
    displayEl.classList.remove('text-gray-400');
    displayEl.classList.add('text-lgu-headline', 'font-medium');
    
    Swal.close();
    updateHiddenDateTimeFields();
}

// Generate time slots HTML for SweetAlert2 (8:00 AM to 9:00 PM)
function generateTimePickerHTML(type, selectedTime) {
    const times = [];
    // Generate times from 8:00 AM to 9:00 PM (21:00)
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
    // Check if date is selected first
    const eventDate = document.getElementById('event_date').value;
    if (!eventDate) {
        Swal.fire({
            icon: 'warning',
            title: 'Select Date First',
            text: 'Please select an event date before choosing the time.',
            confirmButtonColor: '#faae2b'
        });
        return;
    }
    
    const currentTime = type === 'start' ? selectedStartTime : selectedEndTime;
    
    Swal.fire({
        title: type === 'start' ? 'Select Start Time' : 'Select End Time',
        html: generateTimePickerHTML(type, currentTime),
        showCancelButton: true,
        showConfirmButton: false,
        cancelButtonText: 'Cancel',
        cancelButtonColor: '#6b7280',
        width: 380,
        customClass: {
            popup: 'rounded-xl'
        }
    });
}

function selectTime(type, timeValue, displayText) {
    if (type === 'start') {
        selectedStartTime = timeValue;
        const displayEl = document.getElementById('start_time_display');
        displayEl.textContent = displayText;
        displayEl.classList.remove('text-gray-400');
        displayEl.classList.add('text-lgu-headline', 'font-medium');
    } else {
        selectedEndTime = timeValue;
        const displayEl = document.getElementById('end_time_display');
        displayEl.textContent = displayText;
        displayEl.classList.remove('text-gray-400');
        displayEl.classList.add('text-lgu-headline', 'font-medium');
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
    
    // Check for conflicts if both times are set
    if (eventDate && selectedStartTime && selectedEndTime) {
        checkConflicts();
    }
}
</script>
@endpush
@endsection

