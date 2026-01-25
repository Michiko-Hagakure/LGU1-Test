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
            <form action="{{ route('admin.infrastructure.projects.sync-all') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Sync All Statuses
                </button>
            </form>
            <a href="{{ route('admin.infrastructure.project-request') }}" class="px-4 py-2 bg-lgu-highlight text-white rounded-lg hover:bg-lgu-stroke transition-colors flex items-center gap-2">
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
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucwords(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($request->created_at)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.infrastructure.projects.show', $request->id) }}" class="px-3 py-1.5 bg-lgu-highlight text-white text-xs font-medium rounded-lg hover:bg-lgu-stroke transition-colors">
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
            <a href="{{ route('admin.infrastructure.project-request') }}" class="mt-6 inline-flex items-center gap-2 px-4 py-2 bg-lgu-highlight text-white rounded-lg hover:bg-lgu-stroke transition-colors">
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
@endsection
