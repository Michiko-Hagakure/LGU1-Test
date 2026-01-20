

<?php $__env->startSection('page-title', 'Profile Settings'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-lgu-headline mb-2">Profile Settings</h1>
        <p class="text-lgu-paragraph">Manage your admin profile, credentials, and LGU configuration</p>
    </div>

    <!-- Success Message -->
    <?php if(session('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-3"></i>
                <div>
                    <p class="font-semibold text-green-800">Success!</p>
                    <p class="text-sm text-green-700"><?php echo e(session('success')); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Error Messages -->
    <?php if($errors->any()): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex items-start">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 mr-3 mt-0.5"></i>
                <div>
                    <p class="font-semibold text-red-800 mb-2">Update Failed!</p>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Profile Settings Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-0">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1 bg-gray-50 border-r border-gray-200 p-6">
                <nav class="space-y-2">
                    <button onclick="showTab('profile')" id="tab-btn-profile" class="profile-tab-btn w-full text-left px-4 py-3 rounded-lg font-medium transition flex items-center bg-lgu-button text-lgu-button-text">
                        <i data-lucide="user-circle" class="w-5 h-5 mr-3"></i>
                        Admin Profile
                    </button>
                    <button onclick="showTab('security')" id="tab-btn-security" class="profile-tab-btn w-full text-left px-4 py-3 rounded-lg font-medium transition flex items-center text-gray-700 hover:bg-gray-200">
                        <i data-lucide="lock" class="w-5 h-5 mr-3"></i>
                        Password & Security
                    </button>
                </nav>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-3 p-6">
                <!-- Admin Profile Tab -->
                <div id="content-profile" class="profile-tab-content">
                    <form action="<?php echo e(route('admin.profile.update')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <h2 class="text-2xl font-bold text-lgu-headline mb-6">Personal Identification</h2>
                        
                        <!-- Profile Photo Section -->
                        <div class="bg-gray-50 rounded-xl p-6 mb-6 flex items-center space-x-6">
                            <div class="relative">
                                <?php 
                                    // Use session data (single source of truth, same as sidebar)
                                    $userName = session('user_name', $user->full_name ?? 'Admin User');
                                    $userEmail = session('user_email', $user->email ?? 'admin@lgu1.com');
                                    
                                    // Generate initials same as sidebar component
                                    $nameParts = explode(' ', $userName);
                                    $firstName = $nameParts[0] ?? 'A';
                                    $lastName = end($nameParts);
                                    $initials = strtoupper(
                                        substr($firstName, 0, 1) . 
                                        (($lastName !== $firstName) ? substr($lastName, 0, 1) : 'D')
                                    );
                                    
                                    $photo = ($user && $user->profile_photo_path) ? asset($user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&background=064e3b&color=fff&size=200';
                                ?>
                                <img id="avatar-preview" src="<?php echo e($photo); ?>" 
                                     class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                                
                                <label for="avatar_input" class="absolute bottom-0 right-0 w-10 h-10 bg-lgu-button rounded-full flex items-center justify-center border-3 border-white shadow-lg cursor-pointer hover:opacity-90 transition">
                                    <i data-lucide="camera" class="w-5 h-5 text-lgu-button-text"></i>
                                </label>
                                <input type="file" id="avatar_input" name="avatar" class="hidden" onchange="previewImage(this)" accept="image/*">
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-lgu-headline mb-1">Profile Photo</h3>
                                <p class="text-sm text-lgu-paragraph">Accepted formats: JPG, PNG. Max 2MB.</p>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-lgu-headline mb-2">Full Name</label>
                                <input type="text" name="full_name" 
                                       value="<?php echo e(session('user_name', $user->full_name ?? '')); ?>" 
                                       required
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-lgu-headline mb-2">Email Address (Locked)</label>
                                <input type="email" name="email" 
                                       value="<?php echo e(session('user_email', $user->email ?? '')); ?>" 
                                       readonly
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                                <p class="text-sm text-lgu-paragraph mt-1">Contact the Super Admin to change your official email.</p>
                            </div>
                            
                            <div class="pt-4">
                                <button type="submit" class="px-6 py-3 bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:opacity-90 transition">
                                    <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                                    Update Profile Information
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Password & Security Tab -->
                <div id="content-security" class="profile-tab-content hidden">
                    <h2 class="text-2xl font-bold text-lgu-headline mb-6">Password & Security</h2>
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-yellow-500 mr-3 mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-yellow-800">Coming Soon</p>
                                <p class="text-sm text-yellow-700">Password change functionality will be available in a future update.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab switching functionality
    function showTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.profile-tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active state from all buttons
        document.querySelectorAll('.profile-tab-btn').forEach(btn => {
            btn.classList.remove('bg-lgu-button', 'text-lgu-button-text');
            btn.classList.add('text-gray-700', 'hover:bg-gray-200');
        });
        
        // Show selected tab content
        document.getElementById('content-' + tabName).classList.remove('hidden');
        
        // Activate selected button
        const activeBtn = document.getElementById('tab-btn-' + tabName);
        activeBtn.classList.add('bg-lgu-button', 'text-lgu-button-text');
        activeBtn.classList.remove('text-gray-700', 'hover:bg-gray-200');
        
        // Reinitialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Image preview functionality
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Initialize Lucide icons on page load
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/profile/index.blade.php ENDPATH**/ ?>