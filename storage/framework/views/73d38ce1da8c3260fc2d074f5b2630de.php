

<?php $__env->startSection('title', 'LGU1 - Login'); ?>

<?php $__env->startSection('content'); ?>
<div id="loginContainer">
    <!-- Lockout Timer Overlay -->
    <div id="lockoutOverlay" style="display: none;">
        <div class="text-center py-4">
            <i class="bi bi-shield-lock-fill" style="font-size: 3rem; color: #ef4444;"></i>
            <h4 style="color: var(--headline); margin-top: 1rem;">Account Temporarily Locked</h4>
            <p class="text-muted">Too many failed login attempts. Please wait before trying again.</p>
            <div class="lockout-timer mt-3" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-radius: 12px; padding: 1.5rem; border: 2px solid #fecaca;">
                <p class="mb-2" style="color: #991b1b; font-weight: 600;">Time Remaining</p>
                <div id="lockoutCountdown" style="font-size: 2.5rem; font-weight: 700; color: #dc2626; font-family: 'Courier New', monospace;">
                    03:00
                </div>
            </div>
        </div>
    </div>

    <div id="loginContent">
        <div class="text-center mb-4">
            <img src="<?php echo e(asset('assets/images/logo.png')); ?>" alt="LGU1 Logo" class="login-logo">
            <h2 style="color: var(--headline); font-family: 'Merriweather', serif; font-weight: 700;">Sign In</h2>
            <p class="text-muted"><i class="bi bi-shield-check-fill"></i> Secure Login</p>
        </div>

    <!-- Session Timeout Message -->
    <?php if(request()->get('timeout') == '1'): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-clock-history"></i>
        <strong>Session Expired!</strong> Your session has expired due to inactivity. Please login again.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <!-- Error Messages -->
    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i>
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <!-- Step 1: Email/Password Form -->
    <form id="loginForm" style="display: block;" novalidate>
        <div class="mb-3">
            <label for="email" class="form-label"><i class="bi bi-envelope"></i> Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" 
                       class="form-control" 
                       id="email" 
                       name="email" 
                       required>
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label"><i class="bi bi-lock"></i> Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" 
                       class="form-control" 
                       id="password" 
                       name="password" 
                       autocomplete="current-password"
                       required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="bi bi-eye" id="passwordIcon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100" id="loginBtn">
            <i class="bi bi-box-arrow-in-right"></i> Sign In
        </button>
    </form>

    <!-- Step 2: OTP Verification Form (Hidden initially) -->
    <div id="otpForm" style="display: none;">
        <div class="text-center mb-4">
            <i class="bi bi-shield-check" style="font-size: 3rem; color: var(--highlight);"></i>
            <h4 style="color: var(--headline); margin-top: 1rem;">Verify Your Identity</h4>
            <p class="text-muted">Enter the 6-digit code sent to your email</p>
        </div>
        
        <form id="otpVerifyForm" novalidate>
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-key"></i> Verification Code</label>
                <div class="otp-input-container">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="0" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="1" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="2" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="3" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="4" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="5" autocomplete="off">
                </div>
                <input type="hidden" id="otp" name="otp">
            </div>
            
            <!-- OTP Expiry Timer -->
            <div class="text-center mb-3">
                <div id="otpTimerContainer">
                    <span class="text-muted">Code expires in </span>
                    <span id="otpTimer" style="font-weight: 700; color: var(--highlight);">01:00</span>
                </div>
                <div id="otpExpiredMessage" style="display: none; color: #ef4444; font-weight: 600;">
                    <i class="bi bi-exclamation-circle"></i> Code expired! Please request a new one.
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100" id="verifyBtn">
                <i class="bi bi-check-circle"></i> Verify & Sign In
            </button>
            
            <button type="button" class="btn btn-outline-warning w-100 mt-2" id="resendOtpBtn">
                <i class="bi bi-arrow-clockwise"></i> Resend OTP
            </button>
            
            <button type="button" class="btn btn-outline-secondary w-100 mt-2" id="backToLogin">
                <i class="bi bi-arrow-left"></i> Back to Login
            </button>
        </form>
    </div>

    <!-- Step 3: 2FA PIN Verification Form (Hidden initially) -->
    <div id="twoFAForm" style="display: none;">
        <div class="text-center mb-4">
            <i class="bi bi-shield-lock" style="font-size: 3rem; color: var(--highlight);"></i>
            <h4 style="color: var(--headline); margin-top: 1rem;">Two-Factor Authentication</h4>
            <p class="text-muted">Enter your 6-digit PIN</p>
        </div>
        
        <form id="twoFAVerifyForm" novalidate>
            <div class="mb-4">
                <label for="pin" class="form-label"><i class="bi bi-lock"></i> 6-Digit PIN</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                    <input type="password" 
                           class="form-control text-center" 
                           id="pin" 
                           name="pin" 
                           placeholder="••••••"
                           maxlength="6"
                           pattern="[0-9]{6}"
                           style="font-size: 1.5rem; letter-spacing: 0.5rem;"
                           required>
                </div>
                <small class="text-muted">This is a new device - 2FA verification required</small>
            </div>

            <button type="submit" class="btn btn-primary w-100" id="verify2FABtn">
                <i class="bi bi-check-circle"></i> Verify & Sign In
            </button>
        </form>
    </div>

    <div class="divider">or</div>
    <div class="text-center">
        <a href="<?php echo e(route('register')); ?>" class="register-link"><i class="bi bi-person-plus"></i> Create New Account</a>
    </div>
    <div class="text-center mt-2">
        <a href="<?php echo e(route('password.request')); ?>" class="register-link"><i class="bi bi-key"></i> Forgot Password?</a>
    </div>
    </div><!-- End loginContent -->
