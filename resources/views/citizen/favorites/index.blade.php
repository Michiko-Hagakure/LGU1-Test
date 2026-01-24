@extends('layouts.citizen')

@section('title', 'My Favorites')
@section('page-title', 'My Favorite Facilities')
@section('page-subtitle', 'Quick access to your saved facilities')

@section('page-content')
<div class="space-y-gr-lg">
    <div class="flex justify-end items-center">
        <div class="flex items-center gap-gr-sm">
            <i data-lucide="heart" class="w-6 h-6 text-lgu-tertiary"></i>
            <span class="text-2xl font-bold text-lgu-headline">{{ $favorites->total() }}</span>
            <span class="text-lgu-paragraph">Favorites</span>
        </div>
    </div>

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
                            
                            <button onclick="removeFavorite({{ $facility->facility_id }})" 
                                    class="favorite-btn absolute top-3 right-3 bg-white rounded-full p-2 shadow-md hover:bg-lgu-tertiary transition-all"
                                    data-facility-id="{{ $facility->facility_id }}">
                                <i data-lucide="heart" class="w-5 h-5 fill-lgu-tertiary text-lgu-tertiary"></i>
                            </button>

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
