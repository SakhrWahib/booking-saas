<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper;

class ProductController extends Controller
{
    /**
     * عرض قائمة المنتجات
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(12);
        return view('admin.products.index', compact('products'));
    }

    /**
     * عرض صفحة إنشاء منتج جديد
     */
    public function create()
    {
        $serviceProviders = \App\Models\ServiceProvider::active()->get();
        return view('admin.products.create', compact('serviceProviders'));
    }

    /**
     * حفظ منتج جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'service_providers' => 'required|array|min:1',
            'service_providers.*' => 'exists:service_providers,id',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'add_ons' => 'nullable|array',
            'add_ons.*.option' => 'required_with:add_ons|string',
            'add_ons.*.name' => 'required_with:add_ons|string',
            'add_ons.*.price' => 'required_with:add_ons|numeric|min:0',
            'add_ons.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        // معالجة الصور
        $imageGallery = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                if (ImageHelper::isValidImage($image)) {
                    $filename = time() . '_' . $index . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
                    $savedPath = ImageHelper::saveImage($image, 'products', $filename);
                    if ($savedPath) {
                        $imageGallery[] = $savedPath;
                        \Log::info("تم حفظ الصورة: {$savedPath}");
                    } else {
                        \Log::error("فشل في حفظ الصورة: {$filename}");
                    }
                }
            }
        }

        // معالجة الإضافات
        $addOns = [];
        if ($request->has('add_ons') && is_array($request->add_ons)) {
            foreach ($request->add_ons as $index => $addOn) {
                if (!empty($addOn['option']) && !empty($addOn['name']) && isset($addOn['price'])) {
                    $addOnData = [
                        'option' => $addOn['option'],
                        'name' => $addOn['name'],
                        'price' => floatval($addOn['price']),
                        'image' => null
                    ];
                    
                    // معالجة صورة الإضافة
                    if ($request->hasFile("add_ons.{$index}.image")) {
                        $addOnImage = $request->file("add_ons.{$index}.image");
                        if (ImageHelper::isValidImage($addOnImage)) {
                            $addOnImageName = time() . '_addon_' . $index . '_' . Str::random(8) . '.' . $addOnImage->getClientOriginalExtension();
                            $addOnImagePath = ImageHelper::saveImage($addOnImage, 'products/addons', $addOnImageName);
                            if ($addOnImagePath) {
                                $addOnData['image'] = $addOnImagePath;
                            }
                        }
                    }
                    
                    $addOns[] = $addOnData;
                }
            }
        }

        $product = Product::create([
            'name' => $validated['name'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'image_gallery' => $imageGallery,
            'add_ons' => $addOns,
            'is_active' => $request->has('is_active') ? true : false
        ]);

        // ربط المنتج بمقدمي الخدمات
        $product->attachServiceProviders($validated['service_providers']);

        return redirect()->route('admin.products.index')
            ->with('success', 'تم إنشاء المنتج بنجاح');
    }

    /**
     * عرض تفاصيل منتج
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * عرض صفحة تعديل المنتج
     */
    public function edit(Product $product)
    {
        $serviceProviders = \App\Models\ServiceProvider::active()->get();
        return view('admin.products.edit', compact('product', 'serviceProviders'));
    }

    /**
     * تحديث المنتج
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'service_providers' => 'required|array|min:1',
            'service_providers.*' => 'exists:service_providers,id',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'existing_images' => 'nullable|array',
            'add_ons' => 'nullable|array',
            'add_ons.*.option' => 'required_with:add_ons|string',
            'add_ons.*.name' => 'required_with:add_ons|string',
            'add_ons.*.price' => 'required_with:add_ons|numeric|min:0',
            'add_ons.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'existing_addon_images' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        // معالجة الصور
        $imageGallery = [];
        
        // الاحتفاظ بالصور الموجودة
        if ($request->has('existing_images') && is_array($request->existing_images)) {
            $imageGallery = $request->existing_images;
        }

        // إضافة الصور الجديدة
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                if (ImageHelper::isValidImage($image)) {
                    $filename = time() . '_update_' . $index . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
                    $savedPath = ImageHelper::saveImage($image, 'products', $filename);
                    if ($savedPath) {
                        $imageGallery[] = $savedPath;
                    }
                }
            }
        }

        // معالجة الإضافات
        $addOns = [];
        if ($request->has('add_ons') && is_array($request->add_ons)) {
            foreach ($request->add_ons as $index => $addOn) {
                if (!empty($addOn['option']) && !empty($addOn['name']) && isset($addOn['price'])) {
                    $addOnData = [
                        'option' => $addOn['option'],
                        'name' => $addOn['name'],
                        'price' => floatval($addOn['price']),
                        'image' => null
                    ];
                    
                    // الاحتفاظ بالصورة الموجودة إذا كانت موجودة
                    if ($request->has('existing_addon_images') && isset($request->existing_addon_images[$index])) {
                        $addOnData['image'] = $request->existing_addon_images[$index];
                    }
                    
                    // معالجة صورة الإضافة الجديدة
                    if ($request->hasFile("add_ons.{$index}.image")) {
                        $addOnImage = $request->file("add_ons.{$index}.image");
                        if (ImageHelper::isValidImage($addOnImage)) {
                            // حذف الصورة القديمة إذا كانت موجودة
                            if ($addOnData['image']) {
                                ImageHelper::deleteImage($addOnData['image']);
                            }
                            
                            $addOnImageName = time() . '_addon_update_' . $index . '_' . Str::random(8) . '.' . $addOnImage->getClientOriginalExtension();
                            $addOnImagePath = ImageHelper::saveImage($addOnImage, 'products/addons', $addOnImageName);
                            if ($addOnImagePath) {
                                $addOnData['image'] = $addOnImagePath;
                            }
                        }
                    }
                    
                    $addOns[] = $addOnData;
                }
            }
        }

        $product->update([
            'name' => $validated['name'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'image_gallery' => $imageGallery,
            'add_ons' => $addOns,
            'is_active' => $request->has('is_active') ? true : false
        ]);

        // تحديث علاقة المنتج مع مقدمي الخدمات
        $product->attachServiceProviders($validated['service_providers']);

        return redirect()->route('admin.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * حذف المنتج
     */
    public function destroy(Product $product)
    {
        // حذف الصور من التخزين
        if ($product->image_gallery && is_array($product->image_gallery)) {
            foreach ($product->image_gallery as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }

    /**
     * حذف صورة من المنتج
     */
    public function deleteImage(Request $request, Product $product)
    {
        $imageIndex = $request->input('image_index');
        $images = $product->image_gallery ?? [];
        
        if (isset($images[$imageIndex])) {
            // حذف الصورة من التخزين
            if (Storage::disk('public')->exists($images[$imageIndex])) {
                Storage::disk('public')->delete($images[$imageIndex]);
            }
            
            // إزالة الصورة من المصفوفة
            unset($images[$imageIndex]);
            $images = array_values($images); // إعادة ترتيب المصفوفة
            
            // تحديث المنتج
            $product->update(['image_gallery' => $images]);
            
            return response()->json(['success' => true, 'message' => 'تم حذف الصورة بنجاح']);
        }
        
        return response()->json(['success' => false, 'message' => 'الصورة غير موجودة']);
    }

    /**
     * تغيير حالة المنتج (نشط/غير نشط)
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        
        $status = $product->is_active ? 'نشط' : 'غير نشط';
        return redirect()->back()->with('success', "تم تغيير حالة المنتج إلى: {$status}");
    }
} 