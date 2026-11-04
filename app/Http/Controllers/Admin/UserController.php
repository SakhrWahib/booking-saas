<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('users.tenant_id', tenant_id())->with('role')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'role_id.required' => 'الدور مطلوب',
            'role_id.exists' => 'الدور غير موجود'
        ]);

        if ($validator->fails()) {
            $roles = Role::all();
            return view('admin.users.create', compact('roles'))
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'is_active' => $request->boolean('is_active', true)
            ];
            
            \Log::info('Creating user with data:', $userData);
            
            $user = User::create($userData);
            
            \Log::info('User created successfully:', ['id' => $user->id]);

            return redirect()->route('admin.users.index')
                ->with('success', 'تم إنشاء المستخدم بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error creating user:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $roles = Role::all();
            return view('admin.users.create', compact('roles'))
                ->with('error', 'حدث خطأ أثناء إنشاء المستخدم: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::where('users.tenant_id', tenant_id())->with('role')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::where('users.tenant_id', tenant_id())->findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::where('users.tenant_id', tenant_id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'role_id.required' => 'الدور مطلوب',
            'role_id.exists' => 'الدور غير موجود'
        ]);

        if ($validator->fails()) {
            $roles = Role::all();
            return view('admin.users.edit', compact('user', 'roles'))
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'is_active' => $request->boolean('is_active', true)
            ];

            // تحديث كلمة المرور فقط إذا تم إدخالها
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('admin.users.index')
                ->with('success', 'تم تحديث المستخدم بنجاح');
        } catch (\Exception $e) {
            $roles = Role::all();
            return view('admin.users.edit', compact('user', 'roles'))
                ->with('error', 'حدث خطأ أثناء تحديث المستخدم')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::where('users.tenant_id', tenant_id())->findOrFail($id);
            
            // منع حذف المستخدم إذا كان آخر مدير
            if ($user->hasRole('admin') && User::where('users.tenant_id', tenant_id())->whereHas('role', function($query) {
                $query->where('name', 'admin');
            })->count() <= 1) {
                return redirect()->back()
                    ->with('error', 'لا يمكن حذف آخر مدير في النظام');
            }

            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'تم حذف المستخدم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف المستخدم');
        }
    }

    /**
     * تغيير حالة المستخدم (نشط/غير نشط)
     */
    public function toggleStatus(User $user)
    {
        try {
            $user->update(['is_active' => !$user->is_active]);

            $status = $user->is_active ? 'نشط' : 'غير نشط';
            return redirect()->back()
                ->with('success', "تم تغيير حالة المستخدم إلى {$status}");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تغيير حالة المستخدم');
        }
    }

    /**
     * البحث في المستخدمين
     */
    public function search(Request $request)
    {
        $query = User::where('users.tenant_id', tenant_id())->with('role');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * إعادة تعيين كلمة المرور للمستخدم
     */
    public function resetPassword(User $user)
    {
        try {
            // توليد كلمة مرور عشوائية
            $newPassword = Str::random(12);
            
            // تحديث كلمة المرور
            $user->update([
                'password' => Hash::make($newPassword)
            ]);

            // إرسال كلمة المرور الجديدة عبر البريد الإلكتروني
            // يمكن إضافة إرسال البريد الإلكتروني هنا
            
            return redirect()->back()
                ->with('success', "تم إعادة تعيين كلمة المرور للمستخدم {$user->name} بنجاح");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إعادة تعيين كلمة المرور');
        }
    }
}
