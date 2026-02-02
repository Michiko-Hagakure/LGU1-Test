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
            <a href="<?php echo e(route('admin.reviews.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.reviews.*') ? 'active' : ''); ?>">
                <i data-lucide="star" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Reviews Moderation</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.city-events.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.city-events.*') ? 'active' : ''); ?>">
                <i data-lucide="calendar-days" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>City Events</span>
            </a>
        </li>
    </ul>
</div>

<!-- INFRASTRUCTURE INTEGRATION SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Infrastructure PM</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('admin.infrastructure.project-request')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.infrastructure.project-request') ? 'active' : ''); ?>">
                <i data-lucide="hard-hat" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>New Project Request</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.infrastructure.projects.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.infrastructure.projects.*') ? 'active' : ''); ?>">
                <i data-lucide="folder-kanban" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>My Project Requests</span>
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

<!-- CONTENT MANAGEMENT SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Content Management</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="<?php echo e(route('admin.news.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.news.*') ? 'active' : ''); ?>">
                <i data-lucide="newspaper" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>News</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.faq-categories.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.faq-categories.*') ? 'active' : ''); ?>">
                <i data-lucide="folder" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>FAQ Categories</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.faqs.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.faqs.*') ? 'active' : ''); ?>">
                <i data-lucide="help-circle" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>FAQs</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.help-articles.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.help-articles.*') ? 'active' : ''); ?>">
                <i data-lucide="book-open" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Help Articles</span>
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
            <a href="<?php echo e(route('admin.audit-trail.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.audit-trail.*') ? 'active' : ''); ?>">
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
            <a href="<?php echo e(route('admin.profile')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.profile') ? 'active' : ''); ?>">
                <i data-lucide="user-cog" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Profile</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.settings.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.settings.*') ? 'active' : ''); ?>">
                <i data-lucide="settings" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Settings</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.templates.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.templates.*') ? 'active' : ''); ?>">
                <i data-lucide="mail" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Message Templates</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('admin.backup.index')); ?>" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 <?php echo e(request()->routeIs('admin.backup.*') ? 'active' : ''); ?>">
                <i data-lucide="database" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Backup & Restore</span>
            </a>
        </li>
    </ul>
</div>
<?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/components/sidebar/admin-menu.blade.php ENDPATH**/ ?>