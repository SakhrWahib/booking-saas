@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">تعديل بيانات مقدم الخدمة</h1>
                        <p class="text-gray-600 mt-1">تعديل بيانات مقدم الخدمة: <span class="font-semibold text-blue-600">{{ $serviceProvider->name }}</span></p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.service-providers.show', $serviceProvider) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-eye"></i>
                            عرض التفاصيل
                        </a>
                        <a href="{{ route('admin.service-providers.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.service-providers.update', $serviceProvider) }}" id="providerForm">
            @csrf
            @method('PUT')
            
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
                        <!-- معلومات مقدم الخدمة -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($serviceProvider->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $serviceProvider->name }}</h4>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span>رقم المقدم: <span class="font-semibold">#{{ $serviceProvider->id }}</span></span>
                                        <span>تاريخ التسجيل: {{ $serviceProvider->created_at->format('Y/m/d') }}</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $serviceProvider->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $serviceProvider->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- اسم مقدم الخدمة -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم مقدم الخدمة <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name', $serviceProvider->name) }}" 
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
                                       id="phone" name="phone" value="{{ old('phone', $serviceProvider->phone) }}" 
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
                                   id="email" name="email" value="{{ old('email', $serviceProvider->email) }}" 
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
                                   id="capacity" name="capacity" value="{{ old('capacity', $serviceProvider->capacity) }}" 
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
                                <option value="single_person" {{ old('work_type', $serviceProvider->work_type) == 'single_person' ? 'selected' : '' }}>
                                    شخص واحد فقط
                                </option>
                                <option value="multiple_persons" {{ old('work_type', $serviceProvider->work_type) == 'multiple_persons' ? 'selected' : '' }}>
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
                                    <option value="{{ $schedule->id }}" {{ old('working_schedule_id', $serviceProvider->working_schedule_id) == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->schedule_name }}
                                        @if($schedule->is_default)
                                            (افتراضي)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">
                                @if($serviceProvider->workingSchedule)
                                    الجدول الحالي: {{ $serviceProvider->workingSchedule->schedule_name }}
                                @else
                                    لم يتم اختيار جدول عمل
                                @endif
                            </p>
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
                                <option value="area_based" {{ old('coverage_type', $serviceProvider->coverage_type) == 'area_based' ? 'selected' : '' }}>
                                    التعيين على مناطق الخدمة
                                </option>
                                <option value="nearest_booking" {{ old('coverage_type', $serviceProvider->coverage_type) == 'nearest_booking' ? 'selected' : '' }}>
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
                                       name="is_active" value="1" {{ old('is_active', $serviceProvider->is_active) ? 'checked' : '' }}>
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
                    <!-- حالة التغطية -->
                    @if($serviceProvider->coverage_area)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-map-marked-alt text-green-600 text-xl"></i>
                            <div>
                                <h4 class="font-medium text-green-800">منطقة التغطية محددة مسبقاً</h4>
                                <p class="text-sm text-green-600">
                                    نوع التغطية: {{ $serviceProvider->coverage_type == 'area_based' ? 'مناطق محددة' : 'أقرب حجز' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                            <div>
                                <h4 class="font-medium text-yellow-800">منطقة التغطية غير محددة</h4>
                                <p class="text-sm text-yellow-600">يمكنك تحديد منطقة التغطية باستخدام الخريطة أدناه</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- الموقع الأساسي -->
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
                                          placeholder="أدخل العنوان التفصيلي">{{ old('address', $serviceProvider->address) }}</textarea>
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
                                           id="latitude" name="latitude" value="{{ old('latitude', $serviceProvider->latitude) }}" 
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
                                           id="longitude" name="longitude" value="{{ old('longitude', $serviceProvider->longitude) }}" 
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
                                    لتعديل أيام العطل، يجب تعديل جدول العمل المرتبط أو اختيار جدول آخر.
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
                        <input type="hidden" name="coverage_area" id="coverage_area_input" value="{{ old('coverage_area', json_encode($serviceProvider->coverage_area)) }}">
                        
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                            <div id="coverage_instructions" class="text-sm text-blue-800">
                                <div id="area_based_instructions" class="hidden">
                                    <p class="font-medium mb-2">تعليمات رسم وتعديل منطقة التغطية:</p>
                                    <ul class="space-y-1">
                                        <li>• <strong>للرسم الجديد:</strong> انقر على أداة المضلع أعلى الخريطة ثم انقر نقاط متعددة</li>
                                        <li>• <strong>للتعديل:</strong> اسحب النقاط الموجودة على المضلع لتغيير الحدود</li>
                                        <li>• <strong>إضافة نقطة:</strong> انقر في منتصف أي خط لإضافة نقطة جديدة</li>
                                        <li>• <strong>حذف نقطة:</strong> انقر بالزر الأيمن على النقطة واختر حذف</li>
                                        <li>• ✅ <strong>الحفظ التلقائي:</strong> ستحفظ التغييرات تلقائياً عند التعديل</li>
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
                        تأكد من صحة البيانات قبل الحفظ
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.service-providers.show', $serviceProvider) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            حفظ التغييرات
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
let coverageType = '{{ $serviceProvider->coverage_type }}';

function initMap() {
    // Default to existing location or Riyadh
    const defaultLocation = {
        lat: {{ $serviceProvider->latitude ?? 24.7136 }},
        lng: {{ $serviceProvider->longitude ?? 46.6753 }}
    };
    
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 11,
        center: defaultLocation,
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
            fillOpacity: 0.4,
            strokeColor: '#1E40AF',
            strokeWeight: 3,
            clickable: true,
            editable: true,
            draggable: false
        },
        markerOptions: {
            draggable: true
        }
    });

    drawingManager.setMap(map);

    // Load existing data FIRST
    loadExistingData();
    
    // Then setup coverage options
    setTimeout(() => {
        toggleCoverageOptions();
    }, 500);

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

