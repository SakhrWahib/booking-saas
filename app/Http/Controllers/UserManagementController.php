<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        // إحصائيات المستخدمين
        $totalUsers = User::where('users.tenant_id', tenant_id())->count();
        $activeUsers = User::where('users.tenant_id', tenant_id())->where('is_active', true)->count();
        $newUsers = User::where('users.tenant_id', tenant_id())->where('created_at', '>=', now()->subWeek())->count();
        $pendingVerifications = User::where('users.tenant_id', tenant_id())->where('verification_status', 'pending')->count();

        // بناء الاستعلام الأساسي
        $query = User::where('users.tenant_id', tenant_id())->with(['role']);

        // تطبيق الفلاتر
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('role')) {
            $query->whereHas('role', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->where('created_at', '>=', now()->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->subMonth());
                    break;
            }
        }

        if ($request->filled('user_type')) {
            switch($request->user_type) {
                case 'verified':
                    $query->whereNotNull('email_verified_at');
                    break;
                case 'unverified':
                    $query->whereNull('email_verified_at');
                    break;
            }
        }

        if ($request->filled('last_login')) {
            switch($request->last_login) {
                case 'week':
                    $query->where('last_login_at', '>=', Carbon::now()->subWeek());
                    break;
                case 'month':
                    $query->where('last_login_at', '>=', Carbon::now()->subMonth());
                    break;
                case 'inactive':
                    $query->where('last_login_at', '<', Carbon::now()->subMonth());
                    break;
            }
        }

        // ترتيب النتائج
        $query->orderBy('created_at', 'desc');

        // تقسيم النتائج إلى صفحات
        $users = $query->paginate(10)->appends($request->except('page'));

        // جلب الأدوار للفلتر
        $roles = Role::all();

        if ($request->ajax()) {
            return response()->json([
                'users' => $users,
                'pagination' => view('pagination.custom', ['paginator' => $users])->render()
            ]);
        }

        return view('admin.users.manage', compact(
            'users',
            'roles',
            'totalUsers',
            'activeUsers',
            'newUsers',
            'pendingVerifications'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'verification_status' => ['required', Rule::in(['pending', 'verified', 'rejected'])],
            'is_active' => 'required|boolean'
        ]);

        DB::beginTransaction();
        try {
            // معالجة الصورة إذا تم رفعها
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = time() . '_' . $file->getClientOriginalName();
                // تأكد من وجود المجلد
                if (!file_exists(storage_path('app/public/avatars'))) {
                    mkdir(storage_path('app/public/avatars'), 0777, true);
                }
                // حفظ الصورة
                $file->storeAs('public/avatars', $filename);
                $validated['avatar'] = 'avatars/' . $filename;
            }

            // تشفير كلمة المرور
            $validated['password'] = Hash::make($validated['password']);
            
            // إنشاء المستخدم
            $user = User::create($validated);

            DB::commit();
            return redirect()->route('users.manage')
                ->with('success', __('web.messages.success'));
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', __('web.messages.error'));
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
                'password' => 'nullable|string|min:6',
                'role_id' => 'required|exists:roles,id',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'is_active' => 'boolean',
                'verification_status' => ['required', Rule::in(['pending', 'verified', 'rejected'])],
            ]);

            DB::beginTransaction();

            // معالجة الصورة إذا تم رفعها
            if ($request->hasFile('avatar')) {
                // حذف الصورة القديمة
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                
                $file = $request->file('avatar');
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // تأكد من وجود المجلد
                if (!file_exists(storage_path('app/public/avatars'))) {
                    mkdir(storage_path('app/public/avatars'), 0777, true);
                }
                
                // حفظ الصورة
                $file->storeAs('public/avatars', $filename);
                $validated['avatar'] = 'avatars/' . $filename;
            }

            // تحديث كلمة المرور فقط إذا تم تقديمها
            if (isset($validated['password']) && !empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            // تحديث بيانات المستخدم
            $user->update($validated);

            DB::commit();

            // التحقق من نوع الطلب وإرجاع الاستجابة المناسبة
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث بيانات المستخدم بنجاح',
                    'user' => $user->load('role')
                ]);
            }

            return redirect()->route('users.manage')
                ->with('success', 'تم تحديث بيانات المستخدم بنجاح');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطأ في البيانات المدخلة',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollback();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث البيانات'
                ], 500);
            }
            return back()->with('error', 'حدث خطأ أثناء تحديث البيانات');
        }
    }

    public function destroy(User $user)
    {
        try {
            // حذف الصورة إذا كانت موجودة
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->delete();
            return redirect()->route('users.manage')
                ->with('success', 'تم حذف المستخدم بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف المستخدم');
        }
    }

    public function toggleStatus(User $user)
    {
        try {
            $user->update(['is_active' => !$user->is_active]);
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة المستخدم بنجاح',
                'is_active' => $user->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة المستخدم'
            ], 500);
        }
    }

    public function handleUpgradeRequest(User $user, Request $request)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'role_id' => 'required_if:status,approved|exists:roles,id'
        ]);

        try {
            if ($validated['status'] === 'approved') {
                $user->update([
                    'role_id' => $validated['role_id'],
                    'verification_status' => 'verified',
                    'request_upgrade' => 'none'
                ]);
                $message = 'تم ترقية المستخدم بنجاح';
            } else {
                $user->update([
                    'verification_status' => 'rejected',
                    'request_upgrade' => 'none'
                ]);
                $message = 'تم رفض طلب الترقية';
            }

            return redirect()->route('users.manage')
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء معالجة طلب الترقية');
        }
    }

    /**
     * عرض بيانات مستخدم محدد
     */
    public function show(User $user)
    {
        try {
            // تحميل علاقة الدور مع المستخدم
            $user->load('role');
            
            // إرجاع البيانات كـ JSON
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role_id' => $user->role_id,
                'verification_status' => $user->verification_status,
                'is_active' => $user->is_active,
                'avatar' => $user->avatar,
                'role' => [
                    'id' => $user->role->id,
                    'name' => $user->role->name
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'فشل في جلب بيانات المستخدم'
            ], 500);
        }
    }

    /**
     * تحديث حالة التحقق للمستخدم
     */
    public function updateVerificationStatus(User $user, Request $request)
    {
        try {
            $validated = $request->validate([
                'verification_status' => ['required', Rule::in(['pending', 'verified', 'rejected'])]
            ]);

            DB::beginTransaction();

            // تحديث حالة التحقق
            $user->verification_status = $validated['verification_status'];
            $user->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة التحقق بنجاح',
                'verification_status' => $user->verification_status
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة التحقق'
            ], 500);
        }
    }
}
