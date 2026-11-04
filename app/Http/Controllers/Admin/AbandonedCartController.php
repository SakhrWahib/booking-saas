<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbandonedCart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AbandonedCartController extends Controller
{
    /**
     * عرض قائمة السلات المتروكة
     */
    public function index(Request $request)
    {
        $query = AbandonedCart::with('product');

        // فلتر حالة التواصل
        if ($request->has('contacted') && $request->contacted !== '') {
            $query->where('is_contacted', $request->contacted);
        }

        // فلتر التاريخ
        if ($request->has('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->today();
                    break;
                case 'week':
                    $query->thisWeek();
                    break;
                case 'month':
                    $query->thisMonth();
                    break;
            }
        }

        // فلتر البحث
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $abandonedCarts = $query->orderBy('created_at', 'desc')->paginate(15);

        // إحصائيات سريعة
        $stats = [
            'total' => AbandonedCart::count(),
            'not_contacted' => AbandonedCart::notContacted()->count(),
            'contacted' => AbandonedCart::contacted()->count(),
            'today' => AbandonedCart::today()->count(),
            'this_week' => AbandonedCart::thisWeek()->count(),
            'this_month' => AbandonedCart::thisMonth()->count(),
        ];

        return view('admin.abandoned_carts.index', compact('abandonedCarts', 'stats'));
    }

    /**
     * عرض تفاصيل سلة متروكة
     */
    public function show($id)
    {
        $abandonedCart = AbandonedCart::with('product')->findOrFail($id);
        
        return view('admin.abandoned_carts.show', compact('abandonedCart'));
    }

    /**
     * عرض نموذج إنشاء سلة متروكة
     */
    public function create()
    {
        $products = Product::active()->get();
        
        return view('admin.abandoned_carts.create', compact('products'));
    }

    /**
     * حفظ سلة متروكة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'product_id' => 'required|exists:products,id',
            'notes' => 'nullable|string'
        ]);

        try {
            AbandonedCart::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'product_id' => $request->product_id,
                'notes' => $request->notes,
                'is_contacted' => false
            ]);

            return redirect()->route('admin.abandoned-carts.index')
                           ->with('success', 'تم إضافة السلة المتروكة بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إضافة السلة المتروكة')
                        ->withInput();
        }
    }

    /**
     * عرض نموذج تعديل سلة متروكة
     */
    public function edit($id)
    {
        $abandonedCart = AbandonedCart::findOrFail($id);
        $products = Product::active()->get();
        
        return view('admin.abandoned_carts.edit', compact('abandonedCart', 'products'));
    }

    /**
     * تحديث سلة متروكة
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'product_id' => 'required|exists:products,id',
            'is_contacted' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        try {
            $abandonedCart = AbandonedCart::findOrFail($id);
            
            $abandonedCart->update([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'product_id' => $request->product_id,
                'is_contacted' => $request->has('is_contacted'),
                'notes' => $request->notes
            ]);

            return redirect()->route('admin.abandoned-carts.index')
                           ->with('success', 'تم تحديث السلة المتروكة بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحديث السلة المتروكة')
                        ->withInput();
        }
    }

    /**
     * حذف سلة متروكة
     */
    public function destroy($id)
    {
        try {
            $abandonedCart = AbandonedCart::findOrFail($id);
            $abandonedCart->delete();

            return redirect()->route('admin.abandoned-carts.index')
                           ->with('success', 'تم حذف السلة المتروكة بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف السلة المتروكة');
        }
    }

    /**
     * تحديث حالة التواصل
     */
    public function markAsContacted(Request $request, $id)
    {
        try {
            $abandonedCart = AbandonedCart::findOrFail($id);
            $abandonedCart->markAsContacted($request->notes);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة التواصل بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة التواصل'
            ], 500);
        }
    }

    /**
     * تحديث حالة التواصل للعديد من السلات
     */
    public function bulkMarkAsContacted(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:abandoned_carts,id'
        ]);

        try {
            AbandonedCart::whereIn('id', $request->ids)->update([
                'is_contacted' => true,
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة التواصل للعناصر المحددة بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة التواصل'
            ], 500);
        }
    }

    /**
     * حذف العديد من السلات
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:abandoned_carts,id'
        ]);

        try {
            AbandonedCart::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف العناصر المحددة بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف العناصر المحددة'
            ], 500);
        }
    }

    /**
     * تصدير السلات المتروكة
     */
    public function export(Request $request)
    {
        $query = AbandonedCart::with('product');

        // تطبيق نفس الفلاتر
        if ($request->has('contacted') && $request->contacted !== '') {
            $query->where('is_contacted', $request->contacted);
        }

        if ($request->has('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->today();
                    break;
                case 'week':
                    $query->thisWeek();
                    break;
                case 'month':
                    $query->thisMonth();
                    break;
            }
        }

        $abandonedCarts = $query->orderBy('created_at', 'desc')->get();

        $filename = 'abandoned_carts_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($abandonedCarts) {
            $file = fopen('php://output', 'w');
            
            // رأس الجدول
            fputcsv($file, [
                'الاسم',
                'رقم الجوال',
                'المنتج',
                'سعر المنتج',
                'حالة التواصل',
                'الملاحظات',
                'تاريخ الإضافة'
            ]);

            // البيانات
            foreach ($abandonedCarts as $cart) {
                fputcsv($file, [
                    $cart->customer_name,
                    $cart->customer_phone,
                    $cart->product->name,
                    $cart->product->price . ' ر.س',
                    $cart->is_contacted ? 'تم التواصل' : 'لم يتم التواصل',
                    $cart->notes,
                    $cart->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 