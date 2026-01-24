@extends('layouts.citizen')

@section('title', 'Contact Us')

@section('page-content')
<!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Contact Us</h1>
        <p class="text-gray-600">Have a question or issue? We're here to help!</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contact Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('citizen.contact.store') }}" method="POST" enctype="multipart/form-data" id="contactForm">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', session('user_name')) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone (Optional) -->
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number (Optional)</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-4">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select name="category" id="category" required onchange="updateSubjectOptions()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a category</option>
                            <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                            <option value="booking_issue" {{ old('category') == 'booking_issue' ? 'selected' : '' }}>Booking Issue</option>
                            <option value="payment_issue" {{ old('category') == 'payment_issue' ? 'selected' : '' }}>Payment Issue</option>
                            <option value="technical_issue" {{ old('category') == 'technical_issue' ? 'selected' : '' }}>Technical Issue</option>
                            <option value="complaint" {{ old('category') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                            <option value="suggestion" {{ old('category') == 'suggestion' ? 'selected' : '' }}>Suggestion</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                        <select name="subject" id="subject" required onchange="toggleCustomSubject()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a subject</option>
                        </select>
                        @error('subject')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Custom Subject (shown when "Other" is selected) -->
                    <div class="mb-4 hidden" id="customSubjectContainer">
                        <label for="custom_subject" class="block text-sm font-medium text-gray-700 mb-2">Custom Subject *</label>
                        <input type="text" name="custom_subject" id="custom_subject" value="{{ old('custom_subject') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter your custom subject">
                    </div>

                    <!-- Message -->
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                        <textarea name="message" id="message" rows="6" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Attachments -->
                    <div class="mb-6">
                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">Attachments (Optional)</label>
                        <input type="file" name="attachments[]" id="attachments" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-sm text-gray-500 mt-1">Accepted: JPG, PNG, PDF, DOC, DOCX (Max 5MB per file)</p>
                        @error('attachments.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
                            Submit Inquiry
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contact Information Sidebar -->
        <div class="lg:col-span-1">
            <!-- Quick Links -->
            <div class="bg-blue-50 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Need Help?
                </h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('citizen.help-center.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            Visit Help Center
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('citizen.contact.my-inquiries') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            My Inquiries
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Response Time -->
            <div class="bg-green-50 rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Response Time
                </h3>
                <p class="text-sm text-gray-700">
                    We typically respond within <strong>24-48 hours</strong> on business days.
                </p>
            </div>
        </div>
</div>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3b82f6'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        confirmButtonColor: '#ef4444'
    });
</script>
@endif

<script>
// Subject options for each category
const subjectOptions = {
    'general': [
        'Information Request',
        'Hours of Operation',
        'Facility Location',
        'General Question',
        'Other'
    ],
    'booking_issue': [
        'Cancellation Request',
        'Reschedule Request',
        'Booking Not Received',
        'Incorrect Booking Details',
        'Duplicate Booking',
        'Other'
    ],
    'payment_issue': [
        'Payment Failed',
        'Refund Request',
        'Payment Not Processed',
        'Incorrect Amount Charged',
        'Payment Method Issue',
        'Other'
    ],
    'technical_issue': [
        'Login Problem',
        'Website Error',
        'Payment Gateway Error',
        'Page Not Loading',
        'Account Access Issue',
        'Other'
    ],
    'complaint': [
        'Facility Condition',
        'Staff Behavior',
        'Service Quality',
        'Cleanliness Issue',
        'Safety Concern',
        'Other'
    ],
    'suggestion': [
        'Feature Request',
        'Service Improvement',
        'New Facility Suggestion',
        'Website Enhancement',
        'Process Improvement',
        'Other'
    ],
    'other': [
        'Other'
    ]
};

function updateSubjectOptions() {
    const category = document.getElementById('category').value;
    const subjectSelect = document.getElementById('subject');
    
    // Clear existing options
    subjectSelect.innerHTML = '<option value="">Select a subject</option>';
    
    // Add new options based on category
    if (category && subjectOptions[category]) {
        subjectOptions[category].forEach(subject => {
            const option = document.createElement('option');
            option.value = subject;
            option.textContent = subject;
            subjectSelect.appendChild(option);
        });
        
        subjectSelect.disabled = false;
    } else {
        subjectSelect.disabled = true;
    }
    
    // Hide custom subject field when category changes
    document.getElementById('customSubjectContainer').classList.add('hidden');
    document.getElementById('custom_subject').required = false;
}

function toggleCustomSubject() {
    const subject = document.getElementById('subject').value;
    const customContainer = document.getElementById('customSubjectContainer');
    const customInput = document.getElementById('custom_subject');
    
    if (subject === 'Other') {
        customContainer.classList.remove('hidden');
        customInput.required = true;
    } else {
        customContainer.classList.add('hidden');
        customInput.required = false;
        customInput.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const category = document.getElementById('category').value;
    if (category) {
        updateSubjectOptions();
        
        // Restore old subject value if exists
        @if(old('subject'))
        document.getElementById('subject').value = '{{ old('subject') }}';
        toggleCustomSubject();
        @endif
    }
});
</script>
@endsection
