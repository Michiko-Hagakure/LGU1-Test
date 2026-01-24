@extends('layouts.admin')

@section('page-content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-lgu-headline mb-2">
                <i data-lucide="trash-2" class="w-8 h-8 inline mr-2"></i>
                Deleted Templates
            </h1>
            <p class="text-lgu-paragraph">Restore or permanently delete message templates</p>
        </div>
        <a href="{{ route('admin.templates.index') }}" 
           class="px-6 py-3 bg-lgu-button text-white font-semibold rounded-lg hover:opacity-90 transition shadow-lg">
            <i data-lucide="arrow-left" class="w-5 h-5 inline mr-2"></i>
            Back to Templates
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <i data-lucide="check-circle" class="w-5 h-5 inline mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <i data-lucide="alert-circle" class="w-5 h-5 inline mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($templates as $template)
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition opacity-75">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-lgu-headline mb-1">{{ $template->name }}</h3>
                        <div class="flex gap-2 flex-wrap">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $template->type === 'email' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $template->type === 'sms' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $template->type === 'in-app' ? 'bg-purple-100 text-purple-800' : '' }}">
                                {{ strtoupper($template->type) }}
                            </span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ ucfirst($template->category) }}
                            </span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <i data-lucide="trash-2" class="w-3 h-3 inline"></i> Deleted
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                @if($template->subject)
                    <p class="text-sm font-semibold text-gray-700 mb-1">Subject:</p>
                    <p class="text-sm text-gray-600 mb-3 truncate">{{ $template->subject }}</p>
                @endif
                <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ Str::limit($template->body, 120) }}</p>

                <!-- Deletion Info -->
                <div class="mb-4 p-3 bg-red-50 rounded-lg border border-red-200">
                    <p class="text-xs text-gray-700">
                        <strong>Deleted:</strong> {{ \Carbon\Carbon::parse($template->deleted_at)->format('M d, Y h:i A') }}
                    </p>
                    <p class="text-xs text-gray-600 mt-1">
                        {{ \Carbon\Carbon::parse($template->deleted_at)->diffForHumans() }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 border-t pt-4">
                    <form action="{{ route('admin.templates.restore', $template->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="button" 
                                class="w-full px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition"
                                onclick="confirmRestore(this)">
                            <i data-lucide="rotate-ccw" class="w-4 h-4 inline mr-1"></i> Restore
                        </button>
                    </form>
                    <form action="{{ route('admin.templates.force-delete', $template->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                class="w-full px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition"
                                onclick="confirmPermanentDelete(this)">
                            <i data-lucide="x" class="w-4 h-4 inline mr-1"></i> Delete Forever
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i data-lucide="inbox" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                <p class="text-gray-500 text-lg mb-2">Trash is empty</p>
                <p class="text-gray-400 text-sm">Deleted templates will appear here</p>
                <a href="{{ route('admin.templates.index') }}" 
                   class="inline-block mt-4 px-6 py-3 bg-lgu-button text-white font-semibold rounded-lg hover:opacity-90 transition">
                    Back to Templates
                </a>
            </div>
        @endforelse
    </div>
</div>

<script>
function confirmRestore(button) {
    Swal.fire({
        title: 'Restore Template?',
        text: 'This template will be restored and become active again.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Restore It',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}

function confirmPermanentDelete(button) {
    Swal.fire({
        title: 'Permanently Delete?',
        html: '<p class="mb-3">This action <strong>CANNOT</strong> be undone!</p><p>The template will be permanently removed from the database.</p>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete Forever',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        input: 'checkbox',
        inputValue: 0,
        inputPlaceholder: 'I understand this action is permanent',
        inputValidator: (result) => {
            return !result && 'You must confirm to proceed'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}

lucide.createIcons();
</script>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
