@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">إضافة عميل جديد</h1>
                        <p class="text-gray-600 mt-1">قم بملء البيانات التالية لإضافة عميل جديد للنظام</p>
                    </div>
                    <a href="{{ route('admin.customers.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-arrow-right"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.customers.store') }}" id="customerForm">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- البيانات الأساسية -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-user text-blue-600"></i>
                            البيانات الأساسية
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-6">
                        <!-- اسم العميل -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم العميل <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="أدخل اسم العميل" required>
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

                        <!-- نوع العميل -->
                        <div>
                            <label for="customer_type" class="block text-sm font-medium text-gray-700 mb-2">
                                نوع العميل <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('customer_type') border-red-500 @enderror" 
                                    id="customer_type" name="customer_type" required>
                                <option value="">اختر نوع العميل</option>
                                <option value="individual" {{ old('customer_type') == 'individual' ? 'selected' : '' }}>
                                    أفراد
                                </option>
                            </select>
                            @error('customer_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- معلومات الموقع -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-green-600"></i>
                            معلومات الموقع
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-6">
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

                        <!-- الخريطة -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                تحديد الموقع على الخريطة
                            </label>
                            <div id="map" class="w-full h-80 rounded-lg border border-gray-300 bg-gray-100"></div>
                            <p class="mt-2 text-sm text-gray-500 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i>
                                انقر على الخريطة لتحديد موقع العميل (اختياري)
                            </p>
                        </div>

                        <!-- أزرار الموقع -->
                        <div class="flex flex-wrap gap-3">
                            <button type="button" 
                                    class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2" 
                                    onclick="getCurrentLocation()">
                                <i class="fas fa-location-arrow"></i>
                                موقعي الحالي
                            </button>
                            <button type="button" 
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2" 
                                    onclick="clearLocation()">
                                <i class="fas fa-times"></i>
                                مسح الموقع
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
                        <a href="{{ route('admin.customers.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            حفظ العميل
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let map;
let marker;

// تهيئة الخريطة (بدون Google Maps API للاختبار)
function initMap() {
    const mapElement = document.getElementById("map");
    mapElement.innerHTML = `
        <div class="h-full w-full flex items-center justify-center bg-gray-100 rounded-lg">
            <div class="text-center">
                <i class="fas fa-map-marked-alt text-gray-400 text-4xl mb-2"></i>
                <p class="text-gray-500">خريطة تفاعلية</p>
                <p class="text-sm text-gray-400">انقر لتحديد الموقع</p>
            </div>
        </div>
    `;
    
    mapElement.addEventListener('click', function() {
        // محاكاة اختيار موقع عشوائي في الرياض
        const lat = 24.7136 + (Math.random() - 0.5) * 0.1;
        const lng = 46.6753 + (Math.random() - 0.5) * 0.1;
        
        updateLocationFields({
            lat: () => lat,
            lng: () => lng
        });
        
        mapElement.innerHTML = `
            <div class="h-full w-full flex items-center justify-center bg-green-100 rounded-lg">
                <div class="text-center">
                    <i class="fas fa-map-marker-alt text-green-600 text-4xl mb-2"></i>
                    <p class="text-green-700 font-medium">تم تحديد الموقع</p>
                    <p class="text-sm text-green-600">${lat.toFixed(6)}, ${lng.toFixed(6)}</p>
                </div>
            </div>
        `;
    });
}

// تحديث حقول الموقع
function updateLocationFields(location) {
    document.getElementById('latitude').value = location.lat().toFixed(8);
    document.getElementById('longitude').value = location.lng().toFixed(8);
}

// الحصول على الموقع الحالي
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const pos = {
                    lat: () => position.coords.latitude,
                    lng: () => position.coords.longitude,
                };
                updateLocationFields(pos);
                
                const mapElement = document.getElementById("map");
                mapElement.innerHTML = `
                    <div class="h-full w-full flex items-center justify-center bg-blue-100 rounded-lg">
                        <div class="text-center">
                            <i class="fas fa-location-arrow text-blue-600 text-4xl mb-2"></i>
                            <p class="text-blue-700 font-medium">موقعك الحالي</p>
                            <p class="text-sm text-blue-600">${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}</p>
                        </div>
                    </div>
                `;
                
                // محاكاة العنوان
                document.getElementById('address').value = 'الموقع الحالي - الرياض، المملكة العربية السعودية';
            },
            () => {
                alert('فشل في الحصول على موقعك الحالي');
            }
        );
    } else {
        alert('المتصفح لا يدعم تحديد الموقع الجغرافي');
    }
}

// مسح الموقع
function clearLocation() {
    document.getElementById('latitude').value = '';
    document.getElementById('longitude').value = '';
    document.getElementById('address').value = '';
    
    const mapElement = document.getElementById("map");
    mapElement.innerHTML = `
        <div class="h-full w-full flex items-center justify-center bg-gray-100 rounded-lg">
            <div class="text-center">
                <i class="fas fa-map-marked-alt text-gray-400 text-4xl mb-2"></i>
                <p class="text-gray-500">خريطة تفاعلية</p>
                <p class="text-sm text-gray-400">انقر لتحديد الموقع</p>
            </div>
        </div>
    `;
}

// التحقق من رقم الجوال
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 9) {
        value = value.substring(0, 9);
    }
    e.target.value = value;
});

// التحقق من النموذج قبل الإرسال
document.getElementById('customerForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const customerType = document.getElementById('customer_type').value;

    if (!name) {
        e.preventDefault();
        alert('يرجى إدخال اسم العميل');
        document.getElementById('name').focus();
        return;
    }

    if (!phone || phone.length < 9) {
        e.preventDefault();
        alert('يرجى إدخال رقم جوال صحيح (9 أرقام)');
        document.getElementById('phone').focus();
        return;
    }

    if (!customerType) {
        e.preventDefault();
        alert('يرجى اختيار نوع العميل');
        document.getElementById('customer_type').focus();
        return;
    }
});

// تهيئة الخريطة عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});
</script>
@endpush
