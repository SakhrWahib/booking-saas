<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // تحقق من وجود اللغة في الجلسة
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            
            // تحقق من أن اللغة مدعومة
            if (in_array($locale, config('app.supported_locales'))) {
                App::setLocale($locale);
            }
        } else {
            // استخدام اللغة الافتراضية
            $locale = config('app.locale');
            Session::put('locale', $locale);
            App::setLocale($locale);
        }

        return $next($request);
    }
} 