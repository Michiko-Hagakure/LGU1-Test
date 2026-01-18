

<?php $__env->startSection('page-title', 'Reviews Moderation'); ?>
<?php $__env->startSection('page-subtitle', 'Manage facility reviews and ratings'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-gr-lg">
    
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">Reviews Moderation</h1>
            <p class="text-body text-lgu-paragraph">Monitor and manage citizen feedback</p>
        </div>
        <div class="flex items-center gap-gr-sm">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-gr-md py-gr-sm">
                <div class="text-caption text-gray-600 uppercase mb-1">Total Reviews</div>
                <div class="text-h2 font-bold text-lgu-headline"><?php echo e($totalReviews); ?></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-gr-md py-gr-sm">
                <div class="text-caption text-gray-600 uppercase mb-1">Avg Rating</div>
                <div class="text-h2 font-bold text-lgu-button"><?php echo e(number_format($avgRating ?? 0, 1)); ?></div>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
        <form method="GET" action="<?php echo e(route('admin.reviews.index')); ?>" class="space-y-gr-md">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gr-md">
                <div>
                    <label for="search" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Search</label>
                    <input type="text" id="search" name="search" value="<?php echo e($search); ?>" placeholder="Search reviews..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                </div>
                <div>
                    <label for="facility_id" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Facility</label>
                    <select id="facility_id" name="facility_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="">All Facilities</option>
                        <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($facility->facility_id); ?>" <?php echo e($facilityId == $facility->facility_id ? 'selected' : ''); ?>>
                                <?php echo e($facility->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label for="rating" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Rating</label>
                    <select id="rating" name="rating" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="">All Ratings</option>
                        <option value="5" <?php echo e($rating == '5' ? 'selected' : ''); ?>>5 Stars</option>
                        <option value="4" <?php echo e($rating == '4' ? 'selected' : ''); ?>>4 Stars</option>
                        <option value="3" <?php echo e($rating == '3' ? 'selected' : ''); ?>>3 Stars</option>
                        <option value="2" <?php echo e($rating == '2' ? 'selected' : ''); ?>>2 Stars</option>
                        <option value="1" <?php echo e($rating == '1' ? 'selected' : ''); ?>>1 Star</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-gr-sm">
                <button type="submit" class="inline-flex items-center px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                    <i data-lucide="filter" class="w-5 h-5 mr-gr-xs"></i>
                    Apply Filters
                </button>
                <?php if($search || $facilityId || $rating): ?>
                    <a href="<?php echo e(route('admin.reviews.index')); ?>" class="inline-flex items-center px-gr-md py-gr-sm bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i data-lucide="x" class="w-5 h-5 mr-gr-xs"></i>
                        Clear
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    
    <?php if($reviews->count() > 0): ?>
        <div class="space-y-gr-md">
            <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
                    <div class="flex items-start gap-gr-md">
                        <?php if($review->facility_image): ?>
                            <img src="<?php echo e(Storage::url($review->facility_image)); ?>" alt="<?php echo e($review->facility_name); ?>" 
                                class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                        <?php else: ?>
                            <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-lucide="building-2" class="w-10 h-10 text-gray-400"></i>
                            </div>
                        <?php endif; ?>

                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-gr-sm">
                                <div>
                                    <h3 class="text-h3 font-bold text-lgu-headline mb-1"><?php echo e($review->facility_name); ?></h3>
                                    <div class="flex items-center gap-gr-sm text-small text-gray-600">
                                        <span class="font-semibold"><?php echo e($review->user_name); ?></span>
                                        <span>•</span>
                                        <span><?php echo e(\Carbon\Carbon::parse($review->created_at)->format('M j, Y')); ?></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-gr-xs">
                                    <a href="<?php echo e(route('admin.reviews.show', $review->id)); ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200" title="View Details">
                                        <i data-lucide="eye" class="w-5 h-5"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-center gap-gr-xs mb-gr-sm">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= $review->rating): ?>
                                        <i data-lucide="star" class="w-5 h-5 text-lgu-button fill-current"></i>
                                    <?php else: ?>
                                        <i data-lucide="star" class="w-5 h-5 text-gray-300"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <span class="text-body font-semibold text-lgu-headline ml-gr-xs"><?php echo e($review->rating); ?>.0</span>
                            </div>

                            <?php if($review->review): ?>
                                <p class="text-body text-gray-700"><?php echo e($review->review); ?></p>
                            <?php else: ?>
                                <p class="text-body text-gray-400 italic">No written review provided.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php if($reviews->hasPages()): ?>
            <div class="mt-gr-lg">
                <?php echo e($reviews->links()); ?>

            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i data-lucide="star" class="w-16 h-16 text-gray-300 mb-gr-md mx-auto"></i>
            <h3 class="text-h3 font-bold text-lgu-headline mb-gr-xs">No Reviews Found</h3>
            <p class="text-body text-gray-600">No citizen reviews match your filters.</p>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\local-government-unit-1-ph.com\resources\views/admin/reviews/index.blade.php ENDPATH**/ ?>