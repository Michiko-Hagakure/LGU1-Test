@extends('layouts.citizen')

@section('title', 'My Refunds')
@section('page-title', 'My Refunds')
@section('page-subtitle', 'View and manage your refund requests')

@section('page-content')
<div class="max-w-4xl mx-auto">

    @if(session('success'))
        <div class="mb-gr-md bg-green-50 border-l-4 border-green-500 p-gr-sm rounded-lg shadow-sm">
            <div class="flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mr-gr-xs flex-shrink-0"></i>
                <p class="text-body font-semibold text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($refunds->isEmpty())
        <div class="bg-white rounded-xl shadow-md p-gr-xl text-center">
            <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mx-auto mb-gr-sm"></i>
            <h3 class="text-h3 font-bold text-gray-700 mb-gr-xs">No Refund Requests</h3>
            <p class="text-body text-gray-500">You don't have any pending refund requests.</p>
        </div>
    @else
        <div class="space-y-gr-md">
            @foreach($refunds as $refund)
                <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 
                    @if($refund->status === 'pending_method') border-yellow-500
                    @elseif($refund->status === 'pending_processing') border-blue-500
                    @elseif($refund->status === 'processing') border-orange-500
                    @elseif($refund->status === 'completed') border-green-500
                    @else border-gray-300
                    @endif">
                    <div class="p-gr-md">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-gr-sm">
                            <div class="flex-1">
                                <div class="flex items-center gap-gr-sm mb-gr-xs">
                                    <span class="text-h3 font-bold text-lgu-button">{{ $refund->booking_reference }}</span>
                                    @php
                                        $statusColors = [
                                            'pending_method' => 'bg-yellow-100 text-yellow-800',
                                            'pending_processing' => 'bg-blue-100 text-blue-800',
                                            'processing' => 'bg-orange-100 text-orange-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusLabels = [
                                            'pending_method' => 'Action Required',
                                            'pending_processing' => 'Pending Processing',
                                            'processing' => 'Processing',
                                            'completed' => 'Completed',
                                            'failed' => 'Failed',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$refund->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$refund->status] ?? ucfirst($refund->status) }}
                                    </span>
                                </div>
                                <p class="text-body text-gray-700">{{ $refund->facility_name ?? 'N/A' }}</p>
                                <p class="text-small text-gray-500">{{ $refund->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-h3 font-bold text-green-700">₱{{ number_format($refund->refund_amount, 2) }}</p>
                                <p class="text-small text-gray-500">{{ number_format($refund->refund_percentage, 0) }}% refund</p>
                            </div>
                        </div>

                        @if($refund->status === 'pending_method')
                            <div class="mt-gr-sm p-gr-sm bg-yellow-50 rounded-lg border border-yellow-200">
                                <p class="text-small text-yellow-800 font-semibold mb-gr-xs">
                                    <i data-lucide="alert-triangle" class="w-4 h-4 inline-block mr-1"></i>
                                    Action Required: Please select your preferred refund method
                                </p>
                                <a href="{{ route('citizen.refunds.show', $refund->id) }}" class="inline-flex items-center px-gr-md py-gr-xs bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold rounded-lg transition-colors">
                                    Select Refund Method
                                    <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                                </a>
                            </div>
                        @else
                            <div class="mt-gr-sm flex items-center justify-between">
                                @if($refund->refund_method)
                                    <p class="text-small text-gray-600">
                                        Method: <strong>{{ ucfirst(str_replace('_', ' ', $refund->refund_method)) }}</strong>
                                    </p>
                                @endif
                                <a href="{{ route('citizen.refunds.show', $refund->id) }}" class="text-small text-lgu-button hover:text-lgu-highlight font-semibold">
                                    View Details →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
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
