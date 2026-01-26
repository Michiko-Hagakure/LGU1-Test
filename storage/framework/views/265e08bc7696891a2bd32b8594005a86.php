<!-- MAIN SECTION -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Main</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('treasurer.dashboard')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('treasurer.dashboard') ? 'active' : ''); ?>">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Dashboard</span>
            </a>
        </li>
    </ul>
</div>

<!-- PAYMENTS SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Payments</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('treasurer.payment-verification')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('treasurer.payment-verification') || request()->routeIs('treasurer.payment-slips.show') ? 'active' : ''); ?>">
                <i data-lucide="banknote" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Verify Payments</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('treasurer.payment-history')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('treasurer.payment-history*') ? 'active' : ''); ?>">
                <i data-lucide="history" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Payment History</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('treasurer.official-receipts')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('treasurer.official-receipts*') ? 'active' : ''); ?>">
                <i data-lucide="file-text" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Official Receipts</span>
            </a>
        </li>
    </ul>
</div>

<!-- REPORTS SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Reports</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('treasurer.reports.daily-collections')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('treasurer.reports.daily-collections') ? 'active' : ''); ?>">
                <i data-lucide="calendar-check" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Daily Collections</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('treasurer.reports.monthly-summary')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('treasurer.reports.monthly-summary') ? 'active' : ''); ?>">
                <i data-lucide="bar-chart-3" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Monthly Summary</span>
            </a>
        </li>
    </ul>
</div>

<?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/components/sidebar/treasurer-menu.blade.php ENDPATH**/ ?>