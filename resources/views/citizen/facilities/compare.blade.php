@extends('layouts.citizen')

@section('title', 'Compare Facilities')

@section('content')
<div class="min-h-screen bg-lgu-bg">
    <div class="p-gr-lg">
        <div class="mb-gr-xl">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-lgu-headline mb-gr-xs">Compare Facilities</h1>
                    <p class="text-lgu-paragraph">Side-by-side comparison of up to 3 facilities</p>
                </div>
                <a href="{{ route('citizen.facilities.browse') }}" class="bg-gray-200 text-gray-700 px-gr-lg py-gr-sm rounded-lg font-semibold hover:bg-gray-300 transition-all">
                    <i data-lucide="arrow-left" class="w-4 h-4 inline mr-2"></i>
                    Back to Browse
                </a>
            </div>
        </div>

        @if($facilities->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-gr-xl text-center">
                <i data-lucide="scale" class="w-16 h-16 mx-auto mb-gr-md text-gray-300"></i>
                <h3 class="text-xl font-semibold text-lgu-headline mb-gr-xs">No facilities selected</h3>
                <p class="text-lgu-paragraph mb-gr-lg">Please select 2-3 facilities to compare</p>
                <a href="{{ route('citizen.facilities.browse') }}" class="inline-block bg-lgu-button text-lgu-button-text px-gr-lg py-gr-sm rounded-lg font-semibold hover:bg-opacity-90">
                    Browse Facilities
                </a>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm overflow-hidden overflow-x-auto">
                <table class="w-full min-w-[800px]">
                    <thead>
                        <tr class="bg-lgu-headline text-white">
                            <th class="px-gr-lg py-gr-md text-left font-semibold text-small w-1/4">Feature</th>
                            @foreach($facilities as $facility)
                                <th class="px-gr-lg py-gr-md text-center font-semibold text-small">
                                    <div class="space-y-2">
                                        <div class="text-lg">{{ $facility->facility_name }}</div>
                                        <span class="inline-block px-3 py-1 bg-lgu-highlight/20 text-xs rounded-full">
                                            {{ ucwords(str_replace('_', ' ', $facility->facility_type)) }}
                                        </span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-100">
                            <td class="px-gr-lg py-gr-md font-semibold text-lgu-headline">Image</td>
                            @foreach($facilities as $facility)
                                <td class="px-gr-lg py-gr-md text-center">
                                    <div class="relative h-48 bg-gray-200 rounded-lg overflow-hidden mx-auto">
                                        @if($facility->photos && $facility->photos->first())
                                            <img src="{{ asset('storage/' . $facility->photos->first()->photo_path) }}" 
                                                 alt="{{ $facility->facility_name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i data-lucide="building-2" class="w-12 h-12 text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        </tr>

                        <tr class="border-b border-gray-100 bg-gray-50">
                            <td class="px-gr-lg py-gr-md font-semibold text-lgu-headline">Hourly Rate</td>
                            @foreach($facilities as $facility)
                                <td class="px-gr-lg py-gr-md text-center">
                                    <div class="text-2xl font-bold text-lgu-headline">₱{{ number_format($facility->hourly_rate, 2) }}</div>
                                    <span class="text-sm text-lgu-paragraph">/hour</span>
                                </td>
                            @endforeach
                        </tr>

                        <tr class="border-b border-gray-100">
                            <td class="px-gr-lg py-gr-md font-semibold text-lgu-headline">Capacity</td>
                            @foreach($facilities as $facility)
                                <td class="px-gr-lg py-gr-md text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <i data-lucide="users" class="w-5 h-5 text-lgu-paragraph"></i>
                                        <span class="text-lg font-semibold">{{ $facility->capacity }}</span>
                                        <span class="text-sm text-lgu-paragraph">people</span>
                                    </div>
                                </td>
                            @endforeach
                        </tr>

                        @if($facilities->first()->city)
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <td class="px-gr-lg py-gr-md font-semibold text-lgu-headline">Location</td>
                                @foreach($facilities as $facility)
                                    <td class="px-gr-lg py-gr-md text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <i data-lucide="map-pin" class="w-5 h-5 text-lgu-paragraph"></i>
                                            <span>{{ $facility->city ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endif

                        @if($facilities->first()->rating)
                            <tr class="border-b border-gray-100">
                                <td class="px-gr-lg py-gr-md font-semibold text-lgu-headline">Rating</td>
                                @foreach($facilities as $facility)
                                    <td class="px-gr-lg py-gr-md text-center">
                                        @if($facility->rating)
                                            <div class="flex items-center justify-center gap-2">
                                                <i data-lucide="star" class="w-5 h-5 fill-lgu-button text-lgu-button"></i>
                                                <span class="text-lg font-semibold">{{ number_format($facility->rating, 1) }}</span>
                                            </div>
                                        @else
                                            <span class="text-lgu-paragraph">Not rated</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endif

                        <tr class="border-b border-gray-100 bg-gray-50">
                            <td class="px-gr-lg py-gr-md font-semibold text-lgu-headline">Deposit Required</td>
                            @foreach($facilities as $facility)
                                <td class="px-gr-lg py-gr-md text-center">
                                    @if($facility->deposit_amount && $facility->deposit_amount > 0)
                                        <div>
                                            <i data-lucide="check-circle" class="w-6 h-6 text-green-500 inline"></i>
                                            <div class="text-sm text-lgu-paragraph mt-1">₱{{ number_format($facility->deposit_amount, 2) }}</div>
                                        </div>
                                    @else
                                        <i data-lucide="x-circle" class="w-6 h-6 text-gray-400 inline"></i>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        <tr class="border-b border-gray-100">
                            <td class="px-gr-lg py-gr-md font-semibold text-lgu-headline">Min Booking Hours</td>
                            @foreach($facilities as $facility)
                                <td class="px-gr-lg py-gr-md text-center">
                                    <span class="text-lg font-semibold">{{ $facility->min_booking_hours }}</span>
                                    <span class="text-sm text-lgu-paragraph">hours</span>
                                </td>
                            @endforeach
                        </tr>

                        <tr class="border-b border-gray-100 bg-gray-50">
                            <td class="px-gr-lg py-gr-md font-semibold text-lgu-headline">Max Booking Hours</td>
                            @foreach($facilities as $facility)
                                <td class="px-gr-lg py-gr-md text-center">
                                    <span class="text-lg font-semibold">{{ $facility->max_booking_hours }}</span>
                                    <span class="text-sm text-lgu-paragraph">hours</span>
                                </td>
                            @endforeach
                        </tr>

                        <tr class="border-b border-gray-100">
                            <td class="px-gr-lg py-gr-md font-semibold text-lgu-headline">Advance Booking</td>
                            @foreach($facilities as $facility)
                                <td class="px-gr-lg py-gr-md text-center">
                                    <span class="text-lg font-semibold">{{ $facility->advance_booking_days }}</span>
                                    <span class="text-sm text-lgu-paragraph">days ahead</span>
                                </td>
                            @endforeach
                        </tr>

                        <tr class="border-b border-gray-100 bg-gray-50">
                            <td class="px-gr-lg py-gr-md font-semibold text-lgu-headline">Amenities</td>
                            @foreach($facilities as $facility)
                                <td class="px-gr-lg py-gr-md">
                                    @if($facility->amenities && count($facility->amenities) > 0)
                                        <ul class="text-sm text-lgu-paragraph space-y-1 text-left">
                                            @foreach(array_slice($facility->amenities, 0, 5) as $amenity)
                                                <li class="flex items-start">
                                                    <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                                    <span>{{ $amenity }}</span>
                                                </li>
                                            @endforeach
                                            @if(count($facility->amenities) > 5)
                                                <li class="text-xs text-lgu-button font-semibold">+{{ count($facility->amenities) - 5 }} more</li>
                                            @endif
                                        </ul>
                                    @else
                                        <span class="text-sm text-lgu-paragraph">No amenities listed</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="px-gr-lg py-gr-lg font-semibold text-lgu-headline">Actions</td>
                            @foreach($facilities as $facility)
                                <td class="px-gr-lg py-gr-lg">
                                    <div class="space-y-gr-xs">
                                        <a href="{{ route('citizen.facilities.browse.show', $facility->id) }}" 
                                           class="block w-full text-center bg-lgu-button text-lgu-button-text px-gr-md py-gr-sm rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                                            View Details
                                        </a>
                                        <a href="{{ route('citizen.booking.create', $facility->id) }}" 
                                           class="block w-full text-center bg-lgu-headline text-white px-gr-md py-gr-sm rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                                            Book Now
                                        </a>
                                        <button onclick="toggleFavorite({{ $facility->id }})" 
                                                class="favorite-btn w-full text-center bg-gray-100 text-gray-700 px-gr-md py-gr-sm rounded-lg font-semibold hover:bg-gray-200 transition-all"
                                                data-facility-id="{{ $facility->id }}">
                                            <i data-lucide="heart" class="w-4 h-4 inline mr-2 {{ auth()->check() && auth()->user()->hasFavorited($facility->id) ? 'fill-lgu-tertiary text-lgu-tertiary' : '' }}"></i>
                                            {{ auth()->check() && auth()->user()->hasFavorited($facility->id) ? 'Favorited' : 'Add to Favorites' }}
                                        </button>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-gr-lg text-center">
                <a href="{{ route('citizen.facilities.browse') }}" class="inline-block bg-gray-200 text-gray-700 px-gr-lg py-gr-sm rounded-lg font-semibold hover:bg-gray-300 transition-all">
                    <i data-lucide="grid-3x3" class="w-4 h-4 inline mr-2"></i>
                    Compare Different Facilities
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
