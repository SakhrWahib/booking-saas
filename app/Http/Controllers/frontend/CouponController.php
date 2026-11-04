<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    /**
     * التحقق من صحة الكوبون
     */
    public function validateCoupon(Request $request)
    {
        try {
            $request->validate([
                'coupon_code' => 'required|string|max:50',
                'total_amount' => 'required|numeric|min:0'
            ]);

            // البحث عن الكوبون بالكود
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            
            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكوبون غير موجود',
                    'discount_amount' => 0
                ]);
            }

            // التحقق من حالة الكوبون
            if (!$coupon->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكوبون متوقف',
                    'discount_amount' => 0
                ]);
            }

            // التحقق من تاريخ الصلاحية
            $now = \Carbon\Carbon::now();
            if ($coupon->start_date && $now->lt($coupon->start_date)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكوبون لم يبدأ بعد',
                    'discount_amount' => 0
                ]);
            }

            if ($coupon->end_date && $now->gt($coupon->end_date)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكوبون منتهي الصلاحية',
                    'discount_amount' => 0
                ]);
            }

            // التحقق من عدد مرات الاستخدام
            if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
                return response()->json([
                    'success' => false,
                    'message' => 'تم استنفاذ عدد مرات استخدام الكوبون',
                    'discount_amount' => 0
                ]);
            }

            // التحقق من الحد الأدنى للمبلغ
            if ($request->total_amount < $coupon->min_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'المبلغ لا يلبي الحد الأدنى المطلوب للكوبون (' . number_format($coupon->min_amount, 2) . ' ر.س)',
                    'discount_amount' => 0
                ]);
            }

            // حساب قيمة الخصم
            $discountAmount = 0;
            if ($coupon->type === 'percentage') {
                $discountAmount = ($request->total_amount * $coupon->value) / 100;
            } else {
                $discountAmount = min($coupon->value, $request->total_amount);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'الكوبون صالح',
                'coupon' => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'name' => $coupon->name,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'min_amount' => $coupon->min_amount
                ],
                'discount_amount' => $discountAmount,
                'final_amount' => $request->total_amount - $discountAmount
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'البيانات المرسلة غير صحيحة',
                'errors' => $e->errors(),
                'discount_amount' => 0
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in validateCoupon: ' . $e->getMessage(), [
                'coupon_code' => $request->coupon_code,
                'total_amount' => $request->total_amount,
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحقق من الكوبون، يرجى المحاولة مرة أخرى',
                'discount_amount' => 0
            ], 500);
        }
    }
} 