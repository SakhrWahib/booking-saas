<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * عرض قائمة اللغات والترجمات
     */
    public function index()
    {
        $languages = Language::withCount('translations')->get();
        $totalTranslations = Translation::count();
        $defaultLanguage = Language::where('is_default', true)->first();
        $activeLanguages = Language::where('is_active', true)->count();

        return view('admin.languages.index', compact(
            'languages',
            'totalTranslations',
            'defaultLanguage',
            'activeLanguages'
        ));
    }

    /**
     * إضافة لغة جديدة
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:5|unique:languages,code',
                'name' => 'required|string|max:50',
                'native_name' => 'required|string|max:50',
                'flag' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
                'is_active' => 'boolean',
                'is_default' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // إذا كانت اللغة الجديدة افتراضية، قم بإلغاء تعيين اللغة الافتراضية السابقة
            if ($request->is_default) {
                Language::where('is_default', true)->update(['is_default' => false]);
            }

            // معالجة الصورة إذا تم رفعها
            $flagPath = null;
            if ($request->hasFile('flag')) {
                $flag = $request->file('flag');
                $flagName = time() . '_' . $flag->getClientOriginalName();
                $flag->storeAs('public/flags', $flagName);
                $flagPath = 'flags/' . $flagName;
            }

            // إنشاء اللغة
            $language = Language::create([
                'code' => $request->code,
                'name' => $request->name,
                'native_name' => $request->native_name,
                'flag' => $flagPath,
                'is_active' => $request->is_active ?? true,
                'is_default' => $request->is_default ?? false
            ]);

            // استيراد الترجمات من ملفات اللغة
            if ($language) {
                $groups = ['web', 'auth', 'validation', 'dashboard'];
                
                foreach ($groups as $group) {
                    $filePath = resource_path("lang/{$language->code}/{$group}.php");
                    $defaultFilePath = resource_path("lang/ar/{$group}.php");
                    
                    $translations = [];
                    
                    if (File::exists($filePath)) {
                        $translations = require $filePath;
                    } elseif (File::exists($defaultFilePath)) {
                        $translations = require $defaultFilePath;
                    }
                    
                    if (!empty($translations)) {
                        $this->storeTranslations($language->id, $group, $translations);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة اللغة بنجاح',
                'language' => $language
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error in storing language: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة اللغة'
            ], 500);
        }
    }

    /**
     * تخزين الترجمات في قاعدة البيانات
     */
    private function storeTranslations($languageId, $group, $translations, $prefix = '')
    {
        foreach ($translations as $key => $value) {
            $fullKey = $prefix ? "{$prefix}.{$key}" : $key;
            
            if (is_array($value)) {
                $this->storeTranslations($languageId, $group, $value, $fullKey);
            } else {
                Translation::updateOrCreate(
                    [
                        'language_id' => $languageId,
                        'group' => $group,
                        'key' => $fullKey
                    ],
                    ['value' => $value]
                );
            }
        }
    }

    /**
     * تحديث بيانات اللغة
     */
    public function update(Request $request, Language $language)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => ['required', 'string', 'max:5', 'unique:languages,code,' . $language->id],
                'name' => 'required|string|max:50',
                'native_name' => 'required|string|max:50',
                'flag' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
                'is_active' => 'boolean',
                'is_default' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // إذا كانت اللغة الجديدة افتراضية، قم بإلغاء تعيين اللغة الافتراضية السابقة
            if ($request->is_default && !$language->is_default) {
                Language::where('is_default', true)->update(['is_default' => false]);
            }

            // معالجة الصورة إذا تم رفعها
            if ($request->hasFile('flag')) {
                // حذف العلم القديم إذا وجد
                if ($language->flag) {
                    File::delete(storage_path('app/public/' . $language->flag));
                }

                $flag = $request->file('flag');
                $flagName = time() . '_' . $flag->getClientOriginalName();
                $flag->storeAs('public/flags', $flagName);
                $language->flag = 'flags/' . $flagName;
            }

            // تحديث بيانات اللغة
            $language->update([
                'code' => $request->code,
                'name' => $request->name,
                'native_name' => $request->native_name,
                'is_active' => $request->is_active ?? $language->is_active,
                'is_default' => $request->is_default ?? $language->is_default
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث اللغة بنجاح',
                'language' => $language
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث اللغة'
            ], 500);
        }
    }

    /**
     * حذف لغة
     */
    public function destroy(Language $language)
    {
        try {
            if ($language->is_default) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف اللغة الافتراضية'
                ], 422);
            }

            DB::beginTransaction();

            // حذف العلم إذا وجد
            if ($language->flag) {
                File::delete(storage_path('app/public/' . $language->flag));
            }

            // حذف اللغة (سيتم حذف الترجمات تلقائياً بسبب onDelete('cascade'))
            $language->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف اللغة بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف اللغة'
            ], 500);
        }
    }

    /**
     * تحديث حالة اللغة (تفعيل/تعطيل)
     */
    public function toggleStatus(Language $language)
    {
        try {
            if ($language->is_default && $language->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن تعطيل اللغة الافتراضية'
                ], 422);
            }

            $language->update(['is_active' => !$language->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة اللغة بنجاح',
                'is_active' => $language->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة اللغة'
            ], 500);
        }
    }

    /**
     * تحديث الترجمات
     */
    public function updateTranslations(Request $request, Language $language)
    {
        try {
            $validator = Validator::make($request->all(), [
                'translations' => 'required|array',
                'translations.*.group' => 'required|string',
                'translations.*.key' => 'required|string',
                'translations.*.value' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            foreach ($request->translations as $translation) {
                Translation::updateOrCreateTranslation(
                    $language->id,
                    $translation['group'],
                    $translation['key'],
                    $translation['value']
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الترجمات بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الترجمات'
            ], 500);
        }
    }

    /**
     * جلب ترجمات مجموعة معينة
     */
    public function getTranslations(Language $language, $group = null)
    {
        try {
            $translations = Translation::exportTranslations($language->id, $group);

            return response()->json([
                'success' => true,
                'translations' => $translations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الترجمات'
            ], 500);
        }
    }

    /**
     * استيراد ترجمات من ملف
     */
    public function import(Request $request, Language $language)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:json'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file');
            $translations = json_decode(file_get_contents($file->path()), true);

            if (!is_array($translations)) {
                return response()->json([
                    'success' => false,
                    'message' => 'صيغة الملف غير صحيحة'
                ], 422);
            }

            DB::beginTransaction();

            foreach ($translations as $group => $items) {
                foreach ($items as $key => $value) {
                    Translation::updateOrCreateTranslation(
                        $language->id,
                        $group,
                        $key,
                        $value
                    );
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم استيراد الترجمات بنجاح'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استيراد الترجمات'
            ], 500);
        }
    }

    /**
     * تصدير الترجمات إلى ملف
     */
    public function export(Language $language)
    {
        try {
            $translations = $language->translations()
                ->get()
                ->groupBy('group')
                ->map(function ($group) {
                    return $group->pluck('value', 'key');
                });

            $fileName = "translations_{$language->code}_" . date('Y-m-d_His') . '.json';
            
            return response()->json($translations)
                ->header('Content-Disposition', "attachment; filename={$fileName}")
                ->header('Content-Type', 'application/json');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تصدير الترجمات'
            ], 500);
        }
    }
} 