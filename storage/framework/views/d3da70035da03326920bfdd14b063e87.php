

<?php $__env->startSection('title', 'Browse Facilities'); ?>
<?php $__env->startSection('page-title', 'Browse Facilities'); ?>
<?php $__env->startSection('page-subtitle', 'Discover and book public facilities'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-gr-lg" id="facilitiesContainer">
    <!-- View Toggle -->
    <div class="flex justify-end mb-gr-md">
        <div class="inline-flex rounded-lg border-2 border-lgu-stroke overflow-hidden">
            <button type="button" onclick="toggleView('grid')" id="gridViewBtn" class="px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                <span>Grid View</span>
            </button>
            <button type="button" onclick="toggleView('map')" id="mapViewBtn" class="px-gr-md py-gr-sm bg-white text-lgu-headline font-semibold transition-colors flex items-center gap-2 hover:bg-lgu-bg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Map View</span>
            </button>
        </div>
    </div>

    <?php if($sharedFavorites ?? false): ?>
    <!-- Shared Favorites Banner -->
    <div class="bg-lgu-tertiary/10 border border-lgu-tertiary rounded-xl p-gr-md flex items-center gap-3">
        <i data-lucide="heart" class="w-5 h-5 text-lgu-tertiary"></i>
        <span class="text-lgu-headline font-medium">You're viewing someone's shared favorite facilities</span>
        <a href="<?php echo e(route('citizen.browse-facilities')); ?>" class="ml-auto text-sm text-lgu-highlight hover:underline">View All Facilities</a>
    </div>
    <?php endif; ?>

    <!-- Search and Filters -->
    <div class="bg-white shadow rounded-xl p-gr-lg transition-all duration-300 hover:shadow-lg">
        <form method="GET" action="<?php echo e(route('citizen.browse-facilities')); ?>" class="space-y-gr-md" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gr-md">
                <!-- Search -->
                <div class="md:col-span-3 lg:col-span-2">
                    <label for="search" class="block text-small font-medium text-gray-700 mb-gr-xs">Search Facilities</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-gr-sm flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                               placeholder="Search by name, location, or description..." 
                               class="block w-full pl-10 pr-gr-sm py-gr-sm border-2 border-lgu-stroke rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-all duration-200 text-sm <?php echo e(request('search') ? 'border-lgu-highlight bg-lgu-bg' : ''); ?>">
                    </div>
                </div>

                <!-- City Filter -->
                <div>
                    <label for="city" class="block text-small font-medium text-gray-700 mb-gr-xs">
                        City/Municipality
                    </label>
                    <select name="city" id="city" 
                            class="block w-full px-gr-sm py-gr-sm border-2 border-lgu-stroke rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-all duration-200 text-sm <?php echo e(request('city') ? 'border-lgu-highlight bg-lgu-bg font-medium' : ''); ?>">
                        <option value="">All Cities</option>
                        <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($city); ?>" <?php echo e(request('city') == $city ? 'selected' : ''); ?>><?php echo e($city); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Capacity Filter -->
                <div>
                    <label for="capacity" class="block text-small font-medium text-gray-700 mb-gr-xs">
                        Min. Capacity
                    </label>
                    <input type="number" name="capacity" id="capacity" value="<?php echo e(request('capacity')); ?>" 
                           placeholder="e.g., 50" min="0"
                           class="block w-full px-gr-sm py-gr-sm border-2 border-lgu-stroke rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-all duration-200 text-sm <?php echo e(request('capacity') ? 'border-lgu-highlight bg-lgu-bg font-medium' : ''); ?>">
                </div>
            </div>

            <!-- Advanced Filters (Collapsible) -->
            <div class="border-t-2 border-lgu-stroke pt-gr-md">
                <button type="button" onclick="toggleAdvancedFilters()" class="flex items-center gap-2 text-lgu-headline font-semibold text-sm mb-gr-md hover:text-lgu-button transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 21v-7m0-4V3m8 18v-9m0-4V3m8 18v-5m0-4V3M2 14h4M10 8h4M18 16h4"/></svg>
                    <span>Advanced Filters</span>
                    <svg id="advancedFilterIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                
                <div id="advancedFilters" class="hidden space-y-gr-md">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-gr-md">
                        <!-- Price Range -->
                        <div>
                            <label for="min_price" class="block text-small font-medium text-gray-700 mb-gr-xs">
                                Min. Price
                            </label>
                            <input type="number" name="min_price" id="min_price" value="<?php echo e(request('min_price')); ?>" 
                                   placeholder="Min" class="w-full border-lgu-stroke rounded-lg px-gr-sm py-gr-xs">
                        </div>
                        <div>
                            <label for="max_price" class="block text-small font-medium text-gray-700 mb-gr-xs">
                                Max. Price
                            </label>
                            <input type="number" name="max_price" id="max_price" value="<?php echo e(request('max_price')); ?>" 
                                   placeholder="₱10,000" min="0" step="100"
                                   class="block w-full px-gr-sm py-gr-sm border-2 border-lgu-stroke rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-all duration-200 text-sm">
                        </div>
                        
                        <!-- Availability -->
                        <div>
                            <label for="availability" class="block text-small font-medium text-gray-700 mb-gr-xs">
                                Availability
                            </label>
                            <select name="availability" id="availability" 
                                    class="block w-full px-gr-sm py-gr-sm border-2 border-lgu-stroke rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-all duration-200 text-sm">
                                <option value="">All Facilities</option>
                                <option value="available" <?php echo e(request('availability') == 'available' ? 'selected' : ''); ?>>Available Now</option>
                                <option value="coming_soon" <?php echo e(request('availability') == 'coming_soon' ? 'selected' : ''); ?>>Coming Soon</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Amenities Checkboxes -->
                    <div>
                        <label class="block text-small font-medium text-gray-700 mb-gr-sm">
                            Amenities
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-gr-sm">
                            <?php $__currentLoopData = $availableAmenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center gap-2 text-sm text-gray-700 hover:text-lgu-headline cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="<?php echo e($key); ?>" 
                                           <?php echo e(in_array($key, (array)request('amenities', [])) ? 'checked' : ''); ?>

                                           class="rounded border-lgu-stroke text-lgu-button focus:ring-lgu-highlight">
                                    <span><?php echo e($label); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-gr-sm border-t-2 border-lgu-stroke">
                <div class="flex items-center gap-gr-md">
                    <div class="text-sm text-lgu-paragraph">
                        Showing <span class="font-semibold text-lgu-headline"><?php echo e($facilities->total()); ?></span> <?php echo e($facilities->total() == 1 ? 'facility' : 'facilities'); ?>

                    </div>
                    
                    <!-- Sort Dropdown -->
                    <div class="flex items-center gap-2">
                        <label for="sort" class="text-sm font-medium text-gray-700">
                            Sort:
                        </label>
                        <select name="sort" id="sort" onchange="this.form.submit()" 
                                class="px-gr-sm py-1 border-2 border-lgu-stroke rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-all duration-200 text-sm">
                            <option value="favorites" <?php echo e(request('sort') == 'favorites' ? 'selected' : ''); ?>>My Favorites First</option>
                            <option value="name" <?php echo e(request('sort') == 'name' ? 'selected' : ''); ?>>Name (A-Z)</option>
                            <option value="popularity" <?php echo e(request('sort') == 'popularity' ? 'selected' : ''); ?>>Most Popular</option>
                            <option value="rating" <?php echo e(request('sort') == 'rating' ? 'selected' : ''); ?>>Highest Rated</option>
                            <option value="price_low" <?php echo e(request('sort') == 'price_low' ? 'selected' : ''); ?>>Price: Low to High</option>
                            <option value="price_high" <?php echo e(request('sort') == 'price_high' ? 'selected' : ''); ?>>Price: High to Low</option>
                            <option value="capacity_high" <?php echo e(request('sort') == 'capacity_high' ? 'selected' : ''); ?>>Capacity: High to Low</option>
                            <option value="capacity_low" <?php echo e(request('sort') == 'capacity_low' ? 'selected' : ''); ?>>Capacity: Low to High</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-gr-sm">
                    <a href="<?php echo e(route('citizen.browse-facilities')); ?>" 
                       class="px-gr-md py-gr-sm border-2 border-lgu-stroke text-lgu-headline rounded-lg hover:bg-lgu-bg transition-colors text-sm font-semibold">
                        Clear Filters
                    </a>
                    <button type="submit" 
                            class="px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text rounded-lg hover:opacity-90 transition-all text-sm font-semibold shadow-sm">
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Map View (Hidden by default) -->
    <div id="mapView" class="hidden bg-white shadow rounded-xl p-gr-md">
        <div class="mb-gr-md flex items-center justify-between">
            <h3 class="text-h3 text-lgu-headline font-bold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                Facility Locations
            </h3>
            <div class="text-sm text-lgu-paragraph">
                <span class="font-semibold"><?php echo e($facilitiesWithCoords->count()); ?></span> facilities with location data
            </div>
        </div>
        
        <!-- Map Placeholder (shown only if Google Maps fails to load) -->
        <div id="mapPlaceholder" class="<?php echo e(config('services.google_maps.api_key') ? 'hidden' : ''); ?> w-full h-[600px] rounded-lg border-2 border-lgu-stroke bg-lgu-bg flex items-center justify-center">
            <div class="text-center p-gr-lg">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-gr-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                <h4 class="text-xl font-bold text-lgu-headline mb-gr-sm">Map View Coming Soon</h4>
                <p class="text-lgu-paragraph mb-gr-md max-w-md">
                    Interactive map with facility locations will be available once Google Maps API is configured.
                </p>
                <button onclick="toggleView('grid')" class="bg-lgu-button text-lgu-button-text px-gr-lg py-gr-sm rounded-lg font-semibold hover:opacity-90 transition-all">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Back to Grid View
                </button>
            </div>
        </div>
        <!-- Actual Google Map -->
        <?php if(config('services.google_maps.api_key')): ?>
            <div id="map" class="w-full rounded-lg border-2 border-lgu-stroke" style="height: 500px; min-height: 500px; background: #e5e7eb;"></div>
        <?php endif; ?>
        
        <!-- Map Controls -->
        <div class="mt-gr-md flex items-center justify-between flex-wrap gap-gr-sm">
            <!-- Map Legend -->
            <div class="flex items-center gap-gr-lg text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                    <span class="text-gray-700">Available</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-lgu-button rounded-full"></div>
                    <span class="text-gray-700">Coming Soon</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-lgu-tertiary rounded-full"></div>
                    <span class="text-gray-700">Your Favorites</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                    <span class="text-gray-700">Your Location</span>
                </div>
            </div>
            
            <!-- My Location Button -->
            <button type="button" onclick="showMyLocation()" id="myLocationBtn" 
                    class="flex items-center gap-2 px-gr-md py-gr-sm bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm font-semibold shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12 2v2m0 16v2M2 12h2m16 0h2"/>
                    <circle cx="12" cy="12" r="8" stroke-dasharray="2 2"/>
                </svg>
                <span id="locationBtnText">Show My Location</span>
            </button>
        </div>
        
        <!-- Distance Info -->
        <div id="distanceInfo" class="hidden mt-gr-sm p-gr-sm bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800 flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
            <span><span class="font-semibold">Your location detected!</span> Distances to facilities are now shown. Click a facility to see the route.</span>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <!-- Google Maps Initialization - must be defined before API loads -->
    <script>
    // Define initMap globally BEFORE Google Maps API loads
    let map;
    let markers = [];
    let mapInitialized = false;
    let userMarker = null;
    let userLocation = null;
    const favoritedIds = <?php echo json_encode($favoritedIds, 15, 512) ?>;
    const facilitiesData = <?php echo json_encode($facilitiesWithCoords, 15, 512) ?>;

    // Haversine formula to calculate distance between two points
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Earth's radius in kilometers
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    // Format distance for display
    function formatDistance(km) {
        if (km < 1) {
            return Math.round(km * 1000) + ' m';
        }
        return km.toFixed(1) + ' km';
    }

    // Show user's location on the map
    function showMyLocation() {
        const btn = document.getElementById('myLocationBtn');
        const btnText = document.getElementById('locationBtnText');
        
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser');
            return;
        }
        
        btnText.textContent = 'Locating...';
        btn.disabled = true;
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                
                if (map && mapInitialized) {
                    addUserMarker();
                    updateDistances();
                }
                
                btnText.textContent = 'Update Location';
                btn.disabled = false;
                document.getElementById('distanceInfo').classList.remove('hidden');
            },
            (error) => {
                let message = 'Unable to get your location';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        message = 'Location access denied. Please enable location in your browser settings.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message = 'Location information unavailable.';
                        break;
                    case error.TIMEOUT:
                        message = 'Location request timed out.';
                        break;
                }
                alert(message);
                btnText.textContent = 'Show My Location';
                btn.disabled = false;
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 300000 }
        );
    }

    // Add user marker to the map
    function addUserMarker() {
        if (!userLocation || !map) return;
        
        // Remove existing user marker
        if (userMarker) {
            userMarker.setMap(null);
        }
        
        userMarker = new google.maps.Marker({
            position: userLocation,
            map: map,
            title: 'Your Location',
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 10,
                fillColor: '#3b82f6',
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 3
            },
            zIndex: 1000
        });
        
        // Add pulsing effect ring
        new google.maps.Marker({
            position: userLocation,
            map: map,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 20,
                fillColor: '#3b82f6',
                fillOpacity: 0.2,
                strokeColor: '#3b82f6',
                strokeWeight: 1
            },
            zIndex: 999
        });
        
        // Center map to include user location
        const bounds = new google.maps.LatLngBounds();
        bounds.extend(userLocation);
        markers.forEach(marker => bounds.extend(marker.getPosition()));
        map.fitBounds(bounds);
    }

    // Current route polyline
    let currentRouteLine = null;
    
    // Draw route along roads using OSRM (free, no API key required)
    function drawRouteLine(facilityLat, facilityLng) {
        if (!userLocation) return;
        
        // Remove existing route
        if (currentRouteLine) {
            currentRouteLine.setMap(null);
        }
        
        // Use OSRM demo server (free, based on OpenStreetMap)
        const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${userLocation.lng},${userLocation.lat};${facilityLng},${facilityLat}?overview=full&geometries=geojson`;
        
        fetch(osrmUrl)
            .then(response => response.json())
            .then(data => {
                if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
                    const route = data.routes[0];
                    const coordinates = route.geometry.coordinates.map(coord => ({
                        lat: coord[1],
                        lng: coord[0]
                    }));
                    
                    // Draw the route polyline
                    currentRouteLine = new google.maps.Polyline({
                        path: coordinates,
                        geodesic: true,
                        strokeColor: '#3b82f6',
                        strokeOpacity: 0.8,
                        strokeWeight: 4
                    });
                    currentRouteLine.setMap(map);
                    
                    console.log('OSRM route displayed successfully');
                } else {
                    console.log('OSRM routing failed, using straight line');
                    drawFallbackLine(facilityLat, facilityLng);
                }
            })
            .catch(error => {
                console.error('OSRM request failed:', error);
                drawFallbackLine(facilityLat, facilityLng);
            });
    }
    
    // Fallback straight line if routing fails
    function drawFallbackLine(facilityLat, facilityLng) {
        if (currentRouteLine) {
            currentRouteLine.setMap(null);
        }
        
        currentRouteLine = new google.maps.Polyline({
            path: [userLocation, { lat: facilityLat, lng: facilityLng }],
            geodesic: true,
            strokeColor: '#3b82f6',
            strokeOpacity: 0.5,
            strokeWeight: 2,
            icons: [{
                icon: { path: 'M 0,-1 0,1', strokeOpacity: 1, scale: 3 },
                offset: '0',
                repeat: '15px'
            }]
        });
        currentRouteLine.setMap(map);
    }

    // Update info windows with distances
    function updateDistances() {
        if (!userLocation) return;
        
        markers.forEach((marker, index) => {
            const facility = facilitiesData[index];
            if (facility && facility.latitude && facility.longitude) {
                const facilityLat = parseFloat(facility.latitude);
                const facilityLng = parseFloat(facility.longitude);
                const distance = calculateDistance(
                    userLocation.lat, userLocation.lng,
                    facilityLat, facilityLng
                );
                
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 8px; font-family: 'Poppins', sans-serif; max-width: 250px;">
                            <h4 style="font-weight: bold; margin-bottom: 4px;">${facility.name}</h4>
                            <p style="color: #666; font-size: 12px; margin-bottom: 4px;">${facility.city || facility.address || ''}</p>
                            <p style="color: #3b82f6; font-size: 12px; font-weight: 600; margin-bottom: 8px; display: flex; align-items: center; gap: 4px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                ${formatDistance(distance)} away
                            </p>
                            <a href="/citizen/facility-details/${facility.facility_id}" 
                               style="display: inline-block; padding: 6px 12px; background: #faae2b; color: #000; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                View Details
                            </a>
                        </div>
                    `
                });
                
                google.maps.event.clearListeners(marker, 'click');
                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                    drawRouteLine(facilityLat, facilityLng);
                });
            }
        });
    }

    // This is called by Google Maps API callback - just marks API as ready
    window.initMap = function() {
        // Don't initialize map here - wait until map view is visible
        console.log('Google Maps API loaded, ready to initialize when map view is shown');
    };

    // Actually initialize the map (called when map view becomes visible)
    function initializeMap(retryCount = 0) {
        if (mapInitialized) return;
        
        const mapElement = document.getElementById('map');
        if (!mapElement) return;
        
        // Check if script failed to load
        if (window.googleMapsError) {
            mapElement.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500"><div class="text-center"><svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><p class="font-semibold">Unable to load Google Maps</p><p class="text-sm mt-2">Please check your internet connection or try again later.</p></div></div>';
            return;
        }
        
        if (typeof google === 'undefined' || !google.maps) {
            console.log('Google Maps API not yet loaded, retrying... (' + (retryCount + 1) + '/10)');
            // Retry up to 10 times with 500ms delay
            if (retryCount < 10) {
                setTimeout(() => initializeMap(retryCount + 1), 500);
            } else {
                // Show error after all retries
                mapElement.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500"><div class="text-center"><svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><p class="font-semibold">Google Maps failed to load</p><p class="text-sm mt-2">The map service is temporarily unavailable. Please try again.</p></div></div>';
            }
            return;
        }
        
        // Center on Metro Manila
        const metroManila = { lat: 14.6560, lng: 121.0200 };
        
        map = new google.maps.Map(mapElement, {
            zoom: 12,
            center: metroManila,
            mapTypeControl: false,
            streetViewControl: false,
            styles: [
                { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] }
            ]
        });

        // Add markers for each facility
        facilitiesData.forEach(facility => {
            if (facility.latitude && facility.longitude) {
                const isFavorite = favoritedIds.includes(facility.facility_id);
                const isAvailable = facility.is_available;
                
                let markerColor = isAvailable ? '#10b981' : '#faae2b';
                if (isFavorite) markerColor = '#fa5246';
                
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(facility.latitude), lng: parseFloat(facility.longitude) },
                    map: map,
                    title: facility.name,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 12,
                        fillColor: markerColor,
                        fillOpacity: 0.9,
                        strokeColor: '#ffffff',
                        strokeWeight: 2
                    }
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 8px; font-family: 'Poppins', sans-serif; max-width: 250px;">
                            <h4 style="font-weight: bold; margin-bottom: 4px;">${facility.name}</h4>
                            <p style="color: #666; font-size: 12px; margin-bottom: 8px;">${facility.city || facility.address || ''}</p>
                            <a href="/citizen/facility-details/${facility.facility_id}" 
                               style="display: inline-block; padding: 6px 12px; background: #faae2b; color: #000; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                View Details
                            </a>
                        </div>
                    `
                });

                marker.addListener('click', () => infoWindow.open(map, marker));
                markers.push(marker);
            }
        });

        // Fit bounds to show all markers
        if (markers.length > 0) {
            const bounds = new google.maps.LatLngBounds();
            markers.forEach(marker => bounds.extend(marker.getPosition()));
            map.fitBounds(bounds);
        }
        
        console.log('Google Maps initialized with', markers.length, 'markers');
        mapInitialized = true;
    }
    </script>
    
    <!-- Google Maps API -->
    <?php if(config('services.google_maps.api_key')): ?>
        <script>
            // Track if Google Maps script loads
            window.googleMapsLoaded = false;
            window.googleMapsError = false;
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('services.google_maps.api_key')); ?>&callback=initMap" 
                async defer
                onerror="window.googleMapsError = true; console.error('Failed to load Google Maps API script');">
        </script>
    <?php else: ?>
        <script>
            console.warn('Google Maps API key not configured');
            window.googleMapsError = true;
        </script>
    <?php endif; ?>
    <style>
        /* Fade-in animation for facility cards */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Smooth transitions */
        .facility-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .facility-card:hover {
            transform: translateY(-8px) scale(1.02);
        }

    </style>

    <script>
    // View toggle functionality
    function toggleView(view) {
        const gridView = document.getElementById('gridViewContainer');
        const mapView = document.getElementById('mapView');
        const gridBtn = document.getElementById('gridViewBtn');
        const mapBtn = document.getElementById('mapViewBtn');

        if (view === 'map') {
            if (gridView) gridView.classList.add('hidden');
            mapView.classList.remove('hidden');
            gridBtn.classList.remove('bg-lgu-button', 'text-lgu-button-text');
            gridBtn.classList.add('bg-white', 'text-lgu-headline', 'hover:bg-lgu-bg');
            mapBtn.classList.remove('bg-white', 'text-lgu-headline', 'hover:bg-lgu-bg');
            mapBtn.classList.add('bg-lgu-button', 'text-lgu-button-text');
            
            // Wait for next frame so container has dimensions, then initialize or resize
            requestAnimationFrame(() => {
                setTimeout(() => {
                    if (!mapInitialized) {
                        initializeMap();
                    } else if (typeof google !== 'undefined' && map) {
                        google.maps.event.trigger(map, 'resize');
                        if (markers.length > 0) {
                            const bounds = new google.maps.LatLngBounds();
                            markers.forEach(marker => bounds.extend(marker.getPosition()));
                            map.fitBounds(bounds);
                        }
                    }
                }, 100);
            });
        } else {
            if (gridView) gridView.classList.remove('hidden');
            mapView.classList.add('hidden');
            mapBtn.classList.remove('bg-lgu-button', 'text-lgu-button-text');
            mapBtn.classList.add('bg-white', 'text-lgu-headline', 'hover:bg-lgu-bg');
            gridBtn.classList.remove('bg-white', 'text-lgu-headline', 'hover:bg-lgu-bg');
            gridBtn.classList.add('bg-lgu-button', 'text-lgu-button-text');
        }
    }

    // Toggle advanced filters
    function toggleAdvancedFilters() {
        const filters = document.getElementById('advancedFilters');
        const icon = document.getElementById('advancedFilterIcon');
        
        if (filters.classList.contains('hidden')) {
            filters.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            filters.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filterForm');
        const searchInput = document.getElementById('search');
        const citySelect = document.getElementById('city');
        const capacityInput = document.getElementById('capacity');
        
        let searchTimeout;

        // Auto-submit for city dropdown (instant)
        if (citySelect) {
            citySelect.addEventListener('change', function() {
                form.submit();
            });
        }

        // Auto-submit for capacity (instant when user changes value)
        if (capacityInput) {
            capacityInput.addEventListener('change', function() {
                form.submit();
            });
        }

        // Auto-submit for search with debounce (wait 500ms after user stops typing)
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    form.submit();
                }, 500);
            });
        }

        // Show advanced filters if any advanced filter is active
        const hasAdvancedFilters = <?php echo e(request()->filled('min_price') || request()->filled('max_price') || request()->filled('availability') || request()->filled('amenities') ? 'true' : 'false'); ?>;
        if (hasAdvancedFilters) {
            toggleAdvancedFilters();
        }
    });

    </script>
    <?php $__env->stopPush(); ?>

    <!-- Facilities Grid Container -->
    <div id="gridViewContainer">
        <?php if($facilities->isEmpty()): ?>
            <div class="bg-white shadow rounded-xl p-gr-3xl text-center">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-gr-md">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-h3 text-gray-900 mb-gr-sm">No facilities found</h3>
                <p class="text-body text-gray-600 mb-gr-lg">Try adjusting your search or filters</p>
                <a href="<?php echo e(route('citizen.browse-facilities')); ?>" 
                   class="inline-flex items-center px-gr-md py-gr-sm bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-small font-medium">
                    View All Facilities
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gr-lg" id="facilitiesGrid">
            <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="facility-card bg-white shadow-sm rounded-xl overflow-hidden hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 opacity-0 animate-fade-in" style="animation-delay: <?php echo e($loop->index * 0.1); ?>s">
                    <!-- Facility Image -->
                    <div class="relative h-48 bg-gray-200">
                        <div class="w-full h-full flex items-center justify-center bg-primary-100">
                            <svg class="w-16 h-16 text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm6 6H7v2h6v-2z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        
                        <!-- Favorite Button -->
                        <button onclick="toggleFavorite(<?php echo e($facility->facility_id ?? $facility->getKey()); ?>)" 
                                class="favorite-btn absolute top-3 left-3 bg-white rounded-full p-2 shadow-md hover:bg-lgu-highlight transition-all z-10"
                                data-facility-id="<?php echo e($facility->facility_id ?? $facility->getKey()); ?>">
                            <?php if(in_array($facility->facility_id ?? $facility->getKey(), $favoritedIds ?? [])): ?>
                                <svg class="w-5 h-5 fill-lgu-tertiary text-lgu-tertiary" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                            <?php else: ?>
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                            <?php endif; ?>
                        </button>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            <?php if(!$facility->is_available): ?>
                                <span class="px-gr-sm py-1 bg-lgu-button text-lgu-button-text text-caption font-semibold rounded-full shadow-md">Coming Soon</span>
                            <?php else: ?>
                                <span class="px-gr-sm py-1 bg-green-500 text-white text-caption font-semibold rounded-full">Available</span>
                            <?php endif; ?>
                        </div>
                        
                    </div>

                    <!-- Facility Info -->
                    <div class="p-gr-md">
                        <div class="mb-gr-sm">
                            <h3 class="text-h3 text-gray-900 mb-1"><?php echo e($facility->name); ?></h3>
                            <?php if($facility->lguCity): ?>
                                <p class="text-small text-gray-600 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                        <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <?php echo e($facility->lguCity->city_name); ?>

                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- Details -->
                        <div class="space-y-gr-xs mb-gr-md">
                            <div class="flex items-center justify-between text-small">
                                <span class="text-gray-600 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                    Capacity
                                </span>
                                <span class="font-semibold text-gray-900"><?php echo e(number_format($facility->capacity)); ?> people</span>
                            </div>

                        </div>

                        <!-- Price & Action -->
                        <div class="border-t border-gray-200 pt-gr-md">
                            <?php if($facility->per_person_rate): ?>
                                <div class="mb-gr-sm">
                                    <div class="text-h2 font-bold text-primary-600">₱<?php echo e(number_format($facility->per_person_rate, 2)); ?></div>
                                    <div class="text-caption text-gray-500">Per person rate</div>
                                </div>
                            <?php elseif($facility->hourly_rate): ?>
                                <div class="mb-gr-sm">
                                    <div class="text-h2 font-bold text-primary-600">₱<?php echo e(number_format($facility->hourly_rate, 2)); ?></div>
                                    <div class="text-caption text-gray-500">Per hour rate</div>
                                </div>
                            <?php else: ?>
                                <div class="mb-gr-sm">
                                    <div class="text-body-lg font-bold text-gray-900">Contact for pricing</div>
                                    <div class="text-caption text-gray-500">Rates vary</div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="grid grid-cols-2 gap-gr-sm">
                                <a href="<?php echo e(route('citizen.facility-details', ['id' => $facility->facility_id ?? $facility->getKey()])); ?>" 
                                   class="px-gr-sm py-gr-sm border-2 border-lgu-stroke text-lgu-headline font-semibold rounded-lg hover:bg-lgu-bg transition-colors text-center text-sm">
                                    Details
                                </a>
                                <?php if($facility->is_available): ?>
                                    <a href="<?php echo e(route('citizen.booking.create', $facility->facility_id ?? $facility->getKey())); ?>" 
                                       class="px-gr-sm py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:opacity-90 transition-all shadow-sm text-center text-sm">
                                        Book Now
                                    </a>
                                <?php else: ?>
                                    <button disabled 
                                            class="px-gr-sm py-gr-sm bg-gray-300 text-gray-500 font-semibold rounded-lg cursor-not-allowed text-center text-sm">
                                        Coming Soon
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

            <!-- Pagination -->
            <div class="mt-gr-lg">
                <?php echo e($facilities->links()); ?>

            </div>
        <?php endif; ?>
    </div>
    <!-- End Grid View Container -->

    <!-- Info Box -->
    <div class="bg-lgu-bg border-2 border-lgu-stroke rounded-xl p-gr-lg">
        <div class="flex gap-gr-sm">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-lgu-headline" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-base font-semibold text-lgu-headline mb-gr-sm">Booking Information</h3>
                <div class="text-sm text-lgu-paragraph space-y-gr-xs">
                    <div class="flex items-start gap-2">
                        <span class="font-semibold">Pricing:</span>
                        <span>Rates are calculated per person based on number of attendees</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-semibold">Senior/PWD:</span>
                        <span>20% discount with valid ID</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-semibold">Student:</span>
                        <span>20% discount with valid school ID</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-semibold">Advance Booking:</span>
                        <span>Reserve at least 7 business days in advance</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-semibold">Payment:</span>
                        <span>Full payment required before reservation confirmation (no partial payments)</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-semibold">Coming Soon:</span>
                        <span>QC M.I.C.E. facilities pending ordinance approval for public bookings</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.citizen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/citizen/browse-facilities.blade.php ENDPATH**/ ?>