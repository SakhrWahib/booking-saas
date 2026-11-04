@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                        <p class="text-gray-600 mt-1">{{ $product->short_description }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.products.edit', $product) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل المنتج
                        </a>
                        <a href="{{ route('admin.products.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- معلومات المنتج الأساسية -->
            <div class="xl:col-span-2 space-y-6">
                <!-- معرض الصور -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-images text-green-600"></i>
                            معرض الصور ({{ $product->images_count }})
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6">
                        @if($product->image_gallery && count($product->image_gallery) > 0)
                            <!-- الصورة الرئيسية -->
                            <div class="mb-6">
                                <div class="relative">
                                    <img id="mainImage" 
                                         src="{{ getFirstProductImage($product->image_gallery) }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-80 object-cover rounded-lg border border-gray-300"
                                         onerror="this.src='{{ asset('images/default-product.svg') }}';">
                                    <div class="absolute top-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                                        <span id="imageCounter">1</span> / {{ count($product->image_gallery) }}
                                    </div>
                                </div>
                            </div>

                            <!-- الصور المصغرة -->
                            <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                                @foreach($product->raw_images as $index => $image)
                                    <div class="relative cursor-pointer thumbnail-container" 
                                         onclick="changeMainImage('{{ getProductImageUrl($image) }}', {{ $index + 1 }})">
                                        <img src="{{ getProductImageUrl($image) }}" 
                                             alt="صورة {{ $index + 1 }}" 
                                             class="w-full h-16 object-cover rounded-lg border-2 border-gray-300 hover:border-blue-500 transition-colors duration-200 thumbnail-image {{ $index === 0 ? 'border-blue-500' : '' }}"
                                             onerror="this.src='{{ asset('images/default-product.svg') }}';">
                                        <div class="absolute bottom-1 left-1 bg-black bg-opacity-50 text-white text-xs px-1 rounded">
                                            {{ $index + 1 }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-image text-4xl text-gray-400 mb-4"></i>
                                <p class="text-gray-500">لا توجد صور لهذا المنتج</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- وصف المنتج -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-align-left text-blue-600"></i>
                            وصف المنتج
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6">
                        <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                    </div>
                </div>

                <!-- الإضافات -->
                @if($product->add_ons && $product->add_ons->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-plus-circle text-red-600"></i>
                            الإضافات ({{ $product->add_ons_count }})
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($product->add_ons as $addOn)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start gap-4">
                                        @if($addOn->image)
                                            <img src="{{ getProductImageUrl($addOn->image) }}" 
                                                 alt="{{ $addOn->name }}"
                                                 class="w-16 h-16 object-cover rounded-lg border border-gray-300 flex-shrink-0"
                                                 onerror="this.src='{{ asset('images/default-product.svg') }}';">
                                        @else
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-plus text-gray-400"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $addOn->option }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ $addOn->name }}</p>
                                            <p class="text-lg font-semibold text-blue-600 mt-2">
                                                {{ number_format($addOn->price, 2) }} ر.س
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @elseif($product->add_ons && !$product->add_ons->count())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-plus-circle text-red-600"></i>
                            الإضافات (0)
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6">
                        <div class="text-center py-6">
                            <i class="fas fa-info-circle text-2xl text-blue-500 mb-2"></i>
                            <p class="text-gray-500">لا توجد إضافات لهذا المنتج</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- الشريط الجانبي -->
            <div class="xl:col-span-1 space-y-6">
                <!-- معلومات المنتج -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i>
                            معلومات المنتج
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">السعر:</span>
                            <span class="text-xl font-bold text-green-600">{{ number_format($product->price, 2) }} ر.س</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">الحالة:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">عدد الصور:</span>
                            <span class="font-medium">{{ $product->images_count }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">عدد الإضافات:</span>
                            <span class="font-medium">{{ $product->add_ons_count }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">تاريخ الإنشاء:</span>
                            <span class="font-medium">{{ $product->created_at->format('Y/m/d') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">آخر تحديث:</span>
                            <span class="font-medium">{{ $product->updated_at->format('Y/m/d') }}</span>
                        </div>
                    </div>
                </div>

                <!-- مقدمي الخدمات -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-users text-purple-600"></i>
                            @php
                                $serviceProviders = $product->serviceProviders();
                                $count = $serviceProviders->count();
                            @endphp
                            مقدمي الخدمات ({{ $count }})
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6">
                        @if($count > 0)
                            <div class="space-y-3">
                                @foreach($serviceProviders as $provider)
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-purple-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $provider->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $provider->email }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <i class="fas fa-users text-2xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500">لا يوجد مقدمي خدمات</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- إجراءات سريعة -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-bolt text-yellow-600"></i>
                            إجراءات سريعة
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-3">
                        <a href="{{ route('admin.products.edit', $product) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل المنتج
                        </a>
                        
                        <form method="POST" action="{{ route('admin.products.toggle-status', $product) }}" class="w-full">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-{{ $product->is_active ? 'red' : 'green' }}-600 hover:bg-{{ $product->is_active ? 'red' : 'green' }}-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                <i class="fas fa-{{ $product->is_active ? 'times' : 'check' }}"></i>
                                {{ $product->is_active ? 'إيقاف المنتج' : 'تفعيل المنتج' }}
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" 
                              class="w-full" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                                <i class="fas fa-trash"></i>
                                حذف المنتج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function changeMainImage(imageUrl, imageNumber) {
    const mainImage = document.getElementById('mainImage');
    const imageCounter = document.getElementById('imageCounter');
    
    mainImage.src = imageUrl;
    imageCounter.textContent = imageNumber;
    
    // تحديث حدود الصور المصغرة
    document.querySelectorAll('.thumbnail-image').forEach(img => {
        img.classList.remove('border-blue-500');
        img.classList.add('border-gray-300');
    });
    
    // إضافة حدود للصورة المحددة
    document.querySelectorAll('.thumbnail-image')[imageNumber - 1].classList.remove('border-gray-300');
    document.querySelectorAll('.thumbnail-image')[imageNumber - 1].classList.add('border-blue-500');
}
</script>
@endpush
