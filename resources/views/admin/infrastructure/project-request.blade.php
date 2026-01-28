@extends('layouts.admin')

@section('page-title', 'Infrastructure Project Request')
@section('page-subtitle', 'Submit a new infrastructure project request to Infrastructure PM')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
<style>
    #locationMap { z-index: 0; }
    #locationMap.fullscreen-map {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 9999 !important;
        border-radius: 0 !important;
    }
    .fullscreen-btn {
        background: white;
        border: 2px solid rgba(0,0,0,0.2);
        border-radius: 4px;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 14px;
    }
    .fullscreen-btn:hover {
        background: #f4f4f4;
    }
    .leaflet-control-geocoder {
        clear: both;
    }
</style>
@endpush

@section('page-content')
<div class="max-w-5xl mx-auto">
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

    <form action="{{ URL::signedRoute('admin.infrastructure.project-request.store') }}" method="POST" enctype="multipart/form-data" id="projectRequestForm">
        @csrf

        {{-- Section 1: Contact Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Contact Information
                </h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="requesting_office" class="block text-sm font-medium text-gray-700 mb-2">
                        Requesting Office <span class="text-red-500">*</span>
                    </label>
                    <select id="requesting_office" name="requesting_office" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors" required>
                        <option value="">Select office...</option>
                        <option value="Mayor's Office" {{ old('requesting_office') == "Mayor's Office" ? 'selected' : '' }}>Mayor's Office</option>
                        <option value="Vice Mayor's Office" {{ old('requesting_office') == "Vice Mayor's Office" ? 'selected' : '' }}>Vice Mayor's Office</option>
                        <option value="Sangguniang Bayan" {{ old('requesting_office') == "Sangguniang Bayan" ? 'selected' : '' }}>Sangguniang Bayan</option>
                        <option value="Municipal Administrator's Office" {{ old('requesting_office') == "Municipal Administrator's Office" ? 'selected' : '' }}>Municipal Administrator's Office</option>
                        <option value="Municipal Engineering Office" {{ old('requesting_office') == "Municipal Engineering Office" ? 'selected' : '' }}>Municipal Engineering Office</option>
                        <option value="Municipal Planning and Development Office" {{ old('requesting_office') == "Municipal Planning and Development Office" ? 'selected' : '' }}>Municipal Planning and Development Office</option>
                        <option value="Municipal Budget Office" {{ old('requesting_office') == "Municipal Budget Office" ? 'selected' : '' }}>Municipal Budget Office</option>
                        <option value="Municipal Treasurer's Office" {{ old('requesting_office') == "Municipal Treasurer's Office" ? 'selected' : '' }}>Municipal Treasurer's Office</option>
                        <option value="Municipal Assessor's Office" {{ old('requesting_office') == "Municipal Assessor's Office" ? 'selected' : '' }}>Municipal Assessor's Office</option>
                        <option value="Municipal Health Office" {{ old('requesting_office') == "Municipal Health Office" ? 'selected' : '' }}>Municipal Health Office</option>
                        <option value="Municipal Social Welfare and Development Office" {{ old('requesting_office') == "Municipal Social Welfare and Development Office" ? 'selected' : '' }}>Municipal Social Welfare and Development Office</option>
                        <option value="Municipal Agriculture Office" {{ old('requesting_office') == "Municipal Agriculture Office" ? 'selected' : '' }}>Municipal Agriculture Office</option>
                        <option value="Municipal Environment and Natural Resources Office" {{ old('requesting_office') == "Municipal Environment and Natural Resources Office" ? 'selected' : '' }}>Municipal Environment and Natural Resources Office</option>
                        <option value="Municipal Disaster Risk Reduction and Management Office" {{ old('requesting_office') == "Municipal Disaster Risk Reduction and Management Office" ? 'selected' : '' }}>Municipal Disaster Risk Reduction and Management Office</option>
                        <option value="Barangay Office" {{ old('requesting_office') == "Barangay Office" ? 'selected' : '' }}>Barangay Office</option>
                        <option value="Other" {{ old('requesting_office') == "Other" ? 'selected' : '' }}>Other</option>
                    </select>
                    <input type="text" id="requesting_office_other" name="requesting_office_other" 
                        value="{{ old('requesting_office_other') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors mt-2 hidden"
                        placeholder="Please specify office...">
                </div>

                <div>
                    <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Person <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="contact_person" name="contact_person" 
                        value="{{ old('contact_person') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="Full name of contact person" required>
                </div>

                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                        Position
                    </label>
                    <select id="position" name="position" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors">
                        <option value="">Select position...</option>
                        <option value="Department Head" {{ old('position') == 'Department Head' ? 'selected' : '' }}>Department Head</option>
                        <option value="Division Chief" {{ old('position') == 'Division Chief' ? 'selected' : '' }}>Division Chief</option>
                        <option value="Engineer" {{ old('position') == 'Engineer' ? 'selected' : '' }}>Engineer</option>
                        <option value="Architect" {{ old('position') == 'Architect' ? 'selected' : '' }}>Architect</option>
                        <option value="Accountant" {{ old('position') == 'Accountant' ? 'selected' : '' }}>Accountant</option>
                        <option value="Administrative Officer" {{ old('position') == 'Administrative Officer' ? 'selected' : '' }}>Administrative Officer</option>
                        <option value="Administrative Aide" {{ old('position') == 'Administrative Aide' ? 'selected' : '' }}>Administrative Aide</option>
                        <option value="Secretary" {{ old('position') == 'Secretary' ? 'selected' : '' }}>Secretary</option>
                        <option value="Clerk" {{ old('position') == 'Clerk' ? 'selected' : '' }}>Clerk</option>
                        <option value="Staff" {{ old('position') == 'Staff' ? 'selected' : '' }}>Staff</option>
                        <option value="Barangay Captain" {{ old('position') == 'Barangay Captain' ? 'selected' : '' }}>Barangay Captain</option>
                        <option value="Barangay Councilor" {{ old('position') == 'Barangay Councilor' ? 'selected' : '' }}>Barangay Councilor</option>
                        <option value="Barangay Secretary" {{ old('position') == 'Barangay Secretary' ? 'selected' : '' }}>Barangay Secretary</option>
                        <option value="Barangay Treasurer" {{ old('position') == 'Barangay Treasurer' ? 'selected' : '' }}>Barangay Treasurer</option>
                        <option value="Other" {{ old('position') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <input type="text" id="position_other" name="position_other" 
                        value="{{ old('position_other') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors mt-2 hidden"
                        placeholder="Please specify position...">
                </div>

                <div>
                    <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Number
                    </label>
                    <input type="tel" id="contact_number" name="contact_number" 
                        value="{{ old('contact_number') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="09123456789">
                </div>

                <div class="md:col-span-2">
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Email
                    </label>
                    <input type="email" id="contact_email" name="contact_email" 
                        value="{{ old('contact_email') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="contact@example.com">
                </div>
            </div>
        </div>

        {{-- Section 2: Project Details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Project Details
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="project_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Project Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="project_title" name="project_title" 
                            value="{{ old('project_title') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                            placeholder="e.g., Road Construction Project - Barangay Main Road" required>
                    </div>

                    <div>
                        <label for="project_category" class="block text-sm font-medium text-gray-700 mb-2">
                            Project Category <span class="text-red-500">*</span>
                        </label>
                        <select id="project_category" name="project_category" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors" required>
                            <option value="">Select category...</option>
                            @foreach($projectCategories as $category)
                            <option value="{{ $category }}" {{ old('project_category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                            @endforeach
                            <option value="Other" {{ old('project_category') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <input type="text" id="project_category_other" name="project_category_other" 
                            value="{{ old('project_category_other') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors mt-2 hidden"
                            placeholder="Please specify category...">
                    </div>

                    <div>
                        <label for="priority_level" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority Level
                        </label>
                        <select id="priority_level" name="priority_level" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors">
                            <option value="">Select priority...</option>
                            @foreach($priorityLevels as $level)
                            <option value="{{ $level['value'] }}" {{ old('priority_level') == $level['value'] ? 'selected' : '' }}>
                                {{ $level['label'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="problem_identified" class="block text-sm font-medium text-gray-700 mb-2">
                        Problem Identified <span class="text-red-500">*</span>
                    </label>
                    <textarea id="problem_identified" name="problem_identified" rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors resize-none"
                        placeholder="Describe the problem or need that this project aims to address..." required>{{ old('problem_identified') }}</textarea>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-4">Scope of Work (Optional)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="scope_item1" class="block text-xs text-gray-500 mb-1">Scope Item 1</label>
                            <input type="text" id="scope_item1" name="scope_item1" 
                                value="{{ old('scope_item1') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                                placeholder="e.g., Concrete work">
                        </div>
                        <div>
                            <label for="scope_item2" class="block text-xs text-gray-500 mb-1">Scope Item 2</label>
                            <input type="text" id="scope_item2" name="scope_item2" 
                                value="{{ old('scope_item2') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                                placeholder="e.g., Drainage system">
                        </div>
                        <div>
                            <label for="scope_item3" class="block text-xs text-gray-500 mb-1">Scope Item 3</label>
                            <input type="text" id="scope_item3" name="scope_item3" 
                                value="{{ old('scope_item3') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                                placeholder="e.g., Road markings">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: Location & Budget --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Location & Budget
                </h2>
            </div>
            <div class="p-6 space-y-6">
                {{-- Interactive Map --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Select Location on Map <span class="text-gray-400 text-xs">(Click to pin location)</span>
                    </label>
                    <div id="locationMap" class="w-full h-64 rounded-lg border border-gray-300 z-0"></div>
                    <p class="mt-2 text-xs text-gray-500">Click on the map to set the project location. The address, latitude, and longitude will be filled automatically.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="project_location" class="block text-sm font-medium text-gray-700 mb-2">
                            Project Location
                        </label>
                        <input type="text" id="project_location" name="project_location" 
                            value="{{ old('project_location') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                            placeholder="e.g., Main Street, Barangay 123, City">
                    </div>

                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Latitude
                        </label>
                        <input type="number" step="any" id="latitude" name="latitude" 
                            value="{{ old('latitude') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors bg-gray-50"
                            placeholder="e.g., 14.5995" readonly>
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Longitude
                        </label>
                        <input type="number" step="any" id="longitude" name="longitude" 
                            value="{{ old('longitude') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors bg-gray-50"
                            placeholder="e.g., 120.9842" readonly>
                    </div>
                </div>

                <div>
                    <label for="estimated_budget" class="block text-sm font-medium text-gray-700 mb-2">
                        Estimated Budget (₱)
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                        <input type="number" step="0.01" min="0" id="estimated_budget" name="estimated_budget" 
                            value="{{ old('estimated_budget') }}" 
                            class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                            placeholder="500,000.00">
                    </div>
                </div>

                <div>
                    <label for="requested_start_date_display" class="block text-sm font-medium text-gray-700 mb-2">
                        Requested Start Date
                    </label>
                    <input type="hidden" id="requested_start_date" name="requested_start_date" value="{{ old('requested_start_date') }}">
                    <input type="text" id="requested_start_date_display" 
                        value="{{ old('requested_start_date') ? date('F d, Y', strtotime(old('requested_start_date'))) : '' }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors cursor-pointer bg-white"
                        placeholder="Click to select date..."
                        readonly>
                </div>
            </div>
        </div>

        {{-- Section 4: Prepared By --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Prepared By
                </h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="prepared_by" class="block text-sm font-medium text-gray-700 mb-2">
                        Name
                    </label>
                    <input type="text" id="prepared_by" name="prepared_by" 
                        value="{{ session('user_name') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                        readonly>
                </div>

                <div>
                    <label for="prepared_position" class="block text-sm font-medium text-gray-700 mb-2">
                        Position
                    </label>
                    <input type="text" id="prepared_position" name="prepared_position" 
                        value="{{ session('user_role', 'Staff') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                        readonly>
                </div>
            </div>
        </div>

        {{-- Section 5: Attachments --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    Attachments (Optional)
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Site Photos (Max 3 images, 2MB each)
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-lgu-highlight transition-colors" id="photosDropZone">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">Drag and drop photos here, or click to select</p>
                        <input type="file" name="photos[]" multiple accept="image/*" class="hidden" id="photosInput">
                        <button type="button" onclick="document.getElementById('photosInput').click()" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                            Select Photos
                        </button>
                    </div>
                    <div id="photosPreview" class="mt-3 grid grid-cols-5 gap-2"></div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Location/Site Map (1 image, 2MB max)
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-lgu-highlight transition-colors">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">Upload a map or site plan</p>
                        <input type="file" name="map_image" accept="image/*" class="hidden" id="mapInput">
                        <button type="button" onclick="document.getElementById('mapInput').click()" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                            Select Map Image
                        </button>
                    </div>
                    <div id="mapPreview" class="mt-3"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Resolution/Approval Document (PDF, 2MB max)
                        </label>
                        <input type="file" name="resolution_file" accept=".pdf" id="resolutionInput" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-lgu-highlight file:text-white hover:file:bg-lgu-stroke">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Other Supporting Documents (PDF/DOC, 2MB each, max 2)
                        </label>
                        <input type="file" name="other_files[]" multiple accept=".pdf,.doc,.docx" id="otherFilesInput" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-lgu-highlight file:text-white hover:file:bg-lgu-stroke">
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end gap-4">
            <button type="button" onclick="window.history.back()" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                Cancel
            </button>
            <button type="submit" id="submitBtn" class="px-8 py-3 bg-lgu-highlight text-white rounded-lg hover:bg-lgu-stroke transition-colors font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Submit Project Request
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Custom Calendar Picker for Requested Start Date
    const dateDisplay = document.getElementById('requested_start_date_display');
    const dateHidden = document.getElementById('requested_start_date');
    let currentMonth, currentYear, selectedDate = null;
    
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const days = ['SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA'];
    
    function generateCalendar(month, year) {
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        let html = `
            <div style="text-align: center; padding: 10px 0;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 20px;">
                    <button type="button" id="prevMonth" style="width: 36px; height: 36px; border: 1px solid #ddd; border-radius: 50%; background: #fff; cursor: pointer; font-size: 18px;">‹</button>
                    <span style="font-size: 18px; font-weight: 600; min-width: 150px;">${months[month]} ${year}</span>
                    <button type="button" id="nextMonth" style="width: 36px; height: 36px; border: 1px solid #ddd; border-radius: 50%; background: #fff; cursor: pointer; font-size: 18px;">›</button>
                </div>
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; margin-bottom: 10px;">
                    ${days.map(d => `<div style="font-size: 12px; font-weight: 600; color: #666; padding: 8px;">${d}</div>`).join('')}
                </div>
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px;">`;
        
        // Empty cells before first day
        for (let i = 0; i < firstDay; i++) {
            html += `<div style="padding: 10px;"></div>`;
        }
        
        // Days of month
        for (let day = 1; day <= daysInMonth; day++) {
            const dateObj = new Date(year, month, day);
            const isPast = dateObj < tomorrow;
            const isSelected = selectedDate && selectedDate.getTime() === dateObj.getTime();
            const isToday = dateObj.toDateString() === today.toDateString();
            
            let style = 'width: 36px; height: 36px; border-radius: 50%; border: none; cursor: pointer; font-size: 14px; margin: auto;';
            if (isPast) {
                style += ' color: #ccc; cursor: not-allowed; background: transparent;';
            } else if (isSelected) {
                style += ' background: #1a5632; color: white;';
            } else if (isToday) {
                style += ' background: #e8f5e9; color: #1a5632; font-weight: bold;';
            } else {
                style += ' background: transparent; color: #333;';
            }
            
            html += `<button type="button" class="cal-day" data-day="${day}" ${isPast ? 'disabled' : ''} style="${style}">${day}</button>`;
        }
        
        html += `</div></div>`;
        return html;
    }
    
    function showCalendarModal() {
        const existing = dateHidden.value ? new Date(dateHidden.value + 'T00:00:00') : null;
        if (existing) selectedDate = existing;
        
        const now = new Date();
        currentMonth = selectedDate ? selectedDate.getMonth() : now.getMonth();
        currentYear = selectedDate ? selectedDate.getFullYear() : now.getFullYear();
        
        Swal.fire({
            title: 'Select Start Date',
            html: generateCalendar(currentMonth, currentYear),
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            confirmButtonColor: '#1a5632',
            cancelButtonText: 'Cancel',
            width: 380,
            didOpen: () => {
                attachCalendarEvents();
            },
            preConfirm: () => {
                if (!selectedDate) {
                    Swal.showValidationMessage('Please select a date');
                    return false;
                }
                return selectedDate;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const d = result.value;
                const yyyy = d.getFullYear();
                const mm = String(d.getMonth() + 1).padStart(2, '0');
                const dd = String(d.getDate()).padStart(2, '0');
                dateHidden.value = `${yyyy}-${mm}-${dd}`;
                dateDisplay.value = d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            }
        });
    }
    
    function attachCalendarEvents() {
        document.getElementById('prevMonth')?.addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) { currentMonth = 11; currentYear--; }
            Swal.update({ html: generateCalendar(currentMonth, currentYear) });
            attachCalendarEvents();
        });
        
        document.getElementById('nextMonth')?.addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) { currentMonth = 0; currentYear++; }
            Swal.update({ html: generateCalendar(currentMonth, currentYear) });
            attachCalendarEvents();
        });
        
        document.querySelectorAll('.cal-day:not([disabled])').forEach(btn => {
            btn.addEventListener('click', () => {
                const day = parseInt(btn.dataset.day);
                selectedDate = new Date(currentYear, currentMonth, day);
                Swal.update({ html: generateCalendar(currentMonth, currentYear) });
                attachCalendarEvents();
            });
        });
    }
    
    dateDisplay.addEventListener('click', showCalendarModal);
    // Initialize Leaflet Map
    const defaultLat = {{ old('latitude') ?: '14.5995' }};
    const defaultLng = {{ old('longitude') ?: '120.9842' }};
    const mapContainer = document.getElementById('locationMap');
    const map = L.map('locationMap').setView([defaultLat, defaultLng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);
    
    let marker = null;
    let isFullscreen = false;
    
    // Add Fullscreen Control (bottom-right)
    const FullscreenControl = L.Control.extend({
        options: { position: 'bottomright' },
        onAdd: function(map) {
            const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
            const btn = L.DomUtil.create('a', '', container);
            btn.innerHTML = '⛶';
            btn.href = '#';
            btn.title = 'Fullscreen';
            btn.style.cssText = 'font-size:20px;width:34px;height:34px;line-height:34px;text-align:center;display:block;text-decoration:none;color:#333;background:#fff;';
            
            L.DomEvent.on(btn, 'click', function(e) {
                L.DomEvent.preventDefault(e);
                L.DomEvent.stopPropagation(e);
                isFullscreen = !isFullscreen;
                if (isFullscreen) {
                    mapContainer.classList.add('fullscreen-map');
                    btn.innerHTML = '✕';
                } else {
                    mapContainer.classList.remove('fullscreen-map');
                    btn.innerHTML = '⛶';
                }
                setTimeout(() => map.invalidateSize(), 100);
            });
            
            return container;
        }
    });
    map.addControl(new FullscreenControl());
    
    // Add Search/Geocoder Control with Philippines bias
    const geocoder = L.Control.geocoder({
        defaultMarkGeocode: false,
        placeholder: 'Search location in Philippines...',
        collapsed: false,
        geocoder: L.Control.Geocoder.nominatim({
            geocodingQueryParams: {
                countrycodes: 'ph',
                viewbox: '116.9,4.5,127.0,21.2',
                bounded: 0
            }
        })
    }).on('markgeocode', function(e) {
        const latlng = e.geocode.center;
        const lat = latlng.lat.toFixed(7);
        const lng = latlng.lng.toFixed(7);
        
        // Update form fields
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        document.getElementById('project_location').value = e.geocode.name;
        
        // Update or create marker
        if (marker) {
            marker.setLatLng(latlng);
        } else {
            marker = L.marker(latlng).addTo(map);
        }
        
        map.setView(latlng, 16);
    }).addTo(map);
    
    // ESC key to exit fullscreen
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isFullscreen) {
            isFullscreen = false;
            mapContainer.classList.remove('fullscreen-map');
            map.invalidateSize();
        }
    });
    
    // If we have old values, place a marker
    @if(old('latitude') && old('longitude'))
    marker = L.marker([{{ old('latitude') }}, {{ old('longitude') }}]).addTo(map);
    @endif
    
    // Handle map click
    map.on('click', function(e) {
        const lat = e.latlng.lat.toFixed(7);
        const lng = e.latlng.lng.toFixed(7);
        
        // Update form fields
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        // Update or create marker
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }
        
        // Reverse geocode to get address
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    document.getElementById('project_location').value = data.display_name;
                }
            })
            .catch(err => console.log('Geocoding error:', err));
    });

    // Other option handling for Requesting Office
    const requestingOfficeSelect = document.getElementById('requesting_office');
    const requestingOfficeOther = document.getElementById('requesting_office_other');
    
    function toggleRequestingOfficeOther() {
        if (requestingOfficeSelect.value === 'Other') {
            requestingOfficeOther.classList.remove('hidden');
            requestingOfficeOther.required = true;
        } else {
            requestingOfficeOther.classList.add('hidden');
            requestingOfficeOther.required = false;
            requestingOfficeOther.value = '';
        }
    }
    
    requestingOfficeSelect.addEventListener('change', toggleRequestingOfficeOther);
    toggleRequestingOfficeOther(); // Check on page load
    
    // Other option handling for Position
    const positionSelect = document.getElementById('position');
    const positionOther = document.getElementById('position_other');
    
    function togglePositionOther() {
        if (positionSelect.value === 'Other') {
            positionOther.classList.remove('hidden');
        } else {
            positionOther.classList.add('hidden');
            positionOther.value = '';
        }
    }
    
    positionSelect.addEventListener('change', togglePositionOther);
    togglePositionOther(); // Check on page load

    // Other option handling for Project Category
    const projectCategorySelect = document.getElementById('project_category');
    const projectCategoryOther = document.getElementById('project_category_other');
    
    function toggleProjectCategoryOther() {
        if (projectCategorySelect.value === 'Other') {
            projectCategoryOther.classList.remove('hidden');
            projectCategoryOther.required = true;
        } else {
            projectCategoryOther.classList.add('hidden');
            projectCategoryOther.required = false;
            projectCategoryOther.value = '';
        }
    }
    
    projectCategorySelect.addEventListener('change', toggleProjectCategoryOther);
    toggleProjectCategoryOther(); // Check on page load

    // Photo preview handling
    const photosInput = document.getElementById('photosInput');
    const photosPreview = document.getElementById('photosPreview');
    
    photosInput.addEventListener('change', function() {
        photosPreview.innerHTML = '';
        const files = Array.from(this.files).slice(0, 5);
        
        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-20 object-cover rounded-lg border border-gray-200">
                    <span class="absolute bottom-1 left-1 bg-black/50 text-white text-xs px-1 rounded">${index + 1}</span>
                `;
                photosPreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });

    // Map preview handling
    const mapInput = document.getElementById('mapInput');
    const mapPreview = document.getElementById('mapPreview');
    
    mapInput.addEventListener('change', function() {
        mapPreview.innerHTML = '';
        if (this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                mapPreview.innerHTML = `<img src="${e.target.result}" class="max-w-xs h-32 object-cover rounded-lg border border-gray-200">`;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // File size validation constants (server limits)
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB per file
    const MAX_TOTAL_SIZE = 7 * 1024 * 1024; // 7MB total (leave 1MB buffer for form data)
    
    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }
    
    function validateFileSizes() {
        let totalSize = 0;
        const errors = [];
        
        // Check photos
        const photos = document.getElementById('photosInput').files;
        for (let i = 0; i < photos.length; i++) {
            if (photos[i].size > MAX_FILE_SIZE) {
                errors.push(`Photo "${photos[i].name}" is ${formatFileSize(photos[i].size)} (max 2MB)`);
            }
            totalSize += photos[i].size;
        }
        
        // Check map image
        const mapFile = document.getElementById('mapInput').files[0];
        if (mapFile) {
            if (mapFile.size > MAX_FILE_SIZE) {
                errors.push(`Map image "${mapFile.name}" is ${formatFileSize(mapFile.size)} (max 2MB)`);
            }
            totalSize += mapFile.size;
        }
        
        // Check resolution file
        const resFile = document.getElementById('resolutionInput').files[0];
        if (resFile) {
            if (resFile.size > MAX_FILE_SIZE) {
                errors.push(`Resolution file "${resFile.name}" is ${formatFileSize(resFile.size)} (max 2MB)`);
            }
            totalSize += resFile.size;
        }
        
        // Check other files
        const otherFiles = document.getElementById('otherFilesInput').files;
        for (let i = 0; i < otherFiles.length; i++) {
            if (otherFiles[i].size > MAX_FILE_SIZE) {
                errors.push(`File "${otherFiles[i].name}" is ${formatFileSize(otherFiles[i].size)} (max 2MB)`);
            }
            totalSize += otherFiles[i].size;
        }
        
        // Check total size
        if (totalSize > MAX_TOTAL_SIZE) {
            errors.push(`Total file size is ${formatFileSize(totalSize)} (max 7MB combined)`);
        }
        
        return errors;
    }
    
    // Form submission with validation
    const form = document.getElementById('projectRequestForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function(e) {
        const errors = validateFileSizes();
        
        if (errors.length > 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'File Size Error',
                html: '<ul style="text-align:left;margin-left:20px;">' + errors.map(err => `<li>${err}</li>`).join('') + '</ul>',
                confirmButtonColor: '#1a5632'
            });
            return false;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Submitting...
        `;
    });
});
</script>
@endpush
