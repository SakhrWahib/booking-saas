@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">تفاصيل الإشعار</h1>
                        <p class="text-gray-600 mt-1">عرض تفاصيل الإشعار المرتبط</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.notifications.index') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            رجوع للإشعارات
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Notification Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">معلومات الإشعار</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            <!-- Notification Header -->
                            <div class="flex items-start space-x-4" dir="rtl">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-bell text-white text-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $notification->title }}</h2>
                                    <p class="text-gray-600 text-lg leading-relaxed">{{ $notification->message }}</p>
                                </div>
                            </div>

                            <!-- Notification Meta -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500">النوع:</span>
                                        @if($notification->type == 'booking')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-calendar-check mr-2"></i>
                                                حجز
                                            </span>
                                        @elseif($notification->type == 'payment')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-credit-card mr-2"></i>
                                                دفع
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-cog mr-2"></i>
                                                نظام
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500">الحالة:</span>
                                        @if($notification->is_read)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-2"></i>
                                                مقروء
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                جديد
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500">تاريخ الإنشاء:</span>
                                        <span class="text-sm text-gray-900">{{ $notification->created_at->format('Y/m/d') }}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500">الوقت:</span>
                                        <span class="text-sm text-gray-900">{{ $notification->created_at->format('H:i:s') }}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500">رقم الإشعار:</span>
                                        <span class="text-sm text-gray-900">#{{ $notification->id }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Booking Info -->
            @if($notification->booking)
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">تفاصيل الحجز المرتبط</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">رقم الحجز:</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $notification->booking->booking_number }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">العميل:</span>
                                <span class="text-sm text-gray-900">{{ $notification->booking->customer->name }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">المنتج:</span>
                                <span class="text-sm text-gray-900">{{ $notification->booking->product->name }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">مقدم الخدمة:</span>
                                <span class="text-sm text-gray-900">{{ $notification->booking->serviceProvider->name }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">التاريخ:</span>
                                <span class="text-sm text-gray-900">{{ $notification->booking->booking_date->format('Y/m/d') }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">الوقت:</span>
                                <span class="text-sm text-gray-900">{{ $notification->booking->booking_time }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">الحالة:</span>
                                @if($notification->booking->status == 'pending')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        قيد الانتظار
                                    </span>
                                @elseif($notification->booking->status == 'confirmed')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        مؤكد
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $notification->booking->status }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="pt-4 border-t border-gray-200">
                                <a href="{{ route('admin.bookings.show', $notification->booking->id) }}" 
                                   class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                    <i class="fas fa-eye"></i>
                                    عرض تفاصيل الحجز
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 