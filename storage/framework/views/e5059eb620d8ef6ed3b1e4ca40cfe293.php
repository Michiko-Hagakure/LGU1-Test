<!-- MAIN SECTION -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Main</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Dashboard</span>
            </a>
        </li>
    </ul>
</div>

<!-- BOOKING MANAGEMENT SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Booking Management</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('admin.bookings.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.bookings.*') ? 'active' : ''); ?>">
                <i data-lucide="calendar-check" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>All Bookings</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.calendar')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.calendar') ? 'active' : ''); ?>">
                <i data-lucide="calendar-days" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Calendar View</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.schedule-conflicts.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.schedule-conflicts.*') ? 'active' : ''); ?>">
                <i data-lucide="alert-triangle" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Schedule Conflicts</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.maintenance.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.maintenance.*') ? 'active' : ''); ?>">
                <i data-lucide="wrench" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Maintenance Schedule</span>
            </a>
        </li>
    </ul>
</div>

<!-- FINANCIAL MANAGEMENT SUBMODULE (Coming Soon) -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Financial</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('admin.analytics.revenue-report')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.analytics.revenue-report') ? 'active' : ''); ?>">
                <i data-lucide="trending-up" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Revenue Reports</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.analytics.payments')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.analytics.payments') ? 'bg-lgu-green text-white' : 'text-lgu-green hover:bg-lgu-green hover:bg-opacity-10'); ?>">
                <i data-lucide="bar-chart-3" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Payment Analytics</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.transactions.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.transactions.*') ? 'bg-lgu-green text-white' : 'text-lgu-green hover:bg-lgu-green hover:bg-opacity-10'); ?>">
                <i data-lucide="receipt" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Transactions</span>
            </a>
        </li>
    </ul>
</div>

<!-- FACILITIES MANAGEMENT SUBMODULE (Coming Soon) -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Facilities</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('admin.facilities.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.facilities.*') ? 'active' : ''); ?>">
                <i data-lucide="building-2" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Manage Facilities</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.equipment.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.equipment.*') ? 'active' : ''); ?>">
                <i data-lucide="package" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Equipment</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.pricing.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.pricing.*') ? 'active' : ''); ?>">
                <i data-lucide="tag" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Pricing</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.reviews.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.reviews.*') ? 'active' : ''); ?>">
                <i data-lucide="star" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Reviews Moderation</span>
            </a>
        </li>
    </ul>
</div>

<!-- USER MANAGEMENT SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Users</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('admin.staff.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.staff.*') ? 'active' : ''); ?>">
                <i data-lucide="users" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Staff Accounts</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.citizens.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.citizens.*') ? 'active' : ''); ?>">
                <i data-lucide="user-check" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Citizens</span>
            </a>
        </li>
    </ul>
</div>

<!-- REPORTS & ANALYTICS SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Reports & Analytics</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('admin.analytics.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.analytics.index') ? 'active' : ''); ?>">
                <i data-lucide="layout-grid" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Reports Hub</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.analytics.booking-statistics')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.analytics.booking-statistics') ? 'active' : ''); ?>">
                <i data-lucide="bar-chart-2" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Booking Statistics</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.analytics.facility-utilization')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.analytics.facility-utilization') ? 'active' : ''); ?>">
                <i data-lucide="pie-chart" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Facility Utilization</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.analytics.citizen-analytics')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.analytics.citizen-analytics') ? 'active' : ''); ?>">
                <i data-lucide="users-2" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Citizen Analytics</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.analytics.operational-metrics')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.analytics.operational-metrics') ? 'active' : ''); ?>">
                <i data-lucide="activity" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Operational Metrics</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.budget.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.budget.*') ? 'active' : ''); ?>">
                <i data-lucide="wallet" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Budget Management</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.audit.trail')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.audit.trail') ? 'bg-emerald-800 text-white shadow-lg shadow-emerald-900/20' : 'text-gray-300 hover:bg-emerald-700'); ?>">
                <i data-lucide="shield-check" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Audit Trail</span>
            </a>
        </li>
    </ul>
</div>

<!-- SYSTEM SETTINGS SUBMODULE (Coming Soon) -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">System</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('admin.settings')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 
            <?php echo e(request()->routeIs('admin.settings') ? 'bg-green-800 text-white' : 'text-gray-300 hover:bg-green-700'); ?>">
            <i data-lucide="settings" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
            <span>Settings</span>
        </a>
        </li>
        <li>
            <a href="#" onclick="showComingSoon('Backup & Restore'); return false;" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 opacity-60">
                <i data-lucide="database" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Backup</span>
                <span class="ml-auto text-xs bg-gray-500 text-white px-2 py-0.5 rounded-full">Soon</span>
            </a>
        </li>
    </ul>
</div>
<?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\local-government-unit-1-ph.com\resources\views/components/sidebar/admin-menu.blade.php ENDPATH**/ ?>