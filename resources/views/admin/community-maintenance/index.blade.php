@extends('layouts.admin')

@section('page-title', 'My Maintenance Reports')
@section('page-subtitle', 'Track maintenance requests submitted to Community Infrastructure Management')

@section('page-content')
<div class="space-y-gr-lg">
    {{-- Header --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">My Maintenance Reports</h1>
            <p class="text-body text-lgu-paragraph">Track status of maintenance requests sent to Community Infrastructure</p>
        </div>
        <a href="{{ URL::signedRoute('admin.community-maintenance.create') }}" class="inline-flex items-center px-gr-lg py-gr-md bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
            <i data-lucide="plus" class="w-5 h-5 mr-gr-xs"></i>
            New Request
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
        <i data-lucide="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5"></i>
        <p class="text-green-800 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
        <p class="text-red-800 font-medium">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Stats --}}
    @php
        $submittedCount = 0;
        $inProgressCount = 0;
        $resolvedCount = 0;
        
        if ($requests instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            try {
                $submittedCount = \Illuminate\Support\Facades\DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->where('status', 'submitted')
                    ->count();
                $inProgressCount = \Illuminate\Support\Facades\DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->whereIn('status', ['reviewed', 'in_progress'])
                    ->count();
                $resolvedCount = \Illuminate\Support\Facades\DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->whereIn('status', ['resolved', 'closed'])
                    ->count();
            } catch (\Exception $e) {}
        }
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-gr-md">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-caption text-gray-600 uppercase mb-1">Pending</div>
                    <div id="stat-pending" class="text-h1 font-bold text-blue-600">{{ $submittedCount }}</div>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <i data-lucide="clock" class="w-7 h-7 text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-caption text-gray-600 uppercase mb-1">In Progress</div>
                    <div id="stat-in-progress" class="text-h1 font-bold text-amber-600">{{ $inProgressCount }}</div>
                </div>
                <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center">
                    <i data-lucide="wrench" class="w-7 h-7 text-amber-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-caption text-gray-600 uppercase mb-1">Resolved</div>
                    <div id="stat-resolved" class="text-h1 font-bold text-green-600">{{ $resolvedCount }}</div>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-7 h-7 text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Reports Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Report #</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Facility</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Submitted</th>
                    </tr>
                </thead>
                <tbody id="reports-tbody" class="divide-y divide-gray-200">
                    @forelse($requests as $request)
                        @php
                            $statusColors = [
                                'submitted' => 'bg-blue-100 text-blue-800',
                                'reviewed' => 'bg-purple-100 text-purple-800',
                                'in_progress' => 'bg-amber-100 text-amber-800',
                                'resolved' => 'bg-green-100 text-green-800',
                                'closed' => 'bg-gray-100 text-gray-600',
                            ];
                            $priorityColors = [
                                'low' => 'bg-green-100 text-green-800',
                                'medium' => 'bg-yellow-100 text-yellow-800',
                                'high' => 'bg-orange-100 text-orange-800',
                                'urgent' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $request->external_report_id ?? $request->id }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="{{ $request->subject }}">
                                {{ Str::limit($request->subject, 40) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ Str::limit($request->facility_name, 25) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ ucfirst($request->report_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $priorityColors[$request->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($request->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($request->created_at)->format('M j, Y g:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <i data-lucide="wrench" class="w-12 h-12 text-gray-300 mb-3 mx-auto"></i>
                                <p class="text-gray-500">No maintenance reports submitted yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($requests instanceof \Illuminate\Pagination\LengthAwarePaginator && $requests->hasPages())
        <div class="mt-gr-lg">
            {{ $requests->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}

let lastDataHash = '';

function pollMaintenanceReports() {
    fetch('{{ route("admin.community-maintenance.json") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const newHash = JSON.stringify(data.stats);
                if (lastDataHash && newHash !== lastDataHash) {
                    location.reload();
                }
                lastDataHash = newHash;
                
                document.getElementById('stat-pending').textContent = data.stats.pending;
                document.getElementById('stat-in-progress').textContent = data.stats.in_progress;
                document.getElementById('stat-resolved').textContent = data.stats.resolved;
            }
        })
        .catch(err => console.error('Poll error:', err));
}

setInterval(pollMaintenanceReports, 10000);
</script>
@endpush
@endsection
