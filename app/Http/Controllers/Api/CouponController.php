<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    /**
     * التحقق من صحة الكوبون وحساب الخصم
     */
    public function validateCoupon(Request $request)
    {
        try {
            $request->validate([
                'coupon_code' => 'required|string|max:50',
                'total_amount' => 'required|numeric|min:0'
            ]);

            $coupon = Coupon::where('code', $request->coupon_code)->first();

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكوبون غير موجود',
                    'discount_amount' => 0
                ]);
            }

            if (!$coupon->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'الكوبون متوقف',
                    'discount_amount' => 0
                ]);
            }

            $now = now();
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

            if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
                return response()->json([
                    'success' => false,
                    'message' => 'تم استنفاذ عدد مرات استخدام الكوبون',
                    'discount_amount' => 0
                ]);
            }

            if ($request->total_amount < $coupon->min_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'المبلغ لا يلبي الحد الأدنى المطلوب للكوبون (' . number_format($coupon->min_amount, 2) . ' ر.س)',
                    'discount_amount' => 0
                ]);
            }

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
                'discount_amount' => round($discountAmount, 2),
                'final_amount' => round($request->total_amount - $discountAmount, 2)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'البيانات المرسلة غير صحيحة',
                'errors' => $e->errors(),
                'discount_amount' => 0
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحقق من الكوبون',
                'discount_amount' => 0
            ], 500);
        }
    }
}


