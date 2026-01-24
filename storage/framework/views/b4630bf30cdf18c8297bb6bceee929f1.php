<form action="<?php echo e(route('admin.settings.communication.update')); ?>" method="POST" class="space-y-8">
    <?php echo csrf_field(); ?>
    
    <!-- Email Settings Section -->
    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-blue-900 flex items-center gap-2">
                    <i data-lucide="mail" class="w-5 h-5"></i>
                    Email Configuration
                </h3>
                <p class="text-sm text-blue-700 mt-1">Configure SMTP settings for sending emails</p>
            </div>
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="email_enabled" value="1" 
                       <?php echo e(DB::connection('auth_db')->table('system_settings')->where('key', 'email_enabled')->value('value') == '1' ? 'checked' : ''); ?>

                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="ml-2 text-sm font-semibold text-blue-900">Enable Email</span>
            </label>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">SMTP Host</label>
                <input type="text" name="email_smtp_host" 
                       value="<?php echo e(DB::connection('auth_db')->table('system_settings')->where('key', 'email_smtp_host')->value('value')); ?>"
                       placeholder="smtp.gmail.com"
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">SMTP Port</label>
                <input type="number" name="email_smtp_port" 
                       value="<?php echo e(DB::connection('auth_db')->table('system_settings')->where('key', 'email_smtp_port')->value('value')); ?>"
                       placeholder="587"
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">SMTP Username</label>
                <input type="text" name="email_smtp_username" 
                       value="<?php echo e(DB::connection('auth_db')->table('system_settings')->where('key', 'email_smtp_username')->value('value')); ?>"
                       placeholder="your-email@gmail.com"
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">SMTP Password</label>
                <input type="password" name="email_smtp_password" 
                       placeholder="Leave blank to keep current"
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                <p class="text-xs text-gray-500 mt-1">Enter new password to change, leave blank to keep existing</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Encryption</label>
                <select name="email_smtp_encryption" 
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                    <?php $encryption = DB::connection('auth_db')->table('system_settings')->where('key', 'email_smtp_encryption')->value('value'); ?>
                    <option value="tls" <?php echo e($encryption === 'tls' ? 'selected' : ''); ?>>TLS</option>
                    <option value="ssl" <?php echo e($encryption === 'ssl' ? 'selected' : ''); ?>>SSL</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">From Address</label>
                <input type="email" name="email_from_address" 
                       value="<?php echo e(DB::connection('auth_db')->table('system_settings')->where('key', 'email_from_address')->value('value')); ?>"
                       placeholder="noreply@lgu.gov.ph"
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">From Name</label>
                <input type="text" name="email_from_name" 
                       value="<?php echo e(DB::connection('auth_db')->table('system_settings')->where('key', 'email_from_name')->value('value')); ?>"
                       placeholder="LGU Facility Reservation System"
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
            </div>
        </div>
        
        <div class="mt-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Signature (HTML)</label>
            <textarea name="email_signature" rows="3"
                      placeholder="<p>Best regards,<br>LGU Team</p>"
                      class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"><?php echo e(DB::connection('auth_db')->table('system_settings')->where('key', 'email_signature')->value('value')); ?></textarea>
        </div>
        
        <!-- Test Email -->
        <div class="mt-6 pt-6 border-t border-blue-200">
            <h4 class="text-sm font-bold text-blue-900 mb-3">Test Email Configuration</h4>
            <div class="flex gap-3">
                <input type="email" id="test_email_address" 
                       placeholder="Enter email to test"
                       class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                <button type="button" onclick="sendTestEmail()"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    <i data-lucide="send" class="w-4 h-4 inline mr-2"></i>
                    Send Test Email
                </button>
            </div>
        </div>
    </div>
    
    <!-- SMS Settings Section -->
    <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-green-900 flex items-center gap-2">
                    <i data-lucide="message-square" class="w-5 h-5"></i>
                    SMS Configuration
                </h3>
                <p class="text-sm text-green-700 mt-1">Configure SMS gateway for sending text messages</p>
            </div>
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="sms_enabled" value="1" 
                       <?php echo e(DB::connection('auth_db')->table('system_settings')->where('key', 'sms_enabled')->value('value') == '1' ? 'checked' : ''); ?>

                       class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                <span class="ml-2 text-sm font-semibold text-green-900">Enable SMS</span>
            </label>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">SMS Provider</label>
                <select name="sms_provider" 
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none">
                    <?php $provider = DB::connection('auth_db')->table('system_settings')->where('key', 'sms_provider')->value('value'); ?>
                    <option value="semaphore" <?php echo e($provider === 'semaphore' ? 'selected' : ''); ?>>Semaphore (Recommended for PH)</option>
                    <option value="twilio" <?php echo e($provider === 'twilio' ? 'selected' : ''); ?>>Twilio</option>
                    <option value="vonage" <?php echo e($provider === 'vonage' ? 'selected' : ''); ?>>Vonage</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">API Key</label>
                <input type="password" name="sms_api_key" 
                       placeholder="Leave blank to keep current"
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none">
                <p class="text-xs text-gray-500 mt-1">Enter new API key to change, leave blank to keep existing</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sender Name (Max 11 chars)</label>
                <input type="text" name="sms_sender_name" 
                       value="<?php echo e(DB::connection('auth_db')->table('system_settings')->where('key', 'sms_sender_name')->value('value')); ?>"
                       placeholder="LGU"
                       maxlength="11"
                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none">
            </div>
        </div>
        
        <!-- Test SMS -->
        <div class="mt-6 pt-6 border-t border-green-200">
            <h4 class="text-sm font-bold text-green-900 mb-3">Test SMS Configuration</h4>
            <div class="flex gap-3">
                <input type="text" id="test_phone_number" 
                       placeholder="Enter phone number (e.g., +639123456789)"
                       class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:outline-none">
                <button type="button" onclick="sendTestSms()"
                        class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                    <i data-lucide="send" class="w-4 h-4 inline mr-2"></i>
                    Send Test SMS
                </button>
            </div>
        </div>
    </div>
    
    <!-- Save Button -->
    <div class="flex justify-end">
        <button type="submit" 
                class="px-8 py-3 bg-lgu-button text-white font-bold rounded-lg hover:opacity-90 transition shadow-lg">
            <i data-lucide="save" class="w-5 h-5 inline mr-2"></i>
            Save Communication Settings
        </button>
    </div>
</form>

<script>
function sendTestEmail() {
    const email = document.getElementById('test_email_address').value;
    if (!email) {
        alert('Please enter an email address');
        return;
    }
    
    fetch('<?php echo e(route('admin.settings.test-email')); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ test_email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Test email sent successfully!');
        } else {
            alert('Failed to send test email: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

function sendTestSms() {
    const phone = document.getElementById('test_phone_number').value;
    if (!phone) {
        alert('Please enter a phone number');
        return;
    }
    
    fetch('<?php echo e(route('admin.settings.test-sms')); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ test_phone: phone })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Test SMS sent successfully!');
        } else {
            alert('Failed to send test SMS: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

// Reinitialize Lucide icons after content loads
setTimeout(() => lucide.createIcons(), 100);
</script>
<?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/settings/partials/communication.blade.php ENDPATH**/ ?>