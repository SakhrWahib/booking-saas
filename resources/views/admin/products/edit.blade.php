@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">تعديل المنتج</h1>
                        <p class="text-gray-600 mt-1">تعديل بيانات المنتج: <span class="font-semibold text-blue-600">{{ $product->name }}</span></p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.products.show', $product) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-eye"></i>
                            عرض التفاصيل
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

        <!-- Form -->
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" id="productForm">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                <!-- القائمة الجانبية -->
                <div class="xl:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-list text-blue-600"></i>
                                معلومات الحجز
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">تفاصيل الحجز الأساسية</p>
                        </div>
                        
                        <div class="px-6 py-4 space-y-4">


                            <!-- معرض الصور -->
                            <div class="bg-green-50 rounded-lg p-4">
                                <h4 class="font-medium text-green-900 mb-3 flex items-center gap-2">
                                    <i class="fas fa-images text-green-600"></i>
                                    معرض الصور
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-green-700">عدد الصور:</span>
                                        <span class="text-green-900 font-medium">{{ $product->images_count }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- الأسعار ومقدمي الخدمات -->
                            <div class="bg-orange-50 rounded-lg p-4">
                                <h4 class="font-medium text-orange-900 mb-3 flex items-center gap-2">
                                    <i class="fas fa-dollar-sign text-orange-600"></i>
                                    الأسعار ومقدمي الخدمات
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-orange-700">السعر:</span>
                                        <span class="text-orange-900 font-medium">{{ number_format($product->price, 2) }} ر.س</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-orange-700">مقدمي الخدمات:</span>
                                        <span class="text-orange-900 font-medium">{{ $product->serviceProviders()->count() }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- الإضافات -->
                            <div class="bg-red-50 rounded-lg p-4">
                                <h4 class="font-medium text-red-900 mb-3 flex items-center gap-2">
                                    <i class="fas fa-plus-circle text-red-600"></i>
                                    الإضافات
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-red-700">عدد الإضافات:</span>
                                        <span class="text-red-900 font-medium">{{ $product->add_ons_count }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- حالة الحجز -->
                            <div class="bg-{{ $product->is_active ? 'green' : 'gray' }}-50 rounded-lg p-4">
                                <h4 class="font-medium text-{{ $product->is_active ? 'green' : 'gray' }}-900 mb-3 flex items-center gap-2">
                                    <i class="fas fa-toggle-{{ $product->is_active ? 'on' : 'off' }} text-{{ $product->is_active ? 'green' : 'gray' }}-600"></i>
                                    حالة الحجز
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-{{ $product->is_active ? 'green' : 'gray' }}-700">الحالة:</span>
                                        <span class="text-{{ $product->is_active ? 'green' : 'gray' }}-900 font-medium">{{ $product->is_active ? 'نشط' : 'غير نشط' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- المحتوى الرئيسي -->
                <div class="xl:col-span-3 space-y-6">
                    <!-- البيانات الأساسية -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-box text-blue-600"></i>
                                البيانات الأساسية
                            </h3>
                        </div>
                        
                        <div class="px-6 py-6 space-y-6">
                            <!-- اسم المنتج -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    اسم المنتج <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('name') border-red-500 @enderror" 
                                       id="name" name="name" value="{{ old('name', $product->name) }}" 
                                       placeholder="أدخل اسم المنتج" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- وصف مختصر للمنتج -->
                            <div>
                                <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                                    وصف مختصر للمنتج <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('short_description') border-red-500 @enderror" 
                                       id="short_description" name="short_description" value="{{ old('short_description', $product->short_description) }}" 
                                       placeholder="وصف قصير للمنتج" required>
                                @error('short_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- وصف المنتج -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    وصف المنتج <span class="text-red-500">*</span>
                                </label>
                                <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('description') border-red-500 @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="أدخل وصف تفصيلي للمنتج" required>{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>



                    <!-- معرض الصور -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-images text-green-600"></i>
                                معرض الصور
                            </h3>
                        </div>
                        
                        <div class="px-6 py-6">
                            <!-- الصور الموجودة -->
                            @if($product->image_gallery && count($product->image_gallery) > 0)
                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-800 mb-4">الصور الحالية</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="existingImages">
                                    @foreach($product->image_gallery as $index => $image)
                                    <div class="relative group existing-image" data-index="{{ $index }}">
                                        <img src="{{ getProductImageUrl($image) }}" 
                                             alt="صورة {{ $index + 1 }}" 
                                             class="w-full h-32 object-cover rounded-lg border border-gray-300"
                                             onerror="this.src='{{ asset('images/default-product.svg') }}';">
                                        <button type="button" 
                                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                                onclick="removeExistingImage({{ $index }})">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                        <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- رفع صور جديدة -->
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                                <div class="space-y-4">
                                    <div class="flex justify-center">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                                    </div>
                                    <div>
                                        <label for="images" class="cursor-pointer">
                                            <span class="text-lg font-medium text-gray-900">إضافة صور جديدة</span>
                                            <p class="text-sm text-gray-500 mt-1">الملفات المدعومة: صيغة JPEG, PNG, GIF, WEBP بحد أقصى 10 صور</p>
                                            <p class="text-sm text-gray-500">حجم الصورة الواحدة: أقل من 5 ميجابايت</p>
                                        </label>
                                        <input type="file" 
                                               class="hidden" 
                                               id="images" 
                                               name="images[]" 
                                               multiple 
                                               accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                                               onchange="previewImages(this)">
                                    </div>
                                    <div>
                                        <label for="images" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 cursor-pointer">
                                            اختر الصور
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- معاينة الصور الجديدة -->
                            <div id="imagePreview" class="mt-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 hidden">
                                <!-- سيتم إضافة الصور هنا بواسطة JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- الأسعار والاختيارات -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-dollar-sign text-orange-600"></i>
                                الأسعار والاختيارات
                            </h3>
                        </div>
                        
                        <div class="px-6 py-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- السعر -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                        السعر <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex">
                                        <input type="number" 
                                               step="0.01"
                                               class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-r-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('price') border-red-500 @enderror" 
                                               id="price" name="price" value="{{ old('price', $product->price) }}" 
                                               min="0" required>
                                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-l-0 border-gray-300 rounded-l-md">
                                            ر.س
                                        </span>
                                    </div>
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- مقدمي الخدمات -->
                                <div>
                                    <label for="service_providers" class="block text-sm font-medium text-gray-700 mb-2">
                                        مقدمي الخدمات <span class="text-red-500">*</span>
                                    </label>
                                    <select multiple 
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('service_providers') border-red-500 @enderror" 
                                            id="service_providers" name="service_providers[]" required>
                                        @foreach($serviceProviders as $provider)
                                            <option value="{{ $provider->id }}" 
                                                    {{ in_array($provider->id, old('service_providers', $product->service_provider_ids ?? [])) ? 'selected' : '' }}>
                                                {{ $provider->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500">اختر مقدمي الخدمات الذين يقدمون هذا المنتج</p>
                                    @error('service_providers')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الإضافات -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-plus-circle text-red-600"></i>
                                الإضافات
                            </h3>
                        </div>
                        
                        <div class="px-6 py-6">
                            <div id="addOnsContainer">
                                @if($product->add_ons && $product->add_ons->count() > 0)
                                    @foreach($product->add_ons as $index => $addOn)
                                    <div class="border border-gray-200 rounded-lg p-4 mb-4 add-on-item">
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="text-md font-medium text-gray-800">الخيار #{{ $index + 1 }}</h4>
                                            <button type="button" 
                                                    class="text-red-600 hover:text-red-800 p-1"
                                                    onclick="removeAddOn(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">الخيار</label>
                                                <input type="text" 
                                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right" 
                                                       name="add_ons[{{ $index }}][option]" 
                                                       value="{{ old('add_ons.' . $index . '.option', $addOn->option) }}"
                                                       placeholder="مثال: غسيل إضافي">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">الاسم</label>
                                                <input type="text" 
                                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right" 
                                                       name="add_ons[{{ $index }}][name]" 
                                                       value="{{ old('add_ons.' . $index . '.name', $addOn->name) }}"
                                                       placeholder="إضافة غسيل كلب آخر">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">المبلغ</label>
                                                <div class="flex">
                                                    <input type="number" 
                                                           step="0.01"
                                                           class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-r-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right" 
                                                           name="add_ons[{{ $index }}][price]" 
                                                           value="{{ old('add_ons.' . $index . '.price', $addOn->price) }}"
                                                           min="0">
                                                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-l-0 border-gray-300 rounded-l-md">
                                                        ر.س
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">صورة الإضافة</label>
                                                @if($addOn->image)
                                                    <div class="mb-2">
                                                        <img src="{{ getProductImageUrl($addOn->image) }}" 
                                                             alt="صورة الإضافة" 
                                                             class="w-20 h-20 object-cover rounded-lg border border-gray-300"
                                                             onerror="this.src='{{ asset('images/default-product.svg') }}';">
                                                        <input type="hidden" name="existing_addon_images[{{ $index }}]" value="{{ $addOn->image }}">
                                                    </div>
                                                @endif
                                                <input type="file" 
                                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                                       name="add_ons[{{ $index }}][image]" 
                                                       accept="image/*">
                                                <p class="mt-1 text-xs text-gray-500">اختياري - صورة توضيحية للإضافة</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="text-center add-new-btn">
                                        <button type="button" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2 mx-auto"
                                                onclick="addNewAddOn()">
                                            <i class="fas fa-plus"></i>
                                            إضافة خيار آخر
                                        </button>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <i class="fas fa-plus-circle text-4xl text-gray-400 mb-4"></i>
                                        <p class="text-gray-500 mb-4">لا توجد إضافات</p>
                                        <button type="button" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2 mx-auto"
                                                onclick="addNewAddOn()">
                                            <i class="fas fa-plus"></i>
                                            إضافة خيار جديد
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- أزرار الحفظ -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        تأكد من صحة البيانات قبل الحفظ
                    </div>
                    <div class="flex gap-3">
                        <button type="button" 
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2"
                                onclick="previewProduct()">
                            <i class="fas fa-eye"></i>
                            عرض
                        </button>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            حفظ التغييرات
                        </button>
                    </div>
                </div>
            </div>

            <!-- حالة الحجز -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-toggle-on text-green-500 mr-2"></i>
                        حالة الحجز
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                   name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <span class="mr-2 text-sm text-gray-700">نشر على الموقع الإلكتروني</span>
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let addOnCounter = {{ $product->add_ons ? count($product->add_ons) : 0 }};

let selectedFiles = [];

function previewImages(input) {
    const previewContainer = document.getElementById('imagePreview');
    
    if (input.files && input.files.length > 0) {
        // إضافة الملفات الجديدة للمصفوفة الموجودة
        Array.from(input.files).forEach(file => {
            if (selectedFiles.length < 10) { // الحد الأقصى 10 صور
                selectedFiles.push(file);
            }
        });
        
        updatePreview();
    }
}

function updatePreview() {
    const previewContainer = document.getElementById('imagePreview');
    previewContainer.innerHTML = '';
    
    if (selectedFiles.length > 0) {
        previewContainer.classList.remove('hidden');
        
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageContainer = document.createElement('div');
                imageContainer.className = 'relative group';
                imageContainer.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-32 object-cover rounded-lg border border-gray-300">
                    <button type="button" 
                            class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                            onclick="removeImage(${index})">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                    <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                        ${index + 1}/${selectedFiles.length}
                    </div>
                `;
                previewContainer.appendChild(imageContainer);
            };
            reader.readAsDataURL(file);
        });
    } else {
        previewContainer.classList.add('hidden');
    }
    
    // تحديث input file
    updateFileInput();
}

function updateFileInput() {
    const input = document.getElementById('images');
    const dt = new DataTransfer();
    
    selectedFiles.forEach(file => {
        dt.items.add(file);
    });
    
    input.files = dt.files;
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    updatePreview();
}

function removeExistingImage(index) {
    const imageElement = document.querySelector(`.existing-image[data-index="${index}"]`);
    if (imageElement) {
        imageElement.remove();
    }
}

function addNewAddOn() {
    addOnCounter++;
    const container = document.getElementById('addOnsContainer');
    
    if (container.querySelector('.text-center') && !container.querySelector('.add-on-item')) {
        container.innerHTML = '';
    }
    
    const addOnHtml = `
        <div class="border border-gray-200 rounded-lg p-4 mb-4 add-on-item">
            <div class="flex justify-between items-start mb-4">
                <h4 class="text-md font-medium text-gray-800">الخيار #${addOnCounter + 1}</h4>
                <button type="button" 
                        class="text-red-600 hover:text-red-800 p-1"
                        onclick="removeAddOn(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الخيار</label>
                    <input type="text" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right" 
                           name="add_ons[${addOnCounter}][option]" 
                           placeholder="مثال: غسيل إضافي">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الاسم</label>
                    <input type="text" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right" 
                           name="add_ons[${addOnCounter}][name]" 
                           placeholder="إضافة غسيل كلب آخر">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">المبلغ</label>
                    <div class="flex">
                        <input type="number" 
                               step="0.01"
                               class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-r-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right" 
                               name="add_ons[${addOnCounter}][price]" 
                               min="0">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-l-0 border-gray-300 rounded-l-md">
                            ر.س
                        </span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">صورة الإضافة</label>
                    <input type="file" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                           name="add_ons[${addOnCounter}][image]" 
                           accept="image/*">
                    <p class="mt-1 text-xs text-gray-500">اختياري - صورة توضيحية للإضافة</p>
                </div>
            </div>
        </div>
    `;
    
    // إزالة زر الإضافة الموجود
    const addNewBtn = container.querySelector('.add-new-btn');
    if (addNewBtn) {
        addNewBtn.remove();
    }
    
    container.insertAdjacentHTML('beforeend', addOnHtml);
    
    // إضافة زر إضافة جديد
    const addButtonHtml = `
        <div class="text-center add-new-btn">
            <button type="button" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2 mx-auto"
                    onclick="addNewAddOn()">
                <i class="fas fa-plus"></i>
                إضافة خيار آخر
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', addButtonHtml);
}

function removeAddOn(button) {
    const addOnItem = button.closest('.add-on-item');
    addOnItem.remove();
    
    const container = document.getElementById('addOnsContainer');
    const addOnItems = container.querySelectorAll('.add-on-item');
    
    if (addOnItems.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-plus-circle text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500 mb-4">لا توجد إضافات</p>
                <button type="button" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2 mx-auto"
                        onclick="addNewAddOn()">
                    <i class="fas fa-plus"></i>
                    إضافة خيار جديد
                </button>
            </div>
        `;
    }
}

function previewProduct() {
    // يمكن إضافة منطق معاينة المنتج هنا
    alert('معاينة المنتج - سيتم تطبيق هذه الوظيفة لاحقاً');
}


</script>
@endpush
