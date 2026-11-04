@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $coupon->name }}</h1>
                        <p class="text-gray-600 mt-1">رمز الكوبون: <span class="font-mono text-blue-600">{{ $coupon->code }}</span></p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل الكوبون
                        </a>
                        <a href="{{ route('admin.coupons.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- معلومات الكوبون الأساسية -->
            <div class="xl:col-span-2 space-y-6">
                <!-- تفاصيل الكوبون -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            تفاصيل الكوبون
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- نوع الخصم -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">نوع الخصم</h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $coupon->type === 'percentage' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $coupon->type === 'percentage' ? 'نسبة مئوية' : 'مبلغ ثابت' }}
                                </span>
                            </div>

                            <!-- قيمة الخصم -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">قيمة الخصم</h4>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if($coupon->type === 'percentage')
                                        {{ $coupon->value }}%
                                    @else
                                        {{ number_format($coupon->value, 2) }} ر.س
                                    @endif
                                </p>
                            </div>

                            <!-- الحد الأدنى للطلب -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">الحد الأدنى للطلب</h4>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if($coupon->min_amount > 0)
                                        {{ number_format($coupon->min_amount, 2) }} ر.س
                                    @else
                                        <span class="text-gray-500">لا يوجد حد أدنى</span>
                                    @endif
                                </p>
                            </div>

                            <!-- حالة الكوبون -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">حالة الكوبون</h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $coupon->is_active ? 'نشط' : 'متوقف' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الصلاحية والاستخدامات -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-orange-600"></i>
                            الصلاحية والاستخدامات
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- تاريخ البداية -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">تاريخ البداية</h4>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if($coupon->start_date)
                                        {{ $coupon->start_date->format('Y/m/d') }}
                                    @else
                                        <span class="text-gray-500">غير محدد</span>
                                    @endif
                                </p>
                            </div>

                            <!-- تاريخ النهاية -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">تاريخ النهاية</h4>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if($coupon->end_date)
                                        {{ $coupon->end_date->format('Y/m/d') }}
                                    @else
                                        <span class="text-gray-500">غير محدد</span>
                                    @endif
                                </p>
                            </div>

                            <!-- عدد مرات الاستخدام -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">عدد مرات الاستخدام</h4>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ $coupon->used_count }}
                                    @if($coupon->max_uses)
                                        <span class="text-sm text-gray-500">من {{ $coupon->max_uses }}</span>
                                    @endif
                                </p>
                            </div>

                            <!-- الحد الأقصى للاستخدام -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2">الحد الأقصى للاستخدام</h4>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if($coupon->max_uses)
                                        {{ $coupon->max_uses }} مرة
                                    @else
                                        <span class="text-gray-500">غير محدود</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- شريط التقدم للاستخدام -->
                        @if($coupon->max_uses)
                        <div class="mt-6">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>نسبة الاستخدام</span>
                                <span>{{ round(($coupon->used_count / $coupon->max_uses) * 100, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(($coupon->used_count / $coupon->max_uses) * 100, 100) }}%"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- الحجوزات المرتبطة -->
                @if($coupon->bookings->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-calendar-check text-green-600"></i>
                            الحجوزات المرتبطة ({{ $coupon->bookings->count() }})
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الحجز</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">العميل</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المنتج</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">مبلغ الخصم</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($coupon->bookings->take(5) as $booking)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $booking->booking_number }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $booking->customer->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $booking->product->name }}</td>
                                        <td class="px-4 py-3 text-sm text-green-600 font-medium">{{ number_format($booking->discount_amount, 2) }} ر.س</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $booking->created_at->format('Y/m/d') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($coupon->bookings->count() > 5)
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500">عرض {{ $coupon->bookings->take(5)->count() }} من {{ $coupon->bookings->count() }} حجز</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- الفواتير المرتبطة -->
                @if($coupon->invoices->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-file-invoice text-purple-600"></i>
                            الفواتير المرتبطة ({{ $coupon->invoices->count() }})
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الفاتورة</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">العميل</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ الإجمالي</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($coupon->invoices->take(5) as $invoice)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $invoice->invoice_number }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $invoice->customer->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($invoice->total_amount, 2) }} ر.س</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                                   ($invoice->status === 'unpaid' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ $invoice->status === 'paid' ? 'مدفوع' : 
                                                   ($invoice->status === 'unpaid' ? 'غير مدفوع' : 'مدفوع جزئياً') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $invoice->created_at->format('Y/m/d') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($coupon->invoices->count() > 5)
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500">عرض {{ $coupon->invoices->take(5)->count() }} من {{ $coupon->invoices->count() }} فاتورة</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- الشريط الجانبي -->
            <div class="xl:col-span-1 space-y-6">
                <!-- حالة الصلاحية -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-600"></i>
                            حالة الصلاحية
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6">
                        @if($coupon->isValid())
                            <div class="text-center">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-check text-green-600 text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-green-900 mb-2">الكوبون صالح</h4>
                                <p class="text-sm text-green-600">يمكن استخدام هذا الكوبون</p>
                            </div>
                        @else
                            <div class="text-center">
                                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-times text-red-600 text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-red-900 mb-2">الكوبون غير صالح</h4>
                                <p class="text-sm text-red-600">لا يمكن استخدام هذا الكوبون</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- معلومات إضافية -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-info text-blue-600"></i>
                            معلومات إضافية
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">تاريخ الإنشاء</h4>
                            <p class="text-sm text-gray-900">{{ $coupon->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">آخر تحديث</h4>
                            <p class="text-sm text-gray-900">{{ $coupon->updated_at->format('Y/m/d H:i') }}</p>
                        </div>

                        @if($coupon->start_date && $coupon->end_date)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">مدة الصلاحية</h4>
                            <p class="text-sm text-gray-900">{{ (int) abs($coupon->start_date->diffInDays($coupon->end_date)) }} يوم</p>
                        </div>
                        @endif

                        @if($coupon->max_uses)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">الاستخدامات المتبقية</h4>
                            <p class="text-sm text-gray-900">{{ $coupon->max_uses - $coupon->used_count }} مرة</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- الإجراءات السريعة -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-cogs text-gray-600"></i>
                            الإجراءات السريعة
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-3">
                        <form method="POST" action="{{ route('admin.coupons.toggle-status', $coupon) }}" class="w-full">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $coupon->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                <i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }} ml-2"></i>
                                {{ $coupon->is_active ? 'إيقاف الكوبون' : 'تفعيل الكوبون' }}
                            </button>
                        </form>
                        
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                           class="block w-full px-4 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 text-sm font-medium rounded-lg transition-colors duration-200 text-center">
                            <i class="fas fa-edit ml-2"></i>
                            تعديل الكوبون
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
