@extends('layouts.admin')

@section('page-content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-lgu-headline mb-2">Create Message Template</h1>
        <p class="text-lgu-paragraph">Create a new email, SMS, or in-app notification template</p>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.templates.store') }}" method="POST" class="bg-white rounded-2xl shadow-lg p-8">
        @csrf

        <div class="space-y-6">
            <!-- Template Name -->
            <div>
                <label class="block text-sm font-bold text-lgu-headline mb-2">
                    Template Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       placeholder="e.g., Booking Confirmed"
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
            </div>

            <!-- Type and Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-lgu-headline mb-2">
                        Message Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="messageType" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                        <option value="">Select Type</option>
                        <option value="email" {{ old('type') === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ old('type') === 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="in-app" {{ old('type') === 'in-app' ? 'selected' : '' }}>In-App Notification</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-lgu-headline mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select name="category" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                        <option value="">Select Category</option>
                        <option value="booking" {{ old('category') === 'booking' ? 'selected' : '' }}>Booking</option>
                        <option value="payment" {{ old('category') === 'payment' ? 'selected' : '' }}>Payment</option>
                        <option value="reminder" {{ old('category') === 'reminder' ? 'selected' : '' }}>Reminder</option>
                        <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General</option>
                    </select>
                </div>
            </div>

            <!-- Subject (Email only) -->
            <div id="subjectField" class="hidden">
                <label class="block text-sm font-bold text-lgu-headline mb-2">
                    Email Subject <span class="text-red-500">*</span>
                </label>
                <input type="text" name="subject" value="{{ old('subject') }}"
                       placeholder="e.g., Booking Confirmation - {{facility_name}}"
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                <p class="text-xs text-gray-500 mt-1">Use {{variable_name}} for dynamic content</p>
            </div>

            <!-- Message Body -->
            <div>
                <label class="block text-sm font-bold text-lgu-headline mb-2">
                    Message Body <span class="text-red-500">*</span>
                </label>
                <textarea name="body" rows="8" required
                          placeholder="Dear {{citizen_name}},&#10;&#10;Your booking has been confirmed!&#10;&#10;Booking ID: {{booking_id}}&#10;Facility: {{facility_name}}"
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none font-mono text-sm">{{ old('body') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Use {{variable_name}} for dynamic content</p>
                <p id="charCount" class="text-xs text-gray-600 mt-1"></p>
            </div>

            <!-- Available Variables -->
            <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-bold text-blue-900 mb-3">
                    <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                    Available Variables
                </h4>
                <p class="text-xs text-blue-700 mb-3">Click a variable to copy it to your clipboard:</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="copyVariable('citizen_name')" class="variable-btn">{{citizen_name}}</button>
                    <button type="button" onclick="copyVariable('booking_id')" class="variable-btn">{{booking_id}}</button>
                    <button type="button" onclick="copyVariable('facility_name')" class="variable-btn">{{facility_name}}</button>
                    <button type="button" onclick="copyVariable('booking_date')" class="variable-btn">{{booking_date}}</button>
                    <button type="button" onclick="copyVariable('booking_time')" class="variable-btn">{{booking_time}}</button>
                    <button type="button" onclick="copyVariable('amount')" class="variable-btn">{{amount}}</button>
                    <button type="button" onclick="copyVariable('transaction_id')" class="variable-btn">{{transaction_id}}</button>
                    <button type="button" onclick="copyVariable('payment_date')" class="variable-btn">{{payment_date}}</button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-4 border-t">
                <button type="submit" 
                        class="px-8 py-3 bg-lgu-button text-white font-bold rounded-lg hover:opacity-90 transition shadow-lg">
                    <i data-lucide="save" class="w-5 h-5 inline mr-2"></i>
                    Create Template
                </button>
                <a href="{{ route('admin.templates.index') }}" 
                   class="px-8 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
                    <i data-lucide="x" class="w-5 h-5 inline mr-2"></i>
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

<script>
// Show/hide subject field based on message type
const messageTypeSelect = document.getElementById('messageType');
const subjectField = document.getElementById('subjectField');
const bodyTextarea = document.querySelector('textarea[name="body"]');
const charCount = document.getElementById('charCount');

messageTypeSelect.addEventListener('change', function() {
    if (this.value === 'email') {
        subjectField.classList.remove('hidden');
        subjectField.querySelector('input').required = true;
    } else {
        subjectField.classList.add('hidden');
        subjectField.querySelector('input').required = false;
    }
    
    updateCharCount();
});

// Character count
bodyTextarea.addEventListener('input', updateCharCount);

function updateCharCount() {
    const count = bodyTextarea.value.length;
    const type = messageTypeSelect.value;
    
    if (type === 'sms') {
        const maxChars = 160;
        const messages = Math.ceil(count / maxChars);
        charCount.textContent = `${count} characters (${messages} SMS message${messages > 1 ? 's' : ''})`;
        if (count > maxChars) {
            charCount.classList.add('text-orange-600', 'font-semibold');
        } else {
            charCount.classList.remove('text-orange-600', 'font-semibold');
        }
    } else {
        charCount.textContent = `${count} characters`;
    }
}

function copyVariable(varName) {
    const text = `{{${varName}}}`;
    navigator.clipboard.writeText(text).then(() => {
        // Show temporary success message
        const btn = event.target;
        const originalText = btn.textContent;
        btn.textContent = '✓ Copied!';
        btn.classList.add('bg-green-500', 'text-white');
        setTimeout(() => {
            btn.textContent = originalText;
            btn.classList.remove('bg-green-500', 'text-white');
        }, 1500);
    });
}

// Initialize on page load
if (messageTypeSelect.value === 'email') {
    subjectField.classList.remove('hidden');
    subjectField.querySelector('input').required = true;
}
updateCharCount();

lucide.createIcons();
</script>

<style>
.variable-btn {
    @apply px-3 py-1 text-xs font-mono bg-white border-2 border-blue-300 text-blue-700 rounded-lg hover:bg-blue-100 transition cursor-pointer;
}
</style>
@endsection