</div>

<!-- Loader Overlay -->
<div class="loader-overlay" id="loaderOverlay">
    <div class="loader-content">
        <div class="loader-spinner"></div>
        <h5 style="color: var(--headline); margin-bottom: 0.5rem;">Sending Verification Code</h5>
        <p class="text-muted mb-0">Please wait while we send the OTP to your email...</p>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .login-logo {
        display: block;
        margin: 0 auto 1.2rem auto;
        max-width: 90px;
        filter: drop-shadow(0 2px 8px #00473e22);
    }
    
    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .loader-content {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        text-align: center;
        min-width: 300px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }
    
    .loader-spinner {
        display: inline-block;
        width: 50px;
        height: 50px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #00473e;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 1rem;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* OTP Input Boxes */
    .otp-input-container {
        display: flex;
        justify-content: center;
        gap: 8px;
    }
    
    .otp-input {
        width: 48px;
        height: 56px;
        text-align: center;
        font-size: 1.5rem;
        font-weight: 700;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        transition: all 0.2s ease;
        background: white;
    }
    
    .otp-input:focus {
        border-color: var(--highlight);
        box-shadow: 0 0 0 3px rgba(250, 174, 43, 0.2);
        outline: none;
    }
    
    .otp-input.filled {
        border-color: var(--highlight);
        background: #fffbeb;
    }
    
    .otp-input.error {
        border-color: #ef4444;
        background: #fef2f2;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const otpForm = document.getElementById('otpForm');
    const otpVerifyForm = document.getElementById('otpVerifyForm');
    const loaderOverlay = document.getElementById('loaderOverlay');
    const loginBtn = document.getElementById('loginBtn');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendOtpBtn = document.getElementById('resendOtpBtn');
    const backToLoginBtn = document.getElementById('backToLogin');
    
    // CSRF Token Management - Prevent stale token issues
    let csrfToken = '<?php echo e(csrf_token()); ?>';
    
    // Refresh CSRF token function
    async function refreshCSRFToken() {
        try {
            const response = await fetch('/csrf-token', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (data.csrf_token) {
                csrfToken = data.csrf_token;
                console.log('CSRF token refreshed');
            }
        } catch (error) {
            console.error('Failed to refresh CSRF token:', error);
        }
    }
    
    // Refresh token every 30 seconds (aggressive refresh for login/OTP process)
    let tokenRefreshInterval = setInterval(refreshCSRFToken, 30000);
    
    // Refresh token when page becomes visible after being hidden (tab switching)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            refreshCSRFToken();
        }
    });
    
    // Refresh token when window regains focus
    window.addEventListener('focus', function() {
        refreshCSRFToken();
    });
    
    // Handle CSRF token mismatch errors - SILENTLY refresh token, no modals
    async function handleCSRFError() {
        // Silently refresh token without any user notification
        await refreshCSRFToken();
        // Token refreshed, ready for next attempt
    }
    
    // OTP Timer variables
    let otpTimerInterval = null;
    let otpSecondsLeft = 60;
    
    // Function to show OTP form and start aggressive token refresh
    function showOTPForm() {
        loginForm.style.display = 'none';
        otpForm.style.display = 'block';
        
        // Focus first OTP input
        const firstOtpInput = document.querySelector('.otp-input[data-index="0"]');
        if (firstOtpInput) firstOtpInput.focus();
        
        // Clear all OTP inputs
        document.querySelectorAll('.otp-input').forEach(input => {
            input.value = '';
            input.classList.remove('filled', 'error');
        });
        document.getElementById('otp').value = '';
        
        // Start OTP timer
        startOtpTimer();
        
        // Refresh token immediately when OTP form shows
        refreshCSRFToken();
        
        // More aggressive refresh while on OTP screen (every 15 seconds)
        clearInterval(tokenRefreshInterval);
        tokenRefreshInterval = setInterval(refreshCSRFToken, 15000);
    }
    
    // OTP Timer functions
    function startOtpTimer() {
        // Reset timer
        otpSecondsLeft = 60;
        updateOtpTimerDisplay();
        
        // Show timer, hide expired message
        document.getElementById('otpTimerContainer').style.display = 'block';
        document.getElementById('otpExpiredMessage').style.display = 'none';
        document.getElementById('verifyBtn').disabled = false;
        
        // Clear any existing interval
        if (otpTimerInterval) clearInterval(otpTimerInterval);
        
        otpTimerInterval = setInterval(function() {
            otpSecondsLeft--;
            updateOtpTimerDisplay();
            
            if (otpSecondsLeft <= 0) {
                clearInterval(otpTimerInterval);
                onOtpExpired();
            }
        }, 1000);
    }
    
    function updateOtpTimerDisplay() {
        const timerElement = document.getElementById('otpTimer');
        const minutes = Math.floor(otpSecondsLeft / 60);
        const seconds = otpSecondsLeft % 60;
        timerElement.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        
        // Change color when low on time
        if (otpSecondsLeft <= 10) {
            timerElement.style.color = '#ef4444';
        } else {
            timerElement.style.color = 'var(--highlight)';
        }
    }
    
    function onOtpExpired() {
        document.getElementById('otpTimerContainer').style.display = 'none';
        document.getElementById('otpExpiredMessage').style.display = 'block';
        document.getElementById('verifyBtn').disabled = true;
    }
    
    // OTP Input handling
    const otpInputs = document.querySelectorAll('.otp-input');
    
    otpInputs.forEach((input, index) => {
        // Handle input
        input.addEventListener('input', function(e) {
            const value = e.target.value;
            
            // Only allow numbers
            if (!/^\d*$/.test(value)) {
                e.target.value = '';
                return;
            }
            
            // Mark as filled
            if (value) {
                e.target.classList.add('filled');
            } else {
                e.target.classList.remove('filled');
            }
            
            // Move to next input if value entered
            if (value && index < 5) {
                otpInputs[index + 1].focus();
            }
            
            // Update hidden OTP field
            updateHiddenOtp();
            
            // Auto-verify when all 6 digits entered
            if (value && index === 5) {
                const fullOtp = getFullOtp();
                if (fullOtp.length === 6) {
                    // Small delay for UX
                    setTimeout(() => {
                        otpVerifyForm.dispatchEvent(new Event('submit'));
                    }, 200);
                }
            }
        });
        
        // Handle backspace
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
        
        // Handle paste
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
            
            if (pastedData) {
                pastedData.split('').forEach((char, i) => {
                    if (otpInputs[i]) {
                        otpInputs[i].value = char;
                        otpInputs[i].classList.add('filled');
                    }
                });
                
                // Focus last filled or next empty
                const lastIndex = Math.min(pastedData.length, 5);
                otpInputs[lastIndex].focus();
                
                updateHiddenOtp();
                
                // Auto-verify if 6 digits pasted
                if (pastedData.length === 6) {
                    setTimeout(() => {
                        otpVerifyForm.dispatchEvent(new Event('submit'));
                    }, 200);
                }
            }
        });
    });
    
    function getFullOtp() {
        return Array.from(otpInputs).map(input => input.value).join('');
    }
    
    function updateHiddenOtp() {
        document.getElementById('otp').value = getFullOtp();
    }
    
    // Lockout overlay with countdown timer
    let lockoutInterval = null;
    
    function showLockoutOverlay(remainingSeconds) {
        const lockoutOverlay = document.getElementById('lockoutOverlay');
        const loginContent = document.getElementById('loginContent');
        const lockoutCountdown = document.getElementById('lockoutCountdown');
        
        // Hide login content and show lockout overlay
        loginContent.style.display = 'none';
        lockoutOverlay.style.display = 'block';
        
        // Clear any existing interval
        if (lockoutInterval) {
            clearInterval(lockoutInterval);
        }
        
        // Start countdown
        let secondsLeft = remainingSeconds;
        updateCountdownDisplay(secondsLeft);
        
        lockoutInterval = setInterval(function() {
            secondsLeft--;
            updateCountdownDisplay(secondsLeft);
            
            if (secondsLeft <= 0) {
                clearInterval(lockoutInterval);
                hideLockoutOverlay();
            }
        }, 1000);
    }
    
    function updateCountdownDisplay(seconds) {
        const lockoutCountdown = document.getElementById('lockoutCountdown');
        const minutes = Math.floor(seconds / 60);
        const secs = seconds % 60;
        lockoutCountdown.textContent = String(minutes).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
    }
    
    function hideLockoutOverlay() {
        const lockoutOverlay = document.getElementById('lockoutOverlay');
        const loginContent = document.getElementById('loginContent');
        
        lockoutOverlay.style.display = 'none';
        loginContent.style.display = 'block';
        
        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'You Can Try Again',
            text: 'The lockout period has ended. You may now attempt to log in.',
            confirmButtonColor: '#faae2b'
        });
    }
    
    // Password toggle
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const passwordIcon = document.getElementById('passwordIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordIcon.className = 'bi bi-eye-slash';
        } else {
            passwordField.type = 'password';
            passwordIcon.className = 'bi bi-eye';
        }
    });
    
    // Step 1: Login with Email/Password
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Add validation styling
        loginForm.classList.add('was-validated');
        
        // Check if form is valid before proceeding
        if (!loginForm.checkValidity()) {
            return;
        }
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        loginBtn.disabled = true;
        loginBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending OTP...';
        loaderOverlay.style.display = 'flex';
        
        fetch('<?php echo e(route("login.post")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        })
        .then(async response => {
            // Check for CSRF token mismatch (419 status) - Silently refresh and retry
            if (response.status === 419) {
                await handleCSRFError();
                // Retry the request with fresh token
                const retryResponse = await fetch('<?php echo e(route("login.post")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });
                return retryResponse.json();
            }
            return response.json();
        })
        .then(data => {
            loaderOverlay.style.display = 'none';
            loginBtn.disabled = false;
            loginBtn.innerHTML = '<i class="bi bi-box-arrow-in-right"></i> Sign In';
            
            if (data.success) {
                // Switch directly to OTP form (no popup)
                showOTPForm();
            } else if (data.locked) {
                // Show lockout overlay with countdown timer
                showLockoutOverlay(data.remaining_seconds);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: data.message,
                    confirmButtonColor: '#fa5246'
                });
            }
        })
        .catch(error => {
            loaderOverlay.style.display = 'none';
            loginBtn.disabled = false;
            loginBtn.innerHTML = '<i class="bi bi-box-arrow-in-right"></i> Sign In';
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.',
                confirmButtonColor: '#fa5246'
            });
        });
    });
    
    // Step 2: Verify OTP
    otpVerifyForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const otp = document.getElementById('otp').value;
        
        verifyBtn.disabled = true;
        verifyBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Verifying...';
        
        // Refresh token immediately before submission
        await refreshCSRFToken();
        
        fetch('<?php echo e(route("login.verify-otp")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ otp })
        })
        .then(async response => {
            // Check for CSRF token mismatch - Silently refresh and retry
            if (response.status === 419) {
                await handleCSRFError();
                // Retry with fresh token
                const retryResponse = await fetch('<?php echo e(route("login.verify-otp")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ otp })
                });
                return retryResponse.json();
            }
            return response.json();
        })
        .then(data => {
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = '<i class="bi bi-check-circle"></i> Verify & Sign In';
            
            if (data.success) {
                if (data.requires_2fa) {
                    // Show 2FA PIN form
                    otpForm.style.display = 'none';
                    document.getElementById('twoFAForm').style.display = 'block';
                    document.getElementById('pin').focus();
                } else {
                    // Redirect directly to dashboard without popup
                    window.location.href = data.redirect;
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid OTP',
                    text: data.message,
                    confirmButtonColor: '#fa5246'
                });
            }
        })
        .catch(error => {
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = '<i class="bi bi-check-circle"></i> Verify & Sign In';
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.',
                confirmButtonColor: '#fa5246'
            });
        });
    });
    
    // Resend OTP button
    resendOtpBtn.addEventListener('click', async function() {
        resendOtpBtn.disabled = true;
        resendOtpBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending...';
        
        // Refresh token before resending
        await refreshCSRFToken();
        
        fetch('<?php echo e(route("login.resend-otp")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            // Check for CSRF token mismatch - Silently refresh and retry
            if (response.status === 419) {
                await handleCSRFError();
                // Retry with fresh token
                const retryResponse = await fetch('<?php echo e(route("login.resend-otp")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                return retryResponse.json();
            }
            return response.json();
        })
        .then(data => {
            resendOtpBtn.disabled = false;
            resendOtpBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Resend OTP';
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'OTP Sent!',
                    text: data.message,
                    confirmButtonColor: '#00473e',
                    timer: 3000
                });
                
                // Clear all OTP inputs and focus first one
                document.querySelectorAll('.otp-input').forEach(input => {
                    input.value = '';
                    input.classList.remove('filled', 'error');
                });
                document.getElementById('otp').value = '';
                document.querySelector('.otp-input[data-index="0"]').focus();
                
                // Restart OTP timer
                startOtpTimer();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: data.message,
                    confirmButtonColor: '#fa5246'
                });
            }
        })
        .catch(error => {
            resendOtpBtn.disabled = false;
            resendOtpBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Resend OTP';
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.',
                confirmButtonColor: '#fa5246'
            });
        });
    });
    
    // Back to login button
    backToLoginBtn.addEventListener('click', function() {
        otpForm.style.display = 'none';
        loginForm.style.display = 'block';
        document.getElementById('email').focus();
        
        // Reset to normal refresh interval (30 seconds)
        clearInterval(tokenRefreshInterval);
        tokenRefreshInterval = setInterval(refreshCSRFToken, 30000);
    });
    
    // Step 3: Verify 2FA PIN
    const twoFAVerifyForm = document.getElementById('twoFAVerifyForm');
    const verify2FABtn = document.getElementById('verify2FABtn');
    
    twoFAVerifyForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const pin = document.getElementById('pin').value;
        
        verify2FABtn.disabled = true;
        verify2FABtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Verifying...';
        
        // Refresh token immediately before submission
        await refreshCSRFToken();
        
        fetch('<?php echo e(route("login.verify-2fa")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ pin })
        })
        .then(async response => {
            if (response.status === 419) {
                await handleCSRFError();
                const retryResponse = await fetch('<?php echo e(route("login.verify-2fa")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ pin })
                });
                return retryResponse.json();
            }
            return response.json();
        })
        .then(data => {
            verify2FABtn.disabled = false;
            verify2FABtn.innerHTML = '<i class="bi bi-check-circle"></i> Verify & Sign In';
            
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid PIN',
                    text: data.message,
                    confirmButtonColor: '#fa5246'
                });
            }
        })
        .catch(error => {
            verify2FABtn.disabled = false;
            verify2FABtn.innerHTML = '<i class="bi bi-check-circle"></i> Verify & Sign In';
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.',
                confirmButtonColor: '#fa5246'
            });
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/auth/login.blade.php ENDPATH**/ ?>