<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * عرض قائمة المنتجات مع إمكانية البحث والتصفية
     */
    public function index(Request $request)
    {
        try {
            $query = Product::query();

            // البحث بالاسم
            if ($request->has('search') && $request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // البحث بالوصف
            if ($request->has('description') && $request->description) {
                $query->where('description', 'like', '%' . $request->description . '%');
            }

            // تصفية حسب السعر
            if ($request->has('min_price')) {
                $query->where('price', '>=', $request->min_price);
            }
            if ($request->has('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }

            // تصفية حسب الحالة
            if ($request->has('is_active')) {
                $query->where('is_active', $request->is_active);
            }

            // تصفية حسب مقدمي الخدمات
            if ($request->has('service_provider_id')) {
                $query->whereJsonContains('service_provider_ids', (int)$request->service_provider_id);
            }

            // ترتيب النتائج
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // الصفحات
            $perPage = $request->get('per_page', 15);
            $products = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب قائمة المنتجات بنجاح',
                'data' => $products
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب قائمة المنتجات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض منتج محدد
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب بيانات المنتج بنجاح',
                'data' => $product
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'المنتج غير موجود',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * إنشاء منتج جديد
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'short_description' => 'required|string|max:1000',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'image_gallery' => 'nullable|array',
                'image_gallery.*' => 'nullable|string',
                'add_ons' => 'nullable|array',
                'add_ons.*.option' => 'required_with:add_ons|string',
                'add_ons.*.name' => 'required_with:add_ons|string',
                'add_ons.*.price' => 'required_with:add_ons|numeric|min:0',
                'add_ons.*.image' => 'nullable|string',
                'service_provider_ids' => 'nullable|array',
                'service_provider_ids.*' => 'integer|exists:service_providers,id,tenant_id,' . tenant_id(),
                'is_active' => 'boolean',
            ], [
                'name.required' => 'اسم المنتج مطلوب',
                'short_description.required' => 'الوصف المختصر مطلوب',
                'description.required' => 'وصف المنتج مطلوب',
                'price.required' => 'سعر المنتج مطلوب',
                'price.numeric' => 'السعر يجب أن يكون رقماً',
                'price.min' => 'السعر يجب أن يكون أكبر من صفر',
                'add_ons.*.option.required_with' => 'خيار الإضافة مطلوب',
                'add_ons.*.name.required_with' => 'اسم الإضافة مطلوب',
                'add_ons.*.price.required_with' => 'سعر الإضافة مطلوب',
                'service_provider_ids.*.exists' => 'مقدم الخدمة غير موجود',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = Product::create([
                'name' => $request->name,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'price' => $request->price,
                'image_gallery' => $request->image_gallery,
                'add_ons' => $request->add_ons,
                'service_provider_ids' => $request->service_provider_ids,
                'is_active' => $request->is_active ?? true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المنتج بنجاح',
                'data' => $product
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء المنتج',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث بيانات المنتج
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'short_description' => 'sometimes|required|string|max:1000',
                'description' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric|min:0',
                'image_gallery' => 'nullable|array',
                'image_gallery.*' => 'nullable|string',
                'add_ons' => 'nullable|array',
                'add_ons.*.option' => 'required_with:add_ons|string',
                'add_ons.*.name' => 'required_with:add_ons|string',
                'add_ons.*.price' => 'required_with:add_ons|numeric|min:0',
                'add_ons.*.image' => 'nullable|string',
                'service_provider_ids' => 'nullable|array',
                'service_provider_ids.*' => 'integer|exists:service_providers,id,tenant_id,' . tenant_id(),
                'is_active' => 'boolean',
            ], [
                'name.required' => 'اسم المنتج مطلوب',
                'short_description.required' => 'الوصف المختصر مطلوب',
                'description.required' => 'وصف المنتج مطلوب',
                'price.required' => 'سعر المنتج مطلوب',
                'price.numeric' => 'السعر يجب أن يكون رقماً',
                'price.min' => 'السعر يجب أن يكون أكبر من صفر',
                'add_ons.*.option.required_with' => 'خيار الإضافة مطلوب',
                'add_ons.*.name.required_with' => 'اسم الإضافة مطلوب',
                'add_ons.*.price.required_with' => 'سعر الإضافة مطلوب',
                'service_provider_ids.*.exists' => 'مقدم الخدمة غير موجود',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product->update($request->only([
                'name', 'short_description', 'description', 'price',
                'image_gallery', 'add_ons', 'service_provider_ids', 'is_active'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث بيانات المنتج بنجاح',
                'data' => $product
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث بيانات المنتج',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف المنتج
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المنتج بنجاح'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المنتج',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * البحث عن المنتجات حسب السعر
     */
    public function searchByPrice(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'min_price' => 'required|numeric|min:0',
                'max_price' => 'required|numeric|min:0|gte:min_price',
            ], [
                'min_price.required' => 'السعر الأدنى مطلوب',
                'max_price.required' => 'السعر الأقصى مطلوب',
                'max_price.gte' => 'السعر الأقصى يجب أن يكون أكبر من أو يساوي السعر الأدنى',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $products = Product::where('price', '>=', $request->min_price)
                ->where('price', '<=', $request->max_price)
                ->where('is_active', true)
                ->orderBy('price')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'تم البحث بنجاح',
                'data' => [
                    'products' => $products,
                    'price_range' => [
                        'min' => $request->min_price,
                        'max' => $request->max_price
                    ],
                    'total_found' => $products->count()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء البحث',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * البحث عن المنتجات حسب مقدمي الخدمات
     */
    public function searchByServiceProvider(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'service_provider_id' => 'required|integer|exists:service_providers,id,tenant_id,' . tenant_id(),
            ], [
                'service_provider_id.required' => 'معرف مقدم الخدمة مطلوب',
                'service_provider_id.exists' => 'مقدم الخدمة غير موجود',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $products = Product::whereJsonContains('service_provider_ids', (int)$request->service_provider_id)
                ->where('is_active', true)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'تم البحث بنجاح',
                'data' => [
                    'products' => $products,
                    'service_provider_id' => $request->service_provider_id,
                    'total_found' => $products->count()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء البحث',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تفعيل/إلغاء تفعيل المنتج
     */
    public function toggleStatus($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update(['is_active' => !$product->is_active]);

            return response()->json([
                'success' => true,
                'message' => $product->is_active ? 'تم تفعيل المنتج بنجاح' : 'تم إلغاء تفعيل المنتج بنجاح',
                'data' => $product
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير حالة المنتج',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب المنتجات النشطة فقط
     */
    public function activeProducts()
    {
        try {
            $products = Product::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'تم جلب المنتجات النشطة بنجاح',
                'data' => $products
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب المنتجات النشطة',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
