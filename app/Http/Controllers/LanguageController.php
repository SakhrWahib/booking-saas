<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class LanguageController extends Controller
{
    /**
     * تغيير لغة التطبيق
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLang($locale)
    {
        // التحقق من أن اللغة المطلوبة مدعومة
        if (in_array($locale, Config::get('app.supported_locales'))) {
            Session::put('locale', $locale);
        }
        
        // العودة إلى الصفحة السابقة
        return redirect()->back();
    }

    /**
     * عرض صفحة إدارة اللغات
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return redirect()->route('admin.languages.index');
    }
}         