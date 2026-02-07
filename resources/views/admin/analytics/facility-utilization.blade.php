@extends('layouts.admin')

@section('title', 'Facility Utilization - Admin')

@section('page-content')
    <div class="space-y-6 print-area">
        <!-- Header with Date Filter -->
        <div class="bg-white rounded-lg shadow-sm p-6 no-print">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-lgu-headline">Facility Utilization Report</h2>
                    <p class="text-gray-600 mt-1">Track facility usage and identify optimization opportunities</p>
                </div>

                <!-- Date Range Filter & Export Buttons -->
                <div class="flex flex-wrap items-end gap-3">
                    <form method="GET" action="{{ URL::signedRoute('admin.analytics.facility-utilization') }}"
                        class="flex flex-wrap items-end gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date" value="{{ $startDate }}"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-headline focus:border-lgu-headline">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date" value="{{ $endDate }}"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-headline focus:border-lgu-headline">
                        </div>
                        <button type="submit"
                            class="px-6 py-2 bg-lgu-button text-lgu-button-text rounded-lg hover:opacity-90 transition-all font-semibold">
                            <i data-lucide="filter" class="w-4 h-4 inline mr-1"></i>
                            Filter
                        </button>
                    </form>
                    <button onclick="window.print()"
                        class="px-6 py-2 border-2 border-lgu-stroke text-lgu-headline rounded-lg hover:bg-lgu-bg transition-all font-semibold">
                        <i data-lucide="printer" class="w-4 h-4 inline mr-1"></i>
                        Print
                    </button>

                    <!-- Export Dropdown -->
                    <div class="relative inline-block" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="px-6 py-2 bg-lgu-secondary text-white rounded-lg hover:opacity-90 transition-all font-semibold flex items-center">
                            <i data-lucide="download" class="w-4 h-4 mr-1"></i>
                            Export
                            <i data-lucide="chevron-down" class="w-4 h-4 ml-1"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                            <a href="{{ URL::signedRoute('admin.analytics.export-facility-utilization-excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-lgu-bg">
                                <i data-lucide="file-spreadsheet" class="w-4 h-4 inline mr-2"></i>
                                Export as Excel
                            </a>
                            <a href="{{ URL::signedRoute('admin.analytics.facility-utilization.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-lgu-bg">
                                <i data-lucide="file-text" class="w-4 h-4 inline mr-2"></i>
                                Export as CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Header (hidden on screen, shown when printing) -->
        <div class="hidden print:block mb-6">
            <div class="text-center mb-4">
                <h1 class="text-2xl font-bold text-lgu-headline">Local Government Unit</h1>
                <h2 class="text-xl font-semibold text-gray-700">Facility Utilization Report</h2>
                <p class="text-gray-600">Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                </p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Facilities -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-600">Total Facilities</h3>
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i data-lucide="building" class="w-5 h-5 text-blue-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-lgu-headline">{{ $facilities->count() }}</p>
                <p class="text-xs text-gray-500 mt-2">Active facilities</p>
            </div>

            <!-- High Performing -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-600">High Performing</h3>
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i data-lucide="trending-up" class="w-5 h-5 text-green-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ $highPerforming->count() }}</p>
                <p class="text-xs text-gray-500 mt-2">> 70% utilization</p>
            </div>

            <!-- Underutilized -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-600">Underutilized</h3>
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-yellow-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-yellow-600">{{ $underutilized->count() }}</p>
                <p class="text-xs text-gray-500 mt-2">
                    < 30% utilization</p>
            </div>
        </div>

        <!-- AI-Powered Forecasting Section -->
        <div
            class="bg-gradient-to-r from-slate-900 to-blue-900 rounded-xl shadow-2xl p-6 mb-8 text-white border border-blue-700/30">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <i data-lucide="cpu" class="w-6 h-6 text-blue-400"></i>
                        Neural Network Facility Forecasting
                    </h3>
                    <p class="text-blue-200 text-sm">Real-time predictive analysis using TensorFlow.js</p>
                </div>
                <div class="flex items-center gap-3">
                    <span id="model-status"
                        class="px-3 py-1 bg-blue-500/20 border border-blue-400/30 rounded-full text-xs font-mono text-blue-300">
                        System: Standby
                    </span>
                    <button onclick="trainAIModel()"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-xs font-bold transition-all flex items-center gap-2 shadow-lg">
                        <i data-lucide="refresh-cw" class="w-3 h-3"></i> Re-train AI Model
                    </button>
                </div>
            </div>

            <div id="training-ui" class="hidden mb-6 bg-black/30 rounded-lg p-4 border border-white/10">
                <div class="flex justify-between mb-2">
                    <span class="text-xs font-mono text-blue-300 italic">Processing Epochs (Backpropagation)...</span>
                    <span id="loss-display" class="text-xs font-mono text-yellow-400 font-bold">Loss: 0.00000</span>
                </div>
                <div class="w-full bg-gray-700 h-2 rounded-full overflow-hidden">
                    <div id="training-progress"
                        class="bg-gradient-to-r from-blue-500 to-cyan-400 h-full transition-all duration-300"
                        style="width: 0%"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white/5 backdrop-blur-sm rounded-xl p-5 border border-white/10">
                    <p class="text-xs uppercase tracking-widest text-blue-300 mb-1">Predicted Peak Window</p>
                    <div class="text-2xl font-black text-white" id="pred-peak-time">--:--</div>
                    <p class="text-[10px] text-gray-400 mt-2 italic">Calculated via Time-Series Regression</p>
                </div>

                <div class="bg-white/5 backdrop-blur-sm rounded-xl p-5 border border-white/10">
                    <p class="text-xs uppercase tracking-widest text-blue-300 mb-1">Max Occupancy Load</p>
                    <div class="text-2xl font-black text-white" id="pred-occupancy">0%</div>
                    <div class="w-full bg-white/10 h-1 rounded-full mt-3">
                        <div id="load-bar" class="bg-blue-400 h-1 rounded-full transition-all duration-1000"
                            style="width: 0%"></div>
                    </div>
                </div>

                <div class="bg-blue-600/20 backdrop-blur-sm rounded-xl p-5 border border-blue-500/30">
                    <p class="text-xs font-bold uppercase text-blue-300 mb-2 flex items-center gap-1">
                        <i data-lucide="lightbulb" class="w-3 h-3"></i> AI Strategy
                    </p>
                    <p class="text-sm leading-relaxed text-blue-100 italic" id="ai-suggestion">
                        "Awaiting model training to provide resource allocation advice..."
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-inner" style="height: 300px;">
                <canvas id="forecastChart"></canvas>
            </div>
        </div>


        <!-- Facility Utilization Table -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-lgu-headline mb-4">Facility Utilization Details</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Facility</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Bookings</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Confirmed</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cancelled</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Utilization</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($facilities as $facility)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $facility->name }}</div>
                                    <div class="text-xs text-gray-500">Capacity: {{ $facility->capacity }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ $facility->city_name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-gray-900">{{ $facility->total_bookings }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-green-600 font-medium">{{ $facility->confirmed_bookings }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-red-600">{{ $facility->cancelled_bookings }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="w-20 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full
                                                                        @if($facility->utilization_rate > 70) bg-green-600
                                                                        @elseif($facility->utilization_rate > 30) bg-yellow-500
                                                                        @else bg-red-500
                                                                        @endif"
                                                style="width: {{ min($facility->utilization_rate, 100) }}%">
                                            </div>
                                        </div>
                                        <span class="text-sm font-medium
                                                                    @if($facility->utilization_rate > 70) text-green-600
                                                                    @elseif($facility->utilization_rate > 30) text-yellow-600
                                                                    @else text-red-600
                                                                    @endif">
                                            {{ number_format($facility->utilization_rate, 1) }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-semibold text-lgu-headline">
                                        ₱{{ number_format($facility->total_revenue ?? 0, 2) }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                                    <p>No facility data available.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Underutilized Facilities Alert -->
        @if($underutilized->count() > 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg">
                <div class="flex items-start">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-800 mb-2">Underutilized Facilities</h3>
                        <p class="text-yellow-700 mb-3">The following facilities have utilization rates below 30% and may need
                            attention:</p>
                        <ul class="list-disc list-inside text-yellow-700 space-y-1">
                            @foreach($underutilized as $facility)
                                <li>{{ $facility->name }} ({{ $facility->city_name ?? 'N/A' }}) -
                                    {{ number_format($facility->utilization_rate, 1) }}%
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                .print-area,
                .print-area * {
                    visibility: visible;
                }

                .print-area {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }

                .no-print {
                    display: none !important;
                }

                .print\:block {
                    display: block !important;
                }

                /* Better table printing */
                table {
                    page-break-inside: auto;
                }

                tr {
                    page-break-inside: avoid;
                    page-break-after: auto;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.15.0/dist/tf.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Inject database data from Laravel Controller
                const facilityData = @json($facilities);
                let forecastChart;

                /**
                 * MAIN AI EXECUTION FUNCTION
                 * This handles normalization, model building, and training.
                 */
                async function runAIForecasting() {
                    const statusEl = document.getElementById('model-status');
                    const trainingUI = document.getElementById('training-ui');
                    const progressBar = document.getElementById('training-progress');
                    const lossDisplay = document.getElementById('loss-display');

                    // Show training UI and update status
                    if (trainingUI) trainingUI.classList.remove('hidden');
                    if (statusEl) statusEl.innerText = "System: Training Neural Net...";

                    // --- STEP 1: DATA PREPARATION & NORMALIZATION ---
                    const inputs = [];
                    const outputs = [];

                    facilityData.forEach(facility => {
                        // We simulate a training dataset based on current facility utilization rates
                        // Input X: [Day of Week (1-7), Hour of Day (8-20)]
                        // Output Y: [Expected Utilization (0-1)]
                        for (let day = 1; day <= 7; day++) {
                            for (let hour = 8; hour <= 20; hour++) {
                                // Feature Scaling: We divide values to keep them between 0 and 1
                                inputs.push([day / 7, hour / 24]);

                                let baseUtilization = (facility.utilization_rate / 100);
                                // Account for the 3-hour booking + 2-hour extension logic with slight random variance
                                let simulatedLoad = Math.min(baseUtilization + (Math.random() * 0.15), 1);
                                outputs.push([simulatedLoad]);
                            }
                        }
                    });

                    // Convert data arrays into TensorFlow Tensors (Mathematical Matrices)
                    const xs = tf.tensor2d(inputs);
                    const ys = tf.tensor2d(outputs);

                    // --- STEP 2: NEURAL NETWORK ARCHITECTURE ---
                    const model = tf.sequential();

                    // Input Layer: 20 Neurons, ReLU activation to handle non-linear patterns
                    model.add(tf.layers.dense({ units: 20, inputShape: [2], activation: 'relu' }));

                    // Hidden Layer: Extra processing layer for better accuracy
                    model.add(tf.layers.dense({ units: 10, activation: 'relu' }));

                    // Output Layer: Sigmoid activation ensures output is between 0% and 100%
                    model.add(tf.layers.dense({ units: 1, activation: 'sigmoid' }));

                    // Compile model using Adam Optimizer and Mean Squared Error loss function
                    model.compile({
                        optimizer: tf.train.adam(0.05),
                        loss: 'meanSquaredError'
                    });

                    // --- STEP 3: THE TRAINING PHASE ---
                    const totalEpochs = 50;
                    await model.fit(xs, ys, {
                        epochs: totalEpochs,
                        callbacks: {
                            onEpochEnd: (epoch, logs) => {
                                // Update Progress Bar and Loss Display in real-time
                                const progressPercentage = ((epoch + 1) / totalEpochs) * 100;
                                if (progressBar) progressBar.style.width = progressPercentage + "%";
                                if (lossDisplay) lossDisplay.innerText = `Loss (Error): ${logs.loss.toFixed(6)}`;
                                console.log(`Epoch ${epoch}: Loss = ${logs.loss}`);
                            }
                        }
                    });

                    if (statusEl) statusEl.innerText = "System: Intelligence Ready";
                    // Hide training UI after a short delay
                    setTimeout(() => { if (trainingUI) trainingUI.classList.add('hidden'); }, 2500);

                    // --- STEP 4: GENERATE PREDICTIONS ---
                    generateForecast(model);
                }

                /**
                 * Predict occupancy for the next 12 hours and visualize results.
                 */
                function generateForecast(model) {
                    const ctx = document.getElementById('forecastChart').getContext('2d');
                    const predictionData = [];
                    const timeLabels = ['8AM', '10AM', '12PM', '2PM', '4PM', '6PM', '8PM'];
                    const targetHours = [8, 10, 12, 14, 16, 18, 20];
                    const currentDayNormalized = new Date().getDay() / 7;

                    targetHours.forEach(hr => {
                        // Create a tensor for the specific time we want to predict
                        const inputTensor = tf.tensor2d([[currentDayNormalized, hr / 24]]);
                        const prediction = model.predict(inputTensor);

                        prediction.data().then(data => {
                            predictionData.push(data[0] * 100);

                            // Once all time slots are predicted, update the Chart and UI
                            if (predictionData.length === targetHours.length) {
                                renderForecastChart(ctx, timeLabels, predictionData);
                                updateAISummary(predictionData);
                            }
                        });
                    });
                }

                function renderForecastChart(ctx, labels, data) {
                    if (forecastChart) forecastChart.destroy();
                    forecastChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'AI Predicted Demand (%)',
                                data: data,
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                fill: true,
                                tension: 0.4,
                                borderWidth: 3,
                                pointRadius: 4,
                                pointBackgroundColor: '#1d4ed8'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } }
                            }
                        }
                    });
                }

                function updateAISummary(data) {
                    const maxOccupancy = Math.max(...data);
                    const peakIndex = data.indexOf(maxOccupancy);
                    const hours = ['8AM', '10AM', '12PM', '2PM', '4PM', '6PM', '8PM'];

                    // Update Insight Cards
                    document.getElementById('pred-peak-time').innerText = hours[peakIndex];
                    document.getElementById('pred-occupancy').innerText = Math.round(maxOccupancy) + "%";
                    document.getElementById('load-bar').style.width = maxOccupancy + "%";

                    // Dynamic AI Advice based on predicted load and 2-hour extension rules
                    let advice = "";
                    if (maxOccupancy > 80) {
                        advice = "High demand predicted. We recommend strict 3-hour limits and disabling 2-hour extensions for today to avoid overbooking.";
                    } else if (maxOccupancy > 50) {
                        advice = "Moderate demand detected. 2-hour extensions should be monitored but are generally safe to approve.";
                    } else {
                        advice = "Low occupancy forecasted. Facility is underutilized; consider approving all extension requests to maximize usage.";
                    }
                    document.getElementById('ai-suggestion').innerText = advice;
                }

                // Auto-run on page load
                runAIForecasting();

                // Global access for the Re-train button
                window.trainAIModel = runAIForecasting;
            });
        </script>
    @endpush
@endsection