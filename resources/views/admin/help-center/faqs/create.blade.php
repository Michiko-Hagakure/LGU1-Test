@extends('layouts.admin')

@section('title', 'Create FAQ')

@section('page-content')
<div class="mb-6">
    <a href="{{ URL::signedRoute('admin.faqs.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to FAQs
    </a>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Create FAQ</h1>

    <form action="{{ URL::signedRoute('admin.faqs.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
            <select name="category_id" id="category_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Select a category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="question" class="block text-sm font-medium text-gray-700 mb-2">Question *</label>
            <textarea name="question" id="question" rows="2" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('question') }}</textarea>
            @error('question')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="answer" class="block text-sm font-medium text-gray-700 mb-2">Answer *</label>
            <textarea name="answer" id="answer" rows="6" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('answer') }}</textarea>
            @error('answer')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
            <input type="number" name="display_order" id="display_order" value="{{ old('display_order', 0) }}" min="0"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="text-sm text-gray-500 mt-1">Lower numbers appear first</p>
            @error('display_order')
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
            <a href="{{ URL::signedRoute('admin.faqs.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-lg transition duration-200">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                Create FAQ
            </button>
        </div>
    </form>
</div>
@endsection
