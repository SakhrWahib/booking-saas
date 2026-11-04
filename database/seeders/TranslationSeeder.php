<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Translation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TranslationSeeder extends Seeder
{
    public function run()
    {
        $languages = Language::all();
        
        foreach ($languages as $language) {
            $this->importTranslationsForLanguage($language);
        }
    }

    private function importTranslationsForLanguage(Language $language)
    {
        // المجموعات التي نريد استيرادها
        $groups = ['web', 'auth', 'validation', 'dashboard'];
        
        foreach ($groups as $group) {
            // محاولة تحميل ملف الترجمة
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
} 