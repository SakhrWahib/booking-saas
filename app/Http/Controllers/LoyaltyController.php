<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Coupon;

class LoyaltyController extends Controller
{
    public function __construct()
    {
        // التأكد من أن المستخدم مدير فقط
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        // إحصائيات الولاء حسب عدد الحجوزات
        $loyaltyStats = $this->getLoyaltyStats();
        
        // العملاء حسب المستوى
        $customersByLevel = $this->getCustomersByLevel();
        
        // أفضل العملاء (أعلى 10)
        $topCustomers = $this->getTopCustomers();
        
        // إحصائيات النقاط
        $pointsStats = $this->getPointsStats();
        
        // الكوبونات المتاحة
        $availableCoupons = $this->getAvailableCoupons();
        
        return view('admin.loyalty.index', compact(
            'loyaltyStats',
            'customersByLevel', 
            'topCustomers',
            'pointsStats',
            'availableCoupons'
        ));
    }

    private function getLoyaltyStats()
    {
        // إجمالي العملاء
        $totalCustomers = Customer::count();
        
        // العملاء النشطون (لديهم حجوزات)
        $activeCustomers = Customer::whereHas('bookings')->count();
        
        // العملاء حسب المستوى
        $bronzeCustomers = Customer::whereHas('bookings', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->withCount(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->having('bookings_count', '>=', 1)
          ->having('bookings_count', '<=', 3)
          ->count();
        
        $silverCustomers = Customer::whereHas('bookings', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->withCount(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->having('bookings_count', '>=', 4)
          ->having('bookings_count', '<=', 8)
          ->count();
        
        $goldCustomers = Customer::whereHas('bookings', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->withCount(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->having('bookings_count', '>=', 9)
          ->having('bookings_count', '<=', 15)
          ->count();
        
        $platinumCustomers = Customer::whereHas('bookings', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->withCount(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->having('bookings_count', '>=', 16)
          ->having('bookings_count', '<=', 20)
          ->count();
        
        $diamondCustomers = Customer::whereHas('bookings', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->withCount(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->having('bookings_count', '>', 20)
          ->count();

        return [
            'total_customers' => $totalCustomers,
            'active_customers' => $activeCustomers,
            'bronze_customers' => $bronzeCustomers,
            'silver_customers' => $silverCustomers,
            'gold_customers' => $goldCustomers,
            'platinum_customers' => $platinumCustomers,
            'diamond_customers' => $diamondCustomers
        ];
    }

    private function getCustomersByLevel()
    {
        // برونزي (1-3 حجوزات)
        $bronze = Customer::whereHas('bookings', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->withCount(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->withSum(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }], 'total_price')
        ->having('bookings_count', '>=', 1)
        ->having('bookings_count', '<=', 3)
        ->orderBy('bookings_count', 'desc')
        ->limit(10)
        ->get();

        // فضي (4-8 حجوزات)
        $silver = Customer::whereHas('bookings', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->withCount(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->withSum(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }], 'total_price')
        ->having('bookings_count', '>=', 4)
        ->having('bookings_count', '<=', 8)
        ->orderBy('bookings_count', 'desc')
        ->limit(10)
        ->get();

        // ذهبي (9-15 حجوزات)
        $gold = Customer::whereHas('bookings', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->withCount(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->withSum(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }], 'total_price')
        ->having('bookings_count', '>=', 9)
        ->having('bookings_count', '<=', 15)
        ->orderBy('bookings_count', 'desc')
        ->limit(10)
        ->get();

        // بلاتيني (16-20 حجوزات)
        $platinum = Customer::whereHas('bookings', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->withCount(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->withSum(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }], 'total_price')
        ->having('bookings_count', '>=', 16)
        ->having('bookings_count', '<=', 20)
        ->orderBy('bookings_count', 'desc')
        ->limit(10)
        ->get();

        // ماسي (أكثر من 20 حجوزة)
        $diamond = Customer::whereHas('bookings', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->withCount(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->withSum(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }], 'total_price')
        ->having('bookings_count', '>', 20)
        ->orderBy('bookings_count', 'desc')
        ->limit(10)
        ->get();

        return [
            'bronze' => $bronze,
            'silver' => $silver,
            'gold' => $gold,
            'platinum' => $platinum,
            'diamond' => $diamond
        ];
    }

    private function getTopCustomers()
    {
        return Customer::whereHas('bookings', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->withCount(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->withSum(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }], 'total_price')
        ->orderBy('bookings_count', 'desc')
        ->limit(20)
        ->get()
        ->map(function($customer) {
            $customer->loyalty_level = $this->getLoyaltyLevel($customer->bookings_count);
            $customer->points = $this->calculatePoints($customer->bookings_count);
            return $customer;
        });
    }

    private function getPointsStats()
    {
        // إجمالي النقاط الموزعة
        $totalPoints = Customer::whereHas('bookings', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->withCount(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->get()
        ->sum(function($customer) {
            return $this->calculatePoints($customer->bookings_count);
        });

        // متوسط النقاط للعميل
        $avgPoints = Customer::whereHas('bookings', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->withCount(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->get()
        ->avg(function($customer) {
            return $this->calculatePoints($customer->bookings_count);
        });

        // العملاء المؤهلون للحصول على كوبونات
        $eligibleForCoupons = Customer::whereHas('bookings', function($query) {
            $query->where('status', '!=', 'cancelled');
        })->withCount(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->having('bookings_count', '>=', 5)
        ->count();

        return [
            'total_points' => $totalPoints,
            'average_points' => round($avgPoints, 2),
            'eligible_for_coupons' => $eligibleForCoupons
        ];
    }

    private function getAvailableCoupons()
    {
        return Coupon::where('is_active', true)
        ->where(function($query) {
            $query->whereNull('end_date')
                  ->orWhere('end_date', '>', now());
        })
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    }

    private function getLoyaltyLevel($bookingsCount)
    {
        if ($bookingsCount >= 1 && $bookingsCount <= 3) {
            return 'برونزي';
        } elseif ($bookingsCount >= 4 && $bookingsCount <= 8) {
            return 'فضي';
        } elseif ($bookingsCount >= 9 && $bookingsCount <= 15) {
            return 'ذهبي';
        } elseif ($bookingsCount >= 16 && $bookingsCount <= 20) {
            return 'بلاتيني';
        } elseif ($bookingsCount > 20) {
            return 'ماسي';
        } else {
            return 'جديد';
        }
    }

    private function calculatePoints($bookingsCount)
    {
        // نظام النقاط: كل حجز = 10 نقاط
        return $bookingsCount * 10;
    }

    // API للحصول على بيانات العميل
    public function getCustomerDetails($customerId)
    {
        $customer = Customer::with(['bookings' => function($query) {
            $query->where('status', '!=', 'cancelled')
                  ->orderBy('created_at', 'desc');
        }])->findOrFail($customerId);

        $customer->loyalty_level = $this->getLoyaltyLevel($customer->bookings->count());
        $customer->points = $this->calculatePoints($customer->bookings->count());
        $customer->total_spent = $customer->bookings->sum('total_price');
        $customer->last_booking = $customer->bookings->first();

        return response()->json($customer);
    }

    // إنشاء كوبون خصم للعميل
    public function createCustomerCoupon(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id,tenant_id,' . tenant_id(),
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'expiry_days' => 'required|integer|min:1|max:365'
        ]);

        $customer = Customer::findOrFail($request->customer_id);
        $bookingsCount = $customer->bookings()->where('status', '!=', 'cancelled')->count();
        
        // إنشاء كوبون مخصص للعميل
        $coupon = Coupon::create([
            'code' => 'LOYALTY_' . strtoupper(substr(md5(uniqid()), 0, 8)),
            'name' => 'كوبون ولاء للعميل: ' . $customer->name,
            'type' => $request->discount_type,
            'value' => $request->discount_value,
            'min_amount' => 0,
            'max_uses' => 1,
            'start_date' => now(),
            'end_date' => now()->addDays($request->expiry_days),
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء كوبون الخصم بنجاح',
            'coupon' => $coupon
        ]);
    }
}
