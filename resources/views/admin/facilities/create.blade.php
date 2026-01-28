@extends('layouts.admin')

@section('title', 'Add New Facility')
@section('page-title', 'Add New Facility')
@section('page-subtitle', 'Create a new public facility')

@section('page-content')
<div class="container mx-auto px-gr-md py-gr-lg max-w-4xl">
    {{-- Back Button --}}
    <div class="mb-gr-md">
        <a href="{{ URL::signedRoute('admin.facilities.index') }}" class="inline-flex items-center text-lgu-paragraph hover:text-lgu-headline transition-colors duration-200">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-gr-xs"></i>
            Back to Facilities
        </a>
    </div>

    {{-- Page Header --}}
    <div class="mb-gr-lg">
        <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">Add New Facility</h1>
        <p class="text-body text-lgu-paragraph">Create a new facility for public booking</p>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="bg-red-50 border-2 border-red-200 rounded-lg p-gr-md mb-gr-lg">
            <div class="flex items-start gap-gr-sm">
                <i data-lucide="alert-circle" class="w-6 h-6 text-red-600 flex-shrink-0 mt-1"></i>
                <div class="flex-1">
                    <p class="text-body font-semibold text-red-900 mb-2">Please fix the following errors:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-small text-red-700">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ URL::signedRoute('admin.facilities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Basic Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-md">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center">
                <i data-lucide="info" class="w-6 h-6 mr-gr-sm"></i>
                Basic Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-md">
                {{-- Facility Name --}}
                <div class="md:col-span-2">
                    <label for="name" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Facility Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- City --}}
                <div>
                    <label for="city_id" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        City <span class="text-red-500">*</span>
                    </label>
                    <select id="city_id" name="city_id" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('city_id') border-red-500 @enderror">
                        <option value="">Select City</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->city_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Available --}}
                <div>
                    <label for="is_available" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Availability
                    </label>
                    <select id="is_available" name="is_available"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="1" {{ old('is_available', 1) == 1 ? 'selected' : '' }}>Available</option>
                        <option value="0" {{ old('is_available') == 0 ? 'selected' : '' }}>Not Available</option>
                    </select>
                </div>

                {{-- Capacity --}}
                <div>
                    <label for="capacity" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Capacity (persons) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="capacity" name="capacity" value="{{ old('capacity') }}" min="1" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('capacity') border-red-500 @enderror">
                    @error('capacity')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                
                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Location Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-md">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center">
                <i data-lucide="map-pin" class="w-6 h-6 mr-gr-sm"></i>
                Location Information
            </h2>

            <div class="space-y-gr-md">
                {{-- Address --}}
                <div>
                    <label for="address" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Address <span class="text-red-500">*</span>
                    </label>
                    <textarea id="address" name="address" rows="3" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                            </div>
        </div>

        {{-- Pricing Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-md">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center">
                <i data-lucide="peso-sign" class="w-6 h-6 mr-gr-sm"></i>
                Pricing Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-md">
                {{-- Per Person Rate --}}
                <div>
                    <label for="per_person_rate" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Per Person Rate <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-lgu-paragraph font-semibold">₱</span>
                        <input type="number" id="per_person_rate" name="per_person_rate" value="{{ old('per_person_rate') }}" min="0" step="0.01" required
                            class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('per_person_rate') border-red-500 @enderror">
                    </div>
                    @error('per_person_rate')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        
        {{-- Rules & Photo --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-md">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center">
                <i data-lucide="file-text" class="w-6 h-6 mr-gr-sm"></i>
                Additional Information
            </h2>

            <div class="space-y-gr-md">
                
                {{-- Facility Photo --}}
                <div>
                    <label for="image_path" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Facility Photo
                    </label>
                    <input type="file" id="image_path" name="image_path" accept="image/jpeg,image/png,image/jpg"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('image_path') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Accepted formats: JPG, JPEG, PNG. Max size: 2MB</p>
                    @error('image_path')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-gr-sm">
            <a href="{{ URL::signedRoute('admin.facilities.index') }}" class="inline-flex items-center px-gr-lg py-gr-sm bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-gr-lg py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                <i data-lucide="save" class="w-5 h-5 mr-gr-xs"></i>
                Create Facility
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
@endpush
@endsection

