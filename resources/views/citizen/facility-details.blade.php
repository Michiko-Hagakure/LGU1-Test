@extends('layouts.citizen')

@push('styles')
<!-- Pannellum CSS for 360° Panorama Viewer -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css">
@endpush

@section('title', $facility->name)
@section('page-title', $facility->name)
@section('page-subtitle', 'Facility Details')

@section('page-content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ URL::signedRoute('citizen.browse-facilities') }}" 
           class="inline-flex items-center text-lgu-button hover:text-lgu-highlight font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
            </svg>
            Back to Browse Facilities
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Facility Image Gallery / Virtual Tour -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="relative">
                    @if($facility->image_path)
                        <img id="mainImage" src="{{ asset('storage/' . $facility->image_path) }}" 
                             alt="{{ $facility->name }}" 
                             class="w-full h-96 object-cover">
                        
                        <!-- Virtual Tour Badge / 360° Toggle -->
                        <div class="absolute top-4 left-4 flex gap-2">
                            <button onclick="togglePanorama()" id="panoramaToggle" 
                                    class="bg-lgu-button text-lgu-button-text px-4 py-2 rounded-lg shadow-lg font-semibold text-sm flex items-center gap-2 hover:bg-lgu-highlight transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/>
                                </svg>
                                <span id="panoramaBtnText">360° View</span>
                            </button>
                        </div>
                        
                        <!-- Share Button -->
                        <div class="absolute top-4 right-20 mr-2">
                            <button onclick="toggleShareMenu()" class="bg-white/90 hover:bg-white text-gray-800 rounded-lg px-3 py-2 shadow-lg transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.59 13.51 6.83 3.98"/><path d="m15.41 6.51-6.82 3.98"/>
                                </svg>
                                <span class="text-sm font-semibold">Share</span>
                            </button>
                            <!-- Share Menu Dropdown -->
                            <div id="shareMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                <button onclick="shareToFacebook()" class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 flex items-center gap-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    Facebook
                                </button>
                                <button onclick="shareToTwitter()" class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 flex items-center gap-3">
                                    <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    X (Twitter)
                                </button>
                                <button onclick="copyLink()" class="w-full px-4 py-2 text-left text-sm hover:bg-gray-50 flex items-center gap-3">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                    Copy Link
                                </button>
                            </div>
                        </div>
                        
                        <!-- Navigation Controls -->
                        <button onclick="previousImage()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 rounded-full p-3 shadow-lg transition-all">
                            <i data-lucide="chevron-left" class="w-6 h-6"></i>
                        </button>
                        <button onclick="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 rounded-full p-3 shadow-lg transition-all">
                            <i data-lucide="chevron-right" class="w-6 h-6"></i>
                        </button>
                        
                        <!-- Fullscreen Button -->
                        <button onclick="openFullscreen()" class="absolute top-4 right-4 bg-white/90 hover:bg-white text-gray-800 rounded-lg px-3 py-2 shadow-lg transition-all flex items-center gap-2">
                            <i data-lucide="maximize" class="w-4 h-4"></i>
                            <span class="text-sm font-semibold">Fullscreen</span>
                        </button>
                    @else
                        <div class="w-full h-96 bg-lgu-bg flex items-center justify-center">
                            <div class="text-center">
                                <i data-lucide="image-off" class="w-32 h-32 text-gray-300 mx-auto mb-4"></i>
                                <p class="text-gray-500 font-medium">No photos available</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Photo Thumbnails -->
                @if($facility->image_path)
                <div class="bg-gray-50 p-4">
                    <div class="flex gap-2 overflow-x-auto">
                        <button onclick="changeImage(0)" class="thumbnail-btn flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 border-lgu-button transition-all">
                            <img src="{{ asset('storage/' . $facility->image_path) }}" alt="View 1" class="w-full h-full object-cover">
                        </button>
                        <!-- Additional photos would go here if available -->
                        @for($i = 1; $i < 4; $i++)
                        <button onclick="changeImage({{ $i }})" class="thumbnail-btn flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 border-gray-300 hover:border-lgu-highlight transition-all opacity-50">
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i data-lucide="camera" class="w-6 h-6 text-gray-400"></i>
                            </div>
                        </button>
                        @endfor
                    </div>
                </div>
                @endif
            </div>

            @push('scripts')
            <!-- Pannellum JS for 360° Panorama Viewer -->
            <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
            <script>
            // Virtual Tour / Photo Gallery
            let currentImageIndex = 0;
            const images = ['{{ asset('storage/' . $facility->image_path) }}']; // In production, this would be an array of all facility photos

            function changeImage(index) {
                currentImageIndex = index;
                const mainImage = document.getElementById('mainImage');
                if (mainImage && images[index]) {
                    mainImage.src = images[index];
                    
                    // Update thumbnail borders
                    document.querySelectorAll('.thumbnail-btn').forEach((btn, i) => {
                        if (i === index) {
                            btn.classList.remove('border-gray-300');
                            btn.classList.add('border-lgu-button');
                            btn.classList.remove('opacity-50');
                        } else {
                            btn.classList.add('border-gray-300');
                            btn.classList.remove('border-lgu-button');
                            btn.classList.add('opacity-50');
                        }
                    });
                }
            }

            function previousImage() {
                currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
                changeImage(currentImageIndex);
            }

            function nextImage() {
                currentImageIndex = (currentImageIndex + 1) % images.length;
                changeImage(currentImageIndex);
            }

            function openFullscreen() {
                const container = document.getElementById('panoramaContainer') || document.getElementById('mainImage');
                if (container) {
                    if (container.requestFullscreen) {
                        container.requestFullscreen();
                    } else if (container.webkitRequestFullscreen) {
                        container.webkitRequestFullscreen();
                    } else if (container.msRequestFullscreen) {
                        container.msRequestFullscreen();
                    }
                }
            }
            
            // 360° Panorama Viewer
            let panoramaViewer = null;
            let isPanoramaMode = false;
            
            function togglePanorama() {
                const mainImage = document.getElementById('mainImage');
                const btnText = document.getElementById('panoramaBtnText');
                
                if (!isPanoramaMode) {
                    // Switch to 360° mode
                    isPanoramaMode = true;
                    btnText.textContent = 'Photo View';
                    
                    // Create panorama container
                    const container = document.createElement('div');
                    container.id = 'panoramaContainer';
                    container.className = 'w-full h-96';
                    mainImage.parentNode.insertBefore(container, mainImage);
                    mainImage.style.display = 'none';
                    
                    // Initialize Pannellum viewer
                    panoramaViewer = pannellum.viewer('panoramaContainer', {
                        type: 'equirectangular',
                        panorama: mainImage.src,
                        autoLoad: true,
                        compass: true,
                        showControls: true,
                        mouseZoom: true,
                        draggable: true,
                        hfov: 100,
                        pitch: 0,
                        yaw: 0,
                        autoRotate: -2,
                        autoRotateInactivityDelay: 3000
                    });
                } else {
                    // Switch back to photo mode
                    isPanoramaMode = false;
                    btnText.textContent = '360° View';
                    
                    // Destroy panorama viewer
                    if (panoramaViewer) {
                        panoramaViewer.destroy();
                        panoramaViewer = null;
                    }
                    
                    // Remove panorama container and show image
                    const container = document.getElementById('panoramaContainer');
                    if (container) container.remove();
                    mainImage.style.display = 'block';
                }
            }
            
            // Share Menu Functions
            function toggleShareMenu() {
                const menu = document.getElementById('shareMenu');
                menu.classList.toggle('hidden');
            }
            
            // Close share menu when clicking outside
            document.addEventListener('click', function(e) {
                const menu = document.getElementById('shareMenu');
                const shareBtn = e.target.closest('button[onclick="toggleShareMenu()"]');
                if (!shareBtn && menu && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
            
            function shareToFacebook() {
                const url = encodeURIComponent(window.location.href);
                const title = encodeURIComponent('{{ $facility->name }} - LGU1 Facilities');
                window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
                toggleShareMenu();
            }
            
            function shareToTwitter() {
                const url = encodeURIComponent(window.location.href);
                const text = encodeURIComponent('Check out {{ $facility->name }} at LGU1 Facilities Reservation System!');
                window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
                toggleShareMenu();
            }
            
            function copyLink() {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    // Show toast notification
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2';
                    toast.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg> Link copied to clipboard!';
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 3000);
                });
                toggleShareMenu();
            }
            </script>
            @endpush

            <!-- Facility Description -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Facility</h2>
                <p class="text-gray-700 leading-relaxed">{{ $facility->description }}</p>
            </div>

            <!-- Facility Details -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Facility Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lgu-button mr-3 mt-1 flex-shrink-0">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Location</p>
                            <p class="text-base font-semibold text-gray-900">{{ $facility->address }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lgu-button mr-3 mt-1 flex-shrink-0">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Capacity</p>
                            <p class="text-base font-semibold text-gray-900">{{ $facility->capacity }} people</p>
                        </div>
                    </div>

                    @if($facility->per_person_rate)
                        <div class="flex items-start">
                            <div class="w-5 h-5 flex items-center justify-center text-lgu-button font-bold text-lg mr-3 mt-1 flex-shrink-0">
                                ₱
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Per Person Rate</p>
                                <p class="text-base font-semibold text-gray-900">₱{{ number_format($facility->per_person_rate, 2) }}</p>
                            </div>
                        </div>
                    @endif

                    @if($facility->hourly_rate)
                        <div class="flex items-start">
                            <div class="w-5 h-5 flex items-center justify-center text-lgu-button font-bold text-lg mr-3 mt-1 flex-shrink-0">
                                ₱
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Hourly Rate</p>
                                <p class="text-base font-semibold text-gray-900">₱{{ number_format($facility->hourly_rate, 2) }}</p>
                            </div>
                        </div>
                    @endif

                    @if($facility->deposit_amount)
                        <div class="flex items-start">
                            <div class="w-5 h-5 flex items-center justify-center text-lgu-button font-bold text-lg mr-3 mt-1 flex-shrink-0">
                                ₱
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Deposit Required</p>
                                <p class="text-base font-semibold text-gray-900">₱{{ number_format($facility->deposit_amount, 2) }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Availability Notice -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Check Availability</h2>
                <p class="text-gray-600 mb-4">View the facility calendar to see available dates and times for booking.</p>
                <a href="{{ URL::signedRoute('citizen.facility-calendar', ['facility_id' => $facility->facility_id]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-highlight transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                        <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                    </svg>
                    View Calendar
                </a>
            </div>
        </div>

        <!-- Sidebar: Booking Information -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6 sticky top-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Book This Facility</h3>
                
                <!-- Pricing Summary -->
                <div class="space-y-3 mb-6">
                    @if($facility->per_person_rate)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Per Person:</span>
                            <span class="font-bold text-gray-900">₱{{ number_format($facility->per_person_rate, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Payment Type:</span>
                            <span class="text-gray-900">Per attendee</span>
                        </div>
                    @elseif($facility->hourly_rate)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Hourly Rate:</span>
                            <span class="font-bold text-gray-900">₱{{ number_format($facility->hourly_rate, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Min Duration:</span>
                            <span class="text-gray-900">{{ $facility->min_booking_hours }} hours</span>
                        </div>
                    @endif
                    @if($facility->deposit_amount)
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Deposit:</span>
                                <span class="text-gray-900">₱{{ number_format($facility->deposit_amount, 2) }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Discount Information -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="text-sm font-bold text-blue-900 mb-2">Available Discounts</h4>
                    <ul class="text-xs text-blue-800 space-y-1">
                        <li>• Senior Citizen: 20% off (with valid ID)</li>
                        <li>• PWD: 20% off (with valid ID)</li>
                        <li>• Student: 10% off (with valid school ID)</li>
                    </ul>
                </div>

                <!-- Booking Requirements -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h4 class="text-sm font-bold text-yellow-900 mb-2">Requirements</h4>
                    <ul class="text-xs text-yellow-800 space-y-1">
                        <li>• Book 7 days in advance</li>
                        <li>• Valid government ID required</li>
                        <li>• Staff approval needed</li>
                    </ul>
                </div>

                <!-- Book Now Button -->
                <a href="{{ URL::signedRoute('citizen.booking.create', $facility->facility_id) }}" 
                   class="block w-full px-6 py-3 bg-lgu-button text-lgu-button-text text-center font-semibold rounded-lg hover:bg-lgu-highlight transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block mr-2">
                        <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/>
                    </svg>
                    Book Now
                </a>

                <a href="{{ URL::signedRoute('citizen.facility-calendar', ['facility_id' => $facility->facility_id]) }}" 
                   class="block w-full mt-3 px-6 py-3 bg-gray-200 text-gray-700 text-center font-semibold rounded-lg hover:bg-gray-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block mr-2">
                        <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                    </svg>
                    Check Availability
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

