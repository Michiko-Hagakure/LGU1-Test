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
        <div class="flex items-start gap-gr-sm">
            <i data-lucide="traffic-cone" class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-orange-800 font-semibold">Road and Transportation Integration</p>
                <p class="text-orange-700 text-small mt-1">Road assistance requests submitted from the Road and Transportation Infrastructure Monitoring system for events that may cause traffic congestion.</p>
            </div>
        </div>
    </div>

    {{-- Requests Table --}}
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
