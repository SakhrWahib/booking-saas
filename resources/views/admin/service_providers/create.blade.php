@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">إضافة مقدم خدمة جديد</h1>
                        <p class="text-gray-600 mt-1">قم بملء البيانات التالية لإضافة مقدم خدمة جديد للنظام</p>
                    </div>
                    <a href="{{ route('admin.service-providers.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-arrow-right"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.service-providers.store') }}" id="providerForm">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- البيانات الأساسية -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-user-cog text-blue-600"></i>
                            البيانات الأساسية
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-6">
                        <!-- اسم مقدم الخدمة -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم مقدم الخدمة <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="أدخل اسم مقدم الخدمة" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- رقم الجوال -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الجوال <span class="text-red-500">*</span>
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-r-md">
                                    <i class="fas fa-phone text-green-600 mr-2"></i>
                                    +966
                                </span>
                                <input type="tel" 
                                       class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('phone') border-red-500 @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       placeholder="5XXXXXXXX" maxlength="9" required>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">مثال: 551234567</p>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                البريد الإلكتروني
                            </label>
                            <input type="email" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('email') border-red-500 @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="example@email.com">
                            <p class="mt-1 text-sm text-gray-500">اختياري</p>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- إعدادات الخدمة -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-cogs text-purple-600"></i>
                            إعدادات الخدمة
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-6">
                        <!-- السعة -->
                        <div>
                            <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">
                                السعة (عدد الحجوزات المتزامنة) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('capacity') border-red-500 @enderror" 
                                   id="capacity" name="capacity" value="{{ old('capacity', 3) }}" 
                                   min="1" max="50" required>
                            <p class="mt-1 text-sm text-gray-500">عدد الحجوزات التي يمكن لمقدم الخدمة التعامل معها في نفس الوقت</p>
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- نوع العمل -->
                        <div>
                            <label for="work_type" class="block text-sm font-medium text-gray-700 mb-2">
                                نوع العمل <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('work_type') border-red-500 @enderror" 
                                    id="work_type" name="work_type" required>
                                <option value="">اختر نوع العمل</option>
                                <option value="single_person" {{ old('work_type') == 'single_person' ? 'selected' : '' }}>
                                    شخص واحد فقط
                                </option>
                                <option value="multiple_persons" {{ old('work_type') == 'multiple_persons' ? 'selected' : '' }}>
                                    عدة أشخاص في نفس الوقت
                                </option>
                            </select>
                            @error('work_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- جدول العمل -->
                        <div>
                            <label for="working_schedule_id" class="block text-sm font-medium text-gray-700 mb-2">
                                جدول ساعات العمل <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('working_schedule_id') border-red-500 @enderror" 
                                    id="working_schedule_id" name="working_schedule_id" required>
                                <option value="">اختر جدول العمل</option>
                                @foreach($workingSchedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('working_schedule_id') == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->schedule_name }}
                                        @if($schedule->is_default)
                                            (افتراضي)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">يمكنك إنشاء جداول عمل جديدة من قسم إدارة جداول العمل</p>
                            @error('working_schedule_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- نوع التغطية -->
                        <div>
                            <label for="coverage_type" class="block text-sm font-medium text-gray-700 mb-2">
                                نوع التغطية الجغرافية <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('coverage_type') border-red-500 @enderror" 
                                    id="coverage_type" name="coverage_type" required onchange="toggleCoverageOptions()">
                                <option value="">اختر نوع التغطية</option>
                                <option value="area_based" {{ old('coverage_type') == 'area_based' ? 'selected' : '' }}>
                                    التعيين على مناطق الخدمة
                                </option>
                                <option value="nearest_booking" {{ old('coverage_type') == 'nearest_booking' ? 'selected' : '' }}>
                                    التعيين على أقرب حجز
                                </option>
                            </select>
                            @error('coverage_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الحالة -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                       name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="mr-2 text-sm text-gray-700">مفعل ونشط</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات الموقع والتغطية -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-map-marked-alt text-green-600"></i>
                        معلومات الموقع والتغطية الجغرافية
                    </h3>
                </div>
                
                <div class="px-6 py-6 space-y-6">
                    <!-- معلومات الموقع -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="text-md font-medium text-gray-800">الموقع الأساسي لمقدم الخدمة</h4>
                            
                            <!-- العنوان -->
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    العنوان التفصيلي
                                </label>
                                <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('address') border-red-500 @enderror" 
                                          id="address" name="address" rows="3" 
                                          placeholder="أدخل العنوان التفصيلي">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- الإحداثيات -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                                        خط العرض
                                    </label>
                                    <input type="number" step="0.00000001" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 text-right @error('latitude') border-red-500 @enderror" 
                                           id="latitude" name="latitude" value="{{ old('latitude') }}" 
                                           placeholder="24.7136" readonly>
                                    @error('latitude')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                                        خط الطول
                                    </label>
                                    <input type="number" step="0.00000001" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 text-right @error('longitude') border-red-500 @enderror" 
                                           id="longitude" name="longitude" value="{{ old('longitude') }}" 
                                           placeholder="46.6753" readonly>
                                    @error('longitude')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h4 class="text-md font-medium text-gray-800">معلومات إضافية</h4>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-info-circle text-blue-600"></i>
                                    <span class="text-sm font-medium text-blue-800">ملاحظة</span>
                                </div>
                                <p class="text-xs text-blue-600">
                                    أيام العطل والجداول الخاصة (مثل جدول رمضان) يتم إدارتها من خلال جداول العمل المختارة.
                                    يمكنك إنشاء جداول عمل جديدة تحتوي على إعدادات خاصة للعطل والمناسبات.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- الخريطة -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            تحديد الموقع ومنطقة التغطية على الخريطة
                        </label>
                        <div id="map" class="w-full h-96 rounded-lg border border-gray-300 bg-gray-100"></div>
                        <input type="hidden" name="coverage_area" id="coverage_area_input">
                        
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                            <div id="coverage_instructions" class="text-sm text-blue-800">
                                <div id="area_based_instructions" class="hidden">
                                    <p class="font-medium mb-2">تعليمات رسم منطقة التغطية:</p>
                                    <ul class="space-y-1">
                                        <li>• انقر على أدوات الرسم أعلى الخريطة</li>
                                        <li>• اختر أداة المضلع (Polygon)</li>
                                        <li>• انقر على نقاط متعددة لرسم حدود منطقة التغطية</li>
                                        <li>• انقر على النقطة الأولى مرة أخرى لإنهاء الرسم</li>
                                        <li>• ستحفظ الإحداثيات تلقائياً</li>
                                    </ul>
                                </div>
                                <div id="nearest_booking_instructions" class="hidden">
                                    <p class="font-medium mb-2">تحديد الموقع الأساسي:</p>
                                    <ul class="space-y-1">
                                        <li>• انقر على الخريطة لتحديد الموقع الأساسي لمقدم الخدمة</li>
                                        <li>• سيتم تعيين الحجوزات بناءً على القرب من هذا الموقع</li>
                                        <li>• ستحفظ الإحداثيات تلقائياً</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- أزرار الموقع -->
                        <div class="flex flex-wrap gap-3 mt-4">
                            <button type="button" 
                                    class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2" 
                                    onclick="getCurrentLocation()">
                                <i class="fas fa-location-arrow"></i>
                                موقعي الحالي
                            </button>
                            <button type="button" 
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2" 
                                    onclick="clearMap()">
                                <i class="fas fa-times"></i>
                                مسح الخريطة
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        تأكد من صحة البيانات قبل الحفظ. يجب اختيار جدول عمل من القائمة.
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.service-providers.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            حفظ مقدم الخدمة
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=drawing,places&callback=initMap" async defer></script>
<script>
let map;
let drawingManager;
let currentPolygon;
let currentMarker;
let coverageType = '';

function initMap() {
    // Default to Riyadh
    const riyadh = { lat: 24.7136, lng: 46.6753 };
    
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 11,
        center: riyadh,
        mapTypeId: 'roadmap'
    });

    // Drawing Manager
    drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: null,
        drawingControl: true,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: ['polygon', 'marker']
        },
        polygonOptions: {
            fillColor: '#4F46E5',
            fillOpacity: 0.3,
            strokeColor: '#4F46E5',
            strokeWeight: 2,
            clickable: false,
            editable: true
        },
        markerOptions: {
            draggable: true
        }
    });

    drawingManager.setMap(map);

    // Event listeners
    drawingManager.addListener('polygoncomplete', function(polygon) {
        if (currentPolygon) {
            currentPolygon.setMap(null);
        }
        currentPolygon = polygon;
        saveCoverageArea();
        
        // Update coordinates from polygon center
        const bounds = new google.maps.LatLngBounds();
        polygon.getPath().forEach(function(latLng) {
            bounds.extend(latLng);
        });
        const center = bounds.getCenter();
        document.getElementById('latitude').value = center.lat();
        document.getElementById('longitude').value = center.lng();
    });

    drawingManager.addListener('markercomplete', function(marker) {
        if (currentMarker) {
            currentMarker.setMap(null);
        }
        currentMarker = marker;
        
        const position = marker.getPosition();
        document.getElementById('latitude').value = position.lat();
        document.getElementById('longitude').value = position.lng();

        // Add drag listener
        marker.addListener('dragend', function() {
            const newPosition = marker.getPosition();
            document.getElementById('latitude').value = newPosition.lat();
            document.getElementById('longitude').value = newPosition.lng();
        });
    });

    // Simple click for nearest_booking type
    map.addListener('click', function(e) {
        if (coverageType === 'nearest_booking') {
            if (currentMarker) {
                currentMarker.setMap(null);
            }
            currentMarker = new google.maps.Marker({
                position: e.latLng,
                map: map,
                draggable: true
            });

            document.getElementById('latitude').value = e.latLng.lat();
            document.getElementById('longitude').value = e.latLng.lng();

            currentMarker.addListener('dragend', function() {
                const newPosition = currentMarker.getPosition();
                document.getElementById('latitude').value = newPosition.lat();
                document.getElementById('longitude').value = newPosition.lng();
            });
        }
    });
}

