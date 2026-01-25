@extends('layouts.citizen')

@section('title', 'My Favorites')
@section('page-title', 'My Favorite Facilities')
@section('page-subtitle', 'Quick access to your saved facilities')

@section('page-content')
<div class="space-y-gr-lg">
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-gr-sm">
            <i data-lucide="heart" class="w-6 h-6 text-lgu-tertiary"></i>
            <span class="text-2xl font-bold text-lgu-headline">{{ $favorites->total() }}</span>
            <span class="text-lgu-paragraph">Favorites</span>
        </div>
        
        @if($favorites->isNotEmpty())
            <button onclick="shareFavorites()" class="bg-lgu-button text-lgu-button-text px-gr-md py-gr-sm rounded-lg font-semibold hover:opacity-90 transition-all flex items-center gap-2">
                <i data-lucide="share-2" class="w-4 h-4"></i>
                <span>Share My Favorites</span>
            </button>
        @endif
    </div>
    
    @push('scripts')
    <script>
    function shareFavorites() {
        const facilityIds = @json($favorites->pluck('facility_id')->toArray());
        const shareUrl = `{{ route('citizen.browse-facilities') }}?favorites=${facilityIds.join(',')}`;
        
        Swal.fire({
            title: 'Share Your Favorites',
            html: `
                <div class="text-left space-y-4">
                    <p class="text-sm text-gray-600">Share this link with others to show them your favorite facilities:</p>
                    <div class="bg-gray-100 p-3 rounded-lg">
                        <input type="text" id="shareUrlInput" value="${shareUrl}" 
                               class="w-full bg-transparent text-sm font-mono text-gray-800 outline-none" 
                               readonly onclick="this.select()">
                    </div>
                    <div class="flex gap-2">
                        <button onclick="copyShareUrl()" class="flex-1 bg-lgu-button text-lgu-button-text px-4 py-2 rounded-lg font-semibold hover:opacity-90 transition-all flex items-center justify-center gap-2">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                            Copy Link
                        </button>
                        <a href="mailto:?subject=Check out my favorite facilities&body=${encodeURIComponent('I wanted to share my favorite public facilities with you: ' + shareUrl)}" 
                           class="flex-1 bg-lgu-headline text-white px-4 py-2 rounded-lg font-semibold hover:opacity-90 transition-all flex items-center justify-center gap-2">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            Email
                        </a>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            width: '600px',
            didOpen: () => {
                lucide.createIcons();
            }
        });
    }
    
    function copyShareUrl() {
        const input = document.getElementById('shareUrlInput');
        input.select();
        document.execCommand('copy');
        
        Swal.fire({
            icon: 'success',
            title: 'Link Copied!',
            text: 'Share link has been copied to your clipboard',
            timer: 2000,
            showConfirmButton: false,
            confirmButtonColor: '#faae2b'
        });
    }
    </script>
    @endpush

    @if($favorites->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-gr-xl text-center">
                <i data-lucide="heart" class="w-16 h-16 mx-auto mb-gr-md text-gray-300"></i>
                <h3 class="text-xl font-semibold text-lgu-headline mb-gr-xs">No favorites yet</h3>
                <p class="text-lgu-paragraph mb-gr-lg">Start adding facilities to your favorites for quick access</p>
                <a href="{{ route('citizen.browse-facilities') }}" 
                   class="inline-block bg-lgu-button text-lgu-button-text px-gr-lg py-gr-sm rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                    <i data-lucide="building-2" class="w-4 h-4 inline mr-2"></i>
                    Browse Facilities
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gr-lg">
                @foreach($favorites as $facility)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="relative h-48 bg-gray-200">
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-lgu-bg to-gray-200">
                                <i data-lucide="building-2" class="w-16 h-16 text-gray-400"></i>
                            </div>
                            
                            <div class="absolute top-3 right-3 flex gap-2">
                                <button onclick="openNotificationSettings({{ $facility->facility_id }}, {{ json_encode($facility->pivot ?? new stdClass()) }})" 
                                        class="bg-white rounded-full p-2 shadow-md hover:bg-blue-50 transition-all" title="Notification Settings">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                                    </svg>
                                </button>
                                <button onclick="removeFavorite({{ $facility->facility_id }})" 
                                        class="favorite-btn bg-white rounded-full p-2 shadow-md hover:bg-lgu-tertiary transition-all"
                                        data-facility-id="{{ $facility->facility_id }}">
                                    <i data-lucide="heart" class="w-5 h-5 fill-lgu-tertiary text-lgu-tertiary"></i>
                                </button>
                            </div>

                            @if($facility->rating)
                                <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full flex items-center gap-1">
                                    <i data-lucide="star" class="w-4 h-4 fill-lgu-button text-lgu-button"></i>
                                    <span class="text-sm font-semibold">{{ number_format($facility->rating, 1) }}</span>
                                </div>
                            @endif

                            @if(isset($facility->favorited_at))
                                <div class="absolute bottom-3 right-3 bg-lgu-highlight/90 backdrop-blur-sm px-3 py-1 rounded-full">
                                    <span class="text-xs font-semibold text-lgu-button-text">
                                        Added {{ $facility->favorited_at->diffForHumans() }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="p-gr-lg">
                            <div class="mb-gr-sm">
                                <h3 class="text-xl font-bold text-lgu-headline">{{ $facility->name }}</h3>
                                @if($facility->lguCity)
                                    <p class="text-sm text-lgu-paragraph">
                                        <i data-lucide="map-pin" class="w-4 h-4 inline mr-1"></i>
                                        {{ $facility->lguCity->city_name }}
                                    </p>
                                @endif
                            </div>

                            <p class="text-sm text-lgu-paragraph mb-gr-md line-clamp-2">
                                {{ $facility->description ?? 'No description available' }}
                            </p>

                            <div class="space-y-gr-xs mb-gr-md">
                                <div class="flex items-center text-sm text-lgu-paragraph">
                                    <i data-lucide="users" class="w-4 h-4 mr-2 flex-shrink-0"></i>
                                    <span>Capacity: {{ number_format($facility->capacity) }} people</span>
                                </div>
                                @if($facility->per_person_rate)
                                    <div class="flex items-center text-sm text-lgu-paragraph">
                                        <i data-lucide="banknote" class="w-4 h-4 mr-2 flex-shrink-0"></i>
                                        <span>₱{{ number_format($facility->per_person_rate, 2) }}/person</span>
                                    </div>
                                @elseif($facility->hourly_rate)
                                    <div class="flex items-center text-sm text-lgu-paragraph">
                                        <i data-lucide="clock" class="w-4 h-4 mr-2 flex-shrink-0"></i>
                                        <span>₱{{ number_format($facility->hourly_rate, 2) }}/hour</span>
                                    </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-gr-xs">
                                <a href="{{ route('citizen.facility-details', $facility->facility_id) }}" 
                                   class="text-center bg-lgu-button text-lgu-button-text px-gr-md py-gr-xs rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                                    View Details
                                </a>
                                <a href="{{ route('citizen.booking.create', $facility->facility_id) }}" 
                                   class="text-center bg-lgu-headline text-white px-gr-md py-gr-xs rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        <div class="mt-gr-xl">
            {{ $favorites->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
function openNotificationSettings(facilityId, settings) {
    const notifyUpdates = settings?.notify_updates ?? true;
    const notifyAvailability = settings?.notify_availability ?? true;
    const notifyPriceChanges = settings?.notify_price_changes ?? false;
    
    Swal.fire({
        title: 'Notification Settings',
        html: `
            <div class="text-left space-y-4">
                <p class="text-sm text-gray-600 mb-4">Choose what updates you want to receive for this facility:</p>
                
                <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                        <div>
                            <span class="font-semibold text-gray-800">General Updates</span>
                            <p class="text-xs text-gray-500">News, changes, and announcements</p>
                        </div>
                    </div>
                    <input type="checkbox" id="notifyUpdates" ${notifyUpdates ? 'checked' : ''} 
                           class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                </label>
                
                <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                        </svg>
                        <div>
                            <span class="font-semibold text-gray-800">Availability Alerts</span>
                            <p class="text-xs text-gray-500">When slots become available</p>
                        </div>
                    </div>
                    <input type="checkbox" id="notifyAvailability" ${notifyAvailability ? 'checked' : ''} 
                           class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                </label>
                
                <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                        <div>
                            <span class="font-semibold text-gray-800">Price Changes</span>
                            <p class="text-xs text-gray-500">Rate updates and special offers</p>
                        </div>
                    </div>
                    <input type="checkbox" id="notifyPriceChanges" ${notifyPriceChanges ? 'checked' : ''} 
                           class="w-5 h-5 text-yellow-600 rounded focus:ring-yellow-500">
                </label>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Save Settings',
        confirmButtonColor: '#faae2b',
        cancelButtonColor: '#6b7280',
        preConfirm: () => {
            return {
                notify_updates: document.getElementById('notifyUpdates').checked,
                notify_availability: document.getElementById('notifyAvailability').checked,
                notify_price_changes: document.getElementById('notifyPriceChanges').checked
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            saveNotificationSettings(facilityId, result.value);
        }
    });
}

function saveNotificationSettings(facilityId, settings) {
    fetch(`/citizen/favorites/${facilityId}/notifications`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Settings Saved!',
                text: 'Your notification preferences have been updated',
                icon: 'success',
                confirmButtonColor: '#faae2b',
                timer: 2000,
                showConfirmButton: false
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'Failed to save notification settings',
            icon: 'error',
            confirmButtonColor: '#fa5246'
        });
    });
}

function removeFavorite(facilityId) {
    Swal.fire({
        title: 'Remove from Favorites?',
        text: 'This facility will be removed from your favorites list',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#faae2b',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, remove it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/citizen/favorites/${facilityId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Removed!',
                        text: `${data.facility_name} has been removed from favorites`,
                        icon: 'success',
                        confirmButtonColor: '#faae2b',
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to remove favorite',
                    icon: 'error',
                    confirmButtonColor: '#fa5246'
                });
            });
        }
    });
}
</script>
@endpush
@endsection
