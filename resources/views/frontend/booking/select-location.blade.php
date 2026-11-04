<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تحديد الموقع - {{ config('brand.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Add Leaflet CSS for OpenStreetMap fallback -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #ec4899 0%, #a855f7 100%);
        }
        #map {
            height: 400px;
            border-radius: 12px;
            position: relative;
        }
        .map-responsive {
            height: 400px;
        }
        @media (max-width: 640px) {
            #map {
                height: 500px;
            }
            .map-responsive {
                height: 500px;
            }
        }
        .leaflet-container {
            border-radius: 12px;
        }
        /* Fixed center marker */
        .center-marker {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            pointer-events: none;
        }
        .center-marker i {
            color: #dc2626;
            font-size: 1.8rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
            filter: drop-shadow(0 0 3px rgba(220, 38, 38, 0.8));
            animation: pulse 2s infinite;
        }
        
        @media (max-width: 640px) {
            .center-marker i {
                font-size: 1.5rem;
                text-shadow: 1px 1px 1px rgba(0,0,0,0.4);
                filter: drop-shadow(0 0 2px rgba(220, 38, 38, 0.6));
            }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        @media (max-width: 640px) {
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.03); }
                100% { transform: scale(1); }
            }
        }
        .map-container {
            position: relative;
        }
        
        /* Mobile map improvements */
        @media (max-width: 640px) {
            #map {
                touch-action: manipulation;
                -webkit-overflow-scrolling: touch;
            }
            
            .leaflet-container {
                touch-action: manipulation;
            }
            
            /* Improve touch responsiveness */
            .leaflet-interactive {
                touch-action: manipulation;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-3 sm:px-4">
            <div class="flex items-center justify-between h-14 sm:h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 hover:opacity-80 transition-opacity">
                    <div class="bg-white/20 p-1.5 sm:p-2 rounded-full">
                        <i class="fas fa-cat text-xl sm:text-2xl"></i>
                    </div>
                    <h1 class="text-lg sm:text-xl font-bold">{{ config('brand.name') }}</h1>
                </a>
                <a href="{{ route('home') }}" class="text-pink-200 hover:text-white transition-colors text-sm sm:text-base">
                    <i class="fas fa-home ml-1 sm:ml-2"></i>
                    <span class="hidden sm:inline">العودة للرئيسية</span>
                    <span class="sm:hidden">الرئيسية</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Progress Bar -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-3 sm:px-4 py-3 sm:py-4">
            <div class="flex items-center justify-center">
                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Step 1: Completed -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-green-600 hidden sm:inline">اختيار الإضافات</span>
                        <span class="text-xs font-medium text-green-600 sm:hidden">الإضافات</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-green-300"></div>
                    
                    <!-- Step 2: Active -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-pink-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            2
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-pink-600 hidden sm:inline">تحديد الموقع</span>
                        <span class="text-xs font-medium text-pink-600 sm:hidden">الموقع</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-gray-300"></div>
                    
                    <!-- Step 3: Pending -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            3
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-500 hidden sm:inline">اختيار الموعد</span>
                        <span class="text-xs font-medium text-gray-500 sm:hidden">الموعد</span>
                    </div>
                    
                    <div class="w-4 sm:w-8 h-px bg-gray-300"></div>
                    
                    <!-- Step 4: Pending -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold">
                            4
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-gray-500 hidden sm:inline">تأكيد الحجز</span>
                        <span class="text-xs font-medium text-gray-500 sm:hidden">التأكيد</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- إعلان التنبيه للحجز في الرياض فقط -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 sm:p-4 mb-4 sm:mb-6">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="bg-blue-100 p-1.5 sm:p-2 rounded-full">
                        <i class="fas fa-map-marker-alt text-blue-600 text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-blue-800 mb-1 text-sm sm:text-base">خدمة الحجز متاحة في الرياض فقط</h4>
                        <p class="text-blue-700 text-xs sm:text-sm">نقدم خدماتنا حاليًا في مدينة الرياض.</p>
                    </div>
                </div>
            </div>





            <!-- Selected Add-ons Summary -->
            @if(session('booking.add_ons') && count(session('booking.add_ons')) > 0)
                <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 mb-6 sm:mb-8">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                        <i class="fas fa-plus-circle text-purple-600"></i>
                        الإضافات المختارة
                    </h3>
                    <div class="space-y-2 sm:space-y-3">
                        @foreach(session('booking.add_ons') as $addon)
                            <div class="flex items-center justify-between p-2 sm:p-3 bg-purple-50 rounded-lg">
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-plus text-purple-600 text-sm sm:text-base"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-sm sm:text-base">{{ $addon['name'] }}</h4>
                                        <p class="text-xs sm:text-sm text-gray-600">{{ $addon['option'] }}</p>
                                    </div>
                                </div>
                                <div class="text-left">
                                    <div class="font-semibold text-purple-600 text-sm sm:text-base">
                                        {{ number_format($addon['total'], 2) }} ر.س
                                    </div>
                                    <div class="text-xs sm:text-sm text-gray-500">
                                        {{ $addon['quantity'] }} × {{ number_format($addon['price'], 2) }} ر.س
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="border-t pt-2 sm:pt-3 mt-2 sm:mt-3">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-800 text-sm sm:text-base">مجموع الإضافات:</span>
                                <span class="font-bold text-purple-600 text-sm sm:text-base">{{ number_format(session('booking.addons_price', 0), 2) }} ر.س</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Location Selection -->
            <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">

                <!-- Location Instructions -->
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="bg-purple-500 p-1.5 sm:p-2 rounded-full">
                            <i class="fas fa-hand-pointer text-white text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-purple-800 text-sm sm:text-base">تحديد الموقع</h4>
                            <p class="text-purple-700 text-xs sm:text-sm">السهم الأحمر ثابت في الوسط. اسحب الخريطة لتحريكها حتى يصبح السهم على موقعك</p>
                        </div>
                    </div>
                </div>



                <!-- Map Container -->
                <div class="mb-4 sm:mb-6">
                    <div class="map-container">
                        <div id="map" class="w-full shadow-lg map-responsive" style="border-radius: 12px;"></div>
                        <div class="center-marker">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </div>
                </div>

                <!-- Selected Location Display -->
                <div id="locationDisplay" class="hidden bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="bg-green-500 p-1.5 sm:p-2 rounded-full">
                            <i class="fas fa-map-marker-alt text-white text-sm sm:text-base"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-green-800 text-sm sm:text-base">الموقع المحدد</h4>
                            <p class="text-green-700 text-xs sm:text-sm">
                                خط العرض: <span id="selectedLat">-</span> | 
                                خط الطول: <span id="selectedLng">-</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Coverage Status -->
                <div id="coverageStatus" class="hidden mb-6">
                    <!-- Success message will be inserted here -->
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <a href="{{ route('home') }}" 
                       class="flex-1 bg-gray-500 text-white py-3 sm:py-4 rounded-xl font-semibold hover:bg-gray-600 transition-colors text-center text-sm sm:text-base">
                        <i class="fas fa-arrow-right ml-1 sm:ml-2"></i>
                        <span class="hidden sm:inline">العودة</span>
                        <span class="sm:hidden">العودة</span>
                    </a>
                    <button id="confirmLocation" 
                            class="flex-1 bg-gradient-to-r from-pink-600 to-purple-600 text-white py-3 sm:py-4 rounded-xl font-semibold hover:from-pink-700 hover:to-purple-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base"
                            disabled>
                        <i class="fas fa-arrow-left ml-1 sm:ml-2"></i>
                        <span class="block">
                            <span class="hidden sm:inline">المتابعة لاختيار الموعد</span>
                            <span class="sm:hidden">اختيار الموعد</span>
                        </span>
                        @if(session('booking.total_price', 0) > 0)
                            <span class="block text-xs sm:text-sm font-normal opacity-90">
                                المجموع: {{ number_format(session('booking.total_price', 0), 2) }} ر.س
                            </span>
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>



    <!-- Add Leaflet JS for OpenStreetMap -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Add touch improvements for mobile -->
    <script>
        // Improve touch handling for mobile devices
        if ('ontouchstart' in window) {
            document.addEventListener('DOMContentLoaded', function() {
                const mapElement = document.getElementById('map');
                if (mapElement) {
                    mapElement.style.touchAction = 'manipulation';
                    mapElement.style.webkitOverflowScrolling = 'touch';
                }
            });
        }
    </script>
    
    <!-- Maps JavaScript -->
    <script>
        let isUsingLeaflet = false;
        
        // Fallback function using OpenStreetMap (Leaflet)
        function initMapFallback() {
            console.log('Google Maps failed to load, using OpenStreetMap');
            isUsingLeaflet = true;
            
            // Initialize Leaflet map with better mobile support
            map = L.map('map', {
                center: [defaultLocation.lat, defaultLocation.lng],
                zoom: 12,
                tap: true,
                dragging: true,
                touchZoom: true,
                scrollWheelZoom: true,
                doubleClickZoom: true,
                boxZoom: false,
                keyboard: true,
                zoomControl: true,
                attributionControl: false
            });
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 18,
                minZoom: 8
            }).addTo(map);
            
            // Listen for map movement to get center coordinates
            map.on('moveend', function() {
                const center = map.getCenter();
                selectLocation(center.lat, center.lng);
            });
            
            // Listen for drag events for better responsiveness
            map.on('drag', function() {
                const center = map.getCenter();
                selectLocation(center.lat, center.lng);
            });
            
            // Listen for zoom events for better responsiveness
            map.on('zoomend', function() {
                const center = map.getCenter();
                selectLocation(center.lat, center.lng);
            });
            
            // Set initial location at center
            const center = map.getCenter();
            selectLocation(center.lat, center.lng);
            
            // Automatically get user's current location
            getCurrentLocationAuto();
        }


    </script>
    
    <!-- Maps Initialization -->
    <script>
        // Initialize map when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Try to use OpenStreetMap directly for better reliability
            setTimeout(function() {
                if (typeof google === 'undefined' || !google.maps) {
                    console.log('Using OpenStreetMap as primary map');
                    initMapFallback();
                } else {
                    console.log('Google Maps available, using it');
                    initMap();
                }
            }, 1000);
        });
        
        // Set a timeout for Google Maps loading
        let mapLoadTimeout = setTimeout(function() {
            if (typeof google === 'undefined' || !google.maps) {
                initMapFallback();
            }
        }, 3000); // 3 seconds timeout
        
        // Clear timeout if Google Maps loads successfully
        function clearMapTimeout() {
            if (mapLoadTimeout) {
                clearTimeout(mapLoadTimeout);
                mapLoadTimeout = null;
            }
        }
    </script>
    
    <!-- Try Google Maps API (with fallback to OpenStreetMap) -->
    <script>
        // Try to load Google Maps API
        (function() {
            const script = document.createElement('script');
            script.async = true;
            script.defer = true;
            script.src = 'https://maps.googleapis.com/maps/api/js?callback=initMap&libraries=places&language=ar&region=SA';
            script.onerror = function() {
                console.log('Google Maps failed to load, using OpenStreetMap');
                initMapFallback();
            };
            document.head.appendChild(script);
        })();
    </script>
    
    <!-- JavaScript -->
    <script>
        let map;
        let marker;
        let selectedLat = null;
        let selectedLng = null;
        
        // Riyadh coordinates as default
        const defaultLocation = { lat: 24.7136, lng: 46.6753 };
        
        function initMap() {
            try {
                // Clear the timeout since Google Maps loaded successfully
                clearMapTimeout();
                
                // Initialize map with better mobile support
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 12,
                    center: defaultLocation,
                    mapTypeId: 'roadmap',
                    gestureHandling: 'greedy',
                    zoomControl: true,
                    mapTypeControl: false,
                    scaleControl: false,
                    streetViewControl: false,
                    rotateControl: false,
                    fullscreenControl: false,
                    styles: [
                        {
                            featureType: 'poi',
                            elementType: 'labels',
                            stylers: [{ visibility: 'off' }]
                        }
                    ]
                });
                
                // Listen for map movement to get center coordinates
                map.addListener('idle', function() {
                    const center = map.getCenter();
                    selectLocation(center.lat(), center.lng());
                });
                
                // Listen for drag events for better responsiveness
                map.addListener('drag', function() {
                    const center = map.getCenter();
                    selectLocation(center.lat(), center.lng());
                });
                
                // Listen for zoom events for better responsiveness
                map.addListener('zoom_changed', function() {
                    const center = map.getCenter();
                    selectLocation(center.lat(), center.lng());
                });

                // Set initial location at center
                selectLocation(defaultLocation.lat, defaultLocation.lng);

                // Automatically get user's current location when page loads
                getCurrentLocationAuto();
                
                console.log('Google Maps loaded successfully');
            } catch (error) {
                console.error('Error initializing Google Maps:', error);
                initMapFallback();
            }
        }
        
        function getCurrentLocationAuto() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        // Center map based on type
                        if (isUsingLeaflet) {
                            map.setView([lat, lng], 15);
                        } else {
                            map.setCenter({ lat: lat, lng: lng });
                            map.setZoom(15);
                        }
                        
                        // Location will be automatically selected when map stops moving
                        console.log('Auto-located user at:', lat, lng);
                    },
                    function(error) {
                        // If location access denied, keep Riyadh as center
                        console.log('Location access denied or not available');
                        
                        // Location will be automatically selected when map stops moving
                        showLocationPermissionMessage();
                    }
                );
            } else {
                console.log('Geolocation not supported');
                
                // Location will be automatically selected when map stops moving
                showLocationPermissionMessage();
            }
        }

        function showLocationPermissionMessage() {
            // Do nothing - message removed as requested
        }
        
        function selectLocation(lat, lng) {
            selectedLat = lat;
            selectedLng = lng;
            
            // Remove any existing markers since we now use fixed center marker
            if (isUsingLeaflet) {
                if (marker) {
                    map.removeLayer(marker);
                    marker = null;
                }
            } else {
                if (marker) {
                    marker.setMap(null);
                    marker = null;
                }
            }
            
            // Update display immediately
            updateLocationDisplay();
            
            // Check coverage for the new location with debouncing
            checkCoverageAuto(lat, lng);
        }
        
        function updateLocationDisplay() {
            const display = document.getElementById('locationDisplay');
            const latSpan = document.getElementById('selectedLat');
            const lngSpan = document.getElementById('selectedLng');
            
            if (selectedLat && selectedLng) {
                // Update coordinates immediately
                latSpan.textContent = selectedLat.toFixed(6);
                lngSpan.textContent = selectedLng.toFixed(6);
                
                // Show display if hidden
                if (display.classList.contains('hidden')) {
                    display.classList.remove('hidden');
                }
            } else {
                display.classList.add('hidden');
            }
        }

        let coverageCheckTimeout;
        let lastCheckedLat = null;
        let lastCheckedLng = null;
        
        function checkCoverageAuto(lat, lng) {
            // Clear previous timeout
            if (coverageCheckTimeout) {
                clearTimeout(coverageCheckTimeout);
            }
            
            // Check if coordinates are the same as last check (avoid unnecessary requests)
            if (lastCheckedLat === lat && lastCheckedLng === lng) {
                return;
            }
            
            // Set new timeout for debouncing
            coverageCheckTimeout = setTimeout(() => {
                // Update last checked coordinates
                lastCheckedLat = lat;
                lastCheckedLng = lng;
                
                // Check coverage silently without showing loading modal
                fetch('{{ route('frontend.booking.check-coverage') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        latitude: lat,
                        longitude: lng
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const coverageStatus = document.getElementById('coverageStatus');
                    
                    if (data.covered) {
                        // Location is covered
                        coverageStatus.innerHTML = `
                            <div class="bg-green-100 border border-green-300 rounded-lg p-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-green-500 p-2 rounded-full">
                                        <i class="fas fa-check text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-green-800">موقعك مشمول بالخدمة</h4>
                                        <p class="text-green-700 text-sm">يمكنك المتابعة لاختيار الموعد المناسب</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        coverageStatus.classList.remove('hidden');
                        document.getElementById('confirmLocation').disabled = false;
                    } else {
                        // Location not covered
                        coverageStatus.innerHTML = `
                            <div class="bg-red-100 border border-red-300 rounded-lg p-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-red-500 p-2 rounded-full">
                                        <i class="fas fa-times text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-red-800">عذراً، موقعك غير مشمول بالخدمة</h4>
                                        <p class="text-red-700 text-sm">${data.message || 'لا تتوفر خدماتنا في منطقتك حالياً'}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        coverageStatus.classList.remove('hidden');
                        document.getElementById('confirmLocation').disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    const coverageStatus = document.getElementById('coverageStatus');
                    coverageStatus.innerHTML = `
                        <div class="bg-red-100 border border-red-300 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-red-500 p-2 rounded-full">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-red-800">حدث خطأ في التحقق من التغطية</h4>
                                    <p class="text-red-700 text-sm">يرجى المحاولة مرة أخرى أو التواصل معنا</p>
                                </div>
                            </div>
                        </div>
                    `;
                    coverageStatus.classList.remove('hidden');
                });
            }, 200); // 200ms debounce delay for faster response
        }
        

        
        // Confirm location and proceed
        document.getElementById('confirmLocation').addEventListener('click', function() {
            if (!selectedLat || !selectedLng) {
                alert('يرجى تحديد موقعك على الخريطة');
                return;
            }
            
            // Redirect to next step
            window.location.href = '{{ route('frontend.booking.select-datetime') }}';
        });
        
        // Debug session data on page load
        console.log('=== Select Location Page Debug ===');
        console.log('Session booking data:', {
            product_name: @json(session('booking.product_name')),
            base_price: @json(session('booking.base_price')),
            add_ons: @json(session('booking.add_ons', [])),
            addons_price: @json(session('booking.addons_price', 0)),
            total_price: @json(session('booking.total_price', 0))
        });

        // Flash messages
        @if(session('success'))
            alert('{{ session('success') }}');
        @endif

        @if(session('error'))
            alert('{{ session('error') }}');
        @endif
    </script>
</body>
</html> 