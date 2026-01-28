@extends('layouts.admin')

@section('title', 'Edit Facility')
@section('page-title', 'Edit Facility')
@section('page-subtitle', 'Update facility information')

@section('page-content')
<div class="container mx-auto px-gr-md py-gr-lg max-w-4xl">
    {{-- Back Button --}}
    <div class="mb-gr-md">
        <a href="{{ route('admin.facilities.index') }}" class="inline-flex items-center text-lgu-paragraph hover:text-lgu-headline transition-colors duration-200">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-gr-xs"></i>
            Back to Facilities
        </a>
    </div>

    {{-- Page Header --}}
    <div class="mb-gr-lg">
        <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">Edit Facility</h1>
        <p class="text-body text-lgu-paragraph">Update facility information</p>
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
    <form action="{{ route('admin.facilities.update', $facility->facility_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                    <input type="text" id="name" name="name" value="{{ old('name', $facility->name) }}" required
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
                            <option value="{{ $city->id }}" {{ old('city_id', $facility->lgu_city_id) == $city->id ? 'selected' : '' }}>
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
                        <option value="1" {{ old('is_available', $facility->is_available) == 1 ? 'selected' : '' }}>Available</option>
                        <option value="0" {{ old('is_available', $facility->is_available) == 0 ? 'selected' : '' }}>Not Available</option>
                    </select>
                </div>

                {{-- Capacity --}}
                <div>
                    <label for="capacity" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        Capacity (persons) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $facility->capacity) }}" min="1" required
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
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $facility->description) }}</textarea>
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
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address', $facility->address) }}</textarea>
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
                        <input type="number" id="per_person_rate" name="per_person_rate" value="{{ old('per_person_rate', $facility->per_person_rate) }}" min="0" step="0.01" required
                            class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('per_person_rate') border-red-500 @enderror">
                    </div>
                    @error('per_person_rate')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        
        {{-- Photos Management --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-md">
            <h2 class="text-h3 font-bold text-lgu-headline mb-gr-md flex items-center">
                <i data-lucide="images" class="w-6 h-6 mr-gr-sm"></i>
                Facility Photos
            </h2>

            <div class="space-y-gr-md">
                
                {{-- Primary Photo --}}
                <div>
                    <label class="block text-small font-semibold text-lgu-headline mb-gr-xs">Primary Photo</label>
                    @if($facility->image_path)
                        <div class="flex items-start gap-3" id="primary-photo-container">
                            <div class="relative inline-block">
                                <img src="{{ Storage::url($facility->image_path) }}" alt="{{ $facility->name }}" 
                                    class="w-48 h-32 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition"
                                    onclick="showFullImage('{{ Storage::url($facility->image_path) }}', '{{ $facility->name }}')">
                                <span class="absolute top-2 left-2 bg-lgu-green text-white text-xs px-2 py-1 rounded">Primary</span>
                            </div>
                            <button type="button" 
                                onclick="deletePrimaryImage({{ $facility->facility_id }})"
                                class="flex items-center gap-1 px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                Remove
                            </button>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No primary photo uploaded</p>
                    @endif
                </div>

                {{-- Replace Primary Photo --}}
                <div>
                    <label for="image_path" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                        {{ $facility->image_path ? 'Replace Primary Photo' : 'Upload Primary Photo' }}
                    </label>
                    <input type="file" id="image_path" name="image_path" accept="image/jpeg,image/png,image/jpg"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('image_path') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Accepted formats: JPG, JPEG, PNG. Max size: 2MB</p>
                    @error('image_path')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Additional Photos --}}
                <div class="border-t pt-gr-md">
                    <label class="block text-small font-semibold text-lgu-headline mb-gr-xs">Additional Photos</label>
                    
                    @if($facilityImages->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            @foreach($facilityImages as $image)
                                <div class="relative group" id="image-{{ $image->id }}">
                                    <img src="{{ Storage::url($image->image_path) }}" alt="Facility image" 
                                        class="w-full h-24 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition"
                                        onclick="showFullImage('{{ Storage::url($image->image_path) }}', 'Facility Image')">
                                    <button type="button" 
                                        onclick="deleteImage({{ $facility->facility_id }}, {{ $image->id }})"
                                        class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                                        title="Remove image">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm mb-4">No additional photos uploaded</p>
                    @endif

                    {{-- Upload Additional Photos --}}
                    <div>
                        <label for="additional_images" class="block text-small font-semibold text-lgu-headline mb-gr-xs">
                            Add More Photos
                        </label>
                        <input type="file" id="additional_images" name="additional_images[]" accept="image/jpeg,image/png,image/jpg" multiple
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">You can select multiple images. Accepted formats: JPG, JPEG, PNG. Max size: 2MB each</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-gr-sm">
            <a href="{{ route('admin.facilities.index') }}" class="inline-flex items-center px-gr-lg py-gr-sm bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-gr-lg py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                <i data-lucide="save" class="w-5 h-5 mr-gr-xs"></i>
                Update Facility
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

// Show full image in SweetAlert2 modal
function showFullImage(imageUrl, title) {
    Swal.fire({
        title: title,
        imageUrl: imageUrl,
        imageAlt: title,
        showCloseButton: true,
        showConfirmButton: false,
        width: 'auto',
        customClass: {
            image: 'max-h-[80vh] object-contain rounded-lg'
        }
    });
}

// Delete facility image
function deleteImage(facilityId, imageId) {
    Swal.fire({
        title: 'Remove Image?',
        text: 'This image will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, remove it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/facilities/${facilityId}/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the image element from DOM
                    const imageElement = document.getElementById(`image-${imageId}`);
                    if (imageElement) {
                        imageElement.remove();
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Image has been removed.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to delete image.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to delete image. Please try again.'
                });
            });
        }
    });
}

// Delete primary image
function deletePrimaryImage(facilityId) {
    Swal.fire({
        title: 'Remove Primary Photo?',
        text: 'This photo will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, remove it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/facilities/${facilityId}/primary-image`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Replace photo container with "no photo" message
                    const container = document.getElementById('primary-photo-container');
                    if (container) {
                        container.outerHTML = '<p class="text-gray-500 text-sm">No primary photo uploaded</p>';
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Primary photo has been removed.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to delete photo.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to delete photo. Please try again.'
                });
            });
        }
    });
}
</script>
@endpush
@endsection