function loadExistingData() {
    console.log('Loading existing data...');
    
    // Use setTimeout to ensure map is fully loaded
    setTimeout(() => {
        // Load existing marker
        @if($serviceProvider->latitude && $serviceProvider->longitude)
        const markerPosition = {
            lat: {{ $serviceProvider->latitude }},
            lng: {{ $serviceProvider->longitude }}
        };
        
        if (currentMarker) {
            currentMarker.setMap(null);
        }
        
        currentMarker = new google.maps.Marker({
            position: markerPosition,
            map: map,
            draggable: true,
            title: '{{ $serviceProvider->name }}'
        });

        currentMarker.addListener('dragend', function() {
            const newPosition = currentMarker.getPosition();
            document.getElementById('latitude').value = newPosition.lat();
            document.getElementById('longitude').value = newPosition.lng();
        });
        
        console.log('Marker loaded at:', markerPosition);
        @endif

        // Load existing coverage area - ALWAYS load if exists
        @if($serviceProvider->coverage_area)
        console.log('🔄 Starting to load existing coverage area...');
        
        // Wait for map to be fully initialized
        setTimeout(() => {
            try {
                const coverageData = @json($serviceProvider->coverage_area);
                console.log('📊 Raw coverage data:', coverageData);
                
                if (coverageData && coverageData.coordinates && Array.isArray(coverageData.coordinates) && coverageData.coordinates[0]) {
                    const rawCoordinates = coverageData.coordinates[0];
                    console.log('📍 Raw coordinates array:', rawCoordinates);
                    
                    // More robust coordinate parsing
                    const coordinates = [];
                    rawCoordinates.forEach((coord, index) => {
                        if (coord && (coord.lat || coord.latitude) && (coord.lng || coord.longitude)) {
                            const lat = parseFloat(coord.lat || coord.latitude);
                            const lng = parseFloat(coord.lng || coord.longitude);
                            
                            if (!isNaN(lat) && !isNaN(lng)) {
                                coordinates.push({ lat: lat, lng: lng });
                                console.log(`✅ Point ${index + 1}: ${lat}, ${lng}`);
                            } else {
                                console.warn(`⚠️ Invalid coordinate at index ${index}:`, coord);
                            }
                        }
                    });

                    console.log('✅ Final parsed coordinates:', coordinates);

                    if (coordinates.length >= 3) { // Need at least 3 points for a polygon
                        // Remove existing polygon if any
                        if (currentPolygon) {
                            currentPolygon.setMap(null);
                            console.log('🗑️ Removed existing polygon');
                        }

                        // Create polygon with enhanced visibility and editing
                        currentPolygon = new google.maps.Polygon({
                            paths: coordinates,
                            fillColor: '#4F46E5',
                            fillOpacity: 0.5,
                            strokeColor: '#1E40AF',
                            strokeWeight: 4,
                            editable: true,
                            draggable: false,
                            clickable: true
                        });

                        // Add polygon to map
                        currentPolygon.setMap(map);
                        console.log('🎯 Polygon added to map with', coordinates.length, 'points');
                        
                        // Add comprehensive event listeners for polygon editing
                        const path = currentPolygon.getPath();
                        
                        // Listen for path changes
                        google.maps.event.addListener(path, 'set_at', function(index) {
                            console.log('📝 Polygon point', index, 'moved');
                            saveCoverageArea();
                        });
                        
                        google.maps.event.addListener(path, 'insert_at', function(index) {
                            console.log('➕ New point added at index', index);
                            saveCoverageArea();
                        });
                        
                        google.maps.event.addListener(path, 'remove_at', function(index) {
                            console.log('➖ Point removed at index', index);
                            saveCoverageArea();
                        });
                        
                        // Add click listener to polygon itself
                        google.maps.event.addListener(currentPolygon, 'click', function(event) {
                            console.log('🖱️ Polygon clicked at:', event.latLng.toString());
                        });
                        
                        // Fit map to show the polygon completely
                        const bounds = new google.maps.LatLngBounds();
                        coordinates.forEach(coord => {
                            bounds.extend(new google.maps.LatLng(coord.lat, coord.lng));
                        });
                        
                        map.fitBounds(bounds);
                        console.log('🗺️ Map bounds adjusted to show polygon');
                        
                        // Add a slight zoom out after fitting bounds
                        setTimeout(() => {
                            const currentZoom = map.getZoom();
                            if (currentZoom > 15) {
                                map.setZoom(Math.min(currentZoom - 1, 15));
                                console.log('🔍 Adjusted zoom for better view');
                            }
                        }, 1000);
                        
                        // Force visibility - make sure polygon is shown
                        setTimeout(() => {
                            if (currentPolygon && currentPolygon.getMap() !== map) {
                                currentPolygon.setMap(map);
                                console.log('🔄 Re-attached polygon to map');
                            }
                        }, 1500);
                        
                        console.log('✅ Coverage area polygon fully loaded and ready for editing!');
                        
                    } else {
                        console.warn('⚠️ Not enough valid coordinates for polygon. Found:', coordinates.length, 'Need at least 3');
                    }
                } else {
                    console.warn('⚠️ Invalid coverage data structure:', coverageData);
                }
            } catch (e) {
                console.error('❌ Error loading coverage area:', e);
                console.error('📊 Original data:', @json($serviceProvider->coverage_area));
            }
        }, 1000); // Increased timeout for better reliability
        @else
        console.log('ℹ️ No existing coverage area to load');
        @endif
    }, 100);
}

