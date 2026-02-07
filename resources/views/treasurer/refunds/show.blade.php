@extends('layouts.treasurer')

@section('title', 'Refund Details - ' . $refund->booking_reference)
@section('page-title', 'Refund Details')
@section('page-subtitle', $refund->booking_reference)

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

<!-- Back Button -->
<div class="mb-gr-md">
    <a href="{{ route('treasurer.refunds.index') }}" class="inline-flex items-center text-body text-lgu-button hover:text-lgu-highlight transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
        Back to Refund Queue
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-gr-md">
    <!-- Left Column: Refund Details -->
    <div class="lg:col-span-2 space-y-gr-md">
        <!-- Refund Info Card -->
        <div class="bg-white rounded-xl shadow-md p-gr-md">
            <h3 class="text-h3 font-bold text-gray-900 mb-gr-sm flex items-center gap-2">
                <i data-lucide="receipt-text" class="w-5 h-5 text-lgu-button"></i>
                Refund Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-sm">
                <div>
                    <p class="text-caption text-gray-500 font-semibold">Booking Reference</p>
                    <p class="text-body font-bold text-lgu-button">{{ $refund->booking_reference }}</p>
                </div>
                <div>
                    <p class="text-caption text-gray-500 font-semibold">Refund Type</p>
                    <p class="text-body font-semibold">
                        @if($refund->refund_type === 'admin_rejected')
                            <span class="text-red-600">Admin Rejected</span>
                        @else
                            <span class="text-orange-600">Citizen Cancelled</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-caption text-gray-500 font-semibold">Original Amount</p>
                    <p class="text-body font-semibold text-gray-700">₱{{ number_format($refund->original_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-caption text-gray-500 font-semibold">Refund Amount</p>
                    <p class="text-h3 font-bold text-green-700">₱{{ number_format($refund->refund_amount, 2) }}</p>
                    <p class="text-small text-gray-500">{{ number_format($refund->refund_percentage, 0) }}% refund</p>
                </div>
                <div>
                    <p class="text-caption text-gray-500 font-semibold">Facility</p>
                    <p class="text-body text-gray-700">{{ $refund->facility_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-caption text-gray-500 font-semibold">Date Requested</p>
                    <p class="text-body text-gray-700">{{ $refund->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>

            @if($refund->reason)
            <div class="mt-gr-sm p-gr-sm bg-red-50 rounded-lg border border-red-200">
                <p class="text-caption text-red-600 font-semibold">Rejection Reason</p>
                <p class="text-body text-red-800">{{ $refund->reason }}</p>
            </div>
            @endif
        </div>

        <!-- Applicant Info Card -->
        <div class="bg-white rounded-xl shadow-md p-gr-md">
            <h3 class="text-h3 font-bold text-gray-900 mb-gr-sm flex items-center gap-2">
                <i data-lucide="user" class="w-5 h-5 text-lgu-button"></i>
                Applicant Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-sm">
                <div>
                    <p class="text-caption text-gray-500 font-semibold">Name</p>
                    <p class="text-body font-semibold text-gray-900">{{ $refund->applicant_name }}</p>
                </div>
                <div>
                    <p class="text-caption text-gray-500 font-semibold">Email</p>
                    <p class="text-body text-gray-700">{{ $refund->applicant_email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-caption text-gray-500 font-semibold">Phone</p>
                    <p class="text-body text-gray-700">{{ $refund->applicant_phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Refund Method Info -->
        <div class="bg-white rounded-xl shadow-md p-gr-md">
            <h3 class="text-h3 font-bold text-gray-900 mb-gr-sm flex items-center gap-2">
                <i data-lucide="wallet" class="w-5 h-5 text-lgu-button"></i>
                Refund Method
            </h3>

            @if($refund->refund_method)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-sm">
                    <div>
                        <p class="text-caption text-gray-500 font-semibold">Method</p>
                        <p class="text-body font-bold">
                            @if($refund->refund_method === 'cash')
                                <span class="inline-flex items-center gap-1 text-amber-700"><i data-lucide="banknote" class="w-4 h-4"></i> Cash (at CTO)</span>
                            @elseif($refund->refund_method === 'gcash')
                                <span class="inline-flex items-center gap-1 text-blue-700"><i data-lucide="smartphone" class="w-4 h-4"></i> GCash</span>
                            @elseif($refund->refund_method === 'maya')
                                <span class="inline-flex items-center gap-1 text-green-700"><i data-lucide="smartphone" class="w-4 h-4"></i> Maya</span>
                            @else
                                <span class="inline-flex items-center gap-1 text-purple-700"><i data-lucide="landmark" class="w-4 h-4"></i> Bank Transfer</span>
                            @endif
                        </p>
                    </div>
                    @if($refund->refund_method !== 'cash')
                        <div>
                            <p class="text-caption text-gray-500 font-semibold">Account Name</p>
                            <p class="text-body text-gray-700">{{ $refund->account_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-caption text-gray-500 font-semibold">Account Number</p>
                            <p class="text-body font-mono text-gray-700">{{ $refund->account_number ?? 'N/A' }}</p>
                        </div>
                        @if($refund->refund_method === 'bank_transfer' && $refund->bank_name)
                        <div>
                            <p class="text-caption text-gray-500 font-semibold">Bank Name</p>
                            <p class="text-body text-gray-700">{{ $refund->bank_name }}</p>
                        </div>
                        @endif
                    @else
                        <div>
                            <p class="text-caption text-gray-500 font-semibold">Instructions</p>
                            <p class="text-body text-gray-700">Citizen will visit CTO with receipt/OR number</p>
                        </div>
                    @endif
                </div>
            @else
                <div class="p-gr-sm bg-yellow-50 rounded-lg border border-yellow-200 text-center">
                    <i data-lucide="clock" class="w-8 h-8 text-yellow-500 mx-auto mb-2"></i>
                    <p class="text-body font-semibold text-yellow-800">Waiting for citizen to select refund method</p>
                    <p class="text-small text-yellow-600">The citizen has been notified and will choose their preferred refund method.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Status & Actions -->
    <div class="space-y-gr-md">
        <!-- Status Card -->
        <div class="bg-white rounded-xl shadow-md p-gr-md">
            <h3 class="text-h3 font-bold text-gray-900 mb-gr-sm">Status</h3>
            
            @php
                $statusColors = [
                    'pending_method' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                    'pending_processing' => 'bg-blue-100 text-blue-800 border-blue-300',
                    'processing' => 'bg-orange-100 text-orange-800 border-orange-300',
                    'completed' => 'bg-green-100 text-green-800 border-green-300',
                    'failed' => 'bg-red-100 text-red-800 border-red-300',
                ];
                $statusLabels = [
                    'pending_method' => 'Awaiting Refund Method',
                    'pending_processing' => 'Ready to Process',
                    'processing' => 'Processing',
                    'completed' => 'Completed',
                    'failed' => 'Failed',
                ];
            @endphp

            <div class="p-gr-sm rounded-lg border-2 text-center {{ $statusColors[$refund->status] ?? 'bg-gray-100 text-gray-800 border-gray-300' }}">
                <p class="text-h3 font-bold">{{ $statusLabels[$refund->status] ?? ucfirst($refund->status) }}</p>
            </div>

            @if($refund->processed_at)
            <div class="mt-gr-sm text-small text-gray-600">
                <p><strong>Processed:</strong> {{ $refund->processed_at->format('M d, Y h:i A') }}</p>
                @if($refund->or_number)
                    <p><strong>OR Number:</strong> {{ $refund->or_number }}</p>
                @endif
            </div>
            @endif

            @if($refund->treasurer_notes)
            <div class="mt-gr-sm p-gr-xs bg-gray-50 rounded-lg">
                <p class="text-caption text-gray-500 font-semibold">Treasurer Notes</p>
                <p class="text-small text-gray-700 whitespace-pre-line">{{ $refund->treasurer_notes }}</p>
            </div>
            @endif
        </div>

        <!-- Action Card -->
        @if($refund->status !== 'completed' && $refund->status !== 'failed')
        <div class="bg-white rounded-xl shadow-md p-gr-md">
            <h3 class="text-h3 font-bold text-gray-900 mb-gr-sm">Actions</h3>

            <form method="POST" action="{{ route('treasurer.refunds.process', $refund->id) }}" class="space-y-gr-sm">
                @csrf

                @if($refund->status === 'pending_method')
                    <div class="p-gr-sm bg-yellow-50 rounded-lg border border-yellow-200">
                        <p class="text-small text-yellow-800">Cannot process yet. Citizen must first select their preferred refund method.</p>
                    </div>
                @endif

                @if($refund->status === 'pending_processing')
                    <button type="submit" name="action" value="processing" class="w-full px-gr-lg py-gr-sm bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-lg transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="loader" class="w-4 h-4"></i>
                        Mark as Processing
                    </button>
                @endif

                @if(in_array($refund->status, ['pending_processing', 'processing']))
                    <div>
                        <label class="block text-small font-medium text-gray-700 mb-gr-xs">OR Number (optional)</label>
                        <input type="text" name="or_number" value="{{ $refund->or_number }}" placeholder="e.g. OR-2026-0001" class="w-full px-gr-sm py-gr-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-body">
                    </div>

                    <div>
                        <label class="block text-small font-medium text-gray-700 mb-gr-xs">Notes (optional)</label>
                        <textarea name="treasurer_notes" rows="3" placeholder="Add processing notes..." class="w-full px-gr-sm py-gr-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-body"></textarea>
                    </div>

                    <button type="submit" name="action" value="completed" class="w-full px-gr-lg py-gr-sm bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        Mark Refund as Completed
                    </button>
                @endif
            </form>
        </div>
        @endif

        <!-- Timeline -->
        <div class="bg-white rounded-xl shadow-md p-gr-md">
            <h3 class="text-h3 font-bold text-gray-900 mb-gr-sm">Timeline</h3>
            <div class="space-y-gr-sm">
                <div class="flex items-start gap-gr-xs">
                    <div class="w-3 h-3 rounded-full bg-red-500 mt-1.5 flex-shrink-0"></div>
                    <div>
                        <p class="text-small font-semibold text-gray-900">Booking Rejected</p>
                        <p class="text-small text-gray-500">{{ $refund->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                @if($refund->refund_method)
                <div class="flex items-start gap-gr-xs">
                    <div class="w-3 h-3 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></div>
                    <div>
                        <p class="text-small font-semibold text-gray-900">Refund Method Selected</p>
                        <p class="text-small text-gray-500">{{ ucfirst(str_replace('_', ' ', $refund->refund_method)) }}</p>
                    </div>
                </div>
                @endif

                @if($refund->status === 'processing' || $refund->status === 'completed')
                <div class="flex items-start gap-gr-xs">
                    <div class="w-3 h-3 rounded-full bg-orange-500 mt-1.5 flex-shrink-0"></div>
                    <div>
                        <p class="text-small font-semibold text-gray-900">Processing Started</p>
                    </div>
                </div>
                @endif

                @if($refund->status === 'completed')
                <div class="flex items-start gap-gr-xs">
                    <div class="w-3 h-3 rounded-full bg-green-500 mt-1.5 flex-shrink-0"></div>
                    <div>
                        <p class="text-small font-semibold text-gray-900">Refund Completed</p>
                        @if($refund->processed_at)
                        <p class="text-small text-gray-500">{{ $refund->processed_at->format('M d, Y h:i A') }}</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
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
