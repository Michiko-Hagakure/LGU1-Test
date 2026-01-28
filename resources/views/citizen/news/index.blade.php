@extends('layouts.citizen')

@section('title', 'News')
@section('page-title', 'City News & Announcements')
@section('page-subtitle', 'Stay informed with the latest updates from the city')

@section('page-content')
<!-- Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">City News & Announcements</h1>
    <p class="text-gray-600">Stay informed with the latest updates from the city</p>
</div>

<!-- Search & Filters -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <form method="GET" action="{{ URL::signedRoute('citizen.news.index') }}" class="flex flex-col md:flex-row gap-4">
        <!-- Search -->
        <div class="flex-1">
            <input type="text" name="search" placeholder="Search news..." value="{{ request('search') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Category Filter -->
        <div class="w-full md:w-48">
            <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="all">All Categories</option>
                <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General</option>
                <option value="facility_update" {{ request('category') == 'facility_update' ? 'selected' : '' }}>Facility Updates</option>
                <option value="policy_change" {{ request('category') == 'policy_change' ? 'selected' : '' }}>Policy Changes</option>
                <option value="maintenance" {{ request('category') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                <option value="emergency" {{ request('category') == 'emergency' ? 'selected' : '' }}>Emergency</option>
            </select>
        </div>

        <!-- Search Button -->
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition duration-200">
            Search
        </button>
    </form>
</div>

<!-- Urgent News -->
@if($urgentNews->count() > 0)
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
        <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        Urgent Announcements
    </h2>
    <div class="space-y-4">
        @foreach($urgentNews as $newsItem)
        <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6 hover:shadow-lg transition duration-200">
            <div class="flex items-start">
                <div class="flex-shrink-0 bg-red-100 rounded-lg p-3 mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $newsItem->title }}</h3>
                    <p class="text-gray-700 mb-3">{{ Str::limit($newsItem->excerpt ?? $newsItem->content, 150) }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ $newsItem->published_at->format('M j, Y') }}</span>
                        <a href="{{ URL::signedRoute('citizen.news.show', $newsItem->slug) }}" class="text-red-600 hover:text-red-800 font-semibold text-sm">
                            Read More →
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Featured News -->
@if($featuredNews->count() > 0)
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Featured News</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($featuredNews as $newsItem)
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg overflow-hidden text-white">
            @if($newsItem->image_path)
            <img src="{{ asset('storage/' . $newsItem->image_path) }}" alt="{{ $newsItem->title }}" class="w-full h-48 object-cover opacity-90">
            @else
            <div class="w-full h-48 bg-blue-700 flex items-center justify-center">
                <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
            @endif
            <div class="p-4">
                <div class="flex items-center text-sm mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $newsItem->published_at->format('M d, Y') }}
                </div>
                <h3 class="font-bold text-lg mb-2">{{ $newsItem->title }}</h3>
                <p class="text-sm text-blue-100 mb-3">{{ Str::limit($newsItem->excerpt, 80) }}</p>
                <a href="{{ URL::signedRoute('citizen.news.show', $newsItem->slug) }}" class="inline-block bg-white text-blue-600 font-semibold px-4 py-2 rounded-lg hover:bg-blue-50 transition duration-200">
                    Read More
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- All News -->
<div>
    <h2 class="text-2xl font-bold text-gray-800 mb-4">All News</h2>
    
    @if($news->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($news as $newsItem)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
            @if($newsItem->image_path)
            <img src="{{ asset('storage/' . $newsItem->image_path) }}" alt="{{ $newsItem->title }}" class="w-full h-48 object-cover">
            @else
            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
            @endif
            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                        {{ ucfirst(str_replace('_', ' ', $newsItem->category)) }}
                    </span>
                    @if($newsItem->is_urgent)
                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">
                        Urgent
                    </span>
                    @endif
                </div>
                <h3 class="font-bold text-lg mb-2 text-gray-800">{{ $newsItem->title }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($newsItem->excerpt ?? strip_tags($newsItem->content), 100) }}</p>
                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                    <span>{{ $newsItem->published_at->format('M j, Y') }}</span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ $newsItem->view_count }}
                    </span>
                </div>
                <a href="{{ URL::signedRoute('citizen.news.show', $newsItem->slug) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition duration-200">
                    Read Full Article
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $news->links() }}
    </div>
    @else
    <div class="bg-gray-50 rounded-lg p-12 text-center">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
        </svg>
        <p class="text-gray-600 text-lg">No news articles found.</p>
    </div>
    @endif
</div>
@endsection
