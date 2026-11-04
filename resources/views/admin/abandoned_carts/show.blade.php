@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">تفاصيل السلة المتروكة</h1>
                        <p class="text-gray-600 mt-1">عرض تفاصيل السلة المتروكة لـ <span class="font-semibold text-blue-600">{{ $abandonedCart->customer_name }}</span></p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.abandoned-carts.edit', $abandonedCart) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل
                        </a>
                        <a href="{{ route('admin.abandoned-carts.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- معلومات العميل -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-user text-blue-600"></i>
                            معلومات العميل
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6">
                        <div class="flex items-center gap-6 mb-6">
                            <div class="h-20 w-20 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold text-2xl">
                                {{ substr($abandonedCart->customer_name, 0, 1) }}
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $abandonedCart->customer_name }}</h2>
                                <p class="text-gray-600">رقم السلة: #{{ $abandonedCart->id }}</p>
                                <div class="flex items-center gap-4 mt-2">
                                    <a href="tel:+966{{ $abandonedCart->customer_phone }}" 
                                       class="text-blue-600 hover:text-blue-900 font-medium flex items-center gap-2">
                                        <i class="fas fa-phone text-green-500"></i>
                                        +966{{ $abandonedCart->customer_phone }}
                                    </a>
                                    @if($abandonedCart->is_contacted)
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
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">معلومات السلة المتروكة</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">تاريخ الإضافة:</span>
                                        <span class="font-medium">{{ $abandonedCart->created_at->format('Y/m/d H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">آخر تحديث:</span>
                                        <span class="font-medium">{{ $abandonedCart->updated_at->format('Y/m/d H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">حالة التواصل:</span>
                                        <span class="font-medium">
                                            @if($abandonedCart->is_contacted)
                                                <span class="text-green-600">تم التواصل</span>
                                            @else
                                                <span class="text-orange-600">لم يتم التواصل</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">إجراءات سريعة</h4>
                                <div class="space-y-3">
                                    @if(!$abandonedCart->is_contacted)
                                        <button onclick="markAsContacted({{ $abandonedCart->id }})" 
                                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                            <i class="fas fa-check"></i>
                                            تحديد كمتواصل معه
                                        </button>
                                    @endif
                                    <a href="https://wa.me/966{{ $abandonedCart->customer_phone }}" target="_blank"
                                       class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                        <i class="fab fa-whatsapp"></i>
                                        التواصل عبر واتساب
                                    </a>
                                    <a href="tel:+966{{ $abandonedCart->customer_phone }}"
                                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                        <i class="fas fa-phone"></i>
                                        الاتصال بالعميل
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- تفاصيل المنتج -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-box text-green-600"></i>
                            تفاصيل المنتج المختار
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6">
                        @if($abandonedCart->product)
                            <div class="flex items-start gap-6">
                                <div class="flex-shrink-0">
                                    @if($abandonedCart->product->image_gallery && count($abandonedCart->product->image_gallery) > 0)
                                        <img class="h-32 w-32 rounded-lg object-cover" 
                                             src="{{ asset('storage/' . $abandonedCart->product->image_gallery[0]) }}" 
                                             alt="{{ $abandonedCart->product->name ?? 'صورة المنتج' }}">
                                    @else
                                        <div class="h-32 w-32 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-box text-gray-400 text-3xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $abandonedCart->product->name }}</h4>
                                    <p class="text-gray-600 mb-4">{{ $abandonedCart->product->description }}</p>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <span class="text-sm text-gray-600">السعر:</span>
                                            <div class="text-2xl font-bold text-green-600">{{ number_format($abandonedCart->product->price, 2) }} ر.س</div>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-600">الحالة:</span>
                                            <div class="text-sm">
                                                @if($abandonedCart->product->is_active)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        متاح
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        غير متاح
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if($abandonedCart->product->hasAddOns())
                                        <div class="mt-4">
                                            <h5 class="font-semibold text-gray-900 mb-2">الإضافات المتاحة:</h5>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($abandonedCart->product->addOns as $addon)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $addon->name }} - {{ number_format($addon->price, 2) }} ر.س
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-triangle text-gray-300 text-4xl mb-4"></i>
                                <p class="text-gray-500">المنتج غير موجود أو تم حذفه</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- الملاحظات -->
                @if($abandonedCart->notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-sticky-note text-yellow-600"></i>
                            الملاحظات
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-gray-900">{{ $abandonedCart->notes }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- الشريط الجانبي -->
            <div class="space-y-6">
                <!-- إحصائيات سريعة -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">إحصائيات سريعة</h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">عمر السلة:</span>
                            <span class="font-medium">{{ $abandonedCart->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">عدد الأيام:</span>
                            <span class="font-medium">{{ $abandonedCart->created_at->diffInDays(now()) }} يوم</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">قيمة المنتج:</span>
                            <span class="font-medium text-green-600">{{ $abandonedCart->product ? number_format($abandonedCart->product->price, 2) . ' ر.س' : 'غير محدد' }}</span>
                        </div>
                    </div>
                </div>

                <!-- إجراءات سريعة -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">إجراءات سريعة</h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-3">
                        <a href="{{ route('admin.abandoned-carts.edit', $abandonedCart) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل السلة
                        </a>
                        
                        @if(!$abandonedCart->is_contacted)
                            <button onclick="markAsContacted({{ $abandonedCart->id }})" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                <i class="fas fa-check"></i>
                                تحديد كمتواصل معه
                            </button>
                        @endif
                        
                        <button onclick="confirmDelete()" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-trash"></i>
                            حذف السلة
                        </button>
                    </div>
                </div>

                <!-- معلومات إضافية -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">معلومات إضافية</h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-4">
                        <div>
                            <span class="text-sm text-gray-600">تاريخ الإضافة:</span>
                            <div class="font-medium">{{ $abandonedCart->created_at->format('Y/m/d') }}</div>
                            <div class="text-xs text-gray-500">{{ $abandonedCart->created_at->format('H:i:s') }}</div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">آخر تحديث:</span>
                            <div class="font-medium">{{ $abandonedCart->updated_at->format('Y/m/d') }}</div>
                            <div class="text-xs text-gray-500">{{ $abandonedCart->updated_at->format('H:i:s') }}</div>
                        </div>
                    </div>
                </div>
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
            <h3 class="text-lg font-medium text-gray-900 mt-4">تأكيد الحذف</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">هل أنت متأكد من حذف السلة المتروكة لـ <strong>{{ $abandonedCart->customer_name }}</strong>؟</p>
                <p class="text-sm text-red-500 mt-2">هذا الإجراء لا يمكن التراجع عنه.</p>
            </div>
            <div class="flex gap-3 px-4 py-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    إلغاء
                </button>
                <form action="{{ route('admin.abandoned-carts.destroy', $abandonedCart) }}" method="POST" class="flex-1">
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

// تأكيد الحذف
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
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
