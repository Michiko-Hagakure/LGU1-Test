@php
    use Illuminate\Support\Facades\URL;
@endphp

<!-- MAIN SECTION -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Main</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="{{ URL::signedRoute('admin.dashboard') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
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
            <a href="{{ URL::signedRoute('admin.bookings.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i data-lucide="calendar-check" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>All Bookings</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.calendar') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.calendar') ? 'active' : '' }}">
                <i data-lucide="calendar-days" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Calendar View</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.schedule-conflicts.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.schedule-conflicts.*') ? 'active' : '' }}">
                <i data-lucide="alert-triangle" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Schedule Conflicts</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.maintenance.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.maintenance.*') ? 'active' : '' }}">
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
            <a href="{{ URL::signedRoute('admin.analytics.revenue-report') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.analytics.revenue-report') ? 'active' : '' }}">
                <i data-lucide="trending-up" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Revenue Reports</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.analytics.payments') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.analytics.payments') ? 'bg-lgu-green text-white' : 'text-lgu-green hover:bg-lgu-green hover:bg-opacity-10' }}">
                <i data-lucide="bar-chart-3" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Payment Analytics</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.transactions.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.transactions.*') ? 'bg-lgu-green text-white' : 'text-lgu-green hover:bg-lgu-green hover:bg-opacity-10' }}">
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
            <a href="{{ URL::signedRoute('admin.facilities.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
                <i data-lucide="building-2" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Manage Facilities</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.equipment.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.equipment.*') ? 'active' : '' }}">
                <i data-lucide="package" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Equipment</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.reviews.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i data-lucide="star" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Reviews Moderation</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.city-events.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.city-events.*') ? 'active' : '' }}">
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
            <a href="{{ URL::signedRoute('admin.infrastructure.project-request') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.infrastructure.project-request') ? 'active' : '' }}">
                <i data-lucide="hard-hat" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>New Project Request</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.infrastructure.projects.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.infrastructure.projects.*') ? 'active' : '' }}">
                <i data-lucide="folder-kanban" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>My Project Requests</span>
            </a>
        </li>
    </ul>
</div>

<!-- COMMUNITY INFRASTRUCTURE MAINTENANCE SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Community Maintenance</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="{{ URL::signedRoute('admin.community-maintenance.create') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.community-maintenance.create') ? 'active' : '' }}">
                <i data-lucide="wrench" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Request Maintenance</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.community-maintenance.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.community-maintenance.index') ? 'active' : '' }}">
                <i data-lucide="clipboard-list" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>My Maintenance Reports</span>
            </a>
        </li>
    </ul>
</div>

<!-- URBAN PLANNING INTEGRATION SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Urban Planning</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="{{ URL::signedRoute('admin.facility-site-selection.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.facility-site-selection.*') ? 'active' : '' }}">
                <i data-lucide="map-pin" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Facility Site Selection</span>
            </a>
        </li>
    </ul>
</div>

<!-- ENERGY EFFICIENCY INTEGRATION SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Energy Efficiency</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="{{ route('admin.fund-requests') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.fund-requests') ? 'active' : '' }}">
                <i data-lucide="banknote" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Fund Requests</span>
            </a>
        </li>
    </ul>
</div>

<!-- USER MANAGEMENT SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Users</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="{{ URL::signedRoute('admin.staff.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                <i data-lucide="users" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Staff Accounts</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.citizens.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.citizens.*') ? 'active' : '' }}">
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
            <a href="{{ URL::signedRoute('admin.news.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <i data-lucide="newspaper" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>News</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.faq-categories.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.faq-categories.*') ? 'active' : '' }}">
                <i data-lucide="folder" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>FAQ Categories</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.faqs.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                <i data-lucide="help-circle" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>FAQs</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.help-articles.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.help-articles.*') ? 'active' : '' }}">
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
            <a href="{{ URL::signedRoute('admin.analytics.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.analytics.index') ? 'active' : '' }}">
                <i data-lucide="layout-grid" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Reports Hub</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.analytics.booking-statistics') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.analytics.booking-statistics') ? 'active' : '' }}">
                <i data-lucide="bar-chart-2" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Booking Statistics</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.analytics.facility-utilization') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.analytics.facility-utilization') ? 'active' : '' }}">
                <i data-lucide="pie-chart" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Facility Utilization</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.analytics.citizen-analytics') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.analytics.citizen-analytics') ? 'active' : '' }}">
                <i data-lucide="users-2" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Citizen Analytics</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.analytics.operational-metrics') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.analytics.operational-metrics') ? 'active' : '' }}">
                <i data-lucide="activity" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Operational Metrics</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.budget.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.budget.*') ? 'active' : '' }}">
                <i data-lucide="wallet" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Budget Management</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.audit-trail.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.audit-trail.*') ? 'active' : '' }}">
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
            <a href="{{ URL::signedRoute('admin.profile') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                <i data-lucide="user-cog" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Profile</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.settings.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i data-lucide="settings" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Settings</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.templates.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">
                <i data-lucide="mail" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Message Templates</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('admin.backup.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
                <i data-lucide="database" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Backup & Restore</span>
            </a>
        </li>
    </ul>
</div>
