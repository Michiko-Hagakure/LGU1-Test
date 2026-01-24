

<?php $__env->startSection('title', 'Create Help Article'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="mb-6">
    <a href="<?php echo e(route('admin.help-articles.index')); ?>" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Articles
    </a>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Create Help Article</h1>

    <form action="<?php echo e(route('admin.help-articles.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Article Title *</label>
            <input type="text" name="title" id="title" value="<?php echo e(old('title')); ?>" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
            <input type="text" name="slug" id="slug" value="<?php echo e(old('slug')); ?>" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="text-sm text-gray-500 mt-1">URL-friendly version (auto-generated from title)</p>
            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
            <select name="category" id="category" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Select a category</option>
                <option value="getting_started" <?php echo e(old('category') == 'getting_started' ? 'selected' : ''); ?>>Getting Started</option>
                <option value="booking" <?php echo e(old('category') == 'booking' ? 'selected' : ''); ?>>Booking</option>
                <option value="payments" <?php echo e(old('category') == 'payments' ? 'selected' : ''); ?>>Payments</option>
                <option value="account" <?php echo e(old('category') == 'account' ? 'selected' : ''); ?>>Account</option>
                <option value="technical" <?php echo e(old('category') == 'technical' ? 'selected' : ''); ?>>Technical</option>
                <option value="policies" <?php echo e(old('category') == 'policies' ? 'selected' : ''); ?>>Policies</option>
            </select>
            <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
            <textarea name="excerpt" id="excerpt" rows="2"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"><?php echo e(old('excerpt')); ?></textarea>
            <p class="text-sm text-gray-500 mt-1">Brief summary (optional)</p>
            <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
            <textarea name="content" id="content" rows="12" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"><?php echo e(old('content')); ?></textarea>
            <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
            <input type="url" name="video_url" id="video_url" value="<?php echo e(old('video_url')); ?>"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                placeholder="https://youtube.com/watch?v=...">
            <p class="text-sm text-gray-500 mt-1">Optional YouTube or video URL</p>
            <?php $__errorArgs = ['video_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label for="screenshots" class="block text-sm font-medium text-gray-700 mb-2">Screenshots</label>
            <input type="file" name="screenshots[]" id="screenshots" multiple accept="image/jpeg,image/png"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="text-sm text-gray-500 mt-1">Optional images (JPG, PNG, max 2MB each)</p>
            <?php $__errorArgs = ['screenshots'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
            <input type="number" name="display_order" id="display_order" value="<?php echo e(old('display_order', 0)); ?>" min="0"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="text-sm text-gray-500 mt-1">Lower numbers appear first</p>
            <?php $__errorArgs = ['display_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_featured" value="1" <?php echo e(old('is_featured') ? 'checked' : ''); ?>

                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Featured Article</span>
            </label>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>

                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Active</span>
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?php echo e(route('admin.help-articles.index')); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg transition duration-200">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                Create Article
            </button>
        </div>
    </form>
</div>

<script>
// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function(e) {
    const slug = e.target.value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '');
    document.getElementById('slug').value = slug;
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/admin/help-center/articles/create.blade.php ENDPATH**/ ?>