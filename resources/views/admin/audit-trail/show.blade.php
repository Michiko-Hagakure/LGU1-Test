@extends('layouts.admin')

@section('page-content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ URL::signedRoute('admin.audit-trail.index') }}" class="inline-flex items-center text-lgu-paragraph hover:text-lgu-headline mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to Audit Trail
        </a>
        <h1 class="text-3xl font-bold text-lgu-headline mb-2">Audit Log Details</h1>
        <p class="text-lgu-paragraph">Complete information about this audit log entry</p>
    </div>

    <!-- Log Details Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div>
                <h2 class="text-xl font-bold text-lgu-headline mb-4 flex items-center">
                    <i data-lucide="info" class="w-5 h-5 mr-2"></i>
                    Basic Information
                </h2>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">Log ID</p>
                        <p class="text-lgu-headline font-bold">#{{ $log->id }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">Date & Time</p>
                        <p class="text-lgu-headline font-bold">{{ $log->created_at->format('F d, Y - h:i:s A') }}</p>
                        <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">Action Type</p>
                        @if($log->event === 'created')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                <i data-lucide="plus-circle" class="w-4 h-4 inline mr-1"></i>
                                Created
                            </span>
                        @elseif($log->event === 'updated')
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                <i data-lucide="edit" class="w-4 h-4 inline mr-1"></i>
                                Updated
                            </span>
                        @elseif($log->event === 'deleted')
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                <i data-lucide="trash-2" class="w-4 h-4 inline mr-1"></i>
                                Deleted
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold">
                                {{ ucfirst($log->event ?? 'N/A') }}
                            </span>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">Module</p>
                        <span class="px-3 py-1 bg-lgu-bg text-lgu-headline rounded text-sm font-semibold">
                            {{ ucfirst($log->log_name ?? 'N/A') }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">Description</p>
                        <p class="text-lgu-headline">{{ $log->description }}</p>
                    </div>
                </div>
            </div>

            <!-- User & System Information -->
            <div>
                <h2 class="text-xl font-bold text-lgu-headline mb-4 flex items-center">
                    <i data-lucide="user" class="w-5 h-5 mr-2"></i>
                    User & System Information
                </h2>

                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">Performed By</p>
                        <p class="text-lgu-headline font-bold">{{ $log->causer?->name ?? 'System' }}</p>
                        @if($log->causer)
                            <p class="text-xs text-gray-500">{{ $log->causer->email }}</p>
                            <p class="text-xs text-gray-500">Role: {{ ucfirst($log->causer->role) }}</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">IP Address</p>
                        <p class="text-lgu-headline font-mono">{{ $log->ip_address ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-lgu-paragraph mb-1">User Agent</p>
                        <p class="text-sm text-lgu-paragraph break-all">{{ $log->user_agent ?? 'N/A' }}</p>
                    </div>

                    @if($log->subject)
                        <div>
                            <p class="text-sm font-semibold text-lgu-paragraph mb-1">Subject Type</p>
                            <p class="text-lgu-headline">{{ class_basename($log->subject_type) }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-lgu-paragraph mb-1">Subject ID</p>
                            <p class="text-lgu-headline font-mono">#{{ $log->subject_id }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Changed Properties -->
    @if($log->properties && count($log->properties) > 0)
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-lgu-headline mb-4 flex items-center">
                <i data-lucide="file-json" class="w-5 h-5 mr-2"></i>
                Changed Properties
            </h2>

            <div class="bg-gray-50 rounded-lg p-4 overflow-x-auto">
                <pre class="text-sm text-lgu-paragraph"><code>{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</code></pre>
            </div>

            @if(isset($log->properties['attributes']) && $log->event === 'updated')
                <div class="mt-4">
                    <h3 class="text-lg font-bold text-lgu-headline mb-3">Changes Summary</h3>
                    <div class="space-y-2">
                        @foreach($log->properties as $key => $value)
                            @if($key !== 'attributes' && $key !== 'old')
                                <div class="flex items-start border-b border-gray-200 pb-2">
                                    <span class="text-sm font-semibold text-lgu-paragraph w-1/3">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                    <span class="text-sm text-lgu-headline w-2/3">
                                        @if(is_array($value))
                                            {{ json_encode($value) }}
                                        @else
                                            {{ $value ?? 'N/A' }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>

<script>
    lucide.createIcons();
</script>
@endsection
