@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">إدارة السلات المتروكة</h1>
                        <p class="text-gray-600 mt-1">عرض وإدارة السلات المتروكة من العملاء</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.abandoned-carts.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            إضافة سلة متروكة
                        </a>
                        <a href="{{ route('admin.abandoned-carts.export') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-download"></i>
                            تصدير البيانات
                        </a>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="px-6 py-4">
                <form method="GET" action="{{ route('admin.abandoned-carts.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" 
                                   class="block w-full pr-10 pl-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right" 
                                   placeholder="البحث بالاسم أو رقم الجوال أو المنتج" 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div>
                        <select name="contacted" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            <option value="">جميع الحالات</option>
                            <option value="0" {{ request('contacted') === '0' ? 'selected' : '' }}>لم يتم التواصل</option>
                            <option value="1" {{ request('contacted') === '1' ? 'selected' : '' }}>تم التواصل</option>
                        </select>
                    </div>
                    <div>
                        <select name="date_filter" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right">
                            <option value="">جميع الأوقات</option>
                            <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>اليوم</option>
                            <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>هذا الأسبوع</option>
                            <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>هذا الشهر</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            بحث
                        </button>
                        <a href="{{ route('admin.abandoned-carts.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 text-center">
                            إعادة تعيين
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">إجمالي السلات</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">لم يتم التواصل</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $stats['not_contacted'] }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">تم التواصل</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['contacted'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">اليوم</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['today'] }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-calendar-day text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">هذا الأسبوع</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ $stats['this_week'] }}</p>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-full">
                        <i class="fas fa-calendar-week text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">هذا الشهر</p>
                        <p class="text-2xl font-bold text-pink-600">{{ $stats['this_month'] }}</p>
                    </div>
                    <div class="bg-pink-100 p-3 rounded-full">
                        <i class="fas fa-calendar-alt text-pink-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">إجراءات جماعية</h3>
                    <div class="flex gap-3">
                        <button onclick="bulkMarkAsContacted()" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-check"></i>
                            تحديد كمتواصل معه
                        </button>
                        <button onclick="bulkDelete()" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-trash"></i>
                            حذف المحدد
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">قائمة السلات المتروكة</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتج</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الجوال</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">حالة التواصل</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الملاحظات</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإضافة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($abandonedCarts as $cart)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="selected_items[]" value="{{ $cart->id }}" 
                                       class="item-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-orange-500 flex items-center justify-center text-white font-semibold">
                                            {{ substr($cart->customer_name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="mr-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $cart->customer_name }}</div>
                                        <div class="text-sm text-gray-500">#{{ $cart->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($cart->product && $cart->product->image_gallery && count($cart->product->image_gallery) > 0)
                                            <img class="h-10 w-10 rounded-lg object-cover" 
                                                 src="{{ asset('storage/' . $cart->product->image_gallery[0]) }}" 
                                                 alt="{{ $cart->product->name ?? 'صورة المنتج' }}">
                                        @else
                                            <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-box text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mr-4">
                                        @if($cart->product)
                                            <div class="text-sm font-medium text-gray-900">{{ $cart->product->name }}</div>
                                            <div class="text-sm text-gray-500">{{ number_format($cart->product->price, 2) }} ر.س</div>
                                        @else
                                            <div class="text-sm font-medium text-gray-500">المنتج غير موجود</div>
                                            <div class="text-sm text-gray-400">تم حذف المنتج</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="tel:+966{{ $cart->customer_phone }}" class="text-blue-600 hover:text-blue-900 font-medium flex items-center gap-2">
                                    <i class="fas fa-phone text-green-500"></i>
                                    +966{{ $cart->customer_phone }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($cart->is_contacted)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        تم التواصل
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        لم يتم التواصل
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($cart->notes)
                                    <span class="text-sm text-gray-900">{{ Str::limit($cart->notes, 30) }}</span>
                                @else
                                    <span class="text-gray-400">لا توجد ملاحظات</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $cart->created_at->format('Y/m/d') }}
                                <div class="text-xs text-gray-500">{{ $cart->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.abandoned-carts.show', $cart) }}" 
                                       class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition-colors duration-200" 
                                       title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.abandoned-carts.edit', $cart) }}" 
                                       class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 p-2 rounded-lg transition-colors duration-200" 
                                       title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$cart->is_contacted)
                                    <button onclick="markAsContacted({{ $cart->id }})" 
                                            class="bg-green-100 hover:bg-green-200 text-green-700 p-2 rounded-lg transition-colors duration-200" 
                                            title="تحديد كمتواصل معه">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    @endif
                                    <button onclick="confirmDelete({{ $cart->id }})" 
                                            class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition-colors duration-200" 
                                            title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد سلات متروكة</h3>
                                    <p class="text-gray-500 mb-6">لم يتم العثور على أي سلات متروكة مطابقة لمعايير البحث</p>
                                    <a href="{{ route('admin.abandoned-carts.create') }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                                        <i class="fas fa-plus"></i>
                                        إضافة سلة متروكة
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($abandonedCarts->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        عرض {{ $abandonedCarts->firstItem() }} إلى {{ $abandonedCarts->lastItem() }} من {{ $abandonedCarts->total() }} نتيجة
                    </div>
                    <div class="flex gap-2">
                        {{ $abandonedCarts->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
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
            <h3 class="text-lg font-medium text-gray-900 mt-4">تأكيد الحذف</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">هل أنت متأكد من حذف هذه السلة المتروكة؟</p>
                <p class="text-sm text-red-500 mt-2">هذا الإجراء لا يمكن التراجع عنه.</p>
            </div>
            <div class="flex gap-3 px-4 py-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    إلغاء
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        حذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Mark as Contacted Modal -->
<div id="contactedModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4 text-center">تحديث حالة التواصل</h3>
            <div class="mt-4">
                <label for="contactedNotes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات التواصل (اختياري)</label>
                <textarea id="contactedNotes" rows="3" 
                          class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-right"
                          placeholder="أدخل ملاحظات التواصل..."></textarea>
            </div>
            <div class="flex gap-3 px-4 py-3 mt-4">
                <button onclick="closeContactedModal()" 
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    إلغاء
                </button>
                <button onclick="submitMarkAsContacted()" 
                        class="flex-1 px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    تحديث
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedCartId = null;

// تحديد الكل
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// تحديث حالة تحديد الكل
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('item-checkbox')) {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const selectAll = document.getElementById('selectAll');
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        
        selectAll.checked = checkedBoxes.length === checkboxes.length;
        selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
    }
});

// تأكيد الحذف
function confirmDelete(cartId) {
    const form = document.getElementById('deleteForm');
    form.action = `{{ route('admin.abandoned-carts.index') }}/${cartId}`;
    
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// تحديد كمتواصل معه
function markAsContacted(cartId) {
    selectedCartId = cartId;
    document.getElementById('contactedModal').classList.remove('hidden');
}

function closeContactedModal() {
    document.getElementById('contactedModal').classList.add('hidden');
    selectedCartId = null;
    document.getElementById('contactedNotes').value = '';
}

function submitMarkAsContacted() {
    const notes = document.getElementById('contactedNotes').value;
    
    fetch(`{{ route('admin.abandoned-carts.index') }}/${selectedCartId}/mark-as-contacted`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ notes: notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeContactedModal();
            location.reload();
        } else {
            alert('حدث خطأ أثناء تحديث حالة التواصل');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء تحديث حالة التواصل');
    });
}

// إجراءات جماعية
function bulkMarkAsContacted() {
    const selectedItems = document.querySelectorAll('.item-checkbox:checked');
    const ids = Array.from(selectedItems).map(item => item.value);
    
    if (ids.length === 0) {
        alert('يرجى تحديد العناصر المراد تحديثها');
        return;
    }
    
    if (confirm(`هل أنت متأكد من تحديث حالة التواصل لـ ${ids.length} عنصر؟`)) {
        fetch('{{ route("admin.abandoned-carts.bulk-mark-as-contacted") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء تحديث حالة التواصل');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تحديث حالة التواصل');
        });
    }
}

function bulkDelete() {
    const selectedItems = document.querySelectorAll('.item-checkbox:checked');
    const ids = Array.from(selectedItems).map(item => item.value);
    
    if (ids.length === 0) {
        alert('يرجى تحديد العناصر المراد حذفها');
        return;
    }
    
    if (confirm(`هل أنت متأكد من حذف ${ids.length} عنصر؟`)) {
        fetch('{{ route("admin.abandoned-carts.bulk-destroy") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء حذف العناصر');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حذف العناصر');
        });
    }
}

// Close modals when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

document.getElementById('contactedModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeContactedModal();
    }
});
</script>
@endpush
