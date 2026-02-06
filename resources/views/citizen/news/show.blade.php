@extends('layouts.citizen')

@section('title', $newsItem->title)
@section('page-title', $newsItem->title)
@section('page-subtitle', 'News Article')

@section('page-content')
<!-- Breadcrumb -->
<nav class="mb-6 text-sm">
    <a href="{{ URL::signedRoute('citizen.news.index') }}" class="text-blue-600 hover:text-blue-800">News</a>
    <span class="text-gray-400 mx-2">/</span>
    <span class="text-gray-600">{{ $newsItem->title }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- News Image -->
            @if($newsItem->image_path)
            <img src="{{ url('/files/' . $newsItem->image_path) }}" alt="{{ $newsItem->title }}" class="w-full h-96 object-cover">
            @else
            <div class="w-full h-96 bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                <svg class="w-32 h-32 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
            @endif

            <!-- News Details -->
            <div class="p-6">
                <!-- Category Badge -->
                <div class="mb-4">
                    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                        {{ ucfirst(str_replace('_', ' ', $newsItem->category)) }}
                    </span>
                    @if($newsItem->is_featured)
                    <span class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded-full ml-2">
                        Featured
                    </span>
                    @endif
                    @if($newsItem->is_urgent)
                    <span class="bg-red-100 text-red-800 text-sm font-semibold px-3 py-1 rounded-full ml-2">
                        Urgent
                    </span>
                    @endif
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $newsItem->title }}</h1>

                <!-- Meta Info -->
                <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-6 pb-6 border-b">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $newsItem->published_at->format('F j, Y') }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ $newsItem->view_count }} views
                    </div>
                </div>

                <!-- Excerpt -->
                @if($newsItem->excerpt)
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <p class="text-lg text-gray-800 font-medium">{{ $newsItem->excerpt }}</p>
                </div>
                @endif

                <!-- Content -->
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($newsItem->content)) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Share Section -->
        <div class="bg-blue-50 rounded-lg p-6 mb-6">
            <h3 class="font-bold text-gray-800 mb-4">Share This Article</h3>
            <div class="flex gap-2">
                <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-200">
                    Share
                </button>
            </div>
        </div>

        <!-- Related News -->
        @if($relatedNews->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-bold text-gray-800 mb-4">Related News</h3>
            <div class="space-y-4">
                @foreach($relatedNews as $related)
                <a href="{{ URL::signedRoute('citizen.news.show', $related->slug) }}" class="block hover:bg-gray-50 p-3 rounded-lg transition duration-200">
                    <h4 class="font-semibold text-gray-800 mb-1">{{ $related->title }}</h4>
                    <p class="text-sm text-gray-600">{{ Str::limit($related->excerpt ?? strip_tags($related->content), 60) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $related->published_at->format('M j, Y') }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
