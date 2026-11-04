@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">تعديل السلة المتروكة</h1>
                        <p class="text-gray-600 mt-1">تعديل بيانات السلة المتروكة: <span class="font-semibold text-blue-600">{{ $abandonedCart->customer_name }}</span></p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.abandoned-carts.show', $abandonedCart) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-eye"></i>
                            عرض التفاصيل
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

        <!-- Form -->
        <form method="POST" action="{{ route('admin.abandoned-carts.update', $abandonedCart) }}" id="abandonedCartForm">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- بيانات العميل -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-user text-blue-600"></i>
                            بيانات العميل
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-6">
                        <!-- معلومات السلة المتروكة -->
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($abandonedCart->customer_name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $abandonedCart->customer_name }}</h4>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span>رقم السلة: <span class="font-semibold">#{{ $abandonedCart->id }}</span></span>
                                        <span>تاريخ الإضافة: {{ $abandonedCart->created_at->format('Y/m/d') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- اسم العميل -->
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم العميل <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('customer_name') border-red-500 @enderror" 
                                   id="customer_name" name="customer_name" value="{{ old('customer_name', $abandonedCart->customer_name) }}" 
                                   placeholder="أدخل اسم العميل" required>
                            @error('customer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- رقم الجوال -->
                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الجوال <span class="text-red-500">*</span>
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-r-md">
                                    <i class="fas fa-phone text-green-600 mr-2"></i>
                                    +966
                                </span>
                                <input type="tel" 
                                       class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('customer_phone') border-red-500 @enderror" 
                                       id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $abandonedCart->customer_phone) }}" 
                                       placeholder="5XXXXXXXX" maxlength="9" required>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">مثال: 551234567</p>
                            @error('customer_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- حالة التواصل -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_contacted" value="1" 
                                       class="rounded border-gray-300 text-green-600 focus:ring-green-500" 
                                       {{ old('is_contacted', $abandonedCart->is_contacted) ? 'checked' : '' }}>
                                <span class="mr-2 text-sm font-medium text-gray-700">تم التواصل مع العميل</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-500">حدد هذا الخيار إذا تم التواصل مع العميل</p>
                        </div>
                    </div>
                </div>

                <!-- بيانات المنتج -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-box text-green-600"></i>
                            بيانات المنتج
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-6">
                        <!-- اختيار المنتج -->
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                                المنتج المختار <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('product_id') border-red-500 @enderror" 
                                    id="product_id" name="product_id" required>
                                <option value="">اختر المنتج</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id', $abandonedCart->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} - {{ number_format($product->price, 2) }} ر.س
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- عرض تفاصيل المنتج الحالي -->
                        <div id="productDetails" class="{{ $abandonedCart->product ? '' : 'hidden' }}">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        @if($abandonedCart->product && $abandonedCart->product->image_gallery && count($abandonedCart->product->image_gallery) > 0)
                                            <img id="productImage" class="h-16 w-16 rounded-lg object-cover" 
                                                 src="{{ asset('storage/' . $abandonedCart->product->image_gallery[0]) }}" 
                                                 alt="{{ $abandonedCart->product->name ?? 'صورة المنتج' }}">
                                        @else
                                            <img id="productImage" class="h-16 w-16 rounded-lg object-cover" 
                                                 src="{{ asset('images/default-product.svg') }}" 
                                                 alt="صورة المنتج">
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 id="productName" class="font-semibold text-gray-900">
                                            {{ $abandonedCart->product ? $abandonedCart->product->name : '' }}
                                        </h4>
                                        <p id="productDescription" class="text-sm text-gray-600 mt-1">
                                            {{ $abandonedCart->product ? $abandonedCart->product->short_description : '' }}
                                        </p>
                                        <div class="flex items-center gap-4 mt-2">
                                            <span id="productPrice" class="text-lg font-bold text-green-600">
                                                {{ $abandonedCart->product ? number_format($abandonedCart->product->price, 2) . ' ر.س' : '' }}
                                            </span>
                                            @if($abandonedCart->product && $abandonedCart->product->hasAddOns())
                                                <span class="text-sm text-blue-600">
                                                    <i class="fas fa-plus-circle mr-1"></i>
                                                    يحتوي على إضافات
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ملاحظات -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                ملاحظات إضافية
                            </label>
                            <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('notes') border-red-500 @enderror" 
                                      id="notes" name="notes" rows="4" 
                                      placeholder="أدخل أي ملاحظات إضافية حول السلة المتروكة">{{ old('notes', $abandonedCart->notes) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">اختياري - يمكن إضافة ملاحظات حول سبب ترك السلة أو أي معلومات إضافية</p>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                                <div>
                                    <h4 class="font-medium text-blue-800">معلومات التحديث</h4>
                                    <p class="text-sm text-blue-600">يمكنك تحديث بيانات السلة المتروكة أو تغيير حالة التواصل مع العميل</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <button type="button" 
                                class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2" 
                                onclick="confirmDelete()">
                            <i class="fas fa-trash"></i>
                            حذف السلة المتروكة
                        </button>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.abandoned-carts.index') }}" 
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
            <h3 class="text-lg font-medium text-gray-900 mt-4">تأكيد حذف السلة المتروكة</h3>
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
@endsection

@push('scripts')
<script>
// بيانات المنتجات
const products = @json($products);

// عرض تفاصيل المنتج عند اختياره
document.getElementById('product_id').addEventListener('change', function() {
    const productId = this.value;
    const productDetails = document.getElementById('productDetails');
    
    if (productId) {
        const product = products.find(p => p.id == productId);
        if (product) {
            // عرض تفاصيل المنتج
            document.getElementById('productName').textContent = product.name;
            document.getElementById('productDescription').textContent = product.short_description;
            document.getElementById('productPrice').textContent = product.price + ' ر.س';
            
            // عرض صورة المنتج
            const productImage = document.getElementById('productImage');
            if (product.image_gallery && product.image_gallery.length > 0) {
                productImage.src = '{{ asset("storage/") }}/' + product.image_gallery[0];
                productImage.style.display = 'block';
            } else {
                productImage.src = '{{ asset("images/default-product.svg") }}';
                productImage.style.display = 'block';
            }
            
            productDetails.classList.remove('hidden');
        }
    } else {
        productDetails.classList.add('hidden');
    }
});

// التحقق من رقم الجوال
document.getElementById('customer_phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 9) {
        value = value.substring(0, 9);
    }
    e.target.value = value;
});

// التحقق من النموذج قبل الإرسال
document.getElementById('abandonedCartForm').addEventListener('submit', function(e) {
    const customerName = document.getElementById('customer_name').value.trim();
    const customerPhone = document.getElementById('customer_phone').value.trim();
    const productId = document.getElementById('product_id').value;

    if (!customerName) {
        e.preventDefault();
        alert('يرجى إدخال اسم العميل');
        document.getElementById('customer_name').focus();
        return;
    }

    if (!customerPhone || customerPhone.length < 9) {
        e.preventDefault();
        alert('يرجى إدخال رقم جوال صحيح (9 أرقام)');
        document.getElementById('customer_phone').focus();
        return;
    }

    if (!productId) {
        e.preventDefault();
        alert('يرجى اختيار المنتج');
        document.getElementById('product_id').focus();
        return;
    }
});

// تأكيد الحذف
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// عرض تفاصيل المنتج الأول إذا كان محدد مسبقاً
document.addEventListener('DOMContentLoaded', function() {
    const productId = document.getElementById('product_id').value;
    if (productId) {
        document.getElementById('product_id').dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
