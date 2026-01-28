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
        <div class="flex items-center gap-3">
            <form method="POST" action="{{ URL::signedRoute('admin.community-maintenance.refresh') }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-gr-md py-gr-sm bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <i data-lucide="refresh-cw" class="w-5 h-5 mr-gr-xs"></i>
                    Sync Statuses
                </button>
            </form>
            <a href="{{ URL::signedRoute('admin.community-maintenance.create') }}" class="inline-flex items-center px-gr-lg py-gr-md bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                <i data-lucide="plus" class="w-5 h-5 mr-gr-xs"></i>
                New Request
            </a>
        </div>
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

    @if(session('info'))
    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-start gap-3">
        <i data-lucide="info" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
        <p class="text-blue-800 font-medium">{{ session('info') }}</p>
    </div>
    @endif

    {{-- Stats --}}
    @php
        $submittedCount = 0;
        $inProgressCount = 0;
        $resolvedCount = 0;
        
        if ($requests instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            // Get counts from all records, not just current page
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
            } catch (\Exception $e) {
                // Keep defaults
            }
        }
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-gr-md">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-caption text-gray-600 uppercase mb-1">Pending</div>
                    <div class="text-h1 font-bold text-blue-600">{{ $submittedCount }}</div>
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
                    <div class="text-h1 font-bold text-amber-600">{{ $inProgressCount }}</div>
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
                    <div class="text-h1 font-bold text-green-600">{{ $resolvedCount }}</div>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-7 h-7 text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Reports List --}}
    @if($requests instanceof \Illuminate\Pagination\LengthAwarePaginator && $requests->count() > 0)
        <div class="space-y-gr-md">
            @foreach($requests as $request)
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
                    $reportTypeLabels = [
                        'maintenance' => 'Maintenance',
                        'complaint' => 'Complaint',
                        'suggestion' => 'Suggestion',
                        'emergency' => 'Emergency',
                    ];
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-gr-sm mb-gr-sm flex-wrap">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $priorityColors[$request->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($request->priority) }} Priority
                                </span>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                    {{ $reportTypeLabels[$request->report_type] ?? $request->report_type }}
                                </span>
                                @if($request->external_report_id)
                                <span class="text-xs text-gray-500">
                                    Report #{{ $request->external_report_id }}
                                </span>
                                @endif
                            </div>

                            <h3 class="text-h3 font-bold text-lgu-headline mb-gr-xs">{{ $request->subject }}</h3>
                            
                            <div class="flex items-center gap-4 text-small text-gray-600 mb-gr-sm">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="building-2" class="w-4 h-4"></i>
                                    {{ $request->facility_name }}
                                </span>
                                @if($request->unit_number)
                                <span class="flex items-center gap-1">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                    {{ $request->unit_number }}
                                </span>
                                @endif
                                <span class="flex items-center gap-1">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                    {{ \Carbon\Carbon::parse($request->created_at)->format('M j, Y g:i A') }}
                                </span>
                            </div>

                            <p class="text-body text-gray-700 line-clamp-2">{{ $request->description }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($requests->hasPages())
            <div class="mt-gr-lg">
                {{ $requests->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i data-lucide="wrench" class="w-16 h-16 text-gray-300 mb-gr-md mx-auto"></i>
            <h3 class="text-h3 font-bold text-lgu-headline mb-gr-xs">No Maintenance Reports</h3>
            <p class="text-body text-gray-600 mb-gr-md">Submit a maintenance request for severe facility damage</p>
            <a href="{{ URL::signedRoute('admin.community-maintenance.create') }}" class="inline-flex items-center px-gr-lg py-gr-md bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                <i data-lucide="plus" class="w-5 h-5 mr-gr-xs"></i>
                New Request
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
@endpush
@endsection
