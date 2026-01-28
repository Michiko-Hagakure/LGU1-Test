@extends('layouts.citizen')

@section('title', 'Security Settings')
@section('page-title', 'Security Settings')
@section('page-subtitle', 'Manage your account security and privacy')

@section('page-content')
<div class="space-y-gr-lg">
    
    <!-- Tab Navigation -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="border-b border-gray-200">
            <div class="flex flex-wrap gap-gr-xs p-gr-md">
                <button onclick="showSecurityTab('password')" id="tab-btn-password" class="security-tab-btn px-gr-md py-gr-sm rounded-lg font-semibold transition flex items-center gap-gr-xs bg-lgu-button text-lgu-button-text">
                    <i data-lucide="key" class="w-5 h-5"></i>
                    Password & 2FA
                </button>
                <button onclick="showSecurityTab('sessions')" id="tab-btn-sessions" class="security-tab-btn px-gr-md py-gr-sm rounded-lg font-semibold transition flex items-center gap-gr-xs text-gray-700 hover:bg-gray-100">
                    <i data-lucide="monitor" class="w-5 h-5"></i>
                    Devices & Sessions
                </button>
                <button onclick="showSecurityTab('history')" id="tab-btn-history" class="security-tab-btn px-gr-md py-gr-sm rounded-lg font-semibold transition flex items-center gap-gr-xs text-gray-700 hover:bg-gray-100">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                    Login History
                </button>
                <button onclick="showSecurityTab('privacy')" id="tab-btn-privacy" class="security-tab-btn px-gr-md py-gr-sm rounded-lg font-semibold transition flex items-center gap-gr-xs text-gray-700 hover:bg-gray-100">
                    <i data-lucide="shield" class="w-5 h-5"></i>
                    Privacy & Data
                </button>
            </div>
        </div>

        <!-- Tab 1: Password & 2FA -->
        <div id="content-password" class="security-tab-content p-gr-lg">
            <!-- Change Password Section -->
            <div class="bg-gray-50 rounded-xl p-gr-lg mb-gr-lg">
                <h2 class="text-2xl font-bold text-lgu-headline mb-gr-md flex items-center gap-gr-sm">
                    <i data-lucide="lock" class="w-6 h-6"></i>
                    Change Password
                </h2>
                
                <form action="{{ URL::signedRoute('citizen.security.change-password') }}" method="POST" class="space-y-gr-md">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-lgu-headline mb-gr-xs">Current Password</label>
                        <div class="relative">
                            <input type="password" name="current_password" id="currentPassword" required
                                   class="w-full px-gr-md py-gr-sm border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none pr-12">
                            <button type="button" onclick="togglePasswordVisibility('currentPassword')" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                <i data-lucide="eye" class="w-5 h-5 password-eye-icon"></i>
                                <i data-lucide="eye-off" class="w-5 h-5 password-eye-off-icon hidden"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-lgu-headline mb-gr-xs">New Password</label>
                        <div class="relative">
                            <input type="password" name="new_password" id="newPassword" required
                                   class="w-full px-gr-md py-gr-sm border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none pr-12">
                            <button type="button" onclick="togglePasswordVisibility('newPassword')" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                <i data-lucide="eye" class="w-5 h-5 password-eye-icon"></i>
                                <i data-lucide="eye-off" class="w-5 h-5 password-eye-off-icon hidden"></i>
                            </button>
                        </div>
                        <div id="passwordStrength" class="mt-gr-xs h-2 bg-gray-200 rounded-full overflow-hidden hidden">
                            <div id="passwordStrengthBar" class="h-full transition-all duration-300"></div>
                        </div>
                        <p class="text-xs text-lgu-paragraph mt-gr-xs">
                            Must be at least 8 characters with uppercase, lowercase, number, and special character
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-lgu-headline mb-gr-xs">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" name="confirm_password" id="confirmPassword" required
                                   class="w-full px-gr-md py-gr-sm border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none pr-12">
                            <button type="button" onclick="togglePasswordVisibility('confirmPassword')" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                <i data-lucide="eye" class="w-5 h-5 password-eye-icon"></i>
                                <i data-lucide="eye-off" class="w-5 h-5 password-eye-off-icon hidden"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="px-gr-lg py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:opacity-90 transition flex items-center gap-gr-xs">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Update Password
                    </button>
                </form>
            </div>

            <!-- 2FA Section -->
            <div class="bg-gray-50 rounded-xl p-gr-lg">
                <h2 class="text-2xl font-bold text-lgu-headline mb-gr-md flex items-center gap-gr-sm">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                    Two-Factor Authentication
                </h2>
                
                @if($user->two_factor_enabled)
                    <div class="bg-green-50 border-l-4 border-green-500 p-gr-md rounded-lg mb-gr-md">
                        <div class="flex items-start gap-gr-sm">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-green-700">2FA is Enabled</p>
                                <p class="text-sm text-green-600 mt-1">Your account requires a 6-digit PIN when logging in from new devices.</p>
                            </div>
                        </div>
                    </div>
                    
                    <button onclick="disable2FAModal()" class="px-gr-lg py-gr-sm bg-red-500 text-white font-semibold rounded-lg hover:opacity-90 transition flex items-center gap-gr-xs">
                        <i data-lucide="shield-off" class="w-4 h-4"></i>
                        Disable 2FA
                    </button>
                @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-gr-md rounded-lg mb-gr-md">
                        <div class="flex items-start gap-gr-sm">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-500 mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-yellow-700">2FA is Disabled</p>
                                <p class="text-sm text-yellow-600 mt-1">Your account is less secure without two-factor authentication.</p>
                            </div>
                        </div>
                    </div>
                    
                    <button onclick="enable2FAModal()" class="px-gr-lg py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:opacity-90 transition flex items-center gap-gr-xs">
                        <i data-lucide="shield-plus" class="w-4 h-4"></i>
                        Enable 2FA
                    </button>
                @endif
            </div>
        </div>

        <!-- Tab 2: Devices & Sessions -->
        <div id="content-sessions" class="security-tab-content hidden p-gr-lg">
            <!-- Active Sessions -->
            <div class="mb-gr-xl">
                <h2 class="text-2xl font-bold text-lgu-headline mb-gr-sm flex items-center gap-gr-sm">
                    <i data-lucide="monitor" class="w-6 h-6"></i>
                    Active Sessions
                </h2>
                <p class="text-lgu-paragraph mb-gr-md">These devices are currently logged into your account. Revoke sessions that you do not recognize.</p>
                
                @if($activeSessions->count() > 0)
                    <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-gr-md py-gr-sm text-left text-xs font-semibold text-gray-600 uppercase">Device</th>
                                    <th class="px-gr-md py-gr-sm text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                                    <th class="px-gr-md py-gr-sm text-left text-xs font-semibold text-gray-600 uppercase">Location</th>
                                    <th class="px-gr-md py-gr-sm text-left text-xs font-semibold text-gray-600 uppercase">Signed In</th>
                                    <th class="px-gr-md py-gr-sm text-left text-xs font-semibold text-gray-600 uppercase">Last Accessed</th>
                                    <th class="px-gr-md py-gr-sm text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($activeSessions as $session)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-gr-md py-gr-md">
                                        <div class="font-medium text-lgu-headline">{{ $session->device_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $session->ip_address }}</div>
                                    </td>
                                    <td class="px-gr-md py-gr-md text-sm text-lgu-paragraph">{{ $user->full_name }}</td>
                                    <td class="px-gr-md py-gr-md text-sm text-lgu-paragraph">
                                        {{ $session->country ?? 'Unknown' }}<br>
                                        <span class="text-xs text-gray-500">{{ $session->city ?? 'Unknown' }}</span>
                                    </td>
                                    <td class="px-gr-md py-gr-md text-sm text-lgu-paragraph">
                                        {{ \Carbon\Carbon::parse($session->logged_in_at)->format('M d, Y g:i A') }}
                                    </td>
                                    <td class="px-gr-md py-gr-md text-sm text-lgu-paragraph">
                                        @if($session->is_current)
                                            <span class="text-green-600 font-semibold">Just now</span>
                                        @else
                                            {{ \Carbon\Carbon::parse($session->last_active_at)->diffForHumans() }}
                                        @endif
                                    </td>
                                    <td class="px-gr-md py-gr-md">
                                        @if($session->is_current)
                                            <span class="px-gr-sm py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Current</span>
                                        @else
                                            <button onclick="revokeSession('{{ $session->session_id }}')" 
                                                    class="px-gr-md py-1 bg-lgu-stroke text-white rounded-lg text-xs font-semibold hover:opacity-90 transition">
                                                REVOKE
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($activeSessions->where('is_current', false)->count() > 0)
                    <button onclick="revokeAllSessions()" class="mt-gr-md px-gr-lg py-gr-sm bg-red-500 text-white font-semibold rounded-lg hover:opacity-90 transition flex items-center gap-gr-xs">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Logout All Other Sessions
                    </button>
                    @endif
                @else
                    <div class="bg-gray-50 rounded-xl p-gr-xl text-center">
                        <i data-lucide="monitor-off" class="w-12 h-12 text-gray-400 mx-auto mb-gr-md"></i>
                        <p class="text-lgu-paragraph">No active sessions found.</p>
                    </div>
                @endif
            </div>

            <!-- Trusted Devices -->
            <div>
                <h2 class="text-2xl font-bold text-lgu-headline mb-gr-sm flex items-center gap-gr-sm">
                    <i data-lucide="smartphone" class="w-6 h-6"></i>
                    Trusted Devices
                </h2>
                <p class="text-lgu-paragraph mb-gr-md">These devices will not require 2FA PIN on login.</p>
                
                @if($trustedDevices->count() > 0)
                    <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-gr-md py-gr-sm text-left text-xs font-semibold text-gray-600 uppercase">Device</th>
                                    <th class="px-gr-md py-gr-sm text-left text-xs font-semibold text-gray-600 uppercase">IP Address</th>
                                    <th class="px-gr-md py-gr-sm text-left text-xs font-semibold text-gray-600 uppercase">Location</th>
                                    <th class="px-gr-md py-gr-sm text-left text-xs font-semibold text-gray-600 uppercase">Added On</th>
                                    <th class="px-gr-md py-gr-sm text-left text-xs font-semibold text-gray-600 uppercase">Last Used</th>
                                    <th class="px-gr-md py-gr-sm text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($trustedDevices as $device)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-gr-md py-gr-md font-medium text-lgu-headline">{{ $device->device_name }}</td>
                                    <td class="px-gr-md py-gr-md text-sm text-lgu-paragraph">{{ $device->ip_address }}</td>
                                    <td class="px-gr-md py-gr-md text-sm text-lgu-paragraph">
                                        {{ $device->country ?? 'Unknown' }}<br>
                                        <span class="text-xs text-gray-500">{{ $device->city ?? 'Unknown' }}</span>
                                    </td>
                                    <td class="px-gr-md py-gr-md text-sm text-lgu-paragraph">
                                        {{ \Carbon\Carbon::parse($device->trusted_at)->format('M d, Y') }}
                                    </td>
                                    <td class="px-gr-md py-gr-md text-sm text-lgu-paragraph">
                                        {{ \Carbon\Carbon::parse($device->last_used_at)->diffForHumans() }}
                                    </td>
                                    <td class="px-gr-md py-gr-md">
                                        <button onclick="removeTrustedDevice({{ $device->id }})" 
                                                class="px-gr-md py-1 bg-red-500 text-white rounded-lg text-xs font-semibold hover:opacity-90 transition">
                                            REMOVE
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <button onclick="removeAllDevices()" class="mt-gr-md px-gr-lg py-gr-sm bg-red-500 text-white font-semibold rounded-lg hover:opacity-90 transition flex items-center gap-gr-xs">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Remove All Trusted Devices
                    </button>
                @else
                    <div class="bg-gray-50 rounded-xl p-gr-xl text-center">
                        <i data-lucide="smartphone-x" class="w-12 h-12 text-gray-400 mx-auto mb-gr-md"></i>
                        <p class="text-lgu-paragraph">No trusted devices yet. Enable 2FA and login from a new device to create trusted devices.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tab 3: Login History -->
        <div id="content-history" class="security-tab-content hidden p-gr-lg">
            <h2 class="text-2xl font-bold text-lgu-headline mb-gr-sm flex items-center gap-gr-sm">
                <i data-lucide="clock" class="w-6 h-6"></i>
                Recent Login Activity
            </h2>
            <p class="text-lgu-paragraph mb-gr-md">Last 20 login attempts on your account.</p>
            
            @if($loginHistory->count() > 0)
                <div class="space-y-gr-sm">
                    @foreach($loginHistory as $login)
                    <div class="bg-white rounded-xl border-2 border-gray-200 p-gr-md hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-gr-md flex-1">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $login->status === 'success' ? 'bg-green-100' : 'bg-red-100' }}">
                                    @if($login->status === 'success')
                                        <i data-lucide="check" class="w-5 h-5 text-green-600"></i>
                                    @else
                                        <i data-lucide="x" class="w-5 h-5 text-red-600"></i>
                                    @endif
                                </div>
                                
                                <div class="flex-1">
                                    <div class="flex items-center gap-gr-sm mb-1">
                                        <h3 class="font-semibold text-lgu-headline">{{ $login->device_name }}</h3>
                                        <span class="px-gr-sm py-0.5 rounded-full text-xs font-semibold {{ $login->status === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ ucfirst($login->status) }}
                                        </span>
                                        @if($login->required_2fa)
                                            <span class="px-gr-sm py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">2FA Verified</span>
                                        @endif
                                    </div>
                                    
                                    <div class="text-sm text-lgu-paragraph space-y-0.5">
                                        <div class="flex items-center gap-gr-xs">
                                            <i data-lucide="globe" class="w-3.5 h-3.5"></i>
                                            <span>{{ $login->ip_address }} • {{ $login->country ?? 'Unknown' }}, {{ $login->city ?? 'Unknown' }}</span>
                                        </div>
                                        <div class="flex items-center gap-gr-xs">
                                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                            <span>{{ \Carbon\Carbon::parse($login->attempted_at)->format('F d, Y \a\t g:i A') }}</span>
                                        </div>
                                        @if($login->status === 'failed' && $login->failure_reason)
                                            <div class="flex items-center gap-gr-xs text-red-600">
                                                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                                <span>{{ $login->failure_reason }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 rounded-xl p-gr-xl text-center">
                    <i data-lucide="clock-off" class="w-12 h-12 text-gray-400 mx-auto mb-gr-md"></i>
                    <p class="text-lgu-paragraph">No login history available.</p>
                </div>
            @endif
        </div>

        <!-- Tab 4: Privacy & Data -->
        <div id="content-privacy" class="security-tab-content hidden p-gr-lg">
            <!-- Privacy Settings -->
            <div class="bg-gray-50 rounded-xl p-gr-lg mb-gr-lg">
                <h2 class="text-2xl font-bold text-lgu-headline mb-gr-md flex items-center gap-gr-sm">
                    <i data-lucide="eye" class="w-6 h-6"></i>
                    Privacy Settings
                </h2>
                
                <form action="{{ URL::signedRoute('citizen.security.privacy') }}" method="POST" class="space-y-gr-md">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-lgu-headline mb-gr-sm">Profile Visibility</label>
                        <div class="space-y-gr-xs">
                            <label class="flex items-center gap-gr-sm cursor-pointer">
                                <input type="radio" name="profile_visibility" value="public" 
                                       {{ $user->profile_visibility === 'public' ? 'checked' : '' }}
                                       class="w-4 h-4 text-lgu-button focus:ring-lgu-button">
                                <span class="text-lgu-paragraph">Public - Anyone can view my reviews</span>
                            </label>
                            <label class="flex items-center gap-gr-sm cursor-pointer">
                                <input type="radio" name="profile_visibility" value="private" 
                                       {{ $user->profile_visibility === 'private' ? 'checked' : '' }}
                                       class="w-4 h-4 text-lgu-button focus:ring-lgu-button">
                                <span class="text-lgu-paragraph">Private - Only I can see my booking history</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="space-y-gr-sm">
                        <label class="flex items-center gap-gr-sm cursor-pointer">
                            <input type="checkbox" name="show_reviews_publicly" 
                                   {{ $user->show_reviews_publicly ? 'checked' : '' }}
                                   class="w-4 h-4 text-lgu-button focus:ring-lgu-button rounded">
                            <span class="text-lgu-paragraph">Display my reviews on facility pages</span>
                        </label>
                        <label class="flex items-center gap-gr-sm cursor-pointer">
                            <input type="checkbox" name="show_booking_count" 
                                   {{ $user->show_booking_count ? 'checked' : '' }}
                                   class="w-4 h-4 text-lgu-button focus:ring-lgu-button rounded">
                            <span class="text-lgu-paragraph">Show my booking count to public</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="px-gr-lg py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:opacity-90 transition flex items-center gap-gr-xs">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Save Privacy Settings
                    </button>
                </form>
            </div>

            <!-- Data Download -->
            <div class="bg-gray-50 rounded-xl p-gr-lg">
                <h2 class="text-2xl font-bold text-lgu-headline mb-gr-md flex items-center gap-gr-sm">
                    <i data-lucide="download" class="w-6 h-6"></i>
                    Download Your Data
                </h2>
                
                <p class="text-lgu-paragraph mb-gr-md">Request a copy of all your personal data stored in our system.</p>
                
                <div class="bg-white rounded-xl border-2 border-gray-200 p-gr-md mb-gr-md">
                    <p class="text-sm font-semibold text-lgu-headline mb-gr-sm">Data includes:</p>
                    <ul class="text-sm text-lgu-paragraph space-y-1 list-disc list-inside">
                        <li>Personal information (name, email, phone)</li>
                        <li>Booking history</li>
                        <li>Payment records</li>
                        <li>Reviews and ratings</li>
                        <li>Uploaded documents</li>
                    </ul>
                </div>
                
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-gr-md rounded-lg mb-gr-md">
                    <div class="flex items-start gap-gr-sm">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-500 mt-0.5"></i>
                        <p class="text-sm text-yellow-700">Processing may take up to 24 hours. You will receive an email when ready.</p>
                    </div>
                </div>
                
                <form action="{{ URL::signedRoute('citizen.security.data-download') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-gr-lg py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:opacity-90 transition flex items-center gap-gr-xs">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        Request Data Download
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const button = input.parentElement.querySelector('button');
    const eyeIcon = button.querySelector('.password-eye-icon');
    const eyeOffIcon = button.querySelector('.password-eye-off-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeOffIcon.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeOffIcon.classList.add('hidden');
    }
}

// Tab switching
function showSecurityTab(tabName) {
    document.querySelectorAll('.security-tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    document.querySelectorAll('.security-tab-btn').forEach(btn => {
        btn.classList.remove('bg-lgu-button', 'text-lgu-button-text');
        btn.classList.add('text-gray-700', 'hover:bg-gray-100');
    });
    
    document.getElementById('content-' + tabName).classList.remove('hidden');
    const activeBtn = document.getElementById('tab-btn-' + tabName);
    activeBtn.classList.add('bg-lgu-button', 'text-lgu-button-text');
    activeBtn.classList.remove('text-gray-700', 'hover:bg-gray-100');
}

// Password strength indicator
document.getElementById('newPassword')?.addEventListener('input', function(e) {
    const password = e.target.value;
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthContainer = document.getElementById('passwordStrength');
    
    if (password.length === 0) {
        strengthContainer.classList.add('hidden');
        return;
    }
    
    strengthContainer.classList.remove('hidden');
    
    let strength = 0;
    if (password.length >= 8) strength += 25;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
    if (/\d/.test(password)) strength += 25;
    if (/[@$!%*?&#]/.test(password)) strength += 25;
    
    strengthBar.style.width = strength + '%';
    
    if (strength <= 25) {
        strengthBar.className = 'h-full transition-all duration-300 bg-red-500';
    } else if (strength <= 50) {
        strengthBar.className = 'h-full transition-all duration-300 bg-yellow-500';
    } else if (strength <= 75) {
        strengthBar.className = 'h-full transition-all duration-300 bg-blue-500';
    } else {
        strengthBar.className = 'h-full transition-all duration-300 bg-green-500';
    }
});

// Enable 2FA modal
function enable2FAModal() {
    Swal.fire({
        title: 'Enable Two-Factor Authentication',
        html: `
            <div class="text-left space-y-4">
                <p class="text-sm text-gray-600">Create a 6-digit PIN that will be required when logging in from new devices.</p>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Create 6-Digit PIN</label>
                    <input type="password" id="swal-pin" maxlength="6" pattern="[0-9]{6}" 
                           class="swal2-input w-full" placeholder="••••••" style="margin: 0;">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm PIN</label>
                    <input type="password" id="swal-confirm-pin" maxlength="6" pattern="[0-9]{6}" 
                           class="swal2-input w-full" placeholder="••••••" style="margin: 0;">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Enable 2FA',
        confirmButtonColor: '#faae2b',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const pin = document.getElementById('swal-pin').value;
            const confirmPin = document.getElementById('swal-confirm-pin').value;
            
            if (!pin || pin.length !== 6 || !/^\d{6}$/.test(pin)) {
                Swal.showValidationMessage('PIN must be exactly 6 digits');
                return false;
            }
            
            if (pin !== confirmPin) {
                Swal.showValidationMessage('PINs do not match');
                return false;
            }
            
            return { pin, confirmPin };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("citizen.security.enable-2fa") }}';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            const pinInput = document.createElement('input');
            pinInput.type = 'hidden';
            pinInput.name = 'pin';
            pinInput.value = result.value.pin;
            form.appendChild(pinInput);
            
            const confirmPinInput = document.createElement('input');
            confirmPinInput.type = 'hidden';
            confirmPinInput.name = 'confirm_pin';
            confirmPinInput.value = result.value.confirmPin;
            form.appendChild(confirmPinInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Disable 2FA modal
function disable2FAModal() {
    Swal.fire({
        title: 'Disable Two-Factor Authentication?',
        html: `
            <div class="text-left space-y-4">
                <p class="text-sm text-gray-600">This will make your account less secure. All trusted devices will be removed.</p>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Your Password</label>
                    <input type="password" id="swal-password" class="swal2-input w-full" style="margin: 0;">
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Disable 2FA',
        confirmButtonColor: '#ef4444',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const password = document.getElementById('swal-password').value;
            if (!password) {
                Swal.showValidationMessage('Password is required');
                return false;
            }
            return { password };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("citizen.security.disable-2fa") }}';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            const passwordInput = document.createElement('input');
            passwordInput.type = 'hidden';
            passwordInput.name = 'password';
            passwordInput.value = result.value.password;
            form.appendChild(passwordInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Revoke session
function revokeSession(sessionId) {
    Swal.fire({
        title: 'Revoke Session?',
        text: 'This device will be immediately logged out.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Revoke',
        confirmButtonColor: '#ef4444',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("citizen.security.revoke-session") }}';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            const sessionInput = document.createElement('input');
            sessionInput.type = 'hidden';
            sessionInput.name = 'session_id';
            sessionInput.value = sessionId;
            form.appendChild(sessionInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Revoke all sessions
function revokeAllSessions() {
    Swal.fire({
        title: 'Logout All Other Sessions?',
        text: 'All devices except this one will be logged out.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Logout All',
        confirmButtonColor: '#ef4444',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("citizen.security.revoke-all-sessions") }}';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Remove trusted device
function removeTrustedDevice(deviceId) {
    Swal.fire({
        title: 'Remove Trusted Device?',
        text: 'This device will require 2FA PIN on next login.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Remove',
        confirmButtonColor: '#ef4444',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("citizen.security.remove-device", "") }}/' + deviceId;
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Remove all devices
function removeAllDevices() {
    Swal.fire({
        title: 'Remove All Trusted Devices?',
        text: 'You will need to verify with 2FA PIN on next login from any device.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Remove All',
        confirmButtonColor: '#ef4444',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("citizen.security.remove-all-devices") }}';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection
