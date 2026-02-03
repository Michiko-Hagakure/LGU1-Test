@extends('layouts.admin')

@section('page-title', 'Road Assistance Requests')
@section('page-subtitle', 'Manage road assistance requests from Road and Transportation Infrastructure Monitoring')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .swal2-popup {
        font-family: inherit;
    }
    .swal2-html-container {
        text-align: left !important;
    }
    .status-badge {
        font-size: 0.7rem;
        padding: 4px 12px;
        border-radius: 9999px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .status-pending { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
    .status-approved { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .status-rejected { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
</style>
@endpush

@section('page-content')
<div class="space-y-gr-lg">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gr-md">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="inbox" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Total Requests</p>
                    <p id="stat-total" class="text-h2 font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Pending</p>
                    <p id="stat-pending" class="text-h2 font-bold text-amber-600">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Approved</p>
                    <p id="stat-approved" class="text-h2 font-bold text-green-600">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Rejected</p>
                    <p id="stat-rejected" class="text-h2 font-bold text-red-600">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Integration Info --}}
    <div class="p-gr-md bg-orange-50 border border-orange-200 rounded-xl">
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-gr-sm">
                <i data-lucide="traffic-cone" class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-orange-800 font-semibold">Road and Transportation Integration</p>
                    <p class="text-orange-700 text-small mt-1">Send road assistance requests for events that may cause traffic congestion.</p>
                </div>
            </div>
            <button onclick="openSendRequestModal()" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors flex items-center gap-2">
                <i data-lucide="send" class="w-4 h-4"></i>
                Send Request
            </button>
        </div>
    </div>

    {{-- Upcoming Bookings That May Need Road Assistance --}}
    @if(isset($upcomingBookings) && $upcomingBookings->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-gr-md py-gr-sm">
            <h3 class="text-white font-semibold flex items-center gap-2">
                <i data-lucide="calendar-check" class="w-5 h-5"></i>
                Upcoming Confirmed Bookings (May Need Road Assistance)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Booking</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Facility</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Date & Time</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Expected Attendees</th>
                        <th class="px-4 py-3 text-center text-caption font-bold text-gray-600 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($upcomingBookings as $booking)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-900">BK{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-caption text-gray-500">{{ $booking->applicant_name ?? 'N/A' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-900">{{ $booking->facility_name }}</p>
                            <p class="text-caption text-gray-500">{{ Str::limit($booking->facility_address, 40) }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}</p>
                            <p class="text-caption text-gray-500">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                            </p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-700">{{ $booking->expected_attendees ?? 'N/A' }}</p>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button onclick="requestForBooking({{ json_encode($booking) }})" 
                                    class="px-3 py-1.5 bg-blue-600 text-white text-small rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-1 mx-auto">
                                <i data-lucide="truck" class="w-4 h-4"></i> Request Assistance
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Outgoing Requests Sent to Road & Transportation --}}
    @if(isset($outgoingRequests) && count($outgoingRequests) > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-gr-md py-gr-sm">
            <h3 class="text-white font-semibold flex items-center gap-2">
                <i data-lucide="send" class="w-5 h-5"></i>
                Outgoing Requests (Sent to Road & Transportation)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">External ID</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Location</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Date & Time</th>
                        <th class="px-4 py-3 text-center text-caption font-bold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Sent At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($outgoingRequests as $outgoing)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-900">#{{ $outgoing->external_request_id ?? 'Pending' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-700">{{ $outgoing->event_type }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-700">{{ Str::limit($outgoing->location, 30) }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($outgoing->start_datetime)->format('M d, Y') }}</p>
                            <p class="text-caption text-gray-500">
                                {{ \Carbon\Carbon::parse($outgoing->start_datetime)->format('g:i A') }} - {{ \Carbon\Carbon::parse($outgoing->end_datetime)->format('g:i A') }}
                            </p>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $outStatusClass = match(strtolower($outgoing->status)) {
                                    'approved' => 'status-approved',
                                    'rejected' => 'status-rejected',
                                    'pending_sync' => 'bg-orange-100 text-orange-800 border border-orange-300',
                                    default => 'status-pending'
                                };
                                $statusLabel = $outgoing->status === 'pending_sync' ? 'Pending Sync' : ucfirst($outgoing->status);
                            @endphp
                            <span class="status-badge {{ $outStatusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-500 text-small">{{ \Carbon\Carbon::parse($outgoing->created_at)->format('M d, Y g:i A') }}</p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Incoming Requests Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-gr-md py-gr-sm">
            <h3 class="text-white font-semibold flex items-center gap-2">
                <i data-lucide="car" class="w-5 h-5"></i>
                Road Assistance Requests
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Requester</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Event</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Location</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Date & Time</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Assistance Type</th>
                        <th class="px-4 py-3 text-center text-caption font-bold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-caption font-bold text-gray-600 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($requests as $request)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                    <i data-lucide="user" class="w-5 h-5 text-orange-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $request->requester_name }}</p>
                                    <p class="text-caption text-gray-500">{{ $request->contact_phone ?? 'No phone' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-900">{{ $request->event_name }}</p>
                            <p class="text-caption text-gray-500">{{ Str::limit($request->event_description, 40) }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-700">{{ Str::limit($request->event_location, 30) }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($request->event_date)->format('M d, Y') }}</p>
                            @if($request->event_start_time)
                            <p class="text-caption text-gray-500">
                                {{ \Carbon\Carbon::parse($request->event_start_time)->format('g:i A') }}
                                @if($request->event_end_time)
                                - {{ \Carbon\Carbon::parse($request->event_end_time)->format('g:i A') }}
                                @endif
                            </p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-700">{{ $request->assistance_type ?? 'Not specified' }}</p>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $statusClass = match(strtolower($request->status)) {
                                    'approved' => 'status-approved',
                                    'rejected' => 'status-rejected',
                                    default => 'status-pending'
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $request->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($request->status === 'pending')
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="showApprovalModal({{ $request->id }}, {{ json_encode($request) }})"
                                        class="px-3 py-1.5 bg-green-600 text-white text-small rounded-lg hover:bg-green-700 transition-colors flex items-center gap-1">
                                    <i data-lucide="check" class="w-4 h-4"></i> Approve
                                </button>
                                <button onclick="showRejectionModal({{ $request->id }}, {{ json_encode($request) }})"
                                        class="px-3 py-1.5 bg-red-600 text-white text-small rounded-lg hover:bg-red-700 transition-colors flex items-center gap-1">
                                    <i data-lucide="x" class="w-4 h-4"></i> Reject
                                </button>
                            </div>
                            @else
                            <span class="text-caption text-gray-500 flex items-center justify-center gap-1">
                                <i data-lucide="check-circle-2" class="w-4 h-4"></i> Processed
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <i data-lucide="inbox" class="w-12 h-12"></i>
                                <p class="font-medium">No road assistance requests yet</p>
                                <p class="text-small">Requests from the Road and Transportation system will appear here</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Hidden Form for Status Updates --}}
<form id="statusForm" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="status" id="formStatus">
    <input type="hidden" name="feedback" id="formFeedback">
    <input type="hidden" name="assigned_personnel" id="formAssignedPersonnel">
    <input type="hidden" name="assigned_equipment" id="formAssignedEquipment">
    <input type="hidden" name="traffic_plan" id="formTrafficPlan">
    <input type="hidden" name="deployment_date" id="formDeploymentDate">
    <input type="hidden" name="deployment_start_time" id="formDeploymentStartTime">
    <input type="hidden" name="deployment_end_time" id="formDeploymentEndTime">
    <input type="hidden" name="admin_notes" id="formAdminNotes">
</form>

{{-- Send Request Modal --}}
<div id="sendRequestModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeSendRequestModal()"></div>
        
        <div class="relative inline-block w-full max-w-2xl p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Send Road Assistance Request</h3>
                <button type="button" onclick="closeSendRequestModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form action="{{ route('admin.road-assistance.send') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="booking_id" id="send_booking_id">
                
                <div>
                    <label for="send_event_type" class="block text-sm font-medium text-gray-700 mb-1">Type of Assistance <span class="text-red-500">*</span></label>
                    <select name="event_type" id="send_event_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">Select type...</option>
                        @foreach($assistanceTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="send_start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="send_start_date" required min="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="send_start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time <span class="text-red-500">*</span></label>
                        <input type="time" name="start_time" id="send_start_time" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="send_end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" id="send_end_date" required min="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="send_end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time <span class="text-red-500">*</span></label>
                        <input type="time" name="end_time" id="send_end_time" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label for="send_location" class="block text-sm font-medium text-gray-700 mb-1">Location / Address <span class="text-red-500">*</span></label>
                    <input type="text" name="location" id="send_location" required maxlength="500" placeholder="Enter the street or area requiring assistance" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>

                <div>
                    <label for="send_landmark" class="block text-sm font-medium text-gray-700 mb-1">Nearby Landmark</label>
                    <input type="text" name="landmark" id="send_landmark" maxlength="255" placeholder="e.g., Near City Hall" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>

                <div>
                    <label for="send_description" class="block text-sm font-medium text-gray-700 mb-1">Description / Details <span class="text-red-500">*</span></label>
                    <textarea name="description" id="send_description" required rows="4" maxlength="2000" placeholder="Describe the event, expected traffic impact, and assistance needed..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeSendRequestModal()" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors flex items-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Send to Road & Transportation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const requestsData = @json($requests->keyBy('id'));
    const assistanceTypesData = @json($assistanceTypes);

    // Equipment options for traffic management
    const equipmentOptions = [
        { id: 'traffic_cones', name: 'Traffic Cones' },
        { id: 'barricades', name: 'Road Barricades' },
        { id: 'signage', name: 'Traffic Signage' },
        { id: 'vests', name: 'Reflective Vests' },
        { id: 'flashlights', name: 'Traffic Flashlights' },
        { id: 'radios', name: 'Two-way Radios' },
    ];

    function showApprovalModal(id, request) {
        Swal.fire({
            title: '<span class="text-green-600">Approve Road Assistance</span>',
            html: `
                <div class="text-left space-y-4">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-500">Event</p>
                        <p class="font-semibold text-gray-800">${request.event_name}</p>
                        <p class="text-sm text-gray-500 mt-2">Location</p>
                        <p class="font-medium text-gray-700">${request.event_location}</p>
                        <p class="text-sm text-gray-500 mt-2">Date</p>
                        <p class="font-medium text-gray-700">${new Date(request.event_date).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assigned Personnel</label>
                        <input type="text" id="swal_personnel" placeholder="e.g., 4 Traffic Enforcers"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assigned Equipment</label>
                        <div class="max-h-32 overflow-y-auto border border-gray-300 rounded-lg p-2 bg-white">
                            ${equipmentOptions.map(e => `
                                <label class="flex items-center gap-2 py-1 cursor-pointer hover:bg-gray-50 px-1 rounded">
                                    <input type="checkbox" class="swal_equipment_checkbox rounded border-gray-300 text-green-600 focus:ring-green-500" value="${e.name}">
                                    <span class="text-sm text-gray-700">${e.name}</span>
                                </label>
                            `).join('')}
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Deployment Date</label>
                            <input type="date" id="swal_deploy_date" value="${request.event_date}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Start Time</label>
                            <input type="time" id="swal_start_time" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">End Time</label>
                            <input type="time" id="swal_end_time" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Traffic Management Plan</label>
                        <textarea id="swal_traffic_plan" rows="2" placeholder="Describe the traffic management approach..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Admin Notes / Feedback</label>
                        <textarea id="swal_notes" rows="2" placeholder="Additional notes for the requester..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"></textarea>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="lucide lucide-check"></i> Approve Request',
            cancelButtonText: 'Cancel',
            width: '550px',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-lg px-6',
                cancelButton: 'rounded-lg px-6'
            },
            preConfirm: () => {
                const selectedEquipment = Array.from(document.querySelectorAll('.swal_equipment_checkbox:checked'))
                    .map(cb => cb.value);
                
                return {
                    personnel: document.getElementById('swal_personnel').value,
                    equipment: selectedEquipment.join(', '),
                    deploy_date: document.getElementById('swal_deploy_date').value,
                    start_time: document.getElementById('swal_start_time').value,
                    end_time: document.getElementById('swal_end_time').value,
                    traffic_plan: document.getElementById('swal_traffic_plan').value,
                    notes: document.getElementById('swal_notes').value
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitApproval(id, result.value);
            }
        });
    }

    function showRejectionModal(id, request) {
        Swal.fire({
            title: '<span class="text-red-600">Reject Road Assistance Request</span>',
            html: `
                <div class="text-left space-y-4">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-500">Event</p>
                        <p class="font-semibold text-gray-800">${request.event_name}</p>
                        <p class="text-sm text-gray-500 mt-2">Requester</p>
                        <p class="font-medium text-gray-700">${request.requester_name}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Reason for Rejection <span class="text-red-500">*</span></label>
                        <textarea id="swal_rejection_reason" rows="4" placeholder="Please provide a reason for rejecting this request..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"></textarea>
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="lucide lucide-x"></i> Reject Request',
            cancelButtonText: 'Cancel',
            width: '450px',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-lg px-6',
                cancelButton: 'rounded-lg px-6'
            },
            preConfirm: () => {
                const reason = document.getElementById('swal_rejection_reason').value.trim();
                if (!reason) {
                    Swal.showValidationMessage('Please provide a reason for rejection');
                    return false;
                }
                return { reason: reason };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitRejection(id, result.value.reason);
            }
        });
    }

    function submitApproval(id, data) {
        const form = document.getElementById('statusForm');
        form.action = '{{ url("/admin/road-assistance") }}/' + id + '/status';
        document.getElementById('formStatus').value = 'Approved';
        document.getElementById('formFeedback').value = data.notes;
        document.getElementById('formAssignedPersonnel').value = data.personnel;
        document.getElementById('formAssignedEquipment').value = data.equipment;
        document.getElementById('formTrafficPlan').value = data.traffic_plan;
        document.getElementById('formDeploymentDate').value = data.deploy_date;
        document.getElementById('formDeploymentStartTime').value = data.start_time;
        document.getElementById('formDeploymentEndTime').value = data.end_time;
        document.getElementById('formAdminNotes').value = data.notes;
        
        Swal.fire({
            title: 'Processing...',
            text: 'Approving road assistance request',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        form.submit();
    }

    function submitRejection(id, reason) {
        const form = document.getElementById('statusForm');
        form.action = '{{ url("/admin/road-assistance") }}/' + id + '/status';
        document.getElementById('formStatus').value = 'Rejected';
        document.getElementById('formFeedback').value = reason;
        
        Swal.fire({
            title: 'Processing...',
            text: 'Rejecting road assistance request',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        form.submit();
    }

    // Open Send Request Modal
    function openSendRequestModal() {
        document.getElementById('sendRequestModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        lucide.createIcons();
    }

    function closeSendRequestModal() {
        document.getElementById('sendRequestModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Pre-fill from booking
    function requestForBooking(booking) {
        openSendRequestModal();
        setTimeout(() => {
            document.getElementById('send_booking_id').value = booking.id || '';
            document.getElementById('send_location').value = booking.facility_address || '';
            document.getElementById('send_start_date').value = booking.start_time ? booking.start_time.split(' ')[0] : '';
            document.getElementById('send_start_time').value = booking.start_time ? booking.start_time.split(' ')[1]?.substring(0, 5) : '';
            document.getElementById('send_end_date').value = booking.end_time ? booking.end_time.split(' ')[0] : '';
            document.getElementById('send_end_time').value = booking.end_time ? booking.end_time.split(' ')[1]?.substring(0, 5) : '';
            document.getElementById('send_description').value = `Road assistance needed for facility booking at ${booking.facility_name}. Expected attendees: ${booking.expected_attendees || 'N/A'}. Purpose: ${booking.purpose || 'N/A'}`;
        }, 100);
    }

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session("success") }}',
        confirmButtonColor: '#16a34a',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg px-6'
        }
    });
    @endif

    @if(session('warning'))
    Swal.fire({
        icon: 'warning',
        title: 'Saved Locally',
        text: '{{ session("warning") }}',
        confirmButtonColor: '#f59e0b',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg px-6'
        }
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session("error") }}',
        confirmButtonColor: '#dc2626',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-lg px-6'
        }
    });
    @endif

    // AJAX Polling for real-time updates
    let lastTotal = {{ $stats['total'] }};
    function refreshData() {
        fetch('{{ route("admin.road-assistance.json") }}')
            .then(res => res.json())
            .then(data => {
                document.getElementById('stat-total').textContent = data.stats.total;
                document.getElementById('stat-pending').textContent = data.stats.pending;
                document.getElementById('stat-approved').textContent = data.stats.approved;
                document.getElementById('stat-rejected').textContent = data.stats.rejected;
                
                if (data.stats.total !== lastTotal) {
                    location.reload();
                    lastTotal = data.stats.total;
                }
            })
            .catch(err => console.log('Refresh error:', err));
    }
    setInterval(refreshData, 5000);
</script>
@endpush
