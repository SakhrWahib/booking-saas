<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Booking;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول
     */
    public function login()
    {
        return view('frontend.customer.login');
    }

    /**
     * معالجة تسجيل الدخول - المرحلة الأولى (إرسال OTP)
     */
    public function doLogin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20'
        ]);

        // تنظيف رقم الجوال
        $phone = preg_replace('/[^\d]/', '', $request->phone);
        
        // التحقق من صحة رقم الجوال السعودي
        if (!preg_match('/^(05\d{8}|5\d{8})$/', $phone)) {
            return back()->withErrors(['phone' => 'يجب أن يبدأ رقم الجوال بـ 05 أو 5']);
        }

        // البحث عن العميل أو إنشاؤه
        $customer = Customer::firstOrCreate(
            ['phone' => $phone],
            [
                'name' => $request->name,
                'customer_type' => 'individual'
            ]
        );

        // تحديث الاسم إذا كان مختلفاً
        if ($customer->name !== $request->name) {
            $customer->update(['name' => $request->name]);
        }

        // إنشاء وإرسال رمز OTP
        $otpCode = SmsService::generateOtp();
        $expiresAt = now()->addSeconds(90); // دقيقة ونصف

        // حفظ رمز OTP في قاعدة البيانات
        $customer->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => $expiresAt,
            'otp_attempts' => 0
        ]);

        // إرسال OTP عبر SMS
        $smsService = new SmsService();
        $smsResult = $smsService->sendOtp($phone, $otpCode);

        if (!$smsResult['success']) {
            return back()->withErrors(['phone' => 'فشل في إرسال رمز التحقق، يرجى المحاولة مرة أخرى']);
        }

        // حفظ بيانات العميل في الجلسة مؤقتاً
        session([
            'temp_customer_id' => $customer->id,
            'temp_customer_name' => $customer->name,
            'temp_customer_phone' => $customer->phone
        ]);

        return redirect()->route('frontend.customer.verify-otp')
                        ->with('success', 'تم إرسال رمز التحقق إلى رقمك');
    }

    /**
     * عرض صفحة إدخال رمز OTP
     */
    public function verifyOtp()
    {
        if (!session('temp_customer_id')) {
            return redirect()->route('frontend.customer.login')
                           ->with('error', 'يرجى إدخال بياناتك أولاً');
        }

        return view('frontend.customer.verify-otp');
    }

    /**
     * التحقق من رمز OTP
     */
    public function confirmOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:4'
        ]);

        $customerId = session('temp_customer_id');
        if (!$customerId) {
            return redirect()->route('frontend.customer.login')
                           ->with('error', 'انتهت صلاحية الجلسة، يرجى المحاولة مرة أخرى');
        }

        $customer = Customer::find($customerId);
        if (!$customer) {
            return redirect()->route('frontend.customer.login')
                           ->with('error', 'حدث خطأ، يرجى المحاولة مرة أخرى');
        }

        // زيادة عدد المحاولات
        $customer->increment('otp_attempts');

        // التحقق من عدد المحاولات (الحد الأقصى 3 محاولات)
        if ($customer->otp_attempts > 3) {
            // مسح رمز OTP
            $customer->update([
                'otp_code' => null,
                'otp_expires_at' => null,
                'otp_attempts' => 0
            ]);

            session()->forget(['temp_customer_id', 'temp_customer_name', 'temp_customer_phone']);
            
            return redirect()->route('frontend.customer.login')
                           ->with('error', 'تم تجاوز الحد الأقصى للمحاولات، يرجى إعادة المحاولة');
        }

        // التحقق من صحة رمز OTP
        if (!SmsService::verifyOtp($request->otp_code, $customer->otp_code, $customer->otp_expires_at)) {
            $remainingAttempts = 3 - $customer->otp_attempts;
            return back()->withErrors(['otp_code' => "رمز التحقق غير صحيح. متبقي {$remainingAttempts} محاولات"]);
        }

        // رمز OTP صحيح - تسجيل الدخول
        $customer->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
            'last_mobile_login' => now()
        ]);

        // تحديث الموقع إذا كان موجوداً في الجلسة
        if (session('booking.latitude') && session('booking.longitude')) {
            $customer->update([
                'latitude' => session('booking.latitude'),
                'longitude' => session('booking.longitude'),
                'address' => session('booking.address', 'الموقع المحدد على الخريطة')
            ]);
        }

        // تسجيل الدخول
        session([
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone
        ]);

        // مسح البيانات المؤقتة
        session()->forget(['temp_customer_id', 'temp_customer_name', 'temp_customer_phone']);

        // إضافة رسالة تأكيد
        $message = 'تم تسجيل الدخول بنجاح!';
        if (session('booking.latitude') && session('booking.longitude')) {
            $message .= ' تم حفظ موقعك تلقائياً.';
        }

        return redirect()->route('frontend.customer.profile')->with('success', $message);
    }

    /**
     * إعادة إرسال رمز OTP
     */
    public function resendOtp(Request $request)
    {
        $customerId = session('temp_customer_id');
        if (!$customerId) {
            return response()->json([
                'success' => false,
                'message' => 'انتهت صلاحية الجلسة'
            ]);
        }

        $customer = Customer::find($customerId);
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ، يرجى المحاولة مرة أخرى'
            ]);
        }

        // إنشاء رمز OTP جديد
        $otpCode = SmsService::generateOtp();
        $expiresAt = now()->addSeconds(90); // دقيقة ونصف

        $customer->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => $expiresAt,
            'otp_attempts' => 0
        ]);

        // إرسال OTP عبر SMS
        $smsService = new SmsService();
        $smsResult = $smsService->sendOtp($customer->phone, $otpCode);

        if (!$smsResult['success']) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في إرسال رمز التحقق، يرجى المحاولة مرة أخرى'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رمز التحقق مرة أخرى'
        ]);
    }

    /**
     * عرض الملف الشخصي
     */
    public function profile()
    {
        if (!session('customer_id')) {
            return redirect()->route('frontend.customer.login');
        }

        $customer = Customer::find(session('customer_id'));
        $bookings = Booking::where('customer_id', $customer->id)
                          ->with('product')
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('frontend.customer.profile', compact('customer', 'bookings'));
    }

    /**
     * تسجيل الخروج
     */
    public function logout()
    {
        session()->forget(['customer_id', 'customer_name', 'customer_phone']);
        return redirect()->route('home')->with('success', 'تم تسجيل الخروج بنجاح!');
    }

    /**
     * تحديث بيانات العميل
     */
    public function updateProfile(Request $request)
    {
        if (!session('customer_id')) {
            return redirect()->route('frontend.customer.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20'
        ]);

        $customer = Customer::find(session('customer_id'));
        
        // تنظيف رقم الجوال
        $phone = preg_replace('/[^\d]/', '', $request->phone);
        
        // التحقق من صحة رقم الجوال السعودي
        if (!preg_match('/^(05\d{8}|5\d{8})$/', $phone)) {
            return back()->withErrors(['phone' => 'يجب أن يبدأ رقم الجوال بـ 05 أو 5']);
        }

        $updateData = [
            'name' => $request->name,
            'phone' => $phone
        ];
        
        // تحديث الموقع إذا كان موجوداً في الجلسة
        if (session('booking.latitude') && session('booking.longitude')) {
            $updateData['latitude'] = session('booking.latitude');
            $updateData['longitude'] = session('booking.longitude');
            $updateData['address'] = session('booking.address', 'الموقع المحدد على الخريطة');
        }
        
        $customer->update($updateData);

        // تحديث الجلسة
        session(['customer_name' => $request->name]);
        session(['customer_phone' => $phone]);

        // إضافة رسالة تأكيد إذا تم حفظ الموقع
        $message = 'تم تحديث البيانات بنجاح!';
        if (session('booking.latitude') && session('booking.longitude')) {
            $message .= ' تم حفظ موقعك تلقائياً.';
        }

        return back()->with('success', $message);
    }
} 