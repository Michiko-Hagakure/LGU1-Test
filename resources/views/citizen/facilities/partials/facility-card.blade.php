@if($view === 'grid')
    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <div class="relative h-48 bg-gray-200">
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-lgu-bg to-gray-200">
                <i data-lucide="building-2" class="w-16 h-16 text-gray-400"></i>
            </div>
            
            <button onclick="toggleFavorite({{ $facility->id }})" 
                    class="favorite-btn absolute top-3 right-3 bg-white rounded-full p-2 shadow-md hover:bg-lgu-highlight transition-all"
                    data-facility-id="{{ $facility->id }}">
                <i data-lucide="heart" class="w-5 h-5 {{ auth()->check() && auth()->user()->hasFavorited($facility->id) ? 'fill-lgu-tertiary text-lgu-tertiary' : 'text-gray-600' }}"></i>
            </button>

            @if($facility->rating)
                <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full flex items-center gap-1">
                    <i data-lucide="star" class="w-4 h-4 fill-lgu-button text-lgu-button"></i>
                    <span class="text-sm font-semibold">{{ number_format($facility->rating, 1) }}</span>
                </div>
            @endif
        </div>

        <div class="p-gr-lg">
            <div class="mb-gr-sm">
                <span class="inline-block px-3 py-1 bg-lgu-highlight/10 text-lgu-button-text text-xs font-semibold rounded-full mb-gr-xs">
                    {{ ucwords(str_replace('_', ' ', $facility->facility_type)) }}
                </span>
                <h3 class="text-xl font-bold text-lgu-headline">{{ $facility->facility_name }}</h3>
            </div>

            <p class="text-sm text-lgu-paragraph mb-gr-md line-clamp-2">
                {{ $facility->description ?? 'No description available' }}
            </p>

            <div class="space-y-gr-xs mb-gr-md">
                @if($facility->city)
                    <div class="flex items-center text-sm text-lgu-paragraph">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2 flex-shrink-0"></i>
                        <span>{{ $facility->city }}</span>
                    </div>
                @endif
                <div class="flex items-center text-sm text-lgu-paragraph">
                    <i data-lucide="users" class="w-4 h-4 mr-2 flex-shrink-0"></i>
                    <span>Capacity: {{ $facility->capacity }} people</span>
                </div>
                <div class="flex items-center text-sm text-lgu-paragraph">
                    <i data-lucide="eye" class="w-4 h-4 mr-2 flex-shrink-0"></i>
                    <span>{{ number_format($facility->view_count) }} views</span>
                </div>
            </div>

            <div class="flex items-center justify-between pt-gr-md border-t border-gray-100">
                <div>
                    <span class="text-2xl font-bold text-lgu-headline">₱{{ number_format($facility->hourly_rate, 2) }}</span>
                    <span class="text-sm text-lgu-paragraph">/hour</span>
                </div>
                <a href="{{ route('citizen.facility-details', $facility->id) }}" 
                   class="bg-lgu-button text-lgu-button-text px-gr-md py-gr-xs rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                    View Details
                </a>
            </div>
        </div>
    </div>
@else
    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <div class="flex flex-col md:flex-row">
            <div class="relative w-full md:w-64 h-48 bg-gray-200">
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-lgu-bg to-gray-200">
                    <i data-lucide="building-2" class="w-16 h-16 text-gray-400"></i>
                </div>
                
                <button onclick="toggleFavorite({{ $facility->id }})" 
                        class="favorite-btn absolute top-3 right-3 bg-white rounded-full p-2 shadow-md hover:bg-lgu-highlight transition-all"
                        data-facility-id="{{ $facility->id }}">
                    <i data-lucide="heart" class="w-5 h-5 {{ auth()->check() && auth()->user()->hasFavorited($facility->id) ? 'fill-lgu-tertiary text-lgu-tertiary' : 'text-gray-600' }}"></i>
                </button>

                @if($facility->rating)
                    <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full flex items-center gap-1">
                        <i data-lucide="star" class="w-4 h-4 fill-lgu-button text-lgu-button"></i>
                        <span class="text-sm font-semibold">{{ number_format($facility->rating, 1) }}</span>
                    </div>
                @endif
            </div>

            <div class="flex-1 p-gr-lg">
                <div class="flex justify-between items-start mb-gr-sm">
                    <div>
                        <span class="inline-block px-3 py-1 bg-lgu-highlight/10 text-lgu-button-text text-xs font-semibold rounded-full mb-gr-xs">
                            {{ ucwords(str_replace('_', ' ', $facility->facility_type)) }}
                        </span>
                        <h3 class="text-xl font-bold text-lgu-headline">{{ $facility->facility_name }}</h3>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold text-lgu-headline">₱{{ number_format($facility->hourly_rate, 2) }}</span>
                        <span class="text-sm text-lgu-paragraph block">/hour</span>
                    </div>
                </div>

                <p class="text-sm text-lgu-paragraph mb-gr-md line-clamp-2">
                    {{ $facility->description ?? 'No description available' }}
                </p>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-gr-md mb-gr-md">
                    @if($facility->city)
                        <div class="flex items-center text-sm text-lgu-paragraph">
                            <i data-lucide="map-pin" class="w-4 h-4 mr-2 flex-shrink-0"></i>
                            <span>{{ $facility->city }}</span>
                        </div>
                    @endif
                    <div class="flex items-center text-sm text-lgu-paragraph">
                        <i data-lucide="users" class="w-4 h-4 mr-2 flex-shrink-0"></i>
                        <span>{{ $facility->capacity }} people</span>
                    </div>
                    <div class="flex items-center text-sm text-lgu-paragraph">
                        <i data-lucide="eye" class="w-4 h-4 mr-2 flex-shrink-0"></i>
                        <span>{{ number_format($facility->view_count) }} views</span>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('citizen.facilities.browse.show', $facility->id) }}" 
                       class="bg-lgu-button text-lgu-button-text px-gr-lg py-gr-sm rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                        View Details
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
