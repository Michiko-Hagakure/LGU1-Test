<?php $__env->startSection('title', 'Help Articles'); ?>
<?php $__env->startSection('page-title', 'Help Articles'); ?>
<?php $__env->startSection('page-subtitle', 'Browse all help articles'); ?>

<?php $__env->startSection('page-content'); ?>
<!-- Header -->
<div class="mb-8">
    <div class="flex items-center mb-4">
        <a href="<?php echo e(route('citizen.help-center.index')); ?>" 
           class="inline-flex items-center text-lgu-button hover:text-lgu-highlight font-medium">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
            Back to Help Center
        </a>
    </div>
    <h1 class="text-3xl font-bold text-lgu-headline mb-2">Help Articles</h1>
    <p class="text-lgu-paragraph">Browse all articles to find the information you need</p>
</div>

<!-- Category Filter -->
<div class="mb-8">
    <div class="flex flex-wrap gap-2">
        <a href="<?php echo e(route('citizen.help-center.articles')); ?>" 
           class="px-4 py-2 rounded-full text-sm font-medium transition <?php echo e(!request('category') || request('category') == 'all' ? 'bg-lgu-button text-lgu-button-text' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'); ?>">
            All Articles
        </a>
        <a href="<?php echo e(route('citizen.help-center.articles', ['category' => 'getting-started'])); ?>" 
           class="px-4 py-2 rounded-full text-sm font-medium transition <?php echo e(request('category') == 'getting-started' ? 'bg-lgu-button text-lgu-button-text' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'); ?>">
            Getting Started
        </a>
        <a href="<?php echo e(route('citizen.help-center.articles', ['category' => 'booking'])); ?>" 
           class="px-4 py-2 rounded-full text-sm font-medium transition <?php echo e(request('category') == 'booking' ? 'bg-lgu-button text-lgu-button-text' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'); ?>">
            Booking
        </a>
        <a href="<?php echo e(route('citizen.help-center.articles', ['category' => 'payments'])); ?>" 
           class="px-4 py-2 rounded-full text-sm font-medium transition <?php echo e(request('category') == 'payments' ? 'bg-lgu-button text-lgu-button-text' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'); ?>">
            Payments
        </a>
        <a href="<?php echo e(route('citizen.help-center.articles', ['category' => 'account'])); ?>" 
           class="px-4 py-2 rounded-full text-sm font-medium transition <?php echo e(request('category') == 'account' ? 'bg-lgu-button text-lgu-button-text' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'); ?>">
            Account
        </a>
    </div>
</div>

<!-- Articles List -->
<?php if($articles->count() > 0): ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route('citizen.help-center.article', $article->slug)); ?>" 
       class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200 border border-gray-100">
        <div class="flex items-start">
            <div class="flex-shrink-0 bg-lgu-bg rounded-lg p-3 mr-4">
                <i data-lucide="file-text" class="w-6 h-6 text-lgu-headline"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-lgu-headline mb-2"><?php echo e($article->title); ?></h3>
                <p class="text-sm text-lgu-paragraph"><?php echo e(Str::limit($article->excerpt, 100)); ?></p>
                
                <?php if($article->category): ?>
                <span class="inline-block mt-3 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                    <?php echo e(ucfirst($article->category)); ?>

                </span>
                <?php endif; ?>
                
                <div class="flex items-center text-xs text-gray-500 mt-3">
                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                    <?php echo e($article->view_count ?? 0); ?> views
                    <span class="mx-2">|</span>
                    <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                    <?php echo e($article->created_at->diffForHumans()); ?>

                </div>
            </div>
        </div>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php else: ?>
<div class="bg-white rounded-lg shadow-md p-12 text-center">
    <div class="flex justify-center mb-4">
        <div class="bg-gray-100 rounded-full p-4">
            <i data-lucide="file-x" class="w-12 h-12 text-gray-400"></i>
        </div>
    </div>
    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Articles Found</h3>
    <p class="text-gray-600 mb-6">
        <?php if(request('category')): ?>
            No articles found in this category.
        <?php else: ?>
            No help articles are available at this time.
        <?php endif; ?>
    </p>
    <a href="<?php echo e(route('citizen.help-center.index')); ?>" 
       class="inline-flex items-center px-6 py-3 bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-highlight transition">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
        Back to Help Center
    </a>
</div>
<?php endif; ?>

<!-- Contact Support Section -->
<div class="mt-12 bg-lgu-bg rounded-lg p-8 text-center">
    <h3 class="font-bold text-lgu-headline mb-4 text-lg">Can't find what you're looking for?</h3>
    <p class="text-lgu-paragraph mb-6">Our support team is here to help you.</p>
    <a href="<?php echo e(route('citizen.contact.index')); ?>" 
       class="inline-flex items-center px-6 py-3 bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-highlight transition shadow-md">
        <i data-lucide="message-circle" class="w-5 h-5 mr-2"></i>
        Contact Support
    </a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.citizen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/citizen/help-center/articles.blade.php ENDPATH**/ ?>