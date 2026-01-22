

<?php $__env->startSection('title', 'Facility Utilization - Admin'); ?>

<?php $__env->startSection('page-content'); ?>
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
                <form method="GET" action="<?php echo e(route('admin.analytics.facility-utilization')); ?>" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="<?php echo e($startDate); ?>" 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-headline focus:border-lgu-headline">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" value="<?php echo e($endDate); ?>" 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-headline focus:border-lgu-headline">
                    </div>
                    <button type="submit" class="px-6 py-2 bg-lgu-button text-lgu-button-text rounded-lg hover:opacity-90 transition-all font-semibold">
                        <i data-lucide="filter" class="w-4 h-4 inline mr-1"></i>
                        Filter
                    </button>
                </form>
                <button onclick="window.print()" class="px-6 py-2 border-2 border-lgu-stroke text-lgu-headline rounded-lg hover:bg-lgu-bg transition-all font-semibold">
                    <i data-lucide="printer" class="w-4 h-4 inline mr-1"></i>
                    Print
                </button>
                
                <!-- Export Dropdown -->
                <div class="relative inline-block" x-data="{ open: false }">
                    <button @click="open = !open" class="px-6 py-2 bg-lgu-secondary text-white rounded-lg hover:opacity-90 transition-all font-semibold flex items-center">
                        <i data-lucide="download" class="w-4 h-4 mr-1"></i>
                        Export
                        <i data-lucide="chevron-down" class="w-4 h-4 ml-1"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                        <a href="<?php echo e(route('admin.analytics.export-facility-utilization-excel', ['start_date' => $startDate, 'end_date' => $endDate])); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-lgu-bg">
                            <i data-lucide="file-spreadsheet" class="w-4 h-4 inline mr-2"></i>
                            Export as Excel
                        </a>
                        <a href="<?php echo e(route('admin.analytics.facility-utilization.export', ['start_date' => $startDate, 'end_date' => $endDate])); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-lgu-bg">
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
            <p class="text-gray-600">Period: <?php echo e(\Carbon\Carbon::parse($startDate)->format('M d, Y')); ?> - <?php echo e(\Carbon\Carbon::parse($endDate)->format('M d, Y')); ?></p>
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
            <p class="text-3xl font-bold text-lgu-headline"><?php echo e($facilities->count()); ?></p>
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
            <p class="text-3xl font-bold text-green-600"><?php echo e($highPerforming->count()); ?></p>
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
            <p class="text-3xl font-bold text-yellow-600"><?php echo e($underutilized->count()); ?></p>
            <p class="text-xs text-gray-500 mt-2">< 30% utilization</p>
        </div>
    </div>

    <!-- AI-Powered Forecasting Section -->
    <div class="relative overflow-hidden bg-white rounded-3xl shadow-2xl border border-indigo-100 mb-8 no-print">
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-purple-500/5 rounded-full blur-3xl"></div>

    <div class="relative p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-600 rounded-2xl shadow-xl shadow-indigo-200">
                    <i data-lucide="brain-circuit" class="w-8 h-8 text-white"></i>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tight">Neural Intelligence Forecast</h3>
                    <p class="text-gray-500 text-sm">Deep learning analysis: Seasonality, User Profiling, & External Events</p>
                </div>
            </div>
            
            <div class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="text-xs font-bold text-indigo-700 uppercase tracking-widest">TF.JS Core Active</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-gray-900 rounded-3xl p-6 shadow-2xl border border-gray-800">
                <div class="flex items-center justify-between mb-6 text-white/50">
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em]">Hourly Demand Probability</span>
                    <i data-lucide="activity" class="w-4 h-4"></i>
                </div>
                <div style="height: 300px;">
                    <canvas id="aiForecastChart"></canvas>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <div class="p-5 bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl border border-amber-100 shadow-sm transition-transform hover:-translate-y-1">
                    <div class="flex items-center gap-3 mb-3 text-amber-600">
                        <i data-lucide="zap" class="w-5 h-5"></i>
                        <p class="text-[10px] font-bold uppercase tracking-widest">Predicted Peak Slot</p>
                    </div>
                    <h4 id="ai-peak-slot" class="text-2xl font-black text-gray-900 mb-1">Calculating...</h4>
                    <p id="ai-peak-user" class="text-xs font-bold text-amber-700 italic">Analyzing top user profiles...</p>
                </div>

                <div class="p-5 bg-slate-900 rounded-2xl border border-slate-800 shadow-xl transition-transform hover:-translate-y-1">
                    <div class="flex items-center gap-3 mb-3 text-indigo-400">
                        <i data-lucide="calendar-range" class="w-5 h-5"></i>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-300">Seasonality & External Factors</p>
                    </div>
                    <div class="space-y-2">
                        <p id="ai-peak-month" class="text-sm text-white font-bold flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span> Peak Month: --
                        </p>
                        <p id="ai-holiday-effect" class="text-xs text-slate-400 flex items-center gap-2">
                             <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Holiday Impact: Normal
                        </p>
                    </div>
                </div>

                <div class="p-6 bg-indigo-600 rounded-2xl shadow-xl shadow-indigo-200 transition-transform hover:-translate-y-1">
                    <div class="flex items-center gap-3 mb-3 text-indigo-100">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-white">Smart Recommendation</p>
                    </div>
                    <p id="ai-insight" class="text-sm font-bold text-white leading-relaxed italic">Processing complex environment patterns...</p>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Facility Utilization Table -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-lgu-headline mb-4">Facility Utilization Details</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bookings</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Confirmed</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cancelled</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Utilization</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($facility->name); ?></div>
                            <div class="text-xs text-gray-500">Capacity: <?php echo e($facility->capacity); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600"><?php echo e($facility->city_name ?? 'N/A'); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm text-gray-900"><?php echo e($facility->total_bookings); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm text-green-600 font-medium"><?php echo e($facility->confirmed_bookings); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm text-red-600"><?php echo e($facility->cancelled_bookings); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <div class="w-20 bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full
                                        <?php if($facility->utilization_rate > 70): ?> bg-green-600
                                        <?php elseif($facility->utilization_rate > 30): ?> bg-yellow-500
                                        <?php else: ?> bg-red-500
                                        <?php endif; ?>"
                                        style="width: <?php echo e(min($facility->utilization_rate, 100)); ?>%">
                                    </div>
                                </div>
                                <span class="text-sm font-medium
                                    <?php if($facility->utilization_rate > 70): ?> text-green-600
                                    <?php elseif($facility->utilization_rate > 30): ?> text-yellow-600
                                    <?php else: ?> text-red-600
                                    <?php endif; ?>">
                                    <?php echo e(number_format($facility->utilization_rate, 1)); ?>%
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-semibold text-lgu-headline">₱<?php echo e(number_format($facility->total_revenue ?? 0, 2)); ?></div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                            <p>No facility data available.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Underutilized Facilities Alert -->
    <?php if($underutilized->count() > 0): ?>
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg">
        <div class="flex items-start">
            <i data-lucide="alert-triangle" class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0 mt-0.5"></i>
            <div>
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">Underutilized Facilities</h3>
                <p class="text-yellow-700 mb-3">The following facilities have utilization rates below 30% and may need attention:</p>
                <ul class="list-disc list-inside text-yellow-700 space-y-1">
                    <?php $__currentLoopData = $underutilized; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($facility->name); ?> (<?php echo e($facility->city_name ?? 'N/A'); ?>) - <?php echo e(number_format($facility->utilization_rate, 1)); ?>%</li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('styles'); ?>
<style>
@media print {
    body * {
        visibility: hidden;
    }
    .print-area, .print-area * {
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>

<script>
document.addEventListener('DOMContentLoaded', async function() {

    const dbData = <?php echo json_encode($aiTrainingData, 15, 512) ?>;
    const mayorRule = <?php echo json_encode($mayorConflict, 15, 512) ?>;

    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    console.log("🚀 AI Engine: Initializing data from database...");

    // 1. DYNAMIC DATA ANALYSIS

    function analyzeHistoricalData() {

        if (!dbData || dbData.length === 0) {

            console.warn("⚠️ AI Engine: No historical data found for analysis.");
            return null;
        }

        const userFreq = {};
        const hourDensity = Array(24).fill(0);
        const dayDensity = Array(7).fill(0);
        const monthDensity = Array(12).fill(0);

        dbData.forEach(item => {

            // Use full_name from the database schema

            userFreq[item.user_name] = (userFreq[item.user_name] || 0) + 1;
            hourDensity[item.hour_index]++;
            dayDensity[item.day_index - 1]++;
            monthDensity[item.month_index - 1]++;

        });

        const topUser = Object.entries(userFreq).sort((a,b) => b[1] - a[1])[0];
        const peakHour = hourDensity.indexOf(Math.max(...hourDensity));
        const peakDayIdx = dayDensity.indexOf(Math.max(...dayDensity));
        const peakMonthIdx = monthDensity.indexOf(Math.max(...monthDensity));

        document.getElementById('ai-peak-slot').innerText = `${days[peakDayIdx]} at ${peakHour}:00`;

        document.getElementById('ai-peak-user').innerText = `Frequent User: ${topUser ? topUser[0] : 'General Citizens'}`;

       

        // --- PHILIPPINES 2026 HOLIDAY LOGIC ---

        const now = new Date();
        const curMonth = now.getMonth() + 1;
        const curDate = now.getDate();      

        let holidayName = "Normal Operations";

        let dotColor = "bg-emerald-500";

        if (curMonth === 1) {

            if (curDate === 1) { holidayName = "New Year's Day"; dotColor = "bg-red-500"; }
            else { holidayName = "Post-Holiday Normalization"; dotColor = "bg-emerald-500"; }

        }
        else if (curMonth === 2) { holidayName = "Chinese New Year / EDSA Anniversary"; dotColor = "bg-yellow-500"; }
        else if (curMonth === 4) { holidayName = "Holy Week (Maundy Thu/Good Fri)"; dotColor = "bg-orange-600"; }
        else if (curMonth === 12) { holidayName = "Christmas Season"; dotColor = "bg-red-600"; }

        const holidayElement = document.getElementById('ai-holiday-effect');

        if (holidayElement) {
            holidayElement.innerHTML = `
                <span class="w-1.5 h-1.5 ${dotColor} rounded-full animate-pulse"></span>
                Holiday Impact: <b>${holidayName}</b>
            `;
        }
        console.log(`📊 Analysis: Top user ${topUser ? topUser[0] : 'N/A'}. Historical peak: ${months[peakMonthIdx]}.`);
        return { hourDensity, peakHour, peakDayIdx, peakMonthIdx };
    }
    const liveStats = analyzeHistoricalData();

    // 2. AI MODEL TRAINING

    async function trainModel() {

        console.log("🧠 TensorFlow: Building Neural Network...");
        const model = tf.sequential();

        model.add(tf.layers.dense({units: 32, activation: 'relu', inputShape: [3]}));
        model.add(tf.layers.dense({units: 1, activation: 'sigmoid'}));
        model.compile({optimizer: tf.train.adam(0.01), loss: 'meanSquaredError'});



        if(dbData.length > 0) {
            console.log(`👟 TensorFlow: Training with ${dbData.length} database records...`);

            const inputs = dbData.map(d => [d.day_index, d.hour_index, d.month_index]);
            const labels = dbData.map(() => [1]);

            for(let i = 0; i < 20; i++) {
                inputs.push([Math.floor(Math.random()*7)+1, Math.floor(Math.random()*24), Math.floor(Math.random()*12)+1]);
                labels.push([0]);
            }
            await model.fit(tf.tensor2d(inputs), tf.tensor2d(labels), {

                epochs: 30,
                verbose: 0,

                callbacks: {
                    onEpochEnd: (epoch, logs) => {
                        if (epoch % 10 === 0) console.log(`📉 Epoch ${epoch}: Loss = ${logs.loss.toFixed(4)}`);
                    }
                }

            });
            console.log("✅ AI Engine: Training Complete.");

        }
        return model;
    }

    const model = await trainModel();

    // 3. UPDATED CONDITIONAL RECOMMENDATION

    window.updateRecommendation = async () => {

        const now = new Date();
        const currentMonth = now.getMonth() + 1;
        const currentDayOfWeek = now.getDay() + 1; // 1 (Sun) to 7 (Sat) to match DB index

        // CHECK: Is there a Mayor event scheduled for TODAY?

        const isMayorEventToday = (currentDayOfWeek === mayorRule.day_index);
        const insightBox = document.getElementById('ai-insight');
       
        if (insightBox) {

            if (isMayorEventToday) {

                // CONFLICT LOGIC

                const altHour = (mayorRule.hour_start + 3) % 24;
                const prediction = model.predict(tf.tensor2d([[mayorRule.day_index, altHour, currentMonth]]));
                const prob = (await prediction.data())[0];
                const topUser = document.getElementById('ai-peak-user').innerText.replace('Frequent User: ', '');

                insightBox.innerHTML = `

                    <div class="space-y-2">
                        <p class="font-bold text-yellow-400">🚨 Conflict Detected (Mayor's Office)</p>
                        <p class="text-white text-sm">
                            A priority city event is scheduled for today.
                            AI suggests shifting <b>${topUser}'s</b> booking to <b>${altHour}:00</b>.
                        </p>
                        <div class="mt-2 p-2 bg-white/10 rounded border border-indigo-500/30">
                            <span class="text-indigo-200 font-semibold">✨ AI Predicted Demand: ${(prob * 100).toFixed(1)}%</span>
                        </div>
                    </div>
                `;
            } else {
                // AVAILABILITY LOGIC
                insightBox.innerHTML = `
                    <div class="space-y-2">
                        <p class="font-bold text-emerald-400">✅ Facility Available</p>
                        <p class="text-white text-sm">
                            No City Events detected for this schedule.
                            Users may proceed with bookings for today.
                        </p>
                        <div class="mt-2 p-2 bg-emerald-500/10 rounded border border-emerald-500/30">
                            <span class="text-emerald-200 font-semibold">✨ AI Status: Optimal Availability</span>
                        </div>
                    </div>
                `;
            }
        }
    };
    if(liveStats) {
        renderForecastChart(liveStats.hourDensity);
        updateRecommendation();
    }

});
function renderForecastChart(data) {
    const ctx = document.getElementById('aiForecastChart').getContext('2d');
    
    // Create a much brighter gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(34, 211, 238, 0.4)');   // Cyan top
    gradient.addColorStop(0.5, 'rgba(139, 92, 246, 0.1)'); // Violet middle
    gradient.addColorStop(1, 'rgba(15, 23, 42, 0)');      // Dark bottom

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: Array.from({length: 24}, (_, i) => `${i}:00`),
            datasets: [{
                label: 'Facility Activity',
                data: data,
                borderColor: '#22d3ee',         // Bright Electric Cyan
                borderWidth: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#22d3ee',
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#22d3ee',
                backgroundColor: gradient,
                fill: true,
                tension: 0.45,                  // Curvy and modern
                borderCapStyle: 'round',
                shadowColor: 'rgba(34, 211, 238, 0.5)',
                shadowBlur: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 2500,                 // Slower, more elegant entry
                easing: 'easeInOutQuart'
            },
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#fff',     // White tooltips for contrast
                    titleColor: '#0f172a',
                    bodyColor: '#0f172a',
                    padding: 12,
                    displayColors: false,
                    cornerRadius: 8
                }
            },
            scales: { 
                x: { 
                    grid: { display: false },
                    ticks: { color: '#cbd5e1', font: { weight: 'bold' } } 
                }, 
                y: { display: false, beginAtZero: true } 
            }
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/admin/analytics/facility-utilization.blade.php ENDPATH**/ ?>