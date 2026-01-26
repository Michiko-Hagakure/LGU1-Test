<div class="flex items-center justify-between h-16 px-6">
    <!-- Mobile menu button -->
    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600 hover:text-gray-900">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
    </button>

    <!-- Page Title -->
    <div class="flex-1 lg:ml-0 ml-4">
        <h1 class="text-2xl font-bold text-lgu-headline"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
        <p class="text-sm text-lgu-paragraph"><?php echo $__env->yieldContent('page-subtitle', 'Citizen Portal'); ?></p>
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center space-x-4">
        <!-- Real-time Clock -->
        <div class="hidden md:flex flex-col items-end">
            <div id="currentTime" class="text-lg font-bold text-gray-900"></div>
            <div id="currentDate" class="text-xs text-gray-600"></div>
        </div>

        <!-- Notifications Bell -->
        <?php echo $__env->make('components.notification-bell', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function updateClock() {
    const now = new Date();
    
    // Format time as 12-hour with AM/PM
    let hours = now.getHours();
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // Convert 0 to 12
    
    const timeString = `${hours}:${minutes}:${seconds} ${ampm}`;
    
    // Format date
    const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
    const dateString = now.toLocaleDateString('en-US', options);
    
    // Update the DOM
    const timeElement = document.getElementById('currentTime');
    const dateElement = document.getElementById('currentDate');
    
    if (timeElement) timeElement.textContent = timeString;
    if (dateElement) dateElement.textContent = dateString;
}

// Update immediately and then every second
updateClock();
setInterval(updateClock, 1000);
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/components/header/citizen-header.blade.php ENDPATH**/ ?>