@extends('layouts.admin')

@section('page-title', 'Energy Fund Requests')
@section('page-subtitle', 'Manage fund requests from Energy Efficiency and Conservation Management')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .logistics-modal {
        transition: all 0.3s ease;
    }
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
                    <p class="text-h2 font-bold text-gray-900">{{ $stats['total'] }}</p>
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
                    <p class="text-h2 font-bold text-amber-600">{{ $stats['pending'] }}</p>
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
                    <p class="text-h2 font-bold text-green-600">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="banknote" class="w-6 h-6 text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Approved Funds</p>
                    <p class="text-h3 font-bold text-emerald-600">₱{{ number_format($stats['approved_amount'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Integration Info --}}
    <div class="p-gr-md bg-blue-50 border border-blue-200 rounded-xl">
        <div class="flex items-start gap-gr-sm">
            <i data-lucide="zap" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-blue-800 font-semibold">Energy Efficiency Integration</p>
                <p class="text-blue-700 text-small mt-1">Fund requests submitted from the Energy Efficiency and Conservation Management system for facility-related expenses.</p>
            </div>
        </div>
    </div>

    {{-- Requests Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-gr-md py-gr-sm">
            <h3 class="text-white font-semibold flex items-center gap-2">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                Fund Requests
            </h3>
        </div>

        @if($requests->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-500 uppercase tracking-wider">Requester</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-500 uppercase tracking-wider">Purpose & Details</th>
                        <th class="px-gr-md py-gr-sm text-right text-caption font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-500 uppercase tracking-wider">Feedback</th>
                        <th class="px-gr-md py-gr-sm text-center text-caption font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-gr-md py-gr-sm text-center text-caption font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($requests as $req)
                    <tr class="hover:bg-gray-50 transition-colors" x-data="{ showLogistics: false }">
                        <td class="px-gr-md py-gr-md">
                            <div class="font-semibold text-gray-900">{{ $req->requester_name }}</div>
                            <div class="text-caption text-blue-600 font-medium mt-1">
                                <i data-lucide="building-2" class="w-3 h-3 inline"></i> Energy Efficiency & Conservation
                            </div>
                            @if($req->seminar_info)
                            <div class="mt-2 p-2 bg-amber-50 border border-amber-100 rounded-lg flex gap-2 items-center">
                                @if($req->seminar_image)
                                <img src="https://energy.local-government-unit-1-ph.com/{{ $req->seminar_image }}" 
                                     class="w-8 h-8 object-cover rounded border border-white shadow-sm">
                                @endif
                                <div>
                                    <p class="text-caption font-semibold text-amber-700">Linked Seminar:</p>
                                    <p class="text-caption text-gray-700">{{ $req->seminar_info }}</p>
                                </div>
                            </div>
                            @endif
                        </td>

                        <td class="px-gr-md py-gr-md">
                            <div class="text-small font-medium text-gray-700">{{ $req->purpose }}</div>
                            @if($req->logistics)
                            <button @click="showLogistics = true" class="mt-2 text-caption font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                <i data-lucide="list" class="w-3 h-3"></i> View Detailed Logistics
                            </button>
                            @endif

                            {{-- Logistics Modal --}}
                            <div x-show="showLogistics" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm p-4" 
                                 x-cloak
                                 @click.self="showLogistics = false">
                                <div class="bg-white rounded-2xl max-w-lg w-full p-gr-lg shadow-2xl border border-gray-100">
                                    <div class="flex justify-between items-start mb-gr-md">
                                        <h3 class="text-h3 font-bold text-gray-900">Itemized Logistics</h3>
                                        <button @click="showLogistics = false" class="text-gray-400 hover:text-red-500 transition">
                                            <i data-lucide="x" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                    <div class="bg-gray-50 p-gr-md rounded-xl text-small text-gray-600 leading-relaxed whitespace-pre-line border border-gray-200 max-h-[50vh] overflow-y-auto">
                                        {{ $req->logistics }}
                                    </div>
                                    <button @click="showLogistics = false" class="mt-gr-md w-full bg-lgu-headline text-white py-3 rounded-xl font-semibold hover:bg-lgu-stroke transition-colors">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </td>

                        <td class="px-gr-md py-gr-md text-right">
                            <span class="text-h3 font-bold text-gray-900">₱{{ number_format($req->amount, 2) }}</span>
                        </td>

                        <td class="px-gr-md py-gr-md">
                            @if($req->status == 'pending')
                            <textarea id="feedback_{{ $req->id }}" 
                                      placeholder="Note for requester..." 
                                      class="w-full bg-gray-50 border border-gray-200 p-2 rounded-lg text-small outline-none focus:border-lgu-highlight focus:ring-1 focus:ring-lgu-highlight transition h-20 resize-none"></textarea>
                            @else
                            <div class="text-small text-gray-600 bg-gray-50 p-2 rounded-lg border border-gray-100">
                                <span class="font-semibold text-caption text-gray-400 block mb-1">Admin Feedback:</span>
                                {{ $req->feedback ?? 'No feedback provided.' }}
                            </div>
                            @endif
                        </td>

                        <td class="px-gr-md py-gr-md text-center">
                            <span class="status-badge status-{{ strtolower($req->status) }}">
                                {{ $req->status }}
                            </span>
                        </td>

                        <td class="px-gr-md py-gr-md">
                            @if($req->status == 'pending')
                            <div class="flex flex-col gap-2">
                                <button onclick="updateStatus({{ $req->id }}, 'Approved')" 
                                        class="bg-green-600 text-white px-4 py-2 rounded-lg text-caption font-semibold hover:bg-green-700 transition-colors flex items-center justify-center gap-1">
                                    <i data-lucide="check" class="w-3 h-3"></i> Approve
                                </button>
                                <button onclick="updateStatus({{ $req->id }}, 'Rejected')" 
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg text-caption font-semibold hover:bg-red-600 transition-colors flex items-center justify-center gap-1">
                                    <i data-lucide="x" class="w-3 h-3"></i> Reject
                                </button>
                            </div>
                            @else
                            <div class="text-center text-caption text-gray-400">
                                <i data-lucide="check-circle" class="w-5 h-5 mx-auto {{ $req->status == 'Approved' ? 'text-green-500' : 'text-red-500' }}"></i>
                                <p class="mt-1">Processed</p>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-gr-2xl text-center">
            <i data-lucide="inbox" class="w-16 h-16 text-gray-200 mx-auto mb-gr-md"></i>
            <p class="text-gray-400 font-semibold">No fund requests found</p>
            <p class="text-caption text-gray-400 mt-1">Requests from Energy Efficiency will appear here</p>
        </div>
        @endif
    </div>
</div>

{{-- Hidden Form for Status Updates --}}
<form id="statusForm" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="status" id="formStatus">
    <input type="hidden" name="feedback" id="formFeedback">
    <input type="hidden" name="assigned_facility" id="formAssignedFacility">
    <input type="hidden" name="scheduled_date" id="formScheduledDate">
    <input type="hidden" name="scheduled_time" id="formScheduledTime">
    <input type="hidden" name="approved_amount" id="formApprovedAmount">
    <input type="hidden" name="admin_notes" id="formAdminNotes">
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Store request data for modal
    const requestsData = @json($requests->keyBy('id'));

    function updateStatus(id, status) {
        const request = requestsData[id];
        
        if (status === 'Approved') {
            showApprovalModal(id, request);
        } else {
            showRejectionModal(id, request);
        }
    }

    function showApprovalModal(id, request) {
        Swal.fire({
            title: '<span class="text-green-600">Approve Fund Request</span>',
            html: `
                <div class="text-left space-y-4">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-500">Requester</p>
                        <p class="font-semibold text-gray-800">${request.requester_name}</p>
                        <p class="text-sm text-gray-500 mt-2">Requested Amount</p>
                        <p class="font-bold text-lg text-green-600">₱${parseFloat(request.amount).toLocaleString('en-PH', {minimumFractionDigits: 2})}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Approved Amount <span class="text-red-500">*</span></label>
                        <input type="number" id="swal_approved_amount" value="${request.amount}" step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assigned Facility/Venue</label>
                        <input type="text" id="swal_facility" placeholder="e.g., LGU Conference Hall, Training Room A"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Scheduled Date</label>
                            <input type="date" id="swal_date" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Scheduled Time</label>
                            <input type="time" id="swal_time" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Admin Notes / Feedback</label>
                        <textarea id="swal_notes" rows="3" placeholder="Additional notes or instructions for the requester..."
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
            width: '500px',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-lg px-6',
                cancelButton: 'rounded-lg px-6'
            },
            preConfirm: () => {
                const approvedAmount = document.getElementById('swal_approved_amount').value;
                if (!approvedAmount || parseFloat(approvedAmount) <= 0) {
                    Swal.showValidationMessage('Please enter a valid approved amount');
                    return false;
                }
                return {
                    approved_amount: approvedAmount,
                    facility: document.getElementById('swal_facility').value,
                    date: document.getElementById('swal_date').value,
                    time: document.getElementById('swal_time').value,
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
            title: '<span class="text-red-600">Reject Fund Request</span>',
            html: `
                <div class="text-left space-y-4">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-500">Requester</p>
                        <p class="font-semibold text-gray-800">${request.requester_name}</p>
                        <p class="text-sm text-gray-500 mt-2">Requested Amount</p>
                        <p class="font-bold text-lg text-gray-600">₱${parseFloat(request.amount).toLocaleString('en-PH', {minimumFractionDigits: 2})}</p>
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
        form.action = '{{ url("/admin/fund-requests") }}/' + id + '/status?signature={{ request()->get("signature") }}';
        document.getElementById('formStatus').value = 'Approved';
        document.getElementById('formFeedback').value = data.notes;
        document.getElementById('formAssignedFacility').value = data.facility;
        document.getElementById('formScheduledDate').value = data.date;
        document.getElementById('formScheduledTime').value = data.time;
        document.getElementById('formApprovedAmount').value = data.approved_amount;
        document.getElementById('formAdminNotes').value = data.notes;
        
        // Show loading
        Swal.fire({
            title: 'Processing...',
            text: 'Approving fund request',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        form.submit();
    }

    function submitRejection(id, reason) {
        const form = document.getElementById('statusForm');
        form.action = '{{ url("/admin/fund-requests") }}/' + id + '/status?signature={{ request()->get("signature") }}';
        document.getElementById('formStatus').value = 'Rejected';
        document.getElementById('formFeedback').value = reason;
        
        // Show loading
        Swal.fire({
            title: 'Processing...',
            text: 'Rejecting fund request',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        form.submit();
    }

    // Show success/error messages with SweetAlert2
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
</script>
@endpush
@endsection
