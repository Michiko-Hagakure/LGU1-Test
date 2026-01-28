@extends('layouts.admin')

@section('page-title', 'Project Request Details')
@section('page-subtitle', 'View detailed status of your infrastructure project request')

@section('page-content')
<div class="max-w-5xl mx-auto">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ URL::signedRoute('admin.infrastructure.projects.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Project Requests
        </a>
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

    {{-- Project Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $project->project_title }}</h1>
                <p class="text-gray-600 mt-1">{{ $project->requesting_office }}</p>
                @if($project->external_project_id)
                <p class="text-sm text-gray-500 mt-2">
                    <span class="font-medium">External ID:</span> 
                    <span class="font-mono">#{{ $project->external_project_id }}</span>
                </p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                @if($project->external_project_id)
                <form action="{{ URL::signedRoute('admin.infrastructure.projects.refresh', $project->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh Status
                    </button>
                </form>
                @endif
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
                <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucwords(str_replace('_', ' ', $project->status)) }}
                </span>
            </div>
        </div>
    </div>

    @if($apiStatus)
    {{-- Detailed Status from Infrastructure PM --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-lgu-highlight" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Approval Status
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Engineer Status --}}
            <div class="p-4 rounded-lg border {{ ($apiStatus['engineer_status'] ?? '') === 'approved' ? 'bg-green-50 border-green-200' : (($apiStatus['engineer_status'] ?? '') === 'rejected' ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200') }}">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ ($apiStatus['engineer_status'] ?? '') === 'approved' ? 'bg-green-100' : (($apiStatus['engineer_status'] ?? '') === 'rejected' ? 'bg-red-100' : 'bg-gray-100') }}">
                        <svg class="w-5 h-5 {{ ($apiStatus['engineer_status'] ?? '') === 'approved' ? 'text-green-600' : (($apiStatus['engineer_status'] ?? '') === 'rejected' ? 'text-red-600' : 'text-gray-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if(($apiStatus['engineer_status'] ?? '') === 'approved')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            @elseif(($apiStatus['engineer_status'] ?? '') === 'rejected')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Engineer Review</p>
                        <p class="font-semibold {{ ($apiStatus['engineer_status'] ?? '') === 'approved' ? 'text-green-700' : (($apiStatus['engineer_status'] ?? '') === 'rejected' ? 'text-red-700' : 'text-gray-700') }}">
                            {{ ucfirst($apiStatus['engineer_status'] ?? 'Pending') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Treasurer Status --}}
            <div class="p-4 rounded-lg border {{ ($apiStatus['treasurer_status'] ?? '') === 'approved' ? 'bg-green-50 border-green-200' : (($apiStatus['treasurer_status'] ?? '') === 'rejected' ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200') }}">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ ($apiStatus['treasurer_status'] ?? '') === 'approved' ? 'bg-green-100' : (($apiStatus['treasurer_status'] ?? '') === 'rejected' ? 'bg-red-100' : 'bg-gray-100') }}">
                        <svg class="w-5 h-5 {{ ($apiStatus['treasurer_status'] ?? '') === 'approved' ? 'text-green-600' : (($apiStatus['treasurer_status'] ?? '') === 'rejected' ? 'text-red-600' : 'text-gray-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if(($apiStatus['treasurer_status'] ?? '') === 'approved')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            @elseif(($apiStatus['treasurer_status'] ?? '') === 'rejected')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Treasurer Review</p>
                        <p class="font-semibold {{ ($apiStatus['treasurer_status'] ?? '') === 'approved' ? 'text-green-700' : (($apiStatus['treasurer_status'] ?? '') === 'rejected' ? 'text-red-700' : 'text-gray-700') }}">
                            {{ ucfirst($apiStatus['treasurer_status'] ?? 'Pending') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Overall Status --}}
            <div class="p-4 rounded-lg border {{ ($apiStatus['overall_status'] ?? '') === 'approved' ? 'bg-green-50 border-green-200' : (($apiStatus['overall_status'] ?? '') === 'rejected' ? 'bg-red-50 border-red-200' : 'bg-blue-50 border-blue-200') }}">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ ($apiStatus['overall_status'] ?? '') === 'approved' ? 'bg-green-100' : (($apiStatus['overall_status'] ?? '') === 'rejected' ? 'bg-red-100' : 'bg-blue-100') }}">
                        <svg class="w-5 h-5 {{ ($apiStatus['overall_status'] ?? '') === 'approved' ? 'text-green-600' : (($apiStatus['overall_status'] ?? '') === 'rejected' ? 'text-red-600' : 'text-blue-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if(($apiStatus['overall_status'] ?? '') === 'approved')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            @elseif(($apiStatus['overall_status'] ?? '') === 'rejected')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Overall Status</p>
                        <p class="font-semibold {{ ($apiStatus['overall_status'] ?? '') === 'approved' ? 'text-green-700' : (($apiStatus['overall_status'] ?? '') === 'rejected' ? 'text-red-700' : 'text-blue-700') }}">
                            {{ ucfirst($apiStatus['overall_status'] ?? 'Pending') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Feasibility Report --}}
        @if(!empty($apiStatus['engineer_feasibility_report']))
        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <h3 class="font-medium text-blue-900 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Engineer's Feasibility Report
            </h3>
            <p class="text-blue-800 text-sm">{{ $apiStatus['engineer_feasibility_report'] }}</p>
        </div>
        @endif

        {{-- Budget Clearance --}}
        @if(!empty($apiStatus['treasurer_budget_clearance']))
        <div class="mt-4 p-4 bg-emerald-50 rounded-lg border border-emerald-200">
            <h3 class="font-medium text-emerald-900 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Treasurer's Budget Clearance
            </h3>
            <p class="text-emerald-800 text-sm">{{ $apiStatus['treasurer_budget_clearance'] }}</p>
        </div>
        @endif
    </div>
    @endif

    {{-- Project Details --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Basic Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Project Information</h2>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm text-gray-500">Category</dt>
                    <dd class="font-medium text-gray-900">{{ $project->project_category }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Priority Level</dt>
                    <dd>
                        @php
                            $priorityColors = [
                                'low' => 'bg-green-100 text-green-800',
                                'medium' => 'bg-yellow-100 text-yellow-800',
                                'high' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $priorityColors[$project->priority_level] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($project->priority_level) }}
                        </span>
                    </dd>
                </div>
                @if($project->estimated_budget)
                <div>
                    <dt class="text-sm text-gray-500">Estimated Budget</dt>
                    <dd class="font-medium text-gray-900">₱{{ number_format($project->estimated_budget, 2) }}</dd>
                </div>
                @endif
                @if($project->requested_start_date)
                <div>
                    <dt class="text-sm text-gray-500">Requested Start Date</dt>
                    <dd class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($project->requested_start_date)->format('M d, Y') }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm text-gray-500">Submitted On</dt>
                    <dd class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($project->created_at)->format('M d, Y h:i A') }}</dd>
                </div>
            </dl>
        </div>

        {{-- Contact Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h2>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm text-gray-500">Contact Person</dt>
                    <dd class="font-medium text-gray-900">{{ $project->contact_person }}</dd>
                </div>
                @if($project->position)
                <div>
                    <dt class="text-sm text-gray-500">Position</dt>
                    <dd class="font-medium text-gray-900">{{ $project->position }}</dd>
                </div>
                @endif
                @if($project->contact_number)
                <div>
                    <dt class="text-sm text-gray-500">Contact Number</dt>
                    <dd class="font-medium text-gray-900">{{ $project->contact_number }}</dd>
                </div>
                @endif
                @if($project->contact_email)
                <div>
                    <dt class="text-sm text-gray-500">Email</dt>
                    <dd class="font-medium text-gray-900">{{ $project->contact_email }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    {{-- Problem Description --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Problem Identified</h2>
        <p class="text-gray-700 whitespace-pre-wrap">{{ $project->problem_identified }}</p>
    </div>
</div>
@endsection