function toggleCoverageOptions() {
    coverageType = document.getElementById('coverage_type').value;
    
    // Hide all instructions
    document.getElementById('area_based_instructions').classList.add('hidden');
    document.getElementById('nearest_booking_instructions').classList.add('hidden');
    
    // Clear existing drawings
    clearMap();
    
    if (coverageType === 'area_based') {
        document.getElementById('area_based_instructions').classList.remove('hidden');
        drawingManager.setOptions({
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: ['polygon']
            }
        });
    } else if (coverageType === 'nearest_booking') {
        document.getElementById('nearest_booking_instructions').classList.remove('hidden');
        drawingManager.setOptions({
            drawingControl: false
        });
    }
}

function saveCoverageArea() {
    if (currentPolygon && coverageType === 'area_based') {
        const path = currentPolygon.getPath();
        const coordinates = [];
        
        for (let i = 0; i < path.getLength(); i++) {
            const point = path.getAt(i);
            coordinates.push({
                lat: point.lat(),
                lng: point.lng()
            });
        }
        
        const coverageArea = {
            type: 'polygon',
            coordinates: [coordinates]
        };
        
        document.getElementById('coverage_area_input').value = JSON.stringify(coverageArea);
    }
}

function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            
            map.setCenter(pos);
            map.setZoom(15);
            
            if (currentMarker) {
                currentMarker.setMap(null);
            }
            
            currentMarker = new google.maps.Marker({
                position: pos,
                map: map,
                draggable: true
            });
            
            document.getElementById('latitude').value = pos.lat;
            document.getElementById('longitude').value = pos.lng;
            
            currentMarker.addListener('dragend', function() {
                const newPosition = currentMarker.getPosition();
                document.getElementById('latitude').value = newPosition.lat();
                document.getElementById('longitude').value = newPosition.lng();
            });
        });
    }
}

function clearMap() {
    if (currentPolygon) {
        currentPolygon.setMap(null);
        currentPolygon = null;
    }
    if (currentMarker) {
        currentMarker.setMap(null);
        currentMarker = null;
    }
    document.getElementById('coverage_area_input').value = '';
    document.getElementById('latitude').value = '';
    document.getElementById('longitude').value = '';
}

// Form submission handler
document.getElementById('providerForm').addEventListener('submit', function(e) {
    // Save coverage area if polygon exists
    if (currentPolygon && coverageType === 'area_based') {
        saveCoverageArea();
    }
});

// Initialize coverage options on page load
document.addEventListener('DOMContentLoaded', function() {
    const coverageTypeSelect = document.getElementById('coverage_type');
    if (coverageTypeSelect.value) {
        toggleCoverageOptions();
    }
});
</script>
@endpush
