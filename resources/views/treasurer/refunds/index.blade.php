@extends('layouts.treasurer')

@section('title', 'Refund Queue')
@section('page-title', 'Refund Queue')
@section('page-subtitle', 'Manage booking refund requests')

@section('page-content')

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-gr-md bg-green-50 border-l-4 border-green-500 p-gr-sm rounded-lg shadow-sm">
        <div class="flex items-center">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mr-gr-xs flex-shrink-0"></i>
            <p class="text-body font-semibold text-green-800">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-gr-md bg-red-50 border-l-4 border-red-500 p-gr-sm rounded-lg shadow-sm">
        <div class="flex items-center">
            <i data-lucide="x-circle" class="w-5 h-5 text-red-600 mr-gr-xs flex-shrink-0"></i>
            <p class="text-body font-semibold text-red-800">{{ session('error') }}</p>
        </div>
    </div>
@endif

<!-- Filters and Search -->
<div class="bg-white rounded-xl shadow-md p-gr-md mb-gr-md">
    <form method="GET" action="{{ route('treasurer.refunds.index') }}" class="space-y-gr-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gr-sm">
            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-caption font-semibold text-gray-700 mb-gr-xs">Status</label>
                <select name="status" id="status" class="w-full px-gr-sm py-gr-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent text-body transition-all">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                    <option value="pending_method" {{ $status === 'pending_method' ? 'selected' : '' }}>Awaiting Method</option>
                    <option value="pending_processing" {{ $status === 'pending_processing' ? 'selected' : '' }}>Ready to Process</option>
                    <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            
            <!-- Search -->
            <div class="md:col-span-2">
                <label for="search" class="block text-caption font-semibold text-gray-700 mb-gr-xs">Search</label>
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by booking reference, name, or email..."
                           class="w-full px-gr-sm py-gr-xs pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent text-body transition-all">
                    <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-gr-sm top-1/2 transform -translate-y-1/2"></i>
                </div>
            </div>
        </div>
        
        <div class="flex gap-gr-xs pt-gr-xs">
            <button type="submit" class="px-gr-md py-gr-xs bg-lgu-button hover:bg-lgu-highlight text-white font-semibold rounded-lg transition-all shadow-sm hover:shadow-md text-body">
                Apply Filters
            </button>
            <a href="{{ route('treasurer.refunds.index') }}" class="px-gr-md py-gr-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-all text-body">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Refund Requests Table -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-gr-md py-gr-sm border-b border-gray-200 flex items-center justify-between">
        <div>
            <h3 class="text-h3 font-bold text-gray-900">Refund Requests</h3>
            <p class="text-small text-gray-600 mt-gr-xs">{{ $refunds->total() }} total refund request(s)</p>
        </div>
        <div class="flex items-center gap-gr-xs">
            <span class="inline-flex items-center px-gr-xs py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800" id="stat-pending-method">
                {{ $stats['pending_method'] }} Awaiting Method
            </span>
            <span class="inline-flex items-center px-gr-xs py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800" id="stat-pending-processing">
                {{ $stats['pending_processing'] }} Ready
            </span>
            <span class="inline-flex items-center px-gr-xs py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800" id="stat-processing">
                {{ $stats['processing'] }} Processing
            </span>
            <span class="inline-flex items-center px-gr-xs py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800" id="stat-completed">
                {{ $stats['completed'] }} Completed
            </span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-gr-sm py-gr-xs text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reference</th>
                    <th class="px-gr-sm py-gr-xs text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Applicant</th>
                    <th class="px-gr-sm py-gr-xs text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Facility</th>
                    <th class="px-gr-sm py-gr-xs text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                    <th class="px-gr-sm py-gr-xs text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Method</th>
                    <th class="px-gr-sm py-gr-xs text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-gr-sm py-gr-xs text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                    <th class="px-gr-sm py-gr-xs text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200" id="refund-table-body">
                @forelse($refunds as $refund)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-gr-sm py-gr-xs whitespace-nowrap">
                        <span class="text-body font-bold text-lgu-button">{{ $refund->booking_reference }}</span>
                    </td>
                    <td class="px-gr-sm py-gr-xs">
                        <div class="text-body font-semibold text-gray-900">{{ $refund->applicant_name }}</div>
                        <div class="text-small text-gray-500">{{ $refund->applicant_email ?? 'N/A' }}</div>
                    </td>
                    <td class="px-gr-sm py-gr-xs">
                        <span class="text-body text-gray-700">{{ $refund->facility_name ?? 'N/A' }}</span>
                    </td>
                    <td class="px-gr-sm py-gr-xs whitespace-nowrap">
                        <div class="text-body font-bold text-green-700">₱{{ number_format($refund->refund_amount, 2) }}</div>
                        <div class="text-small text-gray-500">{{ number_format($refund->refund_percentage, 0) }}% of ₱{{ number_format($refund->original_amount, 2) }}</div>
                    </td>
                    <td class="px-gr-sm py-gr-xs whitespace-nowrap">
                        @if($refund->refund_method)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                @if($refund->refund_method === 'cash') bg-amber-100 text-amber-800
                                @elseif($refund->refund_method === 'gcash') bg-blue-100 text-blue-800
                                @elseif($refund->refund_method === 'maya') bg-green-100 text-green-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $refund->refund_method)) }}
                            </span>
                            @if($refund->account_number)
                                <div class="text-small text-gray-500 mt-1">{{ $refund->account_name }} - {{ $refund->account_number }}</div>
                            @endif
                        @else
                            <span class="text-small text-gray-400 italic">Not yet selected</span>
                        @endif
                    </td>
                    <td class="px-gr-sm py-gr-xs whitespace-nowrap">
                        @php
                            $statusColors = [
                                'pending_method' => 'bg-yellow-100 text-yellow-800',
                                'pending_processing' => 'bg-blue-100 text-blue-800',
                                'processing' => 'bg-orange-100 text-orange-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'failed' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'pending_method' => 'Awaiting Method',
                                'pending_processing' => 'Ready to Process',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$refund->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$refund->status] ?? ucfirst($refund->status) }}
                        </span>
                    </td>
                    <td class="px-gr-sm py-gr-xs whitespace-nowrap">
                        <span class="text-small text-gray-600">{{ $refund->created_at->format('M d, Y') }}</span>
                        <div class="text-small text-gray-400">{{ $refund->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="px-gr-sm py-gr-xs text-center">
                        <a href="{{ route('treasurer.refunds.show', $refund->id) }}" class="inline-flex items-center px-gr-sm py-1 bg-lgu-button hover:bg-lgu-highlight text-white text-xs font-semibold rounded-lg transition-all">
                            <i data-lucide="eye" class="w-3.5 h-3.5 mr-1"></i>
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-gr-sm py-gr-lg text-center text-gray-500">
                        <div class="flex flex-col items-center py-gr-lg">
                            <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mb-gr-sm"></i>
                            <p class="text-body font-semibold">No refund requests found</p>
                            <p class="text-small text-gray-400">Refund requests will appear here when bookings are rejected.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($refunds->hasPages())
    <div class="px-gr-md py-gr-sm border-t border-gray-200">
        {{ $refunds->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
@endpush
