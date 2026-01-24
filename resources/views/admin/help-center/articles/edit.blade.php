@extends('layouts.admin')

@section('title', 'Edit Help Article')

@section('page-content')
<div class="mb-6">
    <a href="{{ route('admin.help-articles.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Articles
    </a>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Help Article</h1>

    <form action="{{ route('admin.help-articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Article Title *</label>
            <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $article->slug) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            @error('slug')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
            <select name="category" id="category" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Select a category</option>
                <option value="getting_started" {{ old('category', $article->category) == 'getting_started' ? 'selected' : '' }}>Getting Started</option>
                <option value="booking" {{ old('category', $article->category) == 'booking' ? 'selected' : '' }}>Booking</option>
                <option value="payments" {{ old('category', $article->category) == 'payments' ? 'selected' : '' }}>Payments</option>
                <option value="account" {{ old('category', $article->category) == 'account' ? 'selected' : '' }}>Account</option>
                <option value="technical" {{ old('category', $article->category) == 'technical' ? 'selected' : '' }}>Technical</option>
                <option value="policies" {{ old('category', $article->category) == 'policies' ? 'selected' : '' }}>Policies</option>
            </select>
            @error('category')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
            <textarea name="excerpt" id="excerpt" rows="2"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('excerpt', $article->excerpt) }}</textarea>
            @error('excerpt')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
            <textarea name="content" id="content" rows="12" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('content', $article->content) }}</textarea>
            @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
            <input type="url" name="video_url" id="video_url" value="{{ old('video_url', $article->video_url) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            @error('video_url')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        @if($article->screenshots)
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Current Screenshots</label>
            <div class="grid grid-cols-4 gap-4">
                @foreach(json_decode($article->screenshots, true) as $screenshot)
                <img src="{{ asset('storage/' . $screenshot) }}" alt="Screenshot" class="w-full h-24 object-cover rounded-lg">
                @endforeach
            </div>
        </div>
        @endif

        <div class="mb-4">
            <label for="screenshots" class="block text-sm font-medium text-gray-700 mb-2">Upload New Screenshots</label>
            <input type="file" name="screenshots[]" id="screenshots" multiple accept="image/jpeg,image/png"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="text-sm text-gray-500 mt-1">Upload new images (will replace existing)</p>
            @error('screenshots')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
            <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $article->display_order ?? 0) }}" min="0"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            @error('display_order')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4 bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-700 mb-2"><strong>Article Stats:</strong></p>
            <div class="flex gap-4">
                <span class="text-sm">Views: {{ $article->view_count }}</span>
                <span class="text-green-600 text-sm">Helpful: {{ $article->helpful_count }}</span>
            </div>
        </div>

        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Featured Article</span>
            </label>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $article->is_active) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Active</span>
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.help-articles.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg transition duration-200">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                Update Article
            </button>
        </div>
    </form>
</div>
@endsection
