@extends('layouts.citizen')

@section('title', $event->title)
@section('page-title', $event->title)
@section('page-subtitle', 'Event Details')

@section('page-content')
<!-- Breadcrumb -->
    <nav class="mb-6 text-sm">
        <a href="{{ route('citizen.events.index') }}" class="text-blue-600 hover:text-blue-800">Events</a>
        <span class="text-gray-400 mx-2">/</span>
        <span class="text-gray-600">{{ $event->title }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Event Image -->
                @if($event->image_path)
                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-96 object-cover">
                @else
                <div class="w-full h-96 bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                    <svg class="w-32 h-32 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                @endif

                <!-- Event Details -->
                <div class="p-6">
                    <!-- Category Badge -->
                    <div class="mb-4">
                        <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                            {{ ucfirst(str_replace('_', ' ', $event->category)) }}
                        </span>
                        @if($event->is_featured)
                        <span class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded-full ml-2">
                            Featured
                        </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $event->title }}</h1>

                    <!-- Meta Info -->
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-6 pb-6 border-b">
                        @if($event->event_date)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $event->event_date->format('F j, Y') }}
                        </div>
                        @endif
                        @if($event->event_time)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $event->event_time->format('g:i A') }}
                        </div>
                        @endif
                        @if($event->location)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $event->location }}
                        </div>
                        @endif
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ $event->view_count }} views
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="prose max-w-none mb-6">
                        <p class="text-lg text-gray-700">{{ $event->description }}</p>
                    </div>

                    <!-- Content -->
                    @if($event->content)
                    <div class="prose max-w-none">
                        {!! nl2br(e($event->content)) !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Event Info Box -->
            @if($event->organizer || $event->max_attendees)
            <div class="bg-blue-50 rounded-lg p-6 mb-6">
                <h3 class="font-bold text-gray-800 mb-4">Event Information</h3>
                @if($event->organizer)
                <div class="mb-3">
                    <p class="text-sm text-gray-600">Organized by</p>
                    <p class="font-semibold text-gray-800">{{ $event->organizer }}</p>
                </div>
                @endif
                @if($event->max_attendees)
                <div>
                    <p class="text-sm text-gray-600">Maximum Attendees</p>
                    <p class="font-semibold text-gray-800">{{ $event->max_attendees }} people</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Related Events -->
            @if($relatedEvents->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-gray-800 mb-4">Related Events</h3>
                <div class="space-y-4">
                    @foreach($relatedEvents as $related)
                    <a href="{{ route('citizen.events.show', $related->slug) }}" class="block hover:bg-gray-50 p-3 rounded-lg transition duration-200">
                        <h4 class="font-semibold text-gray-800 mb-1">{{ $related->title }}</h4>
                        <p class="text-sm text-gray-600">{{ Str::limit($related->description, 60) }}</p>
                        @if($related->event_date)
                        <p class="text-xs text-gray-500 mt-1">{{ $related->event_date->format('M j, Y') }}</p>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
</div>
@endsection
