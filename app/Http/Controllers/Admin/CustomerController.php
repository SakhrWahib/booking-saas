<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * عرض قائمة العملاء
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // البحث بالاسم أو رقم الجوال
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // فلترة حسب نوع العميل
        if ($request->filled('customer_type')) {
            $query->where('customer_type', $request->customer_type);
        }

        // ترتيب النتائج
        $customers = $query->withCount(['bookings', 'activeBookings'])
                          ->latest()
                          ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * عرض صفحة إضافة عميل جديد
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * حفظ العميل الجديد
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone,NULL,id,tenant_id,' . tenant_id(),
            'email' => 'nullable|email|max:255',
            'customer_type' => 'required|in:individual',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ], [
            'name.required' => 'اسم العميل مطلوب',
            'name.max' => 'اسم العميل يجب ألا يزيد عن 255 حرف',
            'phone.required' => 'رقم الجوال مطلوب',
            'phone.unique' => 'رقم الجوال مستخدم من قبل',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'customer_type.required' => 'نوع العميل مطلوب',
            'customer_type.in' => 'نوع العميل غير صحيح',
            'latitude.numeric' => 'خط العرض يجب أن يكون رقم',
            'latitude.between' => 'خط العرض يجب أن يكون بين -90 و 90',
            'longitude.numeric' => 'خط الطول يجب أن يكون رقم',
            'longitude.between' => 'خط الطول يجب أن يكون بين -180 و 180',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        Customer::create($request->all());

        return redirect()->route('admin.customers.index')
                        ->with('success', 'تم إضافة العميل بنجاح');
    }

    /**
     * عرض تفاصيل العميل
     */
    public function show(Customer $customer)
    {
        $customer->load(['bookings' => function($query) {
            $query->with(['product', 'serviceProvider'])
                  ->latest()
                  ->take(10);
        }, 'invoices' => function($query) {
            $query->latest()->take(5);
        }]);

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * عرض صفحة تعديل العميل
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * تحديث بيانات العميل
     */
    public function update(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone,' . $customer->id . ',id,tenant_id,' . tenant_id(),
            'email' => 'nullable|email|max:255',
            'customer_type' => 'required|in:individual',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ], [
            'name.required' => 'اسم العميل مطلوب',
            'name.max' => 'اسم العميل يجب ألا يزيد عن 255 حرف',
            'phone.required' => 'رقم الجوال مطلوب',
            'phone.unique' => 'رقم الجوال مستخدم من قبل',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'customer_type.required' => 'نوع العميل مطلوب',
            'customer_type.in' => 'نوع العميل غير صحيح',
            'latitude.numeric' => 'خط العرض يجب أن يكون رقم',
            'latitude.between' => 'خط العرض يجب أن يكون بين -90 و 90',
            'longitude.numeric' => 'خط الطول يجب أن يكون رقم',
            'longitude.between' => 'خط الطول يجب أن يكون بين -180 و 180',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $customer->update($request->all());

        return redirect()->route('admin.customers.index')
                        ->with('success', 'تم تحديث بيانات العميل بنجاح');
    }

    /**
     * حذف العميل
     */
    public function destroy(Customer $customer)
    {
        // التحقق من وجود حجوزات للعميل
        if ($customer->bookings()->count() > 0) {
            return redirect()->back()
                           ->with('error', 'لا يمكن حذف العميل لوجود حجوزات مرتبطة به');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
                        ->with('success', 'تم حذف العميل بنجاح');
    }

    /**
     * البحث في العملاء (Ajax)
     */
    public function search(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('term')) {
            $term = $request->term;
            $query->where(function($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%");
            });
        }

        $customers = $query->select('id', 'name', 'phone')
                          ->limit(10)
                          ->get();

        return response()->json($customers);
    }

    /**
     * توليد رمز OTP للعميل
     */
    public function generateOtp(Customer $customer)
    {
        $otp = $customer->generateOtp();
        
        // هنا يمكن إضافة إرسال الرسالة
        // SMS::send($customer->phone, "رمز التحقق: {$otp}");
        
        return redirect()->back()
                        ->with('success', "تم توليد رمز التحقق: {$otp}");
    }

    /**
     * التحقق من رمز OTP
     */
    public function verifyOtp(Request $request, Customer $customer)
    {
        $request->validate([
            'otp' => 'required|digits:4'
        ], [
            'otp.required' => 'رمز التحقق مطلوب',
            'otp.digits' => 'رمز التحقق يجب أن يكون 4 أرقام'
        ]);

        if ($customer->verifyOtp($request->otp)) {
            $customer->clearOtp();
            return redirect()->back()
                           ->with('success', 'تم التحقق بنجاح');
        }

        return redirect()->back()
                        ->with('error', 'رمز التحقق غير صحيح');
    }

    /**
     * تصدير العملاء
     */
    public function export(Request $request)
    {
        $query = Customer::query();

        // تطبيق نفس الفلاتر
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('customer_type')) {
            $query->where('customer_type', $request->customer_type);
        }

        $customers = $query->withCount('bookings')->get();

        // إنشاء ملف CSV
        $filename = 'customers_' . date('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // إضافة BOM للدعم العربي
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // العناوين
            fputcsv($file, ['الرقم', 'الاسم', 'رقم الجوال', 'البريد الإلكتروني', 'نوع العميل', 'العنوان', 'عدد الحجوزات', 'تاريخ الإنشاء']);
            
            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->phone,
                    $customer->email,
                    $customer->customer_type == 'individual' ? 'أفراد' : $customer->customer_type,
                    $customer->address,
                    $customer->bookings_count,
                    $customer->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 