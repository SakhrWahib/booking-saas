<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * إرجاع قائمة الحجوزات مع فلاتر بسيطة
     */
    public function index(Request $request)
    {
        try {
            $query = Booking::query();

            // بحث برقم الحجز
            if ($request->filled('search')) {
                $query->where('booking_number', 'like', "%{$request->get('search')}%");
            }

            // فلترة بحسب العميل
            if ($request->filled('customer_id')) {
                $query->where('customer_id', (int) $request->get('customer_id'));
            }
            if ($request->filled('customer_phone')) {
                $phone = preg_replace('/[^\d]/', '', $request->get('customer_phone'));
                $customerId = Customer::where('phone', $phone)->value('id');
                if ($customerId) {
                    $query->where('customer_id', $customerId);
                } else {
                    // لا يوجد عميل؛ ارجع نتيجة فارغة
                    return response()->json(['success' => true, 'message' => 'لا توجد نتائج', 'data' => $query->paginate(1)], 200);
                }
            }

            // فلترة بحسب مقدم الخدمة / المنتج
            if ($request->filled('service_provider_id')) {
                $query->where('service_provider_id', (int) $request->get('service_provider_id'));
            }
            if ($request->filled('product_id')) {
                $query->where('product_id', (int) $request->get('product_id'));
            }

            // فلترة التاريخ
            if ($request->filled('for_date')) {
                $query->onDate($request->get('for_date'));
            } else {
                if ($request->filled('date_from')) {
                    $query->where('booking_date', '>=', $request->get('date_from'));
                }
                if ($request->filled('date_to')) {
                    $query->where('booking_date', '<=', $request->get('date_to'));
                }
            }

            // فلترة الحالة (قائمة مفصولة بفواصل)
            if ($request->filled('status')) {
                $statuses = array_filter(array_map('trim', explode(',', $request->get('status'))));
                if (!empty($statuses)) {
                    $query->whereIn('status', $statuses);
                }
            }

            // ترتيب وتقسيم صفحات
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = (int) $request->get('per_page', 15);
            $bookings = $query->with(['customer:id,name,phone', 'product:id,name,price', 'serviceProvider:id,name'])->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب قائمة الحجوزات بنجاح',
                'data' => $bookings
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب قائمة الحجوزات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إنشاء حجز جديد
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                // بيانات العميل
                'customer_id' => 'nullable|exists:customers,id,tenant_id,' . tenant_id(),
                'customer.name' => 'required_without:customer_id|string|max:255',
                'customer.phone' => 'required_without:customer_id|string|max:20',

                // بيانات الحجز
                'product_id' => 'required|integer|exists:products,id,tenant_id,' . tenant_id(),
                'service_provider_id' => 'required|integer|exists:service_providers,id,tenant_id,' . tenant_id(),
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required|string',
                'quantity' => 'nullable|integer|min:1',
                'selected_add_ons' => 'nullable|array',
                'selected_add_ons.*.option' => 'required_with:selected_add_ons|string',
                'selected_add_ons.*.name' => 'required_with:selected_add_ons|string',
                'selected_add_ons.*.price' => 'required_with:selected_add_ons|numeric|min:0',
                'selected_add_ons.*.image' => 'nullable|string',

                // الموقع
                'location.latitude' => 'nullable|numeric',
                'location.longitude' => 'nullable|numeric',
                'location.address' => 'nullable|string',

                // الكوبون والدفع
                'coupon_code' => 'nullable|string|max:50',
                'payment_method' => 'nullable|in:pay_later,bank_transfer'
            ], [
                'product_id.required' => 'المنتج مطلوب',
                'service_provider_id.required' => 'مقدم الخدمة مطلوب',
                'date.required' => 'التاريخ مطلوب',
                'time.required' => 'الوقت مطلوب',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            // تحقق من صلاحية التاريخ
            $date = $request->get('date');
            $providerId = (int) $request->get('service_provider_id');
            if (!Booking::isValidWorkingDate($date, $providerId)) {
                $reason = Booking::getDateValidationMessage($date, $providerId);
                return response()->json(['success' => false, 'message' => $reason], 422);
            }

            // تحقق من صلاحية الوقت وتوفره
            $time = $request->get('time');
            if (!Booking::isValidTime($time, $providerId)) {
                return response()->json(['success' => false, 'message' => 'الوقت غير مسموح به ضمن جدول العمل'], 422);
            }
            if (!Booking::isTimeSlotAvailable($date, $time, $providerId)) {
                return response()->json(['success' => false, 'message' => 'عذراً، هذا الوقت محجوز بالفعل'], 422);
            }

            DB::beginTransaction();

            // العميل: استخدام customer_id أو الإنشاء/التحديث عبر الهاتف
            $customerId = $request->get('customer_id');
            if (!$customerId) {
                $rawPhone = $request->input('customer.phone');
                $phone = preg_replace('/[^\d]/', '', $rawPhone);
                $customer = Customer::firstOrCreate(['phone' => $phone], [
                    'name' => $request->input('customer.name')
                ]);

                // تحديث الاسم إذا اختلف
                if ($request->filled('customer.name') && $customer->name !== $request->input('customer.name')) {
                    $customer->update(['name' => $request->input('customer.name')]);
                }

                // تحديث الموقع إذا أُرسل
                if ($request->filled('location.latitude') && $request->filled('location.longitude')) {
                    $customer->update([
                        'latitude' => $request->input('location.latitude'),
                        'longitude' => $request->input('location.longitude'),
                        'address' => $request->input('location.address')
                    ]);
                }
                $customerId = $customer->id;
            }

            // حساب السعر الأساسي
            $product = Product::findOrFail((int) $request->get('product_id'));
            $quantity = (int) $request->get('quantity', 1);
            $selectedAddOns = $request->get('selected_add_ons', []);
            $addOnsTotal = 0;
            foreach ((array) $selectedAddOns as $addOn) {
                $addOnsTotal += isset($addOn['price']) ? (float) $addOn['price'] : 0;
            }
            $subtotal = ($product->price + $addOnsTotal) * max(1, $quantity);

            // التعامل مع الكوبون اختياري
            $couponId = null;
            $discountAmount = 0;
            if ($request->filled('coupon_code')) {
                $coupon = \App\Models\Coupon::where('code', $request->get('coupon_code'))->first();
                if ($coupon && $coupon->is_active && (!$coupon->start_date || now()->gte($coupon->start_date)) && (!$coupon->end_date || now()->lte($coupon->end_date)) && (!$coupon->max_uses || $coupon->used_count < $coupon->max_uses) && $subtotal >= $coupon->min_amount) {
                    $couponId = $coupon->id;
                    $discountAmount = $coupon->type === 'percentage' ? ($subtotal * $coupon->value / 100) : min($coupon->value, $subtotal);
                }
            }

            // إنشاء الحجز
            $booking = Booking::create([
                'booking_number' => Booking::generateBookingNumber(),
                'customer_id' => $customerId,
                'product_id' => (int) $request->get('product_id'),
                'service_provider_id' => $providerId,
                'booking_date' => $date,
                'booking_time' => $time,
                'quantity' => $quantity,
                'selected_add_ons' => $selectedAddOns,
                'status' => 'pending',
                'booking_source' => 'website',
                'total_price' => $subtotal - $discountAmount,
                'coupon_id' => $couponId,
                'discount_amount' => $discountAmount,
                'customer_latitude' => $request->input('location.latitude'),
                'customer_longitude' => $request->input('location.longitude'),
                'customer_address' => $request->input('location.address'),
                'is_riyadh_confirmed' => $request->has('location.latitude') && $request->has('location.longitude') ? true : false,
                'notes' => $request->input('notes'),
                'customer_notes' => 'حجز من تطبيق الموبايل'
            ]);

            // زيادة عداد استخدام الكوبون إن وُجد
            if (isset($coupon) && $couponId) {
                $coupon->incrementUsage();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الحجز بنجاح',
                'data' => $booking
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الحجز',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض تفاصيل حجز
     */
    public function show($id)
    {
        try {
            $booking = Booking::with(['customer:id,name,phone', 'product:id,name,price', 'serviceProvider:id,name'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب بيانات الحجز بنجاح',
                'data' => $booking
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'الحجز غير موجود',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * إلغاء الحجز (قبل 24 ساعة على الأقل)
     */
    public function cancel($id)
    {
        try {
            $booking = Booking::findOrFail($id);

            // منع الإلغاء قبل أقل من 24 ساعة
            $bookingDateTime = Carbon::parse($booking->booking_date . ' ' . $booking->booking_time);
            if (now()->diffInHours($bookingDateTime, false) < 24) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن إلغاء الحجز قبل أقل من 24 ساعة من الموعد'
                ], 422);
            }

            $booking->cancel();

            return response()->json([
                'success' => true,
                'message' => 'تم إلغاء الحجز بنجاح',
                'data' => $booking
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إلغاء الحجز',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إرجاع الأوقات المتاحة فعلياً بعد طرح المحجوز
     */
    public function availableTimeSlots(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'service_provider_id' => 'required|integer|exists:service_providers,id,tenant_id,' . tenant_id(),
                'date' => 'required|date|after_or_equal:today'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $date = $request->get('date');
            $providerId = (int) $request->get('service_provider_id');

            $slots = Booking::getAvailableTimeSlotsWithPeriods($date, $providerId);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب الأوقات المتاحة بنجاح',
                'data' => $slots
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الأوقات المتاحة',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}


