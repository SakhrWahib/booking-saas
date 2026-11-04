@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">تعديل بيانات العميل</h1>
                        <p class="text-gray-600 mt-1">تعديل بيانات العميل: <span class="font-semibold text-blue-600">{{ $customer->name }}</span></p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.customers.show', $customer) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-eye"></i>
                            عرض التفاصيل
                        </a>
                        <a href="{{ route('admin.customers.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.customers.update', $customer) }}" id="customerForm">
            @csrf
            @method('PUT')
            
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
                        <!-- معلومات العميل -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($customer->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $customer->name }}</h4>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span>رقم العميل: <span class="font-semibold">#{{ $customer->id }}</span></span>
                                        <span>تاريخ التسجيل: {{ $customer->created_at->format('Y/m/d') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- اسم العميل -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم العميل <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name', $customer->name) }}" 
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
                                       id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" 
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
                                   id="email" name="email" value="{{ old('email', $customer->email) }}" 
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
                                <option value="individual" {{ old('customer_type', $customer->customer_type) == 'individual' ? 'selected' : '' }}>
                                    أفراد
                                </option>
                            </select>
                            @error('customer_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- إحصائيات سريعة -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <div class="text-lg font-bold text-blue-600">{{ $customer->bookings()->count() }}</div>
                                <div class="text-xs text-gray-600">إجمالي الحجوزات</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <div class="text-lg font-bold text-green-600">{{ $customer->activeBookings()->count() }}</div>
                                <div class="text-xs text-gray-600">الحجوزات النشطة</div>
                            </div>
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
                        <!-- حالة الموقع -->
                        @if($customer->latitude && $customer->longitude)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                                <div>
                                    <h4 class="font-medium text-green-800">الموقع محدد مسبقاً</h4>
                                    <p class="text-sm text-green-600">
                                        خط العرض: {{ $customer->latitude }} | خط الطول: {{ $customer->longitude }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                                <div>
                                    <h4 class="font-medium text-yellow-800">الموقع غير محدد</h4>
                                    <p class="text-sm text-yellow-600">يمكنك تحديد موقع العميل باستخدام الخريطة أدناه</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- العنوان -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                العنوان التفصيلي
                            </label>
                            <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('address') border-red-500 @enderror" 
                                      id="address" name="address" rows="3" 
                                      placeholder="أدخل العنوان التفصيلي">{{ old('address', $customer->address) }}</textarea>
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
                                       id="latitude" name="latitude" value="{{ old('latitude', $customer->latitude) }}" 
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
                                       id="longitude" name="longitude" value="{{ old('longitude', $customer->longitude) }}" 
                                       placeholder="46.6753" readonly>
                                @error('longitude')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- الخريطة -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                تحديث الموقع على الخريطة
                            </label>
                            <div id="map" class="w-full h-80 rounded-lg border border-gray-300 bg-gray-100"></div>
                            <p class="mt-2 text-sm text-gray-500 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i>
                                انقر على الخريطة لتحديث موقع العميل
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
                            @if($customer->latitude && $customer->longitude)
                            <button type="button" 
                                    class="bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2" 
                                    onclick="restoreOriginalLocation()">
                                <i class="fas fa-undo"></i>
                                الموقع الأصلي
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        @if($customer->bookings()->count() == 0)
                        <button type="button" 
                                class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2" 
                                onclick="confirmDelete()">
                            <i class="fas fa-trash"></i>
                            حذف العميل
                        </button>
                        @else
                        <div class="text-sm text-amber-600 flex items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            لا يمكن حذف العميل لوجود {{ $customer->bookings()->count() }} حجز مرتبط به
                        </div>
                        @endif
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
                            حفظ التغييرات
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">تأكيد حذف العميل</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">هل أنت متأكد من حذف العميل <strong>{{ $customer->name }}</strong>؟</p>
                <p class="text-sm text-red-500 mt-2">هذا الإجراء لا يمكن التراجع عنه.</p>
            </div>
            <div class="flex gap-3 px-4 py-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    إلغاء
                </button>
                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        حذف نهائياً
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let map;
let marker;
const originalLocation = {
    lat: {{ $customer->latitude ?? 'null' }},
    lng: {{ $customer->longitude ?? 'null' }}
};

// تهيئة الخريطة
function initMap() {
    const mapElement = document.getElementById("map");
    
    @if($customer->latitude && $customer->longitude)
    // عرض الموقع الحالي
    mapElement.innerHTML = `
        <div class="h-full w-full flex items-center justify-center bg-green-100 rounded-lg">
            <div class="text-center">
                <i class="fas fa-map-marker-alt text-green-600 text-4xl mb-2"></i>
                <p class="text-green-700 font-medium">الموقع المحفوظ</p>
                <p class="text-sm text-green-600">{{ $customer->latitude }}, {{ $customer->longitude }}</p>
                <button onclick="editLocation()" class="mt-2 bg-green-600 text-white px-3 py-1 rounded text-sm">تعديل الموقع</button>
            </div>
        </div>
    `;
    @else
    // لا يوجد موقع محفوظ
    mapElement.innerHTML = `
        <div class="h-full w-full flex items-center justify-center bg-gray-100 rounded-lg">
            <div class="text-center">
                <i class="fas fa-map-marked-alt text-gray-400 text-4xl mb-2"></i>
                <p class="text-gray-500">لا يوجد موقع محفوظ</p>
                <p class="text-sm text-gray-400">انقر لتحديد موقع جديد</p>
            </div>
        </div>
    `;
    mapElement.addEventListener('click', function() {
        editLocation();
    });
    @endif
}

function editLocation() {
    const mapElement = document.getElementById("map");
    mapElement.innerHTML = `
        <div class="h-full w-full flex items-center justify-center bg-blue-100 rounded-lg cursor-pointer">
            <div class="text-center">
                <i class="fas fa-map-marked-alt text-blue-600 text-4xl mb-2"></i>
                <p class="text-blue-700">انقر لتحديد موقع جديد</p>
                <p class="text-sm text-blue-600">أو استخدم الأزرار أدناه</p>
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
                    <p class="text-green-700 font-medium">تم تحديد موقع جديد</p>
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
                
                if (!document.getElementById('address').value.trim()) {
                    document.getElementById('address').value = 'الموقع الحالي - الرياض، المملكة العربية السعودية';
                }
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
    
    const mapElement = document.getElementById("map");
    mapElement.innerHTML = `
        <div class="h-full w-full flex items-center justify-center bg-gray-100 rounded-lg cursor-pointer">
            <div class="text-center">
                <i class="fas fa-map-marked-alt text-gray-400 text-4xl mb-2"></i>
                <p class="text-gray-500">لا يوجد موقع محدد</p>
                <p class="text-sm text-gray-400">انقر لتحديد موقع جديد</p>
            </div>
        </div>
    `;
    
    mapElement.addEventListener('click', function() {
        editLocation();
    });
}

// استعادة الموقع الأصلي
function restoreOriginalLocation() {
    if (originalLocation.lat && originalLocation.lng) {
        document.getElementById('latitude').value = originalLocation.lat;
        document.getElementById('longitude').value = originalLocation.lng;
        document.getElementById('address').value = '{{ old("address", $customer->address) }}';
        
        const mapElement = document.getElementById("map");
        mapElement.innerHTML = `
            <div class="h-full w-full flex items-center justify-center bg-green-100 rounded-lg">
                <div class="text-center">
                    <i class="fas fa-map-marker-alt text-green-600 text-4xl mb-2"></i>
                    <p class="text-green-700 font-medium">الموقع الأصلي</p>
                    <p class="text-sm text-green-600">${originalLocation.lat}, ${originalLocation.lng}</p>
                </div>
            </div>
        `;
    }
}

// تأكيد الحذف
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
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

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush
