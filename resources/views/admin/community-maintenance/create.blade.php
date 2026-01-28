@extends('layouts.admin')

@section('page-title', 'Request Facility Maintenance')
@section('page-subtitle', 'Submit maintenance request to Community Infrastructure Management')

@section('page-content')
<div class="max-w-4xl mx-auto">
    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <div>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <div>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-red-800 font-medium mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    {{-- Integration Info Card --}}
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-start gap-3">
            <i data-lucide="info" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-blue-800 font-medium">Community Infrastructure Maintenance Integration</p>
                <p class="text-blue-700 text-sm mt-1">This form submits maintenance requests to the Community Infrastructure Maintenance Management system for severe facility damage that requires professional attention.</p>
            </div>
        </div>
    </div>

    <form action="{{ URL::signedRoute('admin.community-maintenance.store') }}" method="POST" id="maintenanceRequestForm">
        @csrf

        {{-- Section 1: Facility Selection --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <i data-lucide="building-2" class="w-5 h-5"></i>
                    Facility Information
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label for="facility_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Facility <span class="text-red-500">*</span>
                    </label>
                    <select id="facility_id" name="facility_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors" required>
                        <option value="">Choose a facility...</option>
                        @foreach($facilities as $facility)
                        <option value="{{ $facility->facility_id }}" 
                            data-address="{{ $facility->name }}, {{ $facility->full_address ?? $facility->address }}"
                            {{ old('facility_id') == $facility->facility_id ? 'selected' : '' }}>
                            {{ $facility->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="unit_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Specific Location/Unit Number
                    </label>
                    <input type="text" id="unit_number" name="unit_number" 
                        value="{{ old('unit_number') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="e.g., Building A - Room 101, Main Hall, Sports Court 2">
                </div>
            </div>
        </div>

        {{-- Section 2: Contact Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5"></i>
                    Contact Information
                </h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="resident_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Reporter Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="resident_name" name="resident_name" 
                        value="{{ old('resident_name', session('user_name')) }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="Your full name" required>
                </div>

                <div>
                    <label for="contact_info" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Information <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="contact_info" name="contact_info" 
                        value="{{ old('contact_info') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="Phone number or email address" required>
                </div>
            </div>
        </div>

        {{-- Section 3: Report Details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    Report Details
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="report_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Report Type
                        </label>
                        <select id="report_type" name="report_type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors">
                            @foreach($reportTypes as $type)
                            <option value="{{ $type['value'] }}" {{ old('report_type', 'maintenance') == $type['value'] ? 'selected' : '' }}>
                                {{ $type['label'] }}
                            </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500" id="reportTypeDescription">Facility maintenance issues</p>
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority Level
                        </label>
                        <select id="priority" name="priority" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors">
                            @foreach($priorityLevels as $level)
                            <option value="{{ $level['value'] }}" {{ old('priority', 'medium') == $level['value'] ? 'selected' : '' }}>
                                {{ $level['label'] }} - {{ $level['description'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                        Subject <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="subject" name="subject" 
                        value="{{ old('subject') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="Brief summary of the issue" required>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Detailed Description <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="5" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors resize-none"
                        placeholder="Provide a detailed description of the maintenance issue, including when it started, severity of damage, and any safety concerns..." required>{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Priority Indicator --}}
        <div class="mb-6 p-4 rounded-lg border" id="priorityIndicator" style="display: none;">
            <div class="flex items-center gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                <div>
                    <p class="font-medium" id="priorityIndicatorTitle"></p>
                    <p class="text-sm" id="priorityIndicatorDesc"></p>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end gap-4">
            <a href="{{ URL::signedRoute('admin.community-maintenance.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                View My Reports
            </a>
            <button type="submit" id="submitBtn" class="px-8 py-3 bg-lgu-highlight text-white rounded-lg hover:bg-lgu-stroke transition-colors font-medium flex items-center gap-2">
                <i data-lucide="send" class="w-5 h-5"></i>
                Submit Maintenance Request
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Report type descriptions
    const reportTypeDescriptions = {
        'maintenance': 'Facility maintenance issues',
        'complaint': 'Complaints about facilities or services',
        'suggestion': 'Suggestions for improvement',
        'emergency': 'Emergency situations requiring immediate attention'
    };

    // Priority indicator settings
    const prioritySettings = {
        'low': { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-800', icon: 'text-green-600', title: 'Low Priority', desc: 'This issue will be addressed during regular maintenance schedules.' },
        'medium': { bg: 'bg-yellow-50', border: 'border-yellow-200', text: 'text-yellow-800', icon: 'text-yellow-600', title: 'Medium Priority', desc: 'This issue will be reviewed and addressed soon.' },
        'high': { bg: 'bg-orange-50', border: 'border-orange-200', text: 'text-orange-800', icon: 'text-orange-600', title: 'High Priority', desc: 'This issue requires prompt attention and will be prioritized.' },
        'urgent': { bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-800', icon: 'text-red-600', title: 'Urgent Priority', desc: 'Emergency response team will be notified immediately.' }
    };

    // Auto-fill location when facility is selected
    const facilitySelect = document.getElementById('facility_id');
    const unitNumberInput = document.getElementById('unit_number');
    
    facilitySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const address = selectedOption.getAttribute('data-address');
        if (address) {
            unitNumberInput.value = address;
        }
    });

    // Update report type description
    const reportTypeSelect = document.getElementById('report_type');
    const reportTypeDesc = document.getElementById('reportTypeDescription');
    
    reportTypeSelect.addEventListener('change', function() {
        reportTypeDesc.textContent = reportTypeDescriptions[this.value] || '';
        
        // Auto-set priority to urgent for emergency reports
        if (this.value === 'emergency') {
            document.getElementById('priority').value = 'urgent';
            updatePriorityIndicator('urgent');
        }
    });

    // Update priority indicator
    const prioritySelect = document.getElementById('priority');
    const priorityIndicator = document.getElementById('priorityIndicator');
    
    function updatePriorityIndicator(value) {
        const settings = prioritySettings[value];
        if (settings) {
            priorityIndicator.className = `mb-6 p-4 rounded-lg border ${settings.bg} ${settings.border}`;
            priorityIndicator.querySelector('i').className = `w-5 h-5 ${settings.icon}`;
            document.getElementById('priorityIndicatorTitle').className = `font-medium ${settings.text}`;
            document.getElementById('priorityIndicatorTitle').textContent = settings.title;
            document.getElementById('priorityIndicatorDesc').className = `text-sm ${settings.text}`;
            document.getElementById('priorityIndicatorDesc').textContent = settings.desc;
            priorityIndicator.style.display = 'block';
        }
    }

    prioritySelect.addEventListener('change', function() {
        updatePriorityIndicator(this.value);
    });

    // Initialize priority indicator
    updatePriorityIndicator(prioritySelect.value);

    // Form submission
    const form = document.getElementById('maintenanceRequestForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Submitting...';
    });

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
@endpush
