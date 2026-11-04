<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        // التحقق من تسجيل الدخول
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // التحقق من حالة المستخدم
        if (!$user->isActive()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'تم إيقاف حسابك. يرجى التواصل مع الإدارة.');
        }

        // إذا لم يتم تمرير صلاحيات، السماح بالمرور
        if (empty($permissions)) {
            return $next($request);
        }

        // التحقق من الصلاحيات
        if (!$user->hasAnyPermission($permissions)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'ليس لديك صلاحية للوصول لهذا المورد',
                    'message' => 'غير مصرح لك بالوصول لهذه الصفحة'
                ], 403);
            }

            // إرجاع صفحة خطأ بدلاً من التوجيه
            return response()->view('errors.permission', [
                'message' => 'ليس لديك صلاحية للوصول لهذه الصفحة',
                'permissions' => $permissions
            ], 403);
        }

        return $next($request);
    }
} 