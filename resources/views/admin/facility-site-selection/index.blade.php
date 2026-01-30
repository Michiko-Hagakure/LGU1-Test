@extends('layouts.admin')

@section('page-title', 'Facility Site Selection')
@section('page-subtitle', 'Find suitable locations for new facility development')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #siteMap { height: 500px; border-radius: 0.75rem; }
    .site-marker { background: #667eea; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); }
    .facility-marker { background: #10b981; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); }
    .site-card { transition: all 0.2s ease; cursor: pointer; }
    .site-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .site-card.selected { border-color: #667eea; background: #f0f3ff; }
    .zoning-badge { font-size: 0.7rem; padding: 2px 8px; border-radius: 9999px; }
    .zoning-institutional { background: #dbeafe; color: #1e40af; }
    .zoning-commercial { background: #fef3c7; color: #92400e; }
    .zoning-residential { background: #d1fae5; color: #065f46; }
    .zoning-industrial { background: #e5e7eb; color: #374151; }
    .zoning-agricultural { background: #fce7f3; color: #9d174d; }
    .suitability-score { font-size: 1.5rem; font-weight: 700; }
    .suitability-high { color: #10b981; }
    .suitability-medium { color: #f59e0b; }
    .suitability-low { color: #ef4444; }
</style>
@endpush

@section('page-content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left Panel: Filters and Site List --}}
    <div class="lg:col-span-1 space-y-6">
        {{-- Integration Info --}}
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start gap-3">
                <i data-lucide="map-pin" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-blue-800 font-medium">Urban Planning Integration</p>
                    <p class="text-blue-700 text-sm mt-1">Find suitable sites for facility development based on zoning maps.</p>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-4 py-3">
                <h3 class="text-white font-semibold flex items-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    Search Filters
                </h3>
            </div>
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zoning Type</label>
                    <select id="zoningType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight">
                        @foreach($zoningTypes as $value => $label)
                        <option value="{{ $value }}" {{ $value === 'institutional' ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                    <select id="districtFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight">
                        <option value="">All Districts</option>
                        @foreach($locations as $district)
                        <option value="{{ $district['district_id'] }}">{{ $district['district_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                    <select id="barangayFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight" disabled>
                        <option value="">Select a district first</option>
                    </select>
                </div>
                <button type="button" id="searchBtn" class="w-full px-4 py-2 bg-lgu-headline text-white rounded-lg hover:bg-lgu-stroke transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Search Sites
                </button>
            </div>
        </div>

        {{-- Site Results --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-4 py-3 flex items-center justify-between">
                <h3 class="text-white font-semibold flex items-center gap-2">
                    <i data-lucide="list" class="w-4 h-4"></i>
                    Available Sites
                </h3>
                <span id="siteCount" class="text-white text-sm bg-white/20 px-2 py-0.5 rounded">0 sites</span>
            </div>
            <div id="siteList" class="p-4 space-y-3 max-h-96 overflow-y-auto">
                <div class="text-center py-8 text-gray-500">
                    <i data-lucide="map" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                    <p>Click "Search Sites" to find available locations</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Panel: Map and Details --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Map --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-4 py-3 flex items-center justify-between">
                <h3 class="text-white font-semibold flex items-center gap-2">
                    <i data-lucide="map" class="w-4 h-4"></i>
                    Site Map
                </h3>
                <div class="flex items-center gap-4 text-white text-xs">
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-indigo-500"></span> Available Sites
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span> Existing Facilities
                    </span>
                </div>
            </div>
            <div id="siteMap"></div>
        </div>

        {{-- Site Details / Suitability Check --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-4 py-3">
                <h3 class="text-white font-semibold flex items-center gap-2">
                    <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                    Site Suitability Check
                </h3>
            </div>
            <div class="p-6">
                <div id="suitabilityResult" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Selected Location</h4>
                            <div class="space-y-2 text-sm">
                                <p><span class="text-gray-500">Coordinates:</span> <span id="selectedCoords" class="font-medium">-</span></p>
                                <p><span class="text-gray-500">Zone Name:</span> <span id="selectedZoneName" class="font-medium">-</span></p>
                                <p><span class="text-gray-500">District:</span> <span id="selectedDistrict" class="font-medium">-</span></p>
                                <p><span class="text-gray-500">Barangay:</span> <span id="selectedBarangay" class="font-medium">-</span></p>
                            </div>
                        </div>
                        <div class="text-center">
                            <h4 class="font-semibold text-gray-900 mb-3">Suitability Score</h4>
                            <div id="suitabilityScore" class="suitability-score suitability-high">-</div>
                            <p id="suitabilityMessage" class="text-sm text-gray-600 mt-2">-</p>
                        </div>
                    </div>
                </div>
                <div id="suitabilityPlaceholder" class="text-center py-8 text-gray-500">
                    <i data-lucide="mouse-pointer-click" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                    <p>Click on the map or select a site to check suitability</p>
                </div>
            </div>
        </div>

        {{-- Existing Facilities Reference --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-4 py-3">
                <h3 class="text-white font-semibold flex items-center gap-2">
                    <i data-lucide="building" class="w-4 h-4"></i>
                    Existing Facilities ({{ count($facilities) }})
                </h3>
            </div>
            <div class="p-4">
                @if(count($facilities) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($facilities as $facility)
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="font-medium text-gray-900">{{ $facility->name }}</p>
                        <p class="text-sm text-gray-500">{{ $facility->address ?? $facility->full_address ?? 'No address' }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-4">No existing facilities found</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map centered on Quezon City
    const map = L.map('siteMap').setView([14.6760, 121.0437], 12);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Layer groups
    const sitesLayer = L.layerGroup().addTo(map);
    const facilitiesLayer = L.layerGroup().addTo(map);

    // Location data from PHP
    const locations = @json($locations);
    const facilities = @json($facilities);

    // Add existing facilities to map
    facilities.forEach(facility => {
        if (facility.latitude && facility.longitude) {
            const marker = L.circleMarker([facility.latitude, facility.longitude], {
                radius: 8,
                fillColor: '#10b981',
                color: '#fff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(facilitiesLayer);
            
            marker.bindPopup(`<strong>${facility.name}</strong><br>${facility.address || 'No address'}`);
        }
    });

    // Update barangay dropdown when district changes
    document.getElementById('districtFilter').addEventListener('change', function() {
        const districtId = this.value;
        const barangaySelect = document.getElementById('barangayFilter');
        
        barangaySelect.innerHTML = '<option value="">All Barangays</option>';
        
        if (districtId) {
            const district = locations.find(d => d.district_id == districtId);
            if (district && district.barangays) {
                district.barangays.forEach(brgy => {
                    barangaySelect.innerHTML += `<option value="${brgy.barangay_id}">${brgy.barangay_name}</option>`;
                });
            }
            barangaySelect.disabled = false;
        } else {
            barangaySelect.disabled = true;
        }
    });

    // Search sites
    document.getElementById('searchBtn').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Searching...';
        
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('zoning_type', document.getElementById('zoningType').value);
        
        const districtId = document.getElementById('districtFilter').value;
        const barangayId = document.getElementById('barangayFilter').value;
        
        if (districtId) formData.append('district_id', districtId);
        if (barangayId) formData.append('barangay_id', barangayId);
        
        try {
            const response = await fetch('{{ route("admin.facility-site-selection.search") }}', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if (result.success) {
                displaySites(result.data.sites || []);
            } else {
                alert(result.message || 'Failed to search sites');
            }
        } catch (error) {
            console.error('Search error:', error);
            alert('An error occurred while searching');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="search" class="w-4 h-4"></i> Search Sites';
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    });

    // Display sites on map and list
    function displaySites(sites) {
        sitesLayer.clearLayers();
        const siteList = document.getElementById('siteList');
        document.getElementById('siteCount').textContent = `${sites.length} sites`;
        
        if (sites.length === 0) {
            siteList.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i data-lucide="map-pin-off" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                    <p>No sites found matching your criteria</p>
                </div>`;
            if (typeof lucide !== 'undefined') lucide.createIcons();
            return;
        }
        
        let listHtml = '';
        const bounds = [];
        
        sites.forEach((site, index) => {
            // Add marker to map
            if (site.center_latitude && site.center_longitude) {
                bounds.push([site.center_latitude, site.center_longitude]);
                
                const marker = L.circleMarker([site.center_latitude, site.center_longitude], {
                    radius: 10,
                    fillColor: '#667eea',
                    color: '#fff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.8
                }).addTo(sitesLayer);
                
                marker.bindPopup(`
                    <strong>${site.map_name}</strong><br>
                    <span class="text-xs">${site.district_name} - ${site.barangay_name}</span><br>
                    <span class="text-xs">Area: ${site.area_hectares || 'N/A'} hectares</span>
                `);
                
                marker.on('click', () => checkSuitability(site.center_latitude, site.center_longitude));
            }
            
            // Add to list
            const zoningClass = `zoning-${site.zoning_type || 'institutional'}`;
            listHtml += `
                <div class="site-card p-3 bg-gray-50 rounded-lg border border-gray-200" 
                     data-lat="${site.center_latitude}" data-lng="${site.center_longitude}"
                     onclick="window.selectSite(${site.center_latitude}, ${site.center_longitude})">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="font-medium text-gray-900 text-sm">${site.map_name}</p>
                            <p class="text-xs text-gray-500">${site.district_name} - ${site.barangay_name}</p>
                        </div>
                        <span class="zoning-badge ${zoningClass}">${site.zoning_type || 'institutional'}</span>
                    </div>
                    <div class="mt-2 flex items-center gap-3 text-xs text-gray-500">
                        <span><i data-lucide="ruler" class="w-3 h-3 inline"></i> ${site.area_hectares || 'N/A'} ha</span>
                        <span><i data-lucide="users" class="w-3 h-3 inline"></i> Pop: ${site.population || 'N/A'}</span>
                    </div>
                </div>`;
        });
        
        siteList.innerHTML = listHtml;
        if (typeof lucide !== 'undefined') lucide.createIcons();
        
        // Fit map to bounds
        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    }

    // Select site from list
    window.selectSite = function(lat, lng) {
        map.setView([lat, lng], 15);
        checkSuitability(lat, lng);
        
        // Highlight selected card
        document.querySelectorAll('.site-card').forEach(card => card.classList.remove('selected'));
        const selectedCard = document.querySelector(`.site-card[data-lat="${lat}"][data-lng="${lng}"]`);
        if (selectedCard) selectedCard.classList.add('selected');
    };

    // Check suitability
    async function checkSuitability(lat, lng) {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('latitude', lat);
        formData.append('longitude', lng);
        formData.append('zoning_type', document.getElementById('zoningType').value);
        
        try {
            const response = await fetch('{{ route("admin.facility-site-selection.check-suitability") }}', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            document.getElementById('suitabilityPlaceholder').classList.add('hidden');
            document.getElementById('suitabilityResult').classList.remove('hidden');
            
            document.getElementById('selectedCoords').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            
            if (result.success && result.data) {
                const data = result.data;
                document.getElementById('selectedZoneName').textContent = data.site?.map_name || '-';
                document.getElementById('selectedDistrict').textContent = data.site?.district_name || '-';
                document.getElementById('selectedBarangay').textContent = data.site?.barangay_name || '-';
                
                const score = data.suitability_score || 0;
                const scoreEl = document.getElementById('suitabilityScore');
                scoreEl.textContent = score + '%';
                scoreEl.className = 'suitability-score ' + (score >= 70 ? 'suitability-high' : score >= 40 ? 'suitability-medium' : 'suitability-low');
                
                document.getElementById('suitabilityMessage').textContent = result.message || (data.is_suitable ? 'Site is suitable for facility development' : 'Site may not be suitable');
            } else {
                document.getElementById('selectedZoneName').textContent = '-';
                document.getElementById('selectedDistrict').textContent = '-';
                document.getElementById('selectedBarangay').textContent = '-';
                document.getElementById('suitabilityScore').textContent = '0%';
                document.getElementById('suitabilityScore').className = 'suitability-score suitability-low';
                document.getElementById('suitabilityMessage').textContent = result.message || 'Location is not within a suitable zone';
            }
        } catch (error) {
            console.error('Suitability check error:', error);
        }
    }

    // Map click to check suitability
    map.on('click', function(e) {
        checkSuitability(e.latlng.lat, e.latlng.lng);
    });
});
</script>
@endpush
