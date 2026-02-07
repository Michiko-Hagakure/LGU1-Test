@extends('layouts.citizen')

@section('title', 'Refund Details - ' . $refund->booking_reference)
@section('page-title', 'Refund Details')
@section('page-subtitle', $refund->booking_reference)

@section('page-content')
<div class="max-w-3xl mx-auto">

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

    <!-- Back Link -->
    <div class="mb-gr-md">
        <a href="{{ route('citizen.refunds.index') }}" class="inline-flex items-center text-body text-lgu-button hover:text-lgu-highlight transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
            Back to My Refunds
        </a>
    </div>

    <!-- Refund Summary Card -->
    <div class="bg-white rounded-xl shadow-md p-gr-md mb-gr-md">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-gr-sm">
            <div>
                <h3 class="text-h3 font-bold text-gray-900 mb-gr-xs">{{ $refund->booking_reference }}</h3>
                <p class="text-body text-gray-600">{{ $refund->facility_name ?? 'N/A' }}</p>
                <p class="text-small text-gray-500">Requested on {{ $refund->created_at->format('F d, Y h:i A') }}</p>
            </div>
            <div class="text-right">
                @php
                    $statusColors = [
                        'pending_method' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'pending_processing' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'processing' => 'bg-orange-100 text-orange-800 border-orange-300',
                        'completed' => 'bg-green-100 text-green-800 border-green-300',
                        'failed' => 'bg-red-100 text-red-800 border-red-300',
                    ];
                    $statusLabels = [
                        'pending_method' => 'Select Refund Method',
                        'pending_processing' => 'Pending Processing',
                        'processing' => 'Processing (1-3 days)',
                        'completed' => 'Refund Completed',
                        'failed' => 'Failed',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border {{ $statusColors[$refund->status] ?? 'bg-gray-100 text-gray-800 border-gray-300' }}">
                    {{ $statusLabels[$refund->status] ?? ucfirst($refund->status) }}
                </span>
            </div>
        </div>

        <div class="mt-gr-sm grid grid-cols-2 gap-gr-sm">
            <div class="bg-gray-50 rounded-lg p-gr-sm">
                <p class="text-caption text-gray-500 font-semibold">Original Amount</p>
                <p class="text-body font-semibold text-gray-700">₱{{ number_format($refund->original_amount, 2) }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-gr-sm">
                <p class="text-caption text-green-600 font-semibold">Refund Amount ({{ number_format($refund->refund_percentage, 0) }}%)</p>
                <p class="text-h3 font-bold text-green-700">₱{{ number_format($refund->refund_amount, 2) }}</p>
            </div>
        </div>

        @if($refund->reason)
        <div class="mt-gr-sm p-gr-sm bg-red-50 rounded-lg border border-red-200">
            <p class="text-caption text-red-600 font-semibold">Reason for Rejection</p>
            <p class="text-body text-red-800">{{ $refund->reason }}</p>
        </div>
        @endif
    </div>

    <!-- Refund Method Selection (only if pending_method) -->
    @if($refund->status === 'pending_method')
    <div class="bg-white rounded-xl shadow-md p-gr-md mb-gr-md">
        <h3 class="text-h3 font-bold text-gray-900 mb-gr-xs">Choose Your Refund Method</h3>
        <p class="text-body text-gray-600 mb-gr-md">Select how you would like to receive your refund of <strong class="text-green-700">₱{{ number_format($refund->refund_amount, 2) }}</strong>. Processing takes <strong>1-3 business days</strong>.</p>

        <form method="POST" action="{{ route('citizen.refunds.select-method', $refund->id) }}" id="refundMethodForm">
            @csrf

            <!-- Method Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-gr-sm mb-gr-md" id="methodCards">
                <!-- Cash -->
                <label class="method-card cursor-pointer border-2 border-gray-200 rounded-xl p-gr-md hover:border-amber-400 transition-all" data-method="cash">
                    <input type="radio" name="refund_method" value="cash" class="hidden" required>
                    <div class="flex items-center gap-gr-sm">
                        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="banknote" class="w-6 h-6 text-amber-600"></i>
                        </div>
                        <div>
                            <p class="text-body font-bold text-gray-900">Cash</p>
                            <p class="text-small text-gray-500">Visit the City Treasurer's Office</p>
                        </div>
                    </div>
                </label>

                <!-- GCash -->
                <label class="method-card cursor-pointer border-2 border-gray-200 rounded-xl p-gr-md hover:border-blue-400 transition-all" data-method="gcash">
                    <input type="radio" name="refund_method" value="gcash" class="hidden">
                    <div class="flex items-center gap-gr-sm">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="smartphone" class="w-6 h-6 text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-body font-bold text-gray-900">GCash</p>
                            <p class="text-small text-gray-500">Transfer to your GCash wallet</p>
                        </div>
                    </div>
                </label>

                <!-- Maya -->
                <label class="method-card cursor-pointer border-2 border-gray-200 rounded-xl p-gr-md hover:border-green-400 transition-all" data-method="maya">
                    <input type="radio" name="refund_method" value="maya" class="hidden">
                    <div class="flex items-center gap-gr-sm">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="smartphone" class="w-6 h-6 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-body font-bold text-gray-900">Maya</p>
                            <p class="text-small text-gray-500">Transfer to your Maya wallet</p>
                        </div>
                    </div>
                </label>

                <!-- Bank Transfer -->
                <label class="method-card cursor-pointer border-2 border-gray-200 rounded-xl p-gr-md hover:border-purple-400 transition-all" data-method="bank_transfer">
                    <input type="radio" name="refund_method" value="bank_transfer" class="hidden">
                    <div class="flex items-center gap-gr-sm">
                        <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="landmark" class="w-6 h-6 text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-body font-bold text-gray-900">Bank Transfer</p>
                            <p class="text-small text-gray-500">Transfer to your bank account</p>
                        </div>
                    </div>
                </label>
            </div>

            <!-- Account Details (hidden by default, shown for cashless methods) -->
            <div id="accountDetails" class="hidden space-y-gr-sm mb-gr-md bg-gray-50 rounded-xl p-gr-md">
                <h4 class="text-body font-bold text-gray-900">Account Details</h4>

                <div>
                    <label class="block text-small font-medium text-gray-700 mb-1">Account Name <span class="text-red-500">*</span></label>
                    <input type="text" name="account_name" id="account_name" placeholder="e.g. Juan Dela Cruz" class="w-full px-gr-sm py-gr-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent text-body">
                </div>

                <div>
                    <label class="block text-small font-medium text-gray-700 mb-1">Account / Mobile Number <span class="text-red-500">*</span></label>
                    <input type="text" name="account_number" id="account_number" placeholder="e.g. 09171234567" class="w-full px-gr-sm py-gr-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent text-body">
                </div>

                <div id="bankNameField" class="hidden">
                    <label class="block text-small font-medium text-gray-700 mb-1">Bank Name <span class="text-red-500">*</span></label>
                    <select name="bank_name" id="bank_name" class="w-full px-gr-sm py-gr-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent text-body">
                        <option value="">Select Bank</option>
                        <option value="BDO">BDO Unibank</option>
                        <option value="BPI">Bank of the Philippine Islands (BPI)</option>
                        <option value="Metrobank">Metropolitan Bank & Trust (Metrobank)</option>
                        <option value="Landbank">Land Bank of the Philippines</option>
                        <option value="PNB">Philippine National Bank (PNB)</option>
                        <option value="UnionBank">UnionBank of the Philippines</option>
                        <option value="RCBC">Rizal Commercial Banking Corp (RCBC)</option>
                        <option value="EastWest">EastWest Bank</option>
                        <option value="SecurityBank">Security Bank</option>
                        <option value="ChinaBank">China Banking Corporation</option>
                        <option value="PSBank">PSBank</option>
                    </select>
                </div>
            </div>

            <!-- Cash Instructions (hidden by default) -->
            <div id="cashInstructions" class="hidden mb-gr-md bg-amber-50 rounded-xl p-gr-md border border-amber-200">
                <h4 class="text-body font-bold text-amber-800 mb-gr-xs">
                    <i data-lucide="info" class="w-4 h-4 inline-block mr-1"></i>
                    Cash Refund Instructions
                </h4>
                <ul class="text-small text-amber-700 space-y-1 list-disc list-inside">
                    <li>Visit the City Treasurer's Office (CTO) during office hours</li>
                    <li>Bring your booking receipt or Official Receipt number</li>
                    <li>Present a valid government-issued ID</li>
                    <li>Refund will be released within 1-3 business days after processing</li>
                </ul>
            </div>

            @if($errors->any())
            <div class="mb-gr-md bg-red-50 border border-red-200 rounded-lg p-gr-sm">
                <ul class="text-small text-red-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <button type="submit" id="submitBtn" disabled class="w-full px-gr-lg py-gr-md bg-lgu-button hover:bg-lgu-highlight text-white font-bold rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                Confirm Refund Method
            </button>
        </form>
    </div>
    @endif

    <!-- Refund Method Details (if already selected) -->
    @if($refund->refund_method && $refund->status !== 'pending_method')
    <div class="bg-white rounded-xl shadow-md p-gr-md mb-gr-md">
        <h3 class="text-h3 font-bold text-gray-900 mb-gr-sm">Selected Refund Method</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-sm">
            <div>
                <p class="text-caption text-gray-500 font-semibold">Method</p>
                <p class="text-body font-bold">{{ ucfirst(str_replace('_', ' ', $refund->refund_method)) }}</p>
            </div>
            @if($refund->refund_method !== 'cash')
            <div>
                <p class="text-caption text-gray-500 font-semibold">Account Name</p>
                <p class="text-body text-gray-700">{{ $refund->account_name }}</p>
            </div>
            <div>
                <p class="text-caption text-gray-500 font-semibold">Account Number</p>
                <p class="text-body font-mono text-gray-700">{{ $refund->account_number }}</p>
            </div>
            @if($refund->bank_name)
            <div>
                <p class="text-caption text-gray-500 font-semibold">Bank</p>
                <p class="text-body text-gray-700">{{ $refund->bank_name }}</p>
            </div>
            @endif
            @endif
        </div>

        @if($refund->status === 'completed' && $refund->or_number)
        <div class="mt-gr-sm p-gr-sm bg-green-50 rounded-lg border border-green-200">
            <p class="text-caption text-green-600 font-semibold">Official Receipt Number</p>
            <p class="text-body font-bold text-green-800">{{ $refund->or_number }}</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Progress Timeline -->
    <div class="bg-white rounded-xl shadow-md p-gr-md">
        <h3 class="text-h3 font-bold text-gray-900 mb-gr-sm">Refund Progress</h3>
        <div class="space-y-0">
            @php
                $steps = [
                    ['key' => 'rejected', 'label' => 'Booking Rejected', 'desc' => 'Admin rejected the booking', 'done' => true],
                    ['key' => 'method', 'label' => 'Refund Method Selected', 'desc' => 'Choose how to receive refund', 'done' => !in_array($refund->status, ['pending_method'])],
                    ['key' => 'processing', 'label' => 'Processing Refund', 'desc' => '1-3 business days', 'done' => in_array($refund->status, ['processing', 'completed'])],
                    ['key' => 'completed', 'label' => 'Refund Completed', 'desc' => 'Refund has been released', 'done' => $refund->status === 'completed'],
                ];
            @endphp

            @foreach($steps as $i => $step)
            <div class="flex items-start gap-gr-sm {{ $i < count($steps) - 1 ? 'pb-gr-md' : '' }}">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 {{ $step['done'] ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                        @if($step['done'])
                            <i data-lucide="check" class="w-4 h-4"></i>
                        @else
                            <span class="text-sm font-bold">{{ $i + 1 }}</span>
                        @endif
                    </div>
                    @if($i < count($steps) - 1)
                        <div class="w-0.5 h-full min-h-[20px] {{ $step['done'] ? 'bg-green-300' : 'bg-gray-200' }}"></div>
                    @endif
                </div>
                <div class="pt-1">
                    <p class="text-body font-semibold {{ $step['done'] ? 'text-green-700' : 'text-gray-600' }}">{{ $step['label'] }}</p>
                    <p class="text-small text-gray-500">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
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

    const methodCards = document.querySelectorAll('.method-card');
    const accountDetails = document.getElementById('accountDetails');
    const cashInstructions = document.getElementById('cashInstructions');
    const bankNameField = document.getElementById('bankNameField');
    const submitBtn = document.getElementById('submitBtn');

    if (!methodCards.length) return; // No form on this page

    methodCards.forEach(function(card) {
        card.addEventListener('click', function() {
            // Remove active state from all cards
            methodCards.forEach(function(c) {
                c.classList.remove('border-lgu-button', 'bg-lgu-bg', 'shadow-md');
                c.classList.add('border-gray-200');
            });

            // Set active state
            card.classList.remove('border-gray-200');
            card.classList.add('border-lgu-button', 'bg-lgu-bg', 'shadow-md');

            // Check the radio
            card.querySelector('input[type="radio"]').checked = true;

            const method = card.dataset.method;

            // Toggle account details
            if (method === 'cash') {
                accountDetails.classList.add('hidden');
                cashInstructions.classList.remove('hidden');
                bankNameField.classList.add('hidden');
                // Clear required
                document.getElementById('account_name').removeAttribute('required');
                document.getElementById('account_number').removeAttribute('required');
            } else {
                accountDetails.classList.remove('hidden');
                cashInstructions.classList.add('hidden');
                document.getElementById('account_name').setAttribute('required', 'required');
                document.getElementById('account_number').setAttribute('required', 'required');

                if (method === 'bank_transfer') {
                    bankNameField.classList.remove('hidden');
                    document.getElementById('bank_name').setAttribute('required', 'required');
                } else {
                    bankNameField.classList.add('hidden');
                    document.getElementById('bank_name').removeAttribute('required');
                }

                // Update placeholder based on method
                const numInput = document.getElementById('account_number');
                if (method === 'gcash' || method === 'maya') {
                    numInput.placeholder = 'e.g. 09171234567';
                } else {
                    numInput.placeholder = 'e.g. 1234567890';
                }
            }

            // Enable submit
            submitBtn.disabled = false;
        });
    });
});
</script>
@endpush
