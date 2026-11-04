<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbandonedCartController extends Controller
{
    /**
     * عرض قائمة السلات المتروكة مع فلاتر بسيطة
     */
    public function index(Request $request)
    {
        try {
            $query = AbandonedCart::query();

            // بحث باسم العميل
            if ($request->filled('search')) {
                $query->where('customer_name', 'like', "%{$request->get('search')}%");
            }

            // فلترة بحسب رقم الهاتف
            if ($request->filled('customer_phone')) {
                $query->where('customer_phone', 'like', "%{$request->get('customer_phone')}%");
            }

            // فلترة بحسب المنتج
            if ($request->filled('product_id')) {
                $query->where('product_id', (int) $request->get('product_id'));
            }

            // فلترة بحسب حالة التواصل
            if ($request->filled('is_contacted')) {
                $query->where('is_contacted', (bool) $request->get('is_contacted'));
            }

            // فلترة بحسب التاريخ
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->get('date_from'));
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->get('date_to'));
            }

            // ترتيب وتقسيم صفحات
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = (int) $request->get('per_page', 15);
            $carts = $query->with(['product:id,name,price'])->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب قائمة السلات المتروكة بنجاح',
                'data' => $carts
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب قائمة السلات المتروكة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض سلة متروكة محددة
     */
    public function show($id)
    {
        try {
            $cart = AbandonedCart::with(['product:id,name,price,description'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب بيانات السلة المتروكة بنجاح',
                'data' => $cart
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'السلة المتروكة غير موجودة',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * إنشاء سلة متروكة جديدة
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:20',
                'product_id' => 'required|integer|exists:products,id,tenant_id,' . tenant_id(),
                'notes' => 'nullable|string|max:1000'
            ], [
                'customer_name.required' => 'اسم العميل مطلوب',
                'customer_phone.required' => 'رقم الهاتف مطلوب',
                'product_id.required' => 'المنتج مطلوب',
                'product_id.exists' => 'المنتج غير موجود'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cart = AbandonedCart::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'product_id' => $request->product_id,
                'notes' => $request->notes,
                'is_contacted' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء السلة المتروكة بنجاح',
                'data' => $cart->load(['product:id,name,price'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء السلة المتروكة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث بيانات السلة المتروكة
     */
    public function update(Request $request, $id)
    {
        try {
            $cart = AbandonedCart::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'customer_name' => 'sometimes|required|string|max:255',
                'customer_phone' => 'sometimes|required|string|max:20',
                'product_id' => 'sometimes|required|integer|exists:products,id,tenant_id,' . tenant_id(),
                'is_contacted' => 'sometimes|boolean',
                'notes' => 'nullable|string|max:1000'
            ], [
                'customer_name.required' => 'اسم العميل مطلوب',
                'customer_phone.required' => 'رقم الهاتف مطلوب',
                'product_id.required' => 'المنتج مطلوب',
                'product_id.exists' => 'المنتج غير موجود'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cart->update($request->only([
                'customer_name', 'customer_phone', 'product_id', 'is_contacted', 'notes'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث بيانات السلة المتروكة بنجاح',
                'data' => $cart->load(['product:id,name,price'])
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث بيانات السلة المتروكة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف سلة متروكة
     */
    public function destroy($id)
    {
        try {
            $cart = AbandonedCart::findOrFail($id);
            $cart->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف السلة المتروكة بنجاح'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف السلة المتروكة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث حالة التواصل
     */
    public function markAsContacted(Request $request, $id)
    {
        try {
            $cart = AbandonedCart::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'notes' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cart->markAsContacted($request->notes);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة التواصل بنجاح',
                'data' => $cart->load(['product:id,name,price'])
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة التواصل',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إحصائيات السلات المتروكة
     */
    public function statistics()
    {
        try {
            $stats = [
                'total' => AbandonedCart::count(),
                'contacted' => AbandonedCart::contacted()->count(),
                'not_contacted' => AbandonedCart::notContacted()->count(),
                'today' => AbandonedCart::today()->count(),
                'this_week' => AbandonedCart::thisWeek()->count(),
                'this_month' => AbandonedCart::thisMonth()->count()
            ];

            return response()->json([
                'success' => true,
                'message' => 'تم جلب الإحصائيات بنجاح',
                'data' => $stats
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الإحصائيات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * السلات التي لم يتم التواصل معها
     */
    public function notContacted()
    {
        try {
            $carts = AbandonedCart::notContacted()
                ->with(['product:id,name,price'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'تم جلب السلات غير المتواصل معها بنجاح',
                'data' => $carts
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب السلات',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
