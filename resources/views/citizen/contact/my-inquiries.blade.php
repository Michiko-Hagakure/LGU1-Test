@extends('layouts.citizen')

@section('title', 'My Inquiries')

@section('page-content')
<!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">My Inquiries</h1>
        <p class="text-gray-600">Track your support tickets and inquiries</p>
    </div>

    @if($inquiries->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($inquiries as $inquiry)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-sm text-blue-600 font-semibold">{{ $inquiry->ticket_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $inquiry->subject }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $inquiry->category)) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($inquiry->status)
                                @case('new')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">New</span>
                                    @break
                                @case('open')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Open</span>
                                    @break
                                @case('pending')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Pending</span>
                                    @break
                                @case('resolved')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Resolved</span>
                                    @break
                                @case('closed')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Closed</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($inquiry->priority)
                                @case('urgent')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-red-100 text-red-800">Urgent</span>
                                    @break
                                @case('high')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-orange-100 text-orange-800">High</span>
                                    @break
                                @case('normal')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-blue-100 text-blue-800">Normal</span>
                                    @break
                                @case('low')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded bg-gray-100 text-gray-800">Low</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $inquiry->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('citizen.contact.show-inquiry', $inquiry->ticket_number) }}" 
                                class="text-blue-600 hover:text-blue-800 font-medium">View Details</a>
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">No Inquiries Yet</h3>
        <p class="text-gray-600 mb-6">You haven't submitted any inquiries.</p>
        <a href="{{ route('citizen.contact.index') }}" 
            class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
            Submit an Inquiry
        </a>
    </div>
    @endif
@endsection
