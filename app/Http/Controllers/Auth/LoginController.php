<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            
            // التحقق من أن المستخدم مفعل ولديه دور صحيح
            if (!$user->is_active || !$user->role) {
                Auth::logout();
                return back()->with('error', 'غير مصرح لك بالدخول إلى لوحة التحكم');
            }

            // التحقق من أن المستخدم لديه دور من الأدوار المصرح لها
            $allowedRoles = ['admin', 'office', 'operator'];
            if (!in_array($user->role->name, $allowedRoles)) {
                Auth::logout();
                return back()->with('error', 'غير مصرح لك بالدخول إلى لوحة التحكم');
            }

            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->with('error', 'البريد الإلكتروني أو كلمة المرور غير صحيحة');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
} 