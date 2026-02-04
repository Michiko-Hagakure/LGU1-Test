@extends('layouts.admin')

@section('page-title', 'Infrastructure Project Requests')
@section('page-subtitle', 'View and track your submitted infrastructure project requests')

@section('page-content')
<div class="max-w-7xl mx-auto">
    {{-- Header Actions --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <p class="text-gray-600">Track the status of your infrastructure project requests submitted to the Infrastructure PM system.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ URL::signedRoute('admin.infrastructure.project-request') }}" class="px-4 py-2 bg-lgu-highlight text-white rounded-lg hover:bg-lgu-stroke transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Project Request
            </a>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <p class="text-green-800">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <p class="text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Project Requests Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($requests instanceof \Illuminate\Pagination\LengthAwarePaginator && $requests->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Budget</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Bid Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($requests as $request)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900">{{ $request->project_title }}</p>
                                <p class="text-sm text-gray-500">{{ $request->requesting_office }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $request->project_category }}</td>
                        <td class="px-6 py-4">
                            @php
                                $priorityColors = [
                                    'low' => 'bg-green-100 text-green-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'high' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $priorityColors[$request->priority_level] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($request->priority_level) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($request->estimated_budget)
                            ₱{{ number_format($request->estimated_budget, 2) }}
                            @else
                            <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $bidStatusColors = [
                                    'open' => 'bg-blue-100 text-blue-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'accepted' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'receipts_submitted' => 'bg-purple-100 text-purple-800',
                                ];
                            @endphp
                            @if($request->bid_status)
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $bidStatusColors[$request->bid_status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucwords(str_replace('_', ' ', $request->bid_status)) }}
                            </span>
                            @else
                            <span class="text-gray-400 text-sm">No bid</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'submitted' => 'bg-blue-100 text-blue-800',
                                    'received' => 'bg-indigo-100 text-indigo-800',
                                    'under_review' => 'bg-purple-100 text-purple-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'in_progress' => 'bg-orange-100 text-orange-800',
                                    'completed' => 'bg-emerald-100 text-emerald-800',
                                ];
                            @endphp
                            <span id="status-badge-{{ $request->id }}" class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}" data-status="{{ $request->status }}">
                                {{ ucwords(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($request->created_at)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ URL::signedRoute('admin.infrastructure.projects.show', $request->id) }}" class="px-3 py-1.5 bg-lgu-highlight text-white text-xs font-medium rounded-lg hover:bg-lgu-stroke transition-colors">
                                    View
                                </a>
                                @if($request->external_project_id)
                                <span class="text-xs font-mono text-gray-500">#{{ $request->external_project_id }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($requests->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $requests->links() }}
        </div>
        @endif

        @else
        {{-- Empty State --}}
        <div class="p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No project requests yet</h3>
            <p class="mt-2 text-gray-500">Get started by submitting your first infrastructure project request.</p>
            <a href="{{ URL::signedRoute('admin.infrastructure.project-request') }}" class="mt-6 inline-flex items-center gap-2 px-4 py-2 bg-lgu-highlight text-white rounded-lg hover:bg-lgu-stroke transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Submit Project Request
            </a>
        </div>
        @endif
    </div>

    {{-- Info Card --}}
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex gap-4">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h4 class="font-medium text-blue-900">About Infrastructure PM Integration</h4>
                <p class="mt-1 text-sm text-blue-700">
                    Project requests submitted here are sent to the Infrastructure Project Management system for review and processing. 
                    Once approved, you'll receive updates on contractor assignment, construction progress, and project completion.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const statusColors = {
        'draft': 'bg-gray-100 text-gray-800',
        'submitted': 'bg-blue-100 text-blue-800',
        'received': 'bg-indigo-100 text-indigo-800',
        'under_review': 'bg-purple-100 text-purple-800',
        'approved': 'bg-green-100 text-green-800',
        'rejected': 'bg-red-100 text-red-800',
        'in_progress': 'bg-orange-100 text-orange-800',
        'completed': 'bg-emerald-100 text-emerald-800',
    };

    function formatStatus(status) {
        return status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    function updateStatusBadge(id, newStatus) {
        const badge = document.getElementById('status-badge-' + id);
        if (!badge) return;
        
        const currentStatus = badge.dataset.status;
        if (currentStatus === newStatus) return;
        
        // Remove old color classes
        Object.values(statusColors).forEach(colorClass => {
            colorClass.split(' ').forEach(cls => badge.classList.remove(cls));
        });
        
        // Add new color classes
        const newColorClass = statusColors[newStatus] || 'bg-gray-100 text-gray-800';
        newColorClass.split(' ').forEach(cls => badge.classList.add(cls));
        
        // Update text and data attribute
        badge.textContent = formatStatus(newStatus);
        badge.dataset.status = newStatus;
        
        // Add a brief highlight animation
        badge.style.transform = 'scale(1.1)';
        badge.style.transition = 'transform 0.3s ease';
        setTimeout(() => {
            badge.style.transform = 'scale(1)';
        }, 300);
    }

    function pollStatuses() {
        fetch('{{ URL::signedRoute("admin.infrastructure.projects.statuses-ajax") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.statuses) {
                    Object.entries(data.statuses).forEach(([id, statusData]) => {
                        updateStatusBadge(id, statusData.status);
                    });
                }
            })
            .catch(error => console.log('Status poll failed:', error));
    }

    // Poll every 30 seconds
    setInterval(pollStatuses, 30000);
    
    // Initial poll after 5 seconds
    setTimeout(pollStatuses, 5000);
</script>
@endpush
@endsection
