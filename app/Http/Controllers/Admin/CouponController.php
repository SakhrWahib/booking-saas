<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * عرض قائمة الكوبونات
     */
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * عرض صفحة إنشاء كوبون جديد
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * حفظ كوبون جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code,NULL,id,tenant_id,' . tenant_id(),
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'اسم الكوبون مطلوب',
            'code.required' => 'رمز الكوبون مطلوب',
            'code.unique' => 'رمز الكوبون مستخدم بالفعل',
            'type.required' => 'نوع الخصم مطلوب',
            'value.required' => 'قيمة الخصم مطلوبة',
            'value.min' => 'قيمة الخصم يجب أن تكون أكبر من صفر',
            'min_amount.min' => 'الحد الأدنى يجب أن يكون أكبر من صفر',
            'max_uses.min' => 'الحد الأقصى لمرات الاستخدام يجب أن يكون أكبر من صفر',
            'end_date.after' => 'تاريخ انتهاء الصلاحية يجب أن يكون بعد تاريخ البداية'
        ]);

        // التحقق من قيمة الخصم حسب النوع
        if ($request->type === 'percentage' && $request->value > 100) {
            return back()->withErrors(['value' => 'النسبة المئوية لا يمكن أن تتجاوز 100%'])->withInput();
        }

        $coupon = Coupon::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'min_amount' => $request->min_amount ?? 0,
            'max_uses' => $request->max_uses,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'تم إنشاء الكوبون بنجاح');
    }

    /**
     * عرض تفاصيل الكوبون
     */
    public function show(Coupon $coupon)
    {
        $coupon->load(['bookings.customer', 'bookings.product', 'invoices.customer']);
        
        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * عرض صفحة تعديل الكوبون
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * تحديث الكوبون
     */
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id . ',id,tenant_id,' . tenant_id(),
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'اسم الكوبون مطلوب',
            'code.required' => 'رمز الكوبون مطلوب',
            'code.unique' => 'رمز الكوبون مستخدم بالفعل',
            'type.required' => 'نوع الخصم مطلوب',
            'value.required' => 'قيمة الخصم مطلوبة',
            'value.min' => 'قيمة الخصم يجب أن تكون أكبر من صفر',
            'min_amount.min' => 'الحد الأدنى يجب أن يكون أكبر من صفر',
            'max_uses.min' => 'الحد الأقصى لمرات الاستخدام يجب أن يكون أكبر من صفر',
            'end_date.after' => 'تاريخ انتهاء الصلاحية يجب أن يكون بعد تاريخ البداية'
        ]);

        // التحقق من قيمة الخصم حسب النوع
        if ($request->type === 'percentage' && $request->value > 100) {
            return back()->withErrors(['value' => 'النسبة المئوية لا يمكن أن تتجاوز 100%'])->withInput();
        }

        // التحقق من الحد الأقصى لمرات الاستخدام
        if ($request->max_uses && $request->max_uses < $coupon->used_count) {
            return back()->withErrors(['max_uses' => 'الحد الأقصى لا يمكن أن يكون أقل من عدد مرات الاستخدام الحالية'])->withInput();
        }

        $coupon->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'min_amount' => $request->min_amount ?? 0,
            'max_uses' => $request->max_uses,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'تم تحديث الكوبون بنجاح');
    }

    /**
     * حذف الكوبون
     */
    public function destroy(Coupon $coupon)
    {
        // التحقق من عدم استخدام الكوبون في حجوزات أو فواتير
        if ($coupon->bookings()->exists() || $coupon->invoices()->exists()) {
            return back()->with('error', 'لا يمكن حذف الكوبون لأنه مستخدم في حجوزات أو فواتير');
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'تم حذف الكوبون بنجاح');
    }

    /**
     * تغيير حالة الكوبون
     */
    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        $status = $coupon->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        
        return back()->with('success', "تم {$status} الكوبون بنجاح");
    }

    /**
     * توليد رمز كوبون تلقائياً
     */
    public function generateCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Coupon::where('code', $code)->exists());

        return response()->json(['code' => $code]);
    }

    /**
     * التحقق من صلاحية الكوبون
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::findValidByCode($request->code);

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'الكوبون غير صالح أو منتهي الصلاحية'
            ]);
        }

        if (!$coupon->isValidForAmount($request->amount)) {
            return response()->json([
                'valid' => false,
                'message' => "المبلغ لا يلبي الحد الأدنى للكوبون ({$coupon->min_amount} ر.س)"
            ]);
        }

        $discountAmount = $coupon->calculateDiscount($request->amount);
        $finalAmount = $request->amount - $discountAmount;

        return response()->json([
            'valid' => true,
            'coupon' => [
                'id' => $coupon->id,
                'name' => $coupon->name,
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount
            ],
            'message' => 'الكوبون صالح'
        ]);
    }

    /**
     * إحصائيات الكوبونات
     */
    public function statistics()
    {
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::active()->count();
        $expiredCoupons = Coupon::where('end_date', '<', now())->count();
        $totalUsage = Coupon::sum('used_count');

        $recentCoupons = Coupon::withCount(['bookings', 'invoices'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'total' => $totalCoupons,
            'active' => $activeCoupons,
            'expired' => $expiredCoupons,
            'total_usage' => $totalUsage,
            'recent' => $recentCoupons
        ]);
    }
} 