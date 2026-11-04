@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">إدارة الفواتير</h1>
                        <p class="text-gray-600 mt-1">عرض وإدارة جميع الفواتير في النظام</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.invoices.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            إضافة فاتورة جديدة
                        </a>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="px-6 py-4">
                <form method="GET" action="{{ route('admin.invoices.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" 
                                   class="block w-full pr-10 pl-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" 
                                   placeholder="البحث برقم الفاتورة أو اسم العميل" 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div>
                        <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            <option value="">جميع الحالات</option>
                            <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>غير مدفوعة</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                            <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>مدفوعة جزئياً</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخرة</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغية</option>
                        </select>
                    </div>
                    <div>
                        <input type="date" name="date_from" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right"
                               placeholder="من تاريخ" value="{{ request('date_from') }}">
                    </div>
                    <div>
                        <input type="date" name="date_to" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right"
                               placeholder="إلى تاريخ" value="{{ request('date_to') }}">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-search"></i>
                            بحث
                        </button>
                        <a href="{{ route('admin.invoices.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-undo"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي الفواتير</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-file-invoice text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">غير مدفوعة</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['unpaid'] }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">مدفوعة</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['paid'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            

            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي المبلغ</p>
                        <p class="text-lg font-bold text-blue-600">{{ number_format($stats['total_amount'], 2) }}</p>
                        <p class="text-xs text-gray-500"><span class="icon-saudi_riyal"></span></p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-dollar-sign text-blue-600 text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">المدفوع</p>
                        <p class="text-lg font-bold text-green-600">{{ number_format($stats['paid_amount'], 2) }}</p>
                        <p class="text-xs text-gray-500"><span class="icon-saudi_riyal"></span></p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">المتبقي</p>
                        <p class="text-lg font-bold text-red-600">{{ number_format($stats['remaining_amount'], 2) }}</p>
                        <p class="text-xs text-gray-500"><span class="icon-saudi_riyal"></span></p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-minus text-red-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">قائمة الفواتير</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الفاتورة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الجوال</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">أُنشئ بواسطة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإصدار</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الدفع</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ الإجمالي</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</div>
                                <div class="text-sm text-gray-500">{{ $invoice->created_at->format('Y/m/d') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-sm">
                                            {{ substr($invoice->customer_name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="mr-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $invoice->customer_name }}</div>
                                        @if($invoice->customer)
                                            <div class="text-sm text-gray-500">{{ $invoice->customer->email ?? 'لا يوجد إيميل' }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $invoice->customer_phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $invoice->creator->name ?? 'غير محدد' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $invoice->issue_date->format('Y/m/d') }}</div>
                                @if($invoice->due_date)
                                    <div class="text-sm text-gray-500">استحقاق: {{ $invoice->due_date->format('Y/m/d') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'unpaid' => 'bg-red-100 text-red-800',
                                        'paid' => 'bg-green-100 text-green-800',
                                        'partially_paid' => 'bg-yellow-100 text-yellow-800',
                                        'overdue' => 'bg-orange-100 text-orange-800',
                                        'cancelled' => 'bg-gray-100 text-gray-800',
                                        'refunded' => 'bg-purple-100 text-purple-800'
                                    ];
                                    
                                    $statusNames = [
                                        'unpaid' => 'غير مدفوعة',
                                        'paid' => 'مدفوعة',
                                        'partially_paid' => 'مدفوعة جزئياً',
                                        'overdue' => 'متأخرة',
                                        'cancelled' => 'ملغية',
                                        'refunded' => 'مُسترد'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusNames[$invoice->status] ?? $invoice->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($invoice->payment_date)
                                    <div class="text-sm font-medium text-green-600">{{ $invoice->payment_date->format('Y/m/d') }}</div>
                                    <div class="text-sm text-gray-500">{{ $invoice->payment_date->format('H:i') }}</div>
                                @else
                                    <span class="text-sm text-gray-400">لم يتم الدفع</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($invoice->total_amount, 2) }} <span class="icon-saudi_riyal"></span></div>
                                @if($invoice->remaining_amount > 0)
                                    <div class="text-sm text-red-500">متبقي: {{ number_format($invoice->remaining_amount, 2) }} <span class="icon-saudi_riyal"></span></div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.invoices.show', $invoice) }}" 
                                       class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition-colors duration-200" 
                                       title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($invoice->status !== 'paid')
                                        <a href="{{ route('admin.invoices.edit', $invoice) }}" 
                                           class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 p-2 rounded-lg transition-colors duration-200" 
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('admin.invoices.print', $invoice) }}" 
                                       class="bg-green-100 hover:bg-green-200 text-green-700 p-2 rounded-lg transition-colors duration-200" 
                                       title="طباعة" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    
                                    @if($invoice->booking)
                                        <a href="{{ route('admin.bookings.show', $invoice->booking) }}" 
                                           class="bg-purple-100 hover:bg-purple-200 text-purple-700 p-2 rounded-lg transition-colors duration-200" 
                                           title="عرض الحجز">
                                            <i class="fas fa-calendar-alt"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-file-invoice text-gray-300 text-6xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد فواتير</h3>
                                    <p class="text-gray-500 mb-6">لم يتم العثور على أي فواتير مطابقة لمعايير البحث</p>
                                    <a href="{{ route('admin.invoices.create') }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                                        <i class="fas fa-plus"></i>
                                        إضافة فاتورة جديدة
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($invoices->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        عرض {{ $invoices->firstItem() }} إلى {{ $invoices->lastItem() }} من {{ $invoices->total() }} نتيجة
                    </div>
                    <div class="flex gap-2">
                        {{ $invoices->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
