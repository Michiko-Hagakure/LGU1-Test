@extends('layouts.admin')

@section('title', 'Create FAQ Category')

@section('page-content')
<div class="mb-6">
    <a href="{{ route('admin.faq-categories.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Categories
    </a>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Create FAQ Category</h1>

    <form action="{{ route('admin.faq-categories.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="text-sm text-gray-500 mt-1">URL-friendly version (e.g., booking-help)</p>
            @error('slug')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
            <input type="text" name="icon" id="icon" value="{{ old('icon') }}" maxlength="10"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                placeholder="?">
            <p class="text-sm text-gray-500 mt-1">Optional icon character for visual appeal</p>
            @error('icon')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="text-sm text-gray-500 mt-1">Lower numbers appear first</p>
            @error('sort_order')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Active</span>
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.faq-categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg transition duration-200">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                Create Category
            </button>
        </div>
    </form>
</div>

<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function(e) {
    const slug = e.target.value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '');
    document.getElementById('slug').value = slug;
});
</script>
@endsection
