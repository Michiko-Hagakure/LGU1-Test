@extends('layouts.staff')

@section('page-content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-lgu-headline mb-2">Send Notifications</h1>
        <p class="text-lgu-paragraph">Send email, SMS, or in-app notifications to citizens</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <i data-lucide="check-circle" class="w-5 h-5 inline mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <i data-lucide="alert-circle" class="w-5 h-5 inline mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Notification Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('staff.notifications.send') }}" method="POST" class="bg-white rounded-2xl shadow-lg p-8">
                @csrf

                <h2 class="text-2xl font-bold text-lgu-headline mb-6">Compose Notification</h2>

                <!-- Notification Type -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-lgu-headline mb-3">Notification Type</label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="notification_type" value="email" class="peer sr-only" checked>
                            <div class="p-4 border-2 border-gray-300 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 transition text-center">
                                <i data-lucide="mail" class="w-8 h-8 mx-auto mb-2 text-blue-600"></i>
                                <span class="font-semibold">Email</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="notification_type" value="sms" class="peer sr-only">
                            <div class="p-4 border-2 border-gray-300 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 transition text-center">
                                <i data-lucide="message-square" class="w-8 h-8 mx-auto mb-2 text-green-600"></i>
                                <span class="font-semibold">SMS</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="notification_type" value="in-app" class="peer sr-only">
                            <div class="p-4 border-2 border-gray-300 rounded-lg peer-checked:border-purple-500 peer-checked:bg-purple-50 transition text-center">
                                <i data-lucide="bell" class="w-8 h-8 mx-auto mb-2 text-purple-600"></i>
                                <span class="font-semibold">In-App</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Template Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-lgu-headline mb-2">Use Template (Optional)</label>
                    <select id="templateSelect" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                        <option value="">-- Start from scratch --</option>
                        @foreach($templates as $category => $categoryTemplates)
                            <optgroup label="{{ ucfirst($category) }}">
                                @foreach($categoryTemplates as $template)
                                    <option value="{{ $template->id }}" data-type="{{ $template->type }}">
                                        {{ $template->name }} ({{ strtoupper($template->type) }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <!-- Recipient Type -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-lgu-headline mb-3">Recipient Type</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="recipient_type" value="single" class="peer sr-only" checked>
                            <div class="p-4 border-2 border-gray-300 rounded-lg peer-checked:border-lgu-button peer-checked:bg-green-50 transition text-center">
                                <i data-lucide="user" class="w-6 h-6 mx-auto mb-2"></i>
                                <span class="font-semibold">Single Recipient</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="recipient_type" value="bulk" class="peer sr-only">
                            <div class="p-4 border-2 border-gray-300 rounded-lg peer-checked:border-lgu-button peer-checked:bg-green-50 transition text-center">
                                <i data-lucide="users" class="w-6 h-6 mx-auto mb-2"></i>
                                <span class="font-semibold">Bulk Recipients</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Recipients -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-lgu-headline mb-2">
                        Recipients <span class="text-red-500">*</span>
                    </label>
                    <textarea name="recipients" id="recipientsField" rows="3" required
                              placeholder="Enter email address or phone number (for bulk: separate with commas or new lines)"
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none"></textarea>
                    <p class="text-xs text-gray-500 mt-1">For bulk sending, enter multiple addresses/numbers separated by commas or new lines</p>
                </div>

                <!-- Subject (Email only) -->
                <div id="subjectField" class="mb-6">
                    <label class="block text-sm font-bold text-lgu-headline mb-2">
                        Subject <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="subject" id="subjectInput"
                           placeholder="Notification subject"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                </div>

                <!-- Message -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-lgu-headline mb-2">
                        Message <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message" id="messageField" rows="8" required
                              placeholder="Type your message here..."
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none"></textarea>
                    <p id="charCount" class="text-xs text-gray-600 mt-1"></p>
                </div>

                <!-- Schedule Type -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-lgu-headline mb-3">When to Send</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="schedule_type" value="immediate" class="peer sr-only" checked>
                            <div class="p-4 border-2 border-gray-300 rounded-lg peer-checked:border-lgu-button peer-checked:bg-green-50 transition text-center">
                                <i data-lucide="zap" class="w-6 h-6 mx-auto mb-2"></i>
                                <span class="font-semibold">Send Now</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="schedule_type" value="scheduled" class="peer sr-only">
                            <div class="p-4 border-2 border-gray-300 rounded-lg peer-checked:border-lgu-button peer-checked:bg-green-50 transition text-center">
                                <i data-lucide="clock" class="w-6 h-6 mx-auto mb-2"></i>
                                <span class="font-semibold">Schedule Later</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Scheduled Time -->
                <div id="scheduledTimeField" class="mb-6 hidden">
                    <label class="block text-sm font-bold text-lgu-headline mb-2">Schedule Date & Time</label>
                    <input type="datetime-local" name="scheduled_at"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="px-8 py-3 bg-lgu-button text-white font-bold rounded-lg hover:opacity-90 transition shadow-lg">
                        <i data-lucide="send" class="w-5 h-5 inline mr-2"></i>
                        <span id="submitBtnText">Send Notification</span>
                    </button>
                    <button type="reset" 
                            class="px-8 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
                        <i data-lucide="rotate-ccw" class="w-5 h-5 inline mr-2"></i>
                        Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Recent Campaigns Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-lgu-headline mb-4">
                    <i data-lucide="history" class="w-5 h-5 inline mr-2"></i>
                    Recent Campaigns
                </h3>
                
                @forelse($campaigns as $campaign)
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg border-l-4 
                        {{ $campaign->status === 'sent' ? 'border-green-500' : '' }}
                        {{ $campaign->status === 'pending' ? 'border-yellow-500' : '' }}
                        {{ $campaign->status === 'failed' ? 'border-red-500' : '' }}">
                        <div class="flex items-start justify-between mb-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded 
                                {{ $campaign->type === 'email' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $campaign->type === 'sms' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $campaign->type === 'in-app' ? 'bg-purple-100 text-purple-800' : '' }}">
                                {{ strtoupper($campaign->type) }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($campaign->created_at)->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-sm font-semibold text-gray-700 mb-1">{{ Str::limit($campaign->subject ?? $campaign->message, 40) }}</p>
                        <p class="text-xs text-gray-600">
                            {{ $campaign->sent_count }} sent
                            @if($campaign->failed_count > 0)
                                , {{ $campaign->failed_count }} failed
                            @endif
                        </p>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">No recent campaigns</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
const notificationTypeRadios = document.querySelectorAll('input[name="notification_type"]');
const scheduleTypeRadios = document.querySelectorAll('input[name="schedule_type"]');
const templateSelect = document.getElementById('templateSelect');
const subjectField = document.getElementById('subjectField');
const subjectInput = document.getElementById('subjectInput');
const messageField = document.getElementById('messageField');
const charCount = document.getElementById('charCount');
const scheduledTimeField = document.getElementById('scheduledTimeField');
const recipientsField = document.getElementById('recipientsField');
const submitBtnText = document.getElementById('submitBtnText');

// Notification type change
notificationTypeRadios.forEach(radio => {
    radio.addEventListener('change', function() {
        updateUIForType(this.value);
    });
});

// Schedule type change
scheduleTypeRadios.forEach(radio => {
    radio.addEventListener('change', function() {
        if (this.value === 'scheduled') {
            scheduledTimeField.classList.remove('hidden');
            submitBtnText.textContent = 'Schedule Notification';
        } else {
            scheduledTimeField.classList.add('hidden');
            submitBtnText.textContent = 'Send Notification';
        }
    });
});

// Template selection
templateSelect.addEventListener('change', function() {
    if (this.value) {
        fetch(`/staff/notifications/template/${this.value}`)
            .then(response => response.json())
            .then(data => {
                if (data.subject) {
                    subjectInput.value = data.subject;
                }
                messageField.value = data.body;
                
                // Switch to matching notification type
                const typeRadio = document.querySelector(`input[name="notification_type"][value="${data.type}"]`);
                if (typeRadio) {
                    typeRadio.checked = true;
                    updateUIForType(data.type);
                }
                
                updateCharCount();
            })
            .catch(error => console.error('Error loading template:', error));
    }
});

// Character count
messageField.addEventListener('input', updateCharCount);

function updateCharCount() {
    const count = messageField.value.length;
    const type = document.querySelector('input[name="notification_type"]:checked').value;
    
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

function updateUIForType(type) {
    if (type === 'email') {
        subjectField.classList.remove('hidden');
        subjectInput.required = true;
        recipientsField.placeholder = 'Enter email address (for bulk: separate with commas or new lines)';
    } else {
        subjectField.classList.add('hidden');
        subjectInput.required = false;
        if (type === 'sms') {
            recipientsField.placeholder = 'Enter phone number with country code (e.g., +639123456789)';
        } else {
            recipientsField.placeholder = 'Enter user ID (for bulk: separate with commas or new lines)';
        }
    }
    updateCharCount();
}

// Initialize
updateUIForType('email');
updateCharCount();
lucide.createIcons();
</script>
@endsection
