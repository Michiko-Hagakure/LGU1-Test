@extends('layouts.staff')

@section('title', 'Inquiry Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Inquiry Management</h1>
        <p class="text-gray-600">Manage citizen support tickets and inquiries</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">New</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['new'] }}</p>
                </div>
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
        </div>

        <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Open</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['open'] }}</p>
                </div>
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Urgent</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['urgent'] }}</p>
                </div>
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-gray-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Unassigned</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['unassigned'] }}</p>
                </div>
                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>

        <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Assigned to Me</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['my_assigned'] }}</p>
                </div>
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ URL::signedRoute('staff.inquiries.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="all">All Status</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="all">All Priority</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="all">All Categories</option>
                    <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General</option>
                    <option value="booking_issue" {{ request('category') == 'booking_issue' ? 'selected' : '' }}>Booking Issue</option>
                    <option value="payment_issue" {{ request('category') == 'payment_issue' ? 'selected' : '' }}>Payment Issue</option>
                    <option value="technical_issue" {{ request('category') == 'technical_issue' ? 'selected' : '' }}>Technical Issue</option>
                    <option value="complaint" {{ request('category') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                    <option value="suggestion" {{ request('category') == 'suggestion' ? 'selected' : '' }}>Suggestion</option>
                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Assignment</label>
                <select name="assigned" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="all">All Inquiries</option>
                    <option value="me" {{ request('assigned') == 'me' ? 'selected' : '' }}>Assigned to Me</option>
                    <option value="unassigned" {{ request('assigned') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition duration-200">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Inquiries Table -->
    @if($inquiries->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ticket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Citizen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($inquiries as $inquiry)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm font-semibold text-blue-600">{{ $inquiry->ticket_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $inquiry->name }}</div>
                            <div class="text-sm text-gray-500">{{ $inquiry->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ Str::limit($inquiry->subject, 40) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ ucfirst(str_replace('_', ' ', $inquiry->category)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($inquiry->status)
                                @case('new')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">New</span>
                                    @break
                                @case('open')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Open</span>
                                    @break
                                @case('pending')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Pending</span>
                                    @break
                                @case('resolved')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Resolved</span>
                                    @break
                                @case('closed')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Closed</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($inquiry->priority)
                                @case('urgent')
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Urgent</span>
                                    @break
                                @case('high')
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-orange-100 text-orange-800">High</span>
                                    @break
                                @case('normal')
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">Normal</span>
                                    @break
                                @case('low')
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-800">Low</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $inquiry->assigned_to ? 'Assigned' : 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $inquiry->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ URL::signedRoute('staff.inquiries.show', $inquiry->id) }}" 
                                class="text-blue-600 hover:text-blue-800 font-medium">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $inquiries->links() }}
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        <p class="text-gray-600 text-lg">No inquiries found.</p>
    </div>
    @endif
</div>
@endsection