function toggleCoverageOptions() {
    coverageType = document.getElementById('coverage_type').value;
    console.log('🔄 Coverage type changed to:', coverageType);
    
    // Hide all instructions
    document.getElementById('area_based_instructions').classList.add('hidden');
    document.getElementById('nearest_booking_instructions').classList.add('hidden');
    
    if (coverageType === 'area_based') {
        document.getElementById('area_based_instructions').classList.remove('hidden');
        if (drawingManager) {
            drawingManager.setOptions({
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: ['polygon']
                }
            });
        }
        console.log('📍 Area-based coverage enabled - you can draw polygons');
        
        // Show existing polygon if it exists
        if (currentPolygon) {
            currentPolygon.setMap(map);
            currentPolygon.setOptions({
                editable: true,
                draggable: false
            });
            console.log('👁️ Showing and enabling editing for existing coverage polygon');
        }
    } else if (coverageType === 'nearest_booking') {
        document.getElementById('nearest_booking_instructions').classList.remove('hidden');
        if (drawingManager) {
            drawingManager.setOptions({
                drawingControl: false
            });
        }
        console.log('📍 Nearest booking coverage enabled - click to set location');
        
        // Hide polygon if not area_based
        if (currentPolygon) {
            currentPolygon.setMap(null);
            console.log('👁️ Hiding coverage polygon (not area_based)');
        }
    }
}

function saveCoverageArea() {
    const currentCoverageType = document.getElementById('coverage_type').value;
    
    if (currentPolygon && currentCoverageType === 'area_based') {
        const path = currentPolygon.getPath();
        const coordinates = [];
        
        for (let i = 0; i < path.getLength(); i++) {
            const point = path.getAt(i);
            coordinates.push({
                lat: parseFloat(point.lat().toFixed(8)),
                lng: parseFloat(point.lng().toFixed(8))
            });
        }
        
        if (coordinates.length >= 3) { // Valid polygon needs at least 3 points
            const coverageArea = {
                type: 'polygon',
                coordinates: [coordinates]
            };
            
            document.getElementById('coverage_area_input').value = JSON.stringify(coverageArea);
            console.log('💾 Coverage area saved with', coordinates.length, 'points');
            console.log('📍 Coordinates:', coordinates);
        } else {
            console.warn('⚠️ Cannot save polygon with less than 3 points. Current:', coordinates.length);
        }
    } else if (!currentPolygon) {
        console.log('ℹ️ No polygon to save');
    } else if (currentCoverageType !== 'area_based') {
        console.log('ℹ️ Coverage type is not area_based, clearing coverage area');
        document.getElementById('coverage_area_input').value = '';
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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial coverage type from server data
    coverageType = '{{ $serviceProvider->coverage_type }}';
    console.log('🚀 Page loaded - Initial coverage type:', coverageType);
    
    // Ensure the coverage type dropdown shows the correct value
    const coverageTypeSelect = document.getElementById('coverage_type');
    if (coverageTypeSelect && coverageTypeSelect.value !== coverageType) {
        coverageTypeSelect.value = coverageType;
        console.log('✅ Coverage type dropdown synchronized');
    }
    
    // Call toggle function to ensure proper UI state
    setTimeout(() => {
        toggleCoverageOptions();
        console.log('🔄 Initial coverage options setup complete');
    }, 2000);
});
</script>
@endpush
