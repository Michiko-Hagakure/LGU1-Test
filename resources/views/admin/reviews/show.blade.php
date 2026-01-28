@extends('layouts.admin')

@section('page-title', 'Review Details')
@section('page-subtitle', 'View review information')

@section('page-content')
<div class="space-y-gr-lg">
    {{-- Back Button --}}
    <div class="mb-gr-md">
        <a href="{{ URL::signedRoute('admin.reviews.index') }}" class="inline-flex items-center text-lgu-paragraph hover:text-lgu-headline transition-colors duration-200">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-gr-xs"></i>
            Back to Reviews
        </a>
    </div>

    {{-- Review Details Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
        <div class="flex items-start gap-gr-lg">
            {{-- Facility Image --}}
            <div class="flex-shrink-0">
                @if($review->facility_image)
                    <img src="{{ asset('storage/' . $review->facility_image) }}" alt="{{ $review->facility_name }}" 
                        class="w-32 h-32 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                        onclick="Swal.fire({ imageUrl: '{{ asset('storage/' . $review->facility_image) }}', imageAlt: '{{ $review->facility_name }}', showCloseButton: true, showConfirmButton: false, width: 'auto' })">
                @else
                    <div class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="building-2" class="w-12 h-12 text-gray-400"></i>
                    </div>
                @endif
            </div>

            {{-- Review Content --}}
            <div class="flex-1">
                <div class="flex items-start justify-between mb-gr-md">
                    <div>
                        <h2 class="text-h2 font-bold text-lgu-headline mb-gr-xs">{{ $review->facility_name }}</h2>
                        @if($review->facility_address)
                            <p class="text-small text-gray-600 flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                                {{ $review->facility_address }}
                            </p>
                        @endif
                    </div>
                    
                    {{-- Rating --}}
                    <div class="text-right">
                        <div class="flex items-center gap-1 mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i data-lucide="star" class="w-6 h-6 text-yellow-400 fill-yellow-400"></i>
                                @else
                                    <i data-lucide="star" class="w-6 h-6 text-gray-300"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-h3 font-bold text-lgu-headline">{{ $review->rating }}/5</span>
                    </div>
                </div>

                {{-- Review Text --}}
                <div class="bg-gray-50 rounded-lg p-gr-md mb-gr-md">
                    <h3 class="text-small font-semibold text-lgu-headline mb-gr-xs">Review</h3>
                    <p class="text-body text-lgu-paragraph">{{ $review->comment ?? 'No comment provided.' }}</p>
                </div>

                {{-- Booking Details --}}
                @if($review->event_date)
                <div class="bg-lgu-bg rounded-lg p-gr-md mb-gr-md">
                    <h3 class="text-small font-semibold text-lgu-headline mb-gr-sm">Booking Details</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-gr-sm text-small">
                        <div>
                            <span class="text-gray-500">Event Date:</span>
                            <p class="font-medium text-lgu-headline">{{ \Carbon\Carbon::parse($review->event_date)->format('M d, Y') }}</p>
                        </div>
                        @if($review->start_time && $review->end_time)
                        <div>
                            <span class="text-gray-500">Time:</span>
                            <p class="font-medium text-lgu-headline">{{ \Carbon\Carbon::parse($review->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($review->end_time)->format('g:i A') }}</p>
                        </div>
                        @endif
                        @if($review->purpose)
                        <div class="col-span-2">
                            <span class="text-gray-500">Purpose:</span>
                            <p class="font-medium text-lgu-headline">{{ $review->purpose }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Meta Information --}}
                <div class="flex items-center justify-between text-small text-gray-500">
                    <div class="flex items-center gap-gr-md">
                        <span class="flex items-center gap-1">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            Submitted: {{ \Carbon\Carbon::parse($review->created_at)->format('M d, Y g:i A') }}
                        </span>
                    </div>
                    <span class="text-caption text-gray-400">Review ID: #{{ $review->id }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
@endpush
@endsection
