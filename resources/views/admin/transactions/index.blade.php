@extends('layouts.admin')

@section('page-title', 'Transaction History')
@section('page-subtitle', 'View and manage all payment transactions')

@section('page-content')
<div class="pb-gr-2xl">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-gr-lg">
        <div>
            <h1 class="text-h3 font-bold text-gray-900 mb-gr-2xs">Transaction History</h1>
            <p class="text-small text-gray-600">View and manage all payment transactions</p>
        </div>
        <div class="flex items-center space-x-gr-sm">
            <button onclick="exportTransactions()" class="btn-secondary flex items-center">
                <i data-lucide="download" class="w-4 h-4 mr-gr-xs"></i>
                Export
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md mb-gr-lg">
        <form method="GET" action="{{ URL::signedRoute('admin.transactions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-gr-md">
            <!-- Date Range -->
            <div>
                <label class="block text-caption font-semibold text-gray-700 mb-gr-2xs">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="input-field">
            </div>
            <div>
                <label class="block text-caption font-semibold text-gray-700 mb-gr-2xs">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="input-field">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-caption font-semibold text-gray-700 mb-gr-2xs">Status</label>
                <select name="status" class="input-field">
                    <option value="">All Statuses</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Payment Method Filter -->
            <div>
                <label class="block text-caption font-semibold text-gray-700 mb-gr-2xs">Payment Method</label>
                <select name="payment_method" class="input-field">
                    <option value="">All Methods</option>
                    <option value="gcash" {{ request('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                    <option value="paymaya" {{ request('payment_method') == 'paymaya' ? 'selected' : '' }}>PayMaya</option>
                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="otc" {{ request('payment_method') == 'otc' ? 'selected' : '' }}>Over-the-Counter</option>
                </select>
            </div>

            <!-- Search and Filter Buttons -->
            <div class="md:col-span-4 flex items-end space-x-gr-sm">
                <div class="flex-1">
                    <label class="block text-caption font-semibold text-gray-700 mb-gr-2xs">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Reference number, citizen name..." class="input-field">
                </div>
                <button type="submit" class="btn-primary">
                    <i data-lucide="search" class="w-4 h-4 mr-gr-xs"></i>
                    Filter
                </button>
                <a href="{{ URL::signedRoute('admin.transactions.index') }}" class="btn-secondary">
                    <i data-lucide="x" class="w-4 h-4 mr-gr-xs"></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-gr-md mb-gr-lg">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-600 mb-gr-2xs">Total Transactions</p>
                    <p class="text-h3 font-bold text-gray-900">{{ number_format($transactions->total()) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="receipt" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-600 mb-gr-2xs">Total Amount</p>
                    <p class="text-h3 font-bold text-gray-900">₱{{ number_format($totalAmount, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="text-h4 font-bold text-green-600">₱</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-600 mb-gr-2xs">Paid</p>
                    <p class="text-h3 font-bold text-green-600">{{ number_format($paidCount) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="circle-check" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-600 mb-gr-2xs">Pending</p>
                    <p class="text-h3 font-bold text-orange-600">{{ number_format($pendingCount) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Reference No.</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Date</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Citizen</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Facility</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Amount</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Payment Method</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Status</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">AI Audit</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50 transition-colors transaction-row"
                    data-amount="{{ $transaction->amount_due }}"
                    data-facility="{{ $transaction->facility_id ?? 1 }}"
                    data-status="{{ strtolower($transaction->status) }}"
                    data-unpaid-history="{{ $transaction->unpaid_count ?? 0 }}">
                        <td class="px-gr-md py-gr-sm">
                            <span class="font-mono text-small font-semibold text-gray-900">{{ $transaction->slip_number ?? $transaction->or_number ?? 'N/A' }}</span>
                        </td>
                        <td class="px-gr-md py-gr-sm text-small text-gray-900">
                            {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}<br>
                            <span class="text-caption text-gray-500">{{ \Carbon\Carbon::parse($transaction->created_at)->format('h:i A') }}</span>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-lgu-green rounded-full flex items-center justify-center mr-gr-xs">
                                    <span class="text-caption font-semibold text-white">{{ strtoupper(substr($transaction->citizen_name ?? 'N', 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-small font-medium text-gray-900">{{ $transaction->citizen_name ?? 'N/A' }}</p>
                                    <p class="text-caption text-gray-500">ID: {{ $transaction->citizen_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-gr-md py-gr-sm text-small text-gray-900">
                            {{ $transaction->facility_name ?? 'N/A' }}
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <span class="text-small font-bold text-gray-900">₱{{ number_format($transaction->amount_due, 2) }}</span>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <span class="inline-flex items-center px-gr-xs py-gr-3xs rounded-full text-caption font-medium
                                @if($transaction->payment_method == 'gcash') bg-blue-100 text-blue-700
                                @elseif($transaction->payment_method == 'paymaya') bg-green-100 text-green-700
                                @elseif($transaction->payment_method == 'bank_transfer') bg-purple-100 text-purple-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $transaction->payment_method ?? 'N/A')) }}
                            </span>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <span class="inline-flex items-center px-gr-xs py-gr-3xs rounded-full text-caption font-medium
                                @if($transaction->status == 'paid') bg-green-100 text-green-700
                                @elseif($transaction->status == 'pending') bg-orange-100 text-orange-700
                                @elseif($transaction->status == 'cancelled') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                <i data-lucide="{{ $transaction->status == 'paid' ? 'circle-check' : ($transaction->status == 'pending' ? 'clock' : 'x-circle') }}" class="w-3 h-3 mr-gr-3xs"></i>
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <span class="ai-status-badge inline-flex items-center px-gr-xs py-gr-3xs rounded-full text-caption font-medium bg-gray-100 text-gray-600">
                                <i data-lucide="loader-2" class="w-3 h-3 mr-gr-3xs animate-spin"></i>
                                Scanning...
                            </span>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <a href="{{ URL::signedRoute('admin.transactions.show', $transaction->id) }}" class="text-lgu-green hover:text-lgu-green-dark font-medium text-small">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-gr-md py-gr-xl text-center text-gray-500">
                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-gr-sm text-gray-400"></i>
                            <p class="text-small">No transactions found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="px-gr-md py-gr-md border-t border-gray-200">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest"></script>
<script>
    async function runAIAudit() {
        console.log("[AI Audit] Initializing Strict Security Model...");

        const model = tf.sequential();
        model.add(tf.layers.dense({units: 12, inputShape: [3], activation: 'relu'}));
        model.add(tf.layers.dense({units: 1, activation: 'sigmoid'}));
        model.compile({optimizer: 'adam', loss: 'binaryCrossentropy'});

        // 1. COLLECT REAL DATA FROM THE TABLE FOR TRAINING
        const rows = document.querySelectorAll('.transaction-row');
        const trainingData = [];
        const labels = [];

        rows.forEach(row => {
            const amt = (parseFloat(row.dataset.amount) || 0) / 100000;
            const hist = parseInt(row.dataset.unpaidHistory) || 0;
            const isPaid = ['paid', 'approved', 'verified'].includes(row.dataset.status.toLowerCase()) ? 1 : 0;
            
            trainingData.push([amt, isPaid, hist]);
            labels.push([isPaid]); // We teach the AI that 'Paid' is the target safety state
        });

        // 2. COMBINE HARDCODED PATTERNS WITH REAL DATA
        const xs = tf.tensor2d([
            [0.1, 1, 0], [0.9, 1, 0], [0.8, 0, 5], [0.05, 0, 10], [0.05, 0, 0],
            ...trainingData // Add the real data from your database here
        ]);
        const ys = tf.tensor2d([
            [1], [1], [0], [0], [1],
            ...labels
        ]); 

        await model.fit(xs, ys, {epochs: 50, verbose: 0});
        console.log("[AI Audit] Training Complete using Real DB Data.");

        // 3. EXECUTE PREDICTION (STRICT ENFORCEMENT)
        for (let row of rows) {
            const amount = parseFloat(row.dataset.amount) || 0;
            const status = (row.dataset.status || '').toLowerCase();
            const unpaidHistory = parseInt(row.dataset.unpaidHistory || 0);
            const badge = row.querySelector('.ai-status-badge');

            if (!badge) continue;

            const isPaid = (status === 'paid' || status === 'approved' || status === 'verified');
            const normalizedAmount = amount / 100000;

            const prediction = model.predict(tf.tensor2d([[normalizedAmount, isPaid ? 1 : 0, unpaidHistory]]));
            const scoreData = await prediction.data();
            const safetyScore = scoreData[0];

            if (isPaid) {
                badge.className = "ai-status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700";
                badge.innerHTML = '<i data-lucide="shield-check" class="w-3 h-3 mr-1"></i> Verified';
            } else if (!isPaid && safetyScore < 0.5) {
                badge.className = "ai-status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 animate-pulse";
                badge.innerHTML = '<i data-lucide="shield-alert" class="w-3 h-3 mr-1"></i> High Risk';
            } else {
                badge.className = "ai-status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100";
                badge.innerHTML = '<i data-lucide="user-check" class="w-3 h-3 mr-gr-3xs"></i> Safe to Proceed';
            }
        }
        
        if (window.lucide) lucide.createIcons();
        console.log("🏁 Audit Finished.");
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof tf !== 'undefined') {
            setTimeout(runAIAudit, 1500); 
        }
    });

    function exportTransactions() {
        const params = new URLSearchParams({
            start_date: document.querySelector('input[name="start_date"]')?.value || '',
            end_date: document.querySelector('input[name="end_date"]')?.value || '',
            status: document.querySelector('select[name="status"]')?.value || '',
            payment_method: document.querySelector('select[name="payment_method"]')?.value || ''
        });
        window.location.href = '{{ route("admin.transactions.export.csv") }}?' + params.toString();
    }

    // AJAX Polling for real-time updates
    let lastTotal = {{ $transactions->total() }};
    function refreshData() {
        fetch('{{ route("admin.transactions.json") }}' + window.location.search)
            .then(res => res.json())
            .then(data => {
                if (data.stats.total !== lastTotal) {
                    location.reload();
                    lastTotal = data.stats.total;
                }
            })
            .catch(err => console.log('Refresh error:', err));
    }
    setInterval(refreshData, 5000);
</script>
@endsection
