@extends('layouts.admin')

@section('page-title', 'Infrastructure Project Request')
@section('page-subtitle', 'Submit a new infrastructure project request to Infrastructure PM')

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

    <form action="{{ route('admin.infrastructure.project-request.store') }}" method="POST" enctype="multipart/form-data" id="projectRequestForm">
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
                    <input type="text" id="requesting_office" name="requesting_office" 
                        value="{{ old('requesting_office') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="e.g., Barangay Office, Mayor's Office" required>
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
                    <input type="text" id="position" name="position" 
                        value="{{ old('position') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="e.g., Secretary, Engineer">
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
                        </select>
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
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="e.g., 14.5995">
                </div>

                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                        Longitude
                    </label>
                    <input type="number" step="any" id="longitude" name="longitude" 
                        value="{{ old('longitude') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="e.g., 120.9842">
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
                    <label for="requested_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Requested Start Date
                    </label>
                    <input type="date" id="requested_start_date" name="requested_start_date" 
                        value="{{ old('requested_start_date') }}" 
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors">
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
                        value="{{ old('prepared_by', session('user_name')) }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="Full name of person preparing request">
                </div>

                <div>
                    <label for="prepared_position" class="block text-sm font-medium text-gray-700 mb-2">
                        Position
                    </label>
                    <input type="text" id="prepared_position" name="prepared_position" 
                        value="{{ old('prepared_position') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="e.g., Engineer, Admin Staff">
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
                        Site Photos (Max 5 images, 5MB each)
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
                        Location/Site Map (1 image, 5MB max)
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
                            Resolution/Approval Document (PDF, 10MB max)
                        </label>
                        <input type="file" name="resolution_file" accept=".pdf" id="resolutionInput" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-lgu-highlight file:text-white hover:file:bg-lgu-stroke">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Other Supporting Documents (PDF/DOC, 10MB each, max 3)
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
<script>
document.addEventListener('DOMContentLoaded', function() {
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

    // Form submission loading state
    const form = document.getElementById('projectRequestForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function() {
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
