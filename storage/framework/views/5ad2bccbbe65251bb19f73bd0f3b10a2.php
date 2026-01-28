

<?php $__env->startSection('title', 'Help Center'); ?>
<?php $__env->startSection('page-title', 'Help Center'); ?>
<?php $__env->startSection('page-subtitle', 'Find answers to your questions'); ?>

<?php $__env->startSection('page-content'); ?>
<!-- Header -->
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Help Center</h1>
        <p class="text-gray-600 text-lg">Find answers to your questions</p>
    </div>

    <!-- Search Bar -->
    <div class="max-w-3xl mx-auto mb-12">
        <form action="<?php echo e(route('citizen.help-center.search')); ?>" method="GET" class="relative">
            <input type="text" name="q" placeholder="Search for help..." 
                class="w-full px-6 py-4 pr-12 text-lg border-2 border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-lg">
            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full transition duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
        </form>
    </div>

    <!-- Popular Articles -->
    <?php if($popularArticles->count() > 0): ?>
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Popular Articles</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $popularArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('citizen.help-center.article', $article->slug)); ?>" 
                class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3 mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 mb-2"><?php echo e($article->title); ?></h3>
                        <p class="text-sm text-gray-600"><?php echo e(Str::limit($article->excerpt, 80)); ?></p>
                        <div class="flex items-center text-xs text-gray-500 mt-3">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <?php echo e($article->view_count); ?> views
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- FAQ Categories -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Frequently Asked Questions</h2>
        
        <?php if($faqCategories->count() > 0): ?>
        <div class="space-y-6">
            <?php $__currentLoopData = $faqCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4">
                    <h3 class="font-bold text-white text-lg flex items-center">
                        <?php if($category->icon): ?>
                        <span class="mr-3 text-2xl"><?php echo e($category->icon); ?></span>
                        <?php endif; ?>
                        <?php echo e($category->name); ?>

                    </h3>
                    <?php if($category->description): ?>
                    <p class="text-blue-100 text-sm mt-1"><?php echo e($category->description); ?></p>
                    <?php endif; ?>
                </div>
                
                <?php if($category->faqs->count() > 0): ?>
                <div class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $category->faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4 hover:bg-gray-50">
                        <button type="button" onclick="toggleFaq(<?php echo e($faq->id); ?>)" class="w-full text-left">
                            <div class="flex justify-between items-start">
                                <h4 class="font-semibold text-gray-800 pr-4"><?php echo e($faq->question); ?></h4>
                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0 transform transition-transform" id="icon-<?php echo e($faq->id); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        <div id="faq-<?php echo e($faq->id); ?>" class="hidden mt-3 text-gray-600">
                            <p><?php echo e($faq->answer); ?></p>
                            <div class="flex items-center mt-4 pt-4 border-t border-gray-200">
                                <span class="text-sm text-gray-500 mr-4">Was this helpful?</span>
                                <button onclick="markHelpful('faq', <?php echo e($faq->id); ?>, 'yes')" class="text-green-600 hover:text-green-700 text-sm font-medium mr-3">
                                    👍 Yes (<?php echo e($faq->helpful_count); ?>)
                                </button>
                                <button onclick="markHelpful('faq', <?php echo e($faq->id); ?>, 'no')" class="text-red-600 hover:text-red-700 text-sm font-medium">
                                    👎 No (<?php echo e($faq->not_helpful_count); ?>)
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php else: ?>
                <div class="p-6 text-center text-gray-500">
                    No FAQs available in this category.
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="bg-gray-50 rounded-lg p-12 text-center">
            <p class="text-gray-600">No FAQ categories available yet.</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Quick Links -->
    <div class="bg-blue-50 rounded-lg p-8 text-center">
        <h3 class="font-bold text-gray-800 mb-4 text-lg">Still need help?</h3>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo e(route('citizen.help-center.articles')); ?>" class="bg-white hover:bg-gray-50 text-blue-600 font-semibold px-6 py-3 rounded-lg transition duration-200 shadow-md">
                Browse All Articles
            </a>
            <a href="<?php echo e(route('citizen.contact.index')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200 shadow-md">
                Contact Support
            </a>
        </div>
</div>

<script>
function toggleFaq(id) {
    const content = document.getElementById('faq-' + id);
    const icon = document.getElementById('icon-' + id);
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

function markHelpful(type, id, helpful) {
    fetch(`<?php echo e(route('citizen.help-center.helpful', ['type' => 'TYPE', 'id' => 'ID'])); ?>`.replace('TYPE', type).replace('ID', id), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ helpful: helpful })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Thank you!',
                text: 'Your feedback helps us improve.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.citizen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/citizen/help-center/index.blade.php ENDPATH**/ ?>