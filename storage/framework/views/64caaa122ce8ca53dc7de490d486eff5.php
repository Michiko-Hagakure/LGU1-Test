

<?php $__env->startSection('title', 'Events'); ?>

<?php $__env->startSection('page-content'); ?>
<!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">City Events</h1>
        <p class="text-gray-600">Stay updated with upcoming city events and activities</p>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="<?php echo e(route('citizen.events.index')); ?>" class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <input type="text" name="search" placeholder="Search events..." value="<?php echo e(request('search')); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Category Filter -->
            <div class="w-full md:w-48">
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Categories</option>
                    <option value="city_event" <?php echo e(request('category') == 'city_event' ? 'selected' : ''); ?>>City Events</option>
                    <option value="facility_news" <?php echo e(request('category') == 'facility_news' ? 'selected' : ''); ?>>Facility News</option>
                    <option value="promotion" <?php echo e(request('category') == 'promotion' ? 'selected' : ''); ?>>Promotions</option>
                    <option value="announcement" <?php echo e(request('category') == 'announcement' ? 'selected' : ''); ?>>Announcements</option>
                    <option value="holiday" <?php echo e(request('category') == 'holiday' ? 'selected' : ''); ?>>Holidays</option>
                </select>
            </div>

            <!-- Search Button -->
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition duration-200">
                Search
            </button>
        </form>
    </div>

    <!-- Featured Events -->
    <?php if($featuredEvents->count() > 0): ?>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Featured Events</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php $__currentLoopData = $featuredEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg overflow-hidden text-white">
                <?php if($event->image_path): ?>
                <img src="<?php echo e(asset('storage/' . $event->image_path)); ?>" alt="<?php echo e($event->title); ?>" class="w-full h-48 object-cover opacity-90">
                <?php else: ?>
                <div class="w-full h-48 bg-blue-700 flex items-center justify-center">
                    <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <?php endif; ?>
                <div class="p-4">
                    <div class="flex items-center text-sm mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <?php echo e($event->event_date ? $event->event_date->format('M d, Y') : 'TBA'); ?>

                    </div>
                    <h3 class="font-bold text-lg mb-2"><?php echo e($event->title); ?></h3>
                    <p class="text-sm text-blue-100 mb-3"><?php echo e(Str::limit($event->description, 80)); ?></p>
                    <a href="<?php echo e(route('citizen.events.show', $event->slug)); ?>" class="inline-block bg-white text-blue-600 font-semibold px-4 py-2 rounded-lg hover:bg-blue-50 transition duration-200">
                        Learn More
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- All Events -->
    <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">All Events</h2>
        
        <?php if($events->count() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                <?php if($event->image_path): ?>
                <img src="<?php echo e(asset('storage/' . $event->image_path)); ?>" alt="<?php echo e($event->title); ?>" class="w-full h-48 object-cover">
                <?php else: ?>
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <?php endif; ?>
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                            <?php echo e(ucfirst(str_replace('_', ' ', $event->category))); ?>

                        </span>
                        <span class="text-sm text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <?php echo e($event->view_count); ?>

                        </span>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-800"><?php echo e($event->title); ?></h3>
                    <p class="text-sm text-gray-600 mb-3"><?php echo e(Str::limit($event->description, 100)); ?></p>
                    <div class="flex items-center text-sm text-gray-500 mb-3">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <?php echo e($event->event_date ? $event->event_date->format('F j, Y') : 'Date TBA'); ?>

                    </div>
                    <a href="<?php echo e(route('citizen.events.show', $event->slug)); ?>" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition duration-200">
                        View Details
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <?php echo e($events->links()); ?>

        </div>
        <?php else: ?>
        <div class="bg-gray-50 rounded-lg p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-600 text-lg">No events found.</p>
        </div>
        <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.citizen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/citizen/events/index.blade.php ENDPATH**/ ?>