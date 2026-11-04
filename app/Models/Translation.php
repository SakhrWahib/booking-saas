<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_id',
        'group',
        'key',
        'value'
    ];

    // العلاقة مع اللغة
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    // الحصول على ترجمة محددة
    public static function getTranslation($languageId, $group, $key)
    {
        return static::where([
            'language_id' => $languageId,
            'group' => $group,
            'key' => $key
        ])->value('value');
    }

    /**
     * تحديث أو إنشاء ترجمة
     */
    public static function updateOrCreateTranslation($languageId, $group, $key, $value)
    {
        return static::updateOrCreate(
            [
                'language_id' => $languageId,
                'group' => $group,
                'key' => $key
            ],
            ['value' => $value]
        );
    }

    /**
     * استيراد مجموعة من الترجمات
     */
    public static function importTranslations($languageId, $translations)
    {
        foreach ($translations as $group => $items) {
            foreach ($items as $key => $value) {
                static::updateOrCreateTranslation($languageId, $group, $key, $value);
            }
        }
    }

    /**
     * تصدير الترجمات لمجموعة معينة
     */
    public static function exportTranslations($languageId, $group = null)
    {
        $query = static::where('language_id', $languageId);
        
        if ($group) {
            $query->where('group', $group);
        }
        
        return $query->get()->groupBy('group')->map(function ($items) {
            return $items->pluck('value', 'key');
        });
    }
} 