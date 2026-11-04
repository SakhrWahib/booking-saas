@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">إضافة سلة متروكة جديدة</h1>
                        <p class="text-gray-600 mt-1">قم بملء البيانات التالية لإضافة سلة متروكة جديدة للنظام</p>
                    </div>
                    <a href="{{ route('admin.abandoned-carts.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-arrow-right"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.abandoned-carts.store') }}" id="abandonedCartForm">
            @csrf
            
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
                        <!-- اسم العميل -->
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم العميل <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('customer_name') border-red-500 @enderror" 
                                   id="customer_name" name="customer_name" value="{{ old('customer_name') }}" 
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
                                       id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" 
                                       placeholder="5XXXXXXXX" maxlength="9" required>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">مثال: 551234567</p>
                            @error('customer_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                                <div>
                                    <h4 class="font-medium text-blue-800">معلومات السلة المتروكة</h4>
                                    <p class="text-sm text-blue-600">سيتم إضافة هذه السلة كسلة متروكة جديدة في النظام</p>
                                </div>
                            </div>
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
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} - {{ number_format($product->price, 2) }} ر.س
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- عرض تفاصيل المنتج -->
                        <div id="productDetails" class="hidden">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        <img id="productImage" class="h-16 w-16 rounded-lg object-cover" 
                                             src="" alt="صورة المنتج">
                                    </div>
                                    <div class="flex-1">
                                        <h4 id="productName" class="font-semibold text-gray-900"></h4>
                                        <p id="productDescription" class="text-sm text-gray-600 mt-1"></p>
                                        <div class="flex items-center gap-4 mt-2">
                                            <span id="productPrice" class="text-lg font-bold text-green-600"></span>
                                            @if($products->count() > 0)
                                                @if($products->first()->hasAddOns())
                                                    <span class="text-sm text-blue-600">
                                                        <i class="fas fa-plus-circle mr-1"></i>
                                                        يحتوي على إضافات
                                                    </span>
                                                @endif
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
                                      placeholder="أدخل أي ملاحظات إضافية حول السلة المتروكة">{{ old('notes') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">اختياري - يمكن إضافة ملاحظات حول سبب ترك السلة أو أي معلومات إضافية</p>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-exclamation-triangle text-orange-600 text-xl"></i>
                                <div>
                                    <h4 class="font-medium text-orange-800">معلومات مهمة</h4>
                                    <p class="text-sm text-orange-600">سيتم إضافة هذه السلة كسلة متروكة جديدة. يمكن التواصل مع العميل لاحقاً وتحويلها إلى حجز.</p>
                                </div>
                            </div>
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
                        <a href="{{ route('admin.abandoned-carts.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            حفظ السلة المتروكة
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

// عرض تفاصيل المنتج الأول إذا كان محدد مسبقاً
document.addEventListener('DOMContentLoaded', function() {
    const productId = document.getElementById('product_id').value;
    if (productId) {
        document.getElementById('product_id').dispatchEvent(new Event('change'));
    }
});
</script>
@endpush