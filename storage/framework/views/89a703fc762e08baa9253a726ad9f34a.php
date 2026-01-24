

<?php $__env->startSection('page-content'); ?>
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-lgu-headline mb-2">System Settings</h1>
        <p class="text-lgu-paragraph">Configure system behavior, rules, and preferences</p>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <i data-lucide="check-circle" class="w-5 h-5 inline mr-2"></i>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <i data-lucide="alert-circle" class="w-5 h-5 inline mr-2"></i>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Settings Form -->
    <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" id="settingsForm" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-2xl shadow-lg mb-6">
            <div class="flex border-b border-gray-200 overflow-x-auto">
                <?php $__currentLoopData = $settingsByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" 
                            class="tab-button px-6 py-4 font-semibold text-sm whitespace-nowrap transition-colors <?php echo e($loop->first ? 'active' : ''); ?>"
                            data-tab="<?php echo e($key); ?>">
                        <?php echo e($category['label']); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Tab Content -->
            <?php $__currentLoopData = $settingsByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="tab-content p-6 <?php echo e(!$loop->first ? 'hidden' : ''); ?>" id="tab-<?php echo e($key); ?>">
                    <h2 class="text-xl font-bold text-lgu-headline mb-4"><?php echo e($category['label']); ?> Settings</h2>
                    
                    <?php if($key === 'communication'): ?>
                        <!-- Special Communication Settings UI -->
                        <?php echo $__env->make('admin.settings.partials.communication', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php elseif($category['settings']->isEmpty()): ?>
                        <p class="text-gray-500">No settings available in this category.</p>
                    <?php else: ?>
                        <div class="space-y-6">
                            <?php $__currentLoopData = $category['settings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                    <label class="block mb-2">
                                        <span class="text-sm font-bold text-lgu-headline">
                                            <?php echo e(str_replace(['_', '.'], ' ', ucwords(str_replace($key.'.', '', $setting->key)))); ?>

                                        </span>
                                        <?php if($setting->description): ?>
                                            <span class="block text-xs text-gray-500 mt-1"><?php echo e($setting->description); ?></span>
                                        <?php endif; ?>
                                    </label>

                                    <?php if($setting->type === 'boolean'): ?>
                                        <select name="settings[<?php echo e($setting->key); ?>]" 
                                                class="w-full md:w-1/2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                                            <option value="1" <?php echo e($setting->getTypedValue() ? 'selected' : ''); ?>>Enabled</option>
                                            <option value="0" <?php echo e(!$setting->getTypedValue() ? 'selected' : ''); ?>>Disabled</option>
                                        </select>
                                    <?php elseif($setting->type === 'integer' || $setting->type === 'float'): ?>
                                        <input type="number" 
                                               name="settings[<?php echo e($setting->key); ?>]" 
                                               value="<?php echo e($setting->value); ?>"
                                               step="<?php echo e($setting->type === 'float' ? '0.01' : '1'); ?>"
                                               class="w-full md:w-1/2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                                    <?php elseif($setting->key === 'system.maintenance_message' || $setting->key === 'system.announcement'): ?>
                                        <textarea name="settings[<?php echo e($setting->key); ?>]" 
                                                  rows="3"
                                                  class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none"><?php echo e($setting->value); ?></textarea>
                                        
                                        <?php if($setting->key === 'system.announcement'): ?>
                                            <div class="mt-4 border-t pt-4">
                                                <label class="block mb-3">
                                                    <span class="text-sm font-bold text-lgu-headline">Announcement Image (Optional)</span>
                                                    <span class="block text-xs text-gray-500 mt-1">Upload an image to display in the announcement modal (max 2MB)</span>
                                                </label>
                                                
                                                <div class="flex items-center gap-4">
                                                    <label for="announcement_image" class="cursor-pointer inline-flex items-center px-6 py-3 bg-lgu-button text-white font-semibold rounded-lg hover:opacity-90 transition shadow-md">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        Choose Image
                                                    </label>
                                                    <span id="fileName" class="text-sm text-gray-600">No file chosen</span>
                                                </div>
                                                
                                                <input type="file" 
                                                       name="announcement_image" 
                                                       id="announcement_image"
                                                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                                       class="hidden">
                                                
                                                <?php if($setting->announcement_image): ?>
                                                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                                        <div class="flex items-start justify-between mb-2">
                                                            <p class="text-xs font-semibold text-gray-700">Current Image:</p>
                                                            <label class="flex items-center cursor-pointer">
                                                                <input type="checkbox" 
                                                                       name="remove_announcement_image" 
                                                                       value="1"
                                                                       class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                                                <span class="ml-2 text-xs font-semibold text-red-600">Remove this image</span>
                                                            </label>
                                                        </div>
                                                        <img src="<?php echo e(asset($setting->announcement_image)); ?>" 
                                                             alt="Announcement Image" 
                                                             class="max-w-xs rounded-lg border-2 border-gray-300 shadow-sm">
                                                        <p class="text-xs text-gray-500 mt-2 italic">Check "Remove this image" above and save to delete</p>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div id="imagePreview" class="mt-4 p-4 bg-green-50 rounded-lg hidden">
                                                    <p class="text-xs font-semibold text-green-700 mb-2">New Image Preview:</p>
                                                    <img id="previewImg" src="" alt="Preview" class="max-w-xs rounded-lg border-2 border-green-500 shadow-sm">
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <input type="text" 
                                               name="settings[<?php echo e($setting->key); ?>]" 
                                               value="<?php echo e($setting->value); ?>"
                                               class="w-full md:w-1/2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <button type="submit" class="px-6 py-3 bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:opacity-90 transition">
                <i data-lucide="save" class="w-5 h-5 inline mr-2"></i>
                Save Settings
            </button>
            <a href="<?php echo e(route('admin.settings.clear-cache')); ?>" 
               onclick="return confirm('Are you sure you want to clear the settings cache?')"
               class="px-6 py-3 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition">
                <i data-lucide="refresh-cw" class="w-5 h-5 inline mr-2"></i>
                Clear Cache
            </a>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                <i data-lucide="x" class="w-5 h-5 inline mr-2"></i>
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    // Tab switching functionality
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'text-lgu-button', 'border-b-2', 'border-lgu-button');
                btn.classList.add('text-gray-600');
            });
            
            // Add active class to clicked tab
            this.classList.add('active', 'text-lgu-button', 'border-b-2', 'border-lgu-button');
            this.classList.remove('text-gray-600');
            
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show selected tab content
            const tabId = 'tab-' + this.dataset.tab;
            document.getElementById(tabId).classList.remove('hidden');
        });
    });

    // Initialize first tab as active
    document.querySelector('.tab-button.active')?.classList.add('text-lgu-button', 'border-b-2', 'border-lgu-button');

    // Image preview functionality
    const announcementImageInput = document.getElementById('announcement_image');
    const fileNameDisplay = document.getElementById('fileName');
    
    if (announcementImageInput) {
        announcementImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Update filename display
                fileNameDisplay.textContent = file.name;
                fileNameDisplay.classList.remove('text-gray-600');
                fileNameDisplay.classList.add('text-green-600', 'font-semibold');
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('imagePreview');
                    const previewImg = document.getElementById('previewImg');
                    previewImg.src = event.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                fileNameDisplay.textContent = 'No file chosen';
                fileNameDisplay.classList.remove('text-green-600', 'font-semibold');
                fileNameDisplay.classList.add('text-gray-600');
            }
        });
    }

    // Initialize Lucide icons
    lucide.createIcons();
</script>

<style>
    .tab-button {
        border-bottom: 2px solid transparent;
    }
    .tab-button.active {
        color: #047857;
        border-bottom-color: #047857;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>