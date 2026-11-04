@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                            {{ $customer->name }}
                        </h1>
                        <p class="text-gray-600 mt-1">العميل رقم #{{ $customer->id }} - تاريخ التسجيل: {{ $customer->created_at->format('Y/m/d') }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.customers.edit', $customer) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل البيانات
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

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Customer Info & Statistics -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-user text-blue-600"></i>
                            المعلومات الأساسية
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-blue-100 p-2 rounded-lg">
                                        <i class="fas fa-hashtag text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">رقم العميل</p>
                                        <p class="font-semibold text-gray-900">#{{ $customer->id }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <div class="bg-green-100 p-2 rounded-lg">
                                        <i class="fas fa-phone text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">رقم الجوال</p>
                                        <a href="tel:+966{{ $customer->phone }}" class="font-semibold text-blue-600 hover:text-blue-800">
                                            +966{{ $customer->phone }}
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="bg-purple-100 p-2 rounded-lg">
                                        <i class="fas fa-envelope text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">البريد الإلكتروني</p>
                                        @if($customer->email)
                                            <a href="mailto:{{ $customer->email }}" class="font-semibold text-blue-600 hover:text-blue-800">
                                                {{ $customer->email }}
                                            </a>
                                        @else
                                            <p class="font-semibold text-gray-400">غير محدد</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-indigo-100 p-2 rounded-lg">
                                        <i class="fas fa-user-tag text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">نوع العميل</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            أفراد
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="bg-orange-100 p-2 rounded-lg">
                                        <i class="fas fa-calendar-plus text-orange-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">تاريخ التسجيل</p>
                                        <p class="font-semibold text-gray-900">{{ $customer->created_at->format('Y/m/d H:i') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="bg-gray-100 p-2 rounded-lg">
                                        <i class="fas fa-clock text-gray-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">آخر تحديث</p>
                                        <p class="font-semibold text-gray-900">{{ $customer->updated_at->format('Y/m/d H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-chart-line text-green-600"></i>
                            إحصائيات العميل
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                                <div class="bg-blue-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-calendar-check text-blue-600"></i>
                                </div>
                                <div class="text-2xl font-bold text-blue-600">{{ $customer->bookings()->count() }}</div>
                                <div class="text-xs text-blue-600">إجمالي الحجوزات</div>
                            </div>
                            
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                                <div class="bg-green-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-clock text-green-600"></i>
                                </div>
                                <div class="text-2xl font-bold text-green-600">{{ $customer->activeBookings()->count() }}</div>
                                <div class="text-xs text-green-600">الحجوزات النشطة</div>
                            </div>
                            
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center">
                                <div class="bg-purple-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-check-circle text-purple-600"></i>
                                </div>
                                <div class="text-2xl font-bold text-purple-600">{{ $customer->bookings()->where('status', 'completed')->count() }}</div>
                                <div class="text-xs text-purple-600">الحجوزات المكتملة</div>
                            </div>
                            
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                                <div class="bg-yellow-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-file-invoice-dollar text-yellow-600"></i>
                                </div>
                                <div class="text-2xl font-bold text-yellow-600">{{ $customer->invoices()->count() }}</div>
                                <div class="text-xs text-yellow-600">الفواتير</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking History -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-history text-indigo-600"></i>
                            سجل الحجوزات
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        @if($customer->bookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الحجز</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الخدمة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($customer->bookings->take(5) as $booking)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-semibold text-gray-900">#{{ $booking->booking_number ?? $booking->id }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($booking->product)
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ $booking->product->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $booking->product->duration ?? 60 }} دقيقة</div>
                                                </div>
                                            @else
                                                <span class="text-gray-400">خدمة محذوفة</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $booking->booking_date ? $booking->booking_date->format('Y/m/d') : 'غير محدد' }}</div>
                                            <div class="text-sm text-gray-500">{{ $booking->booking_time ?? 'غير محدد' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'confirmed' => 'bg-blue-100 text-blue-800', 
                                                    'in_progress' => 'bg-purple-100 text-purple-800',
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800'
                                                ];
                                                $statusTexts = [
                                                    'pending' => 'قيد الانتظار',
                                                    'confirmed' => 'مؤكد',
                                                    'in_progress' => 'جاري التنفيذ',
                                                    'completed' => 'مكتمل',
                                                    'cancelled' => 'ملغي'
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $statusTexts[$booking->status] ?? $booking->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-semibold text-gray-900">{{ number_format($booking->total_amount ?? 0, 2) }} ر.س</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition-colors duration-200" title="عرض التفاصيل">
                                                <i class="fas fa-eye text-sm"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($customer->bookings->count() > 5)
                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-500">عرض {{ min(5, $customer->bookings->count()) }} من إجمالي {{ $customer->bookings->count() }} حجز</p>
                        </div>
                        @endif
                        @else
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-gray-300 text-4xl mb-4"></i>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">لا توجد حجوزات</h4>
                            <p class="text-gray-500">لم يقم هذا العميل بأي حجوزات حتى الآن</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Location Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-red-600"></i>
                            معلومات الموقع
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        @if($customer->latitude && $customer->longitude)
                            <!-- Map -->
                            <div id="customerMap" class="w-full h-48 rounded-lg border border-gray-300 bg-gray-100 mb-4"></div>
                            
                            <!-- Location Details -->
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">خط العرض:</span>
                                    <span class="font-mono text-gray-900">{{ $customer->latitude }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">خط الطول:</span>
                                    <span class="font-mono text-gray-900">{{ $customer->longitude }}</span>
                                </div>
                                @if($customer->address)
                                <div>
                                    <span class="text-sm text-gray-600">العنوان:</span>
                                    <p class="text-sm text-gray-900 mt-1">{{ $customer->address }}</p>
                                </div>
                                @endif
                            </div>

                            <!-- Location Actions -->
                            <div class="mt-4 space-y-2">
                                <a href="https://www.google.com/maps?q={{ $customer->latitude }},{{ $customer->longitude }}" 
                                   target="_blank" 
                                   class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                    <i class="fas fa-external-link-alt"></i>
                                    عرض في خرائط جوجل
                                </a>
                                <button onclick="copyLocation()" 
                                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                    <i class="fas fa-copy"></i>
                                    نسخ الإحداثيات
                                </button>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-map-marked-alt text-gray-300 text-4xl mb-4"></i>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">الموقع غير محدد</h4>
                                <p class="text-gray-500 text-sm mb-4">لم يتم تحديد موقع هذا العميل بعد</p>
                                <a href="{{ route('admin.customers.edit', $customer) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 inline-flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt"></i>
                                    تحديد الموقع
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-tools text-gray-600"></i>
                            إجراءات سريعة
                        </h3>
                    </div>
                    <div class="px-6 py-6 space-y-3">
                        <a href="{{ route('admin.customers.edit', $customer) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل البيانات
                        </a>
                        
                        <button onclick="generateOtp()" 
                                class="w-full bg-green-100 hover:bg-green-200 text-green-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-key"></i>
                            توليد رمز التحقق
                        </button>
                        
                        <a href="tel:+966{{ $customer->phone }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-phone"></i>
                            اتصال مباشر
                        </a>
                        
                        @if($customer->email)
                        <a href="mailto:{{ $customer->email }}" 
                           class="w-full bg-purple-100 hover:bg-purple-200 text-purple-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-envelope"></i>
                            إرسال بريد إلكتروني
                        </a>
                        @endif

                        @if($customer->bookings()->count() == 0)
                        <hr class="my-4">
                        <button onclick="confirmDelete()" 
                                class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-trash"></i>
                            حذف العميل
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Recent Invoices -->
                @if($customer->invoices->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-file-invoice text-yellow-600"></i>
                            الفواتير الأخيرة
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-3">
                            @foreach($customer->invoices->take(3) as $invoice)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-medium text-sm">#{{ $invoice->invoice_number ?? $invoice->id }}</div>
                                    <div class="text-xs text-gray-500">{{ $invoice->created_at->format('Y/m/d') }}</div>
                                </div>
                                <div class="text-left">
                                    <div class="font-semibold text-sm">{{ number_format($invoice->total_amount ?? 0, 2) }} ر.س</div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $invoice->status == 'paid' ? 'مدفوعة' : 'معلقة' }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- OTP Modal -->
<div id="otpModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <i class="fas fa-key text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">توليد رمز التحقق</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">سيتم توليد رمز تحقق جديد للعميل <strong>{{ $customer->name }}</strong></p>
                <p class="text-sm text-yellow-600 mt-2">يمكنك استخدام هذا الرمز للتحقق من هوية العميل</p>
            </div>
            <div class="flex gap-3 px-4 py-3">
                <button onclick="closeOtpModal()" 
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                    إلغاء
                </button>
                <form action="{{ route('admin.customers.generate-otp', $customer) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700">
                        توليد الرمز
                    </button>
                </form>
            </div>
        </div>
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
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                    إلغاء
                </button>
                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700">
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
@if($customer->latitude && $customer->longitude)
// Initialize customer map
function initCustomerMap() {
    const mapElement = document.getElementById("customerMap");
    mapElement.innerHTML = `
        <div class="h-full w-full flex items-center justify-center bg-blue-100 rounded-lg">
            <div class="text-center">
                <i class="fas fa-map-marker-alt text-blue-600 text-3xl mb-2"></i>
                <p class="text-blue-700 font-medium">موقع العميل</p>
                <p class="text-xs text-blue-600">{{ $customer->latitude }}, {{ $customer->longitude }}</p>
            </div>
        </div>
    `;
}

// Initialize map on page load
document.addEventListener('DOMContentLoaded', function() {
    initCustomerMap();
});
@endif

function generateOtp() {
    document.getElementById('otpModal').classList.remove('hidden');
}

function closeOtpModal() {
    document.getElementById('otpModal').classList.add('hidden');
}

function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function copyLocation() {
    const location = "{{ $customer->latitude }},{{ $customer->longitude }}";
    navigator.clipboard.writeText(location).then(function() {
        // Show success message
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check mr-2"></i>تم النسخ!';
        btn.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
        btn.classList.add('bg-green-100', 'text-green-700');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('bg-green-100', 'text-green-700');
            btn.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
        }, 2000);
    });
}

// Close modals when clicking outside
document.getElementById('otpModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeOtpModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush
