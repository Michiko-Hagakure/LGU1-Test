@php
    use Illuminate\Support\Facades\URL;
@endphp

<!-- MAIN SECTION -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Main</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="{{ URL::signedRoute('citizen.dashboard') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Dashboard</span>
            </a>
        </li>
    </ul>
</div>

<!-- BOOKING MANAGEMENT SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Bookings</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="{{ URL::signedRoute('citizen.browse-facilities') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.browse-facilities') ? 'active' : '' }}">
                <i data-lucide="calendar-plus" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Book Facility</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('citizen.reservations') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.reservations') ? 'active' : '' }}">
                <i data-lucide="calendar-check" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>My Reservations</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('citizen.conflicts.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.conflicts.*') ? 'active' : '' }}">
                <i data-lucide="alert-triangle" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Booking Conflicts</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('citizen.reservation.history') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.reservation.history') ? 'active' : '' }}">
                <i data-lucide="history" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Booking History</span>
            </a>
        </li>
    </ul>
</div>

<!-- FACILITIES BROWSE SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Facilities</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="{{ URL::signedRoute('citizen.facility-calendar') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.facility-calendar') ? 'active' : '' }}">
                <i data-lucide="calendar-days" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Availability Calendar</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('citizen.reviews.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.reviews.*') ? 'active' : '' }}">
                <i data-lucide="star" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>My Reviews</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('citizen.favorites.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.favorites.*') ? 'active' : '' }}">
                <i data-lucide="heart" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Favorites</span>
                @php
                    $favoritesCount = auth()->check() ? auth()->user()->favorites()->count() : 0;
                @endphp
                @if($favoritesCount > 0)
                    <span class="ml-auto text-xs bg-lgu-highlight text-lgu-button-text px-2 py-0.5 rounded-full font-semibold">{{ $favoritesCount }}</span>
                @endif
            </a>
        </li>
    </ul>
</div>

<!-- PAYMENTS SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Payments</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="{{ URL::signedRoute('citizen.payment-slips') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.payment-slips') ? 'active' : '' }}">
                <i data-lucide="file-text" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Payment Slips</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('citizen.payment-methods.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.payment-methods.*') ? 'active' : '' }}">
                <i data-lucide="credit-card" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Payment Methods</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('citizen.transactions.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.transactions.*') ? 'active' : '' }}">
                <i data-lucide="receipt" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Transaction History</span>
            </a>
        </li>
    </ul>
</div>

<!-- COMMUNITY SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Community</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="{{ URL::signedRoute('citizen.bulletin') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.bulletin') ? 'active' : '' }}">
                <i data-lucide="layout-grid" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Bulletin Board</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('citizen.events.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.events.*') ? 'active' : '' }}">
                <i data-lucide="calendar" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Events</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('citizen.news.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.news.*') ? 'active' : '' }}">
                <i data-lucide="newspaper" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>News</span>
            </a>
        </li>
    </ul>
</div>

<!-- SUPPORT SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Support</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="{{ URL::signedRoute('citizen.help-center.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.help-center.*') ? 'active' : '' }}">
                <i data-lucide="help-circle" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Help Center</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('citizen.contact.index') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.contact.*') ? 'active' : '' }}">
                <i data-lucide="message-circle" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Contact Us</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('citizen.contact.my-inquiries') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.contact.my-inquiries') || request()->routeIs('citizen.contact.show-inquiry') ? 'active' : '' }}">
                <i data-lucide="inbox" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>My Inquiries</span>
            </a>
        </li>
    </ul>
</div>

<!-- ACCOUNT SUBMODULE -->
<div class="px-gr-md mb-gr-lg">
    <h4 class="text-gray-400 text-caption font-semibold uppercase tracking-wider mb-gr-xs">Account</h4>
    <ul class="space-y-gr-xs">
        <li>
            <a href="{{ URL::signedRoute('citizen.profile') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.profile') ? 'active' : '' }}">
                <i data-lucide="user-circle" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Profile Settings</span>
            </a>
        </li>
        <li>
            <a href="{{ URL::signedRoute('citizen.security') }}" class="sidebar-link flex items-center px-gr-sm py-gr-xs text-small font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('citizen.security*') ? 'active' : '' }}">
                <i data-lucide="shield" class="w-5 h-5 mr-gr-xs flex-shrink-0"></i>
                <span>Security</span>
            </a>
        </li>
    </ul>
</div>
