@extends('layouts.admin')

@section('page-title', 'City Event Details')
@section('page-subtitle', 'View city event information and affected bookings')

@section('page-content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ URL::signedRoute('admin.city-events.index') }}" class="inline-flex items-center gap-2 text-lgu-paragraph hover:text-lgu-headline transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to City Events</span>
        </a>
    </div>

    <!-- Event Header Card -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-2xl font-bold text-lgu-headline">{{ $cityEvent->event_title }}</h2>
                    @php
                        $statusColors = [
                            'scheduled' => 'bg-blue-100 text-blue-800',
                            'ongoing' => 'bg-green-100 text-green-800',
                            'completed' => 'bg-gray-100 text-gray-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                        $typeColors = [
                            'government' => 'bg-purple-100 text-purple-800',
                            'emergency' => 'bg-red-100 text-red-800',
                            'maintenance' => 'bg-orange-100 text-orange-800',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$cityEvent->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($cityEvent->status) }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $typeColors[$cityEvent->event_type] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($cityEvent->event_type) }}
                    </span>
                </div>
                <p class="text-lgu-paragraph">{{ $cityEvent->event_description ?: 'No description provided.' }}</p>
            </div>
            
            @if($cityEvent->status === 'scheduled')
            <div class="flex gap-2">
                <a href="{{ URL::signedRoute('admin.city-events.edit', $cityEvent) }}" class="btn-secondary flex items-center gap-2">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    <span>Edit</span>
                </a>
                <button type="button" onclick="deleteEvent({{ $cityEvent->id }})" class="btn-danger flex items-center gap-2">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    <span>Delete</span>
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Event Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Facility & Schedule -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-lgu-headline mb-4 flex items-center gap-2">
                <i data-lucide="building-2" class="w-5 h-5 text-lgu-highlight"></i>
                Facility & Schedule
            </h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Facility</p>
                    <p class="font-semibold text-lgu-headline">{{ $cityEvent->facility->name ?? 'Unknown Facility' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Start Date & Time</p>
                        <p class="font-semibold text-lgu-headline">
                            {{ \Carbon\Carbon::parse($cityEvent->start_time)->format('M d, Y') }}
                        </p>
                        <p class="text-lgu-paragraph">
                            {{ \Carbon\Carbon::parse($cityEvent->start_time)->format('g:i A') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">End Date & Time</p>
                        <p class="font-semibold text-lgu-headline">
                            {{ \Carbon\Carbon::parse($cityEvent->end_time)->format('M d, Y') }}
                        </p>
                        <p class="text-lgu-paragraph">
                            {{ \Carbon\Carbon::parse($cityEvent->end_time)->format('g:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-lgu-headline mb-4 flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="w-5 h-5 text-lgu-highlight"></i>
                Conflict Statistics
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-orange-50 rounded-lg p-4 text-center">
                    <p class="text-3xl font-bold text-orange-600">{{ count($affectedBookings) }}</p>
                    <p class="text-sm text-orange-800">Total Affected</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4 text-center">
                    <p class="text-3xl font-bold text-yellow-600">
                        {{ $cityEvent->bookingConflicts->where('status', 'pending')->count() }}
                    </p>
                    <p class="text-sm text-yellow-800">Pending Resolution</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <p class="text-3xl font-bold text-green-600">
                        {{ $cityEvent->bookingConflicts->where('status', 'resolved')->count() }}
                    </p>
                    <p class="text-sm text-green-800">Resolved</p>
                </div>
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <p class="text-3xl font-bold text-blue-600">
                        {{ $cityEvent->bookingConflicts->where('citizen_choice', 'reschedule')->count() }}
                    </p>
                    <p class="text-sm text-blue-800">Rescheduled</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Affected Bookings -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-lgu-headline mb-4 flex items-center gap-2">
            <i data-lucide="users" class="w-5 h-5 text-lgu-highlight"></i>
            Affected Bookings ({{ count($affectedBookings) }})
        </h3>

        @if(count($affectedBookings) > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-lgu-headline">Citizen</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-lgu-headline">Booking Time</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-lgu-headline">Conflict Status</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-lgu-headline">Choice</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-lgu-headline">Response Deadline</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($affectedBookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div>
                                <p class="font-medium text-lgu-headline">{{ $booking->citizen->full_name ?? 'Unknown' }}</p>
                                <p class="text-sm text-gray-500">{{ $booking->citizen->email ?? 'No email' }}</p>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <p class="text-lgu-headline">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - 
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                            </p>
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $conflictStatusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'resolved' => 'bg-green-100 text-green-800',
                                    'expired' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $conflictStatusColors[$booking->conflict->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($booking->conflict->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if($booking->conflict->citizen_choice)
                                <span class="font-medium text-lgu-headline">{{ ucfirst($booking->conflict->citizen_choice) }}</span>
                            @else
                                <span class="text-gray-400">Awaiting response</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($booking->conflict->response_deadline)
                                <p class="text-sm {{ \Carbon\Carbon::parse($booking->conflict->response_deadline)->isPast() ? 'text-red-600' : 'text-lgu-headline' }}">
                                    {{ \Carbon\Carbon::parse($booking->conflict->response_deadline)->format('M d, Y g:i A') }}
                                </p>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8">
            <i data-lucide="calendar-check" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
            <p class="text-gray-500">No bookings are affected by this city event.</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}

function deleteEvent(eventId) {
    Swal.fire({
        title: 'Delete City Event?',
        text: 'This action will archive the city event. Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ url('admin/city-events') }}/${eventId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message,
                        confirmButtonColor: '#faae2b'
                    }).then(() => {
                        window.location.href = '{{ URL::signedRoute('admin.city-events.index') }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonColor: '#faae2b'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to delete city event.',
                    confirmButtonColor: '#faae2b'
                });
            });
        }
    });
}
</script>
@endpush
@endsection
