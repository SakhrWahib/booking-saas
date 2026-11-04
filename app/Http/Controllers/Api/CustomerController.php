<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * عرض قائمة العملاء مع إمكانية البحث والتصفية
     */
    public function index(Request $request)
    {
        try {
            $query = Customer::query();

            // البحث بالاسم
            if ($request->has('search') && $request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // البحث برقم الهاتف
            if ($request->has('phone') && $request->phone) {
                $query->where('phone', 'like', '%' . $request->phone . '%');
            }

            // تصفية حسب نوع العميل
            if ($request->has('customer_type') && $request->customer_type) {
                $query->where('customer_type', $request->customer_type);
            }

            // ترتيب النتائج
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // الصفحات
            $perPage = $request->get('per_page', 15);
            $customers = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب قائمة العملاء بنجاح',
                'data' => $customers
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب قائمة العملاء',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * عرض عميل محدد
     */
    public function show($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب بيانات العميل بنجاح',
                'data' => $customer
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'العميل غير موجود',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * إنشاء عميل جديد
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20|unique:customers,phone',
                'email' => 'nullable|email|max:255',
                'customer_type' => 'nullable|in:individual',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'address' => 'nullable|string|max:500',
            ], [
                'name.required' => 'اسم العميل مطلوب',
                'phone.required' => 'رقم الهاتف مطلوب',
                'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
                'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
                'latitude.numeric' => 'خط العرض يجب أن يكون رقماً',
                'longitude.numeric' => 'خط الطول يجب أن يكون رقماً',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $customer = Customer::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'customer_type' => $request->customer_type ?? 'individual',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $request->address,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء العميل بنجاح',
                'data' => $customer
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء العميل',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث بيانات العميل
     */
    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'phone' => 'sometimes|required|string|max:20|unique:customers,phone,' . $id,
                'email' => 'nullable|email|max:255',
                'customer_type' => 'nullable|in:individual',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'address' => 'nullable|string|max:500',
            ], [
                'name.required' => 'اسم العميل مطلوب',
                'phone.required' => 'رقم الهاتف مطلوب',
                'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
                'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
                'latitude.numeric' => 'خط العرض يجب أن يكون رقماً',
                'longitude.numeric' => 'خط الطول يجب أن يكون رقماً',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $customer->update($request->only([
                'name', 'phone', 'email', 'customer_type', 
                'latitude', 'longitude', 'address'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث بيانات العميل بنجاح',
                'data' => $customer
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث بيانات العميل',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف العميل
     */
    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف العميل بنجاح'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف العميل',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إرسال رمز التحقق OTP
     */
    public function sendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string|max:20',
            ], [
                'phone.required' => 'رقم الهاتف مطلوب',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $customer = Customer::where('phone', $request->phone)->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'رقم الهاتف غير مسجل'
                ], 404);
            }

            // إنشاء رمز OTP عشوائي من 4 أرقام
            $otp = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            
            // تحديث بيانات OTP
            $customer->update([
                'otp_code' => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(5), // صالح لمدة 5 دقائق
                'otp_attempts' => 0
            ]);

            // هنا يمكن إضافة كود إرسال SMS
            // sendSms($customer->phone, "رمز التحقق الخاص بك هو: " . $otp);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رمز التحقق بنجاح',
                'data' => [
                    'phone' => $customer->phone,
                    'expires_at' => $customer->otp_expires_at
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إرسال رمز التحقق',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * التحقق من رمز OTP
     */
    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string|max:20',
                'otp_code' => 'required|string|size:4',
            ], [
                'phone.required' => 'رقم الهاتف مطلوب',
                'otp_code.required' => 'رمز التحقق مطلوب',
                'otp_code.size' => 'رمز التحقق يجب أن يكون 4 أرقام',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $customer = Customer::where('phone', $request->phone)->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'رقم الهاتف غير مسجل'
                ], 404);
            }

            // التحقق من انتهاء صلاحية OTP
            if ($customer->otp_expires_at && Carbon::now()->isAfter($customer->otp_expires_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'رمز التحقق منتهي الصلاحية'
                ], 400);
            }

            // التحقق من عدد المحاولات
            if ($customer->otp_attempts >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'تم تجاوز الحد الأقصى للمحاولات، يرجى طلب رمز جديد'
                ], 400);
            }

            // التحقق من صحة الرمز
            if ($customer->otp_code !== $request->otp_code) {
                $customer->increment('otp_attempts');
                
                return response()->json([
                    'success' => false,
                    'message' => 'رمز التحقق غير صحيح',
                    'attempts_remaining' => 3 - $customer->otp_attempts
                ], 400);
            }

            // إعادة تعيين محاولات OTP عند النجاح
            $customer->update([
                'otp_attempts' => 0,
                'otp_code' => null,
                'otp_expires_at' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم التحقق من الرمز بنجاح',
                'data' => [
                    'customer' => $customer,
                    'verified_at' => Carbon::now()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحقق من الرمز',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * البحث عن العملاء حسب الموقع
     */
    public function searchByLocation(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'radius' => 'nullable|numeric|min:0.1|max:100', // بالكيلومترات
            ], [
                'latitude.required' => 'خط العرض مطلوب',
                'longitude.required' => 'خط الطول مطلوب',
                'latitude.numeric' => 'خط العرض يجب أن يكون رقماً',
                'longitude.numeric' => 'خط الطول يجب أن يكون رقماً',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            $radius = $request->get('radius', 10); // افتراضي 10 كم

            // حساب المسافة باستخدام صيغة Haversine
            $customers = Customer::selectRaw("
                    *,
                    (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(?)) + 
                    sin(radians(?)) * sin(radians(latitude)))) AS distance
                ", [$request->latitude, $request->longitude, $request->latitude])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->having('distance', '<=', $radius)
                ->orderBy('distance')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'تم البحث بنجاح',
                'data' => [
                    'customers' => $customers,
                    'search_center' => [
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude
                    ],
                    'radius_km' => $radius,
                    'total_found' => $customers->count()
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
     * تسجيل دخول العميل عبر الجوال
     */
    public function recordMobileLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string|max:20',
                'device_type' => 'nullable|string|in:android,ios,web',
                'device_model' => 'nullable|string|max:255',
                'os_version' => 'nullable|string|max:100',
                'app_version' => 'nullable|string|max:50',
            ], [
                'phone.required' => 'رقم الجوال مطلوب',
                'device_type.in' => 'نوع الجهاز يجب أن يكون android أو ios أو web',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            // البحث عن العميل برقم الجوال
            $customer = Customer::where('phone', $request->phone)->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'العميل غير موجود'
                ], 404);
            }

            // تسجيل الدخول
            $customer->recordMobileLogin($request->device_type);

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                'data' => [
                    'customer' => $customer,
                    'login_time' => $customer->last_mobile_login,
                    'device_type' => $customer->device_type
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تسجيل الدخول',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إنشاء عميل جديد مع تسجيل دخول عبر الجوال
     */
    public function storeWithMobileLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20|unique:customers,phone',
                'email' => 'nullable|email|max:255',
                'customer_type' => 'required|in:individual',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'address' => 'nullable|string|max:500',
                'device_type' => 'required|string|in:android,ios,web',
                'device_model' => 'nullable|string|max:255',
                'os_version' => 'nullable|string|max:100',
                'app_version' => 'nullable|string|max:50',
            ], [
                'name.required' => 'اسم العميل مطلوب',
                'phone.required' => 'رقم الجوال مطلوب',
                'phone.unique' => 'رقم الجوال مستخدم بالفعل',
                'email.email' => 'البريد الإلكتروني غير صحيح',
                'customer_type.in' => 'نوع العميل غير صحيح',
                'device_type.required' => 'نوع الجهاز مطلوب',
                'device_type.in' => 'نوع الجهاز يجب أن يكون android أو ios أو web',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'بيانات غير صحيحة',
                    'errors' => $validator->errors()
                ], 422);
            }

            // إنشاء العميل
            $customer = Customer::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'customer_type' => $request->customer_type,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $request->address,
            ]);

            // تسجيل الدخول مباشرة
            $customer->recordMobileLogin($request->device_type);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء العميل وتسجيل الدخول بنجاح',
                'data' => [
                    'customer' => $customer,
                    'login_time' => $customer->last_mobile_login,
                    'device_type' => $customer->device_type
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء العميل',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

