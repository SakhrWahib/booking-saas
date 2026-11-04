<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ImageHelper;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_description',
        'description',
        'price',
        'image_gallery',
        'add_ons',
        'service_provider_ids',
        'is_active'
    ];

    protected $casts = [
        'image_gallery' => 'array',
        'add_ons' => 'array',
        'service_provider_ids' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    /**
     * العلاقة مع مقدمي الخدمات (محدثة لاستخدام الحقول JSON)
     */
    public function serviceProviders()
    {
        $rawServiceProviderIds = $this->getRawServiceProviderIdsAttribute();
        if (!$rawServiceProviderIds || !is_array($rawServiceProviderIds)) {
            return collect();
        }
        
        return ServiceProvider::whereIn('id', $rawServiceProviderIds)->get();
    }

    /**
     * ربط مقدمي الخدمات بالمنتج
     */
    public function attachServiceProviders($serviceProviderIds)
    {
        if (!is_array($serviceProviderIds)) {
            $serviceProviderIds = [$serviceProviderIds];
        }
        
        $this->attributes['service_provider_ids'] = json_encode($serviceProviderIds);
        $this->save();
        
        return $this;
    }

    /**
     * إزالة مقدمي الخدمات من المنتج
     */
    public function detachServiceProviders($serviceProviderIds = null)
    {
        if ($serviceProviderIds === null) {
            // إزالة جميع مقدمي الخدمات
            $this->attributes['service_provider_ids'] = json_encode([]);
        } else {
            if (!is_array($serviceProviderIds)) {
                $serviceProviderIds = [$serviceProviderIds];
            }
            
            $currentIds = $this->getRawServiceProviderIdsAttribute() ?? [];
            $newIds = array_diff($currentIds, $serviceProviderIds);
            $this->attributes['service_provider_ids'] = json_encode($newIds);
        }
        
        $this->save();
        
        return $this;
    }

    /**
     * التحقق من وجود مقدم خدمة معين
     */
    public function hasServiceProvider($serviceProviderId)
    {
        $rawServiceProviderIds = $this->getRawServiceProviderIdsAttribute();
        return in_array($serviceProviderId, $rawServiceProviderIds ?? []);
    }

    /**
     * العلاقة مع الحجوزات
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * العلاقة مع السلات المتروكة
     */
    public function abandonedCarts()
    {
        return $this->hasMany(AbandonedCart::class);
    }

    /**
     * الحصول على الصورة الأولى للمنتج
     */
    public function getFirstImageAttribute()
    {
        $rawImages = $this->getRawImagesAttribute();
        return ImageHelper::getFirstProductImage($rawImages);
    }

    /**
     * الحصول على جميع صور المنتج
     */
    public function getImagesAttribute()
    {
        $rawImages = $this->getRawImagesAttribute();
        return ImageHelper::getProductImages($rawImages);
    }

    /**
     * الحصول على عدد الصور
     */
    public function getImagesCountAttribute()
    {
        $rawImages = $this->getRawImagesAttribute();
        return $rawImages ? count($rawImages) : 0;
    }

    /**
     * الحصول على عدد الإضافات
     */
    public function getAddOnsCountAttribute()
    {
        $rawAddOns = $this->getRawAddOnsAttribute();
        return $rawAddOns && is_array($rawAddOns) ? count($rawAddOns) : 0;
    }
    
    /**
     * التحقق من وجود إضافات
     */
    public function hasAddOns()
    {
        $rawAddOns = $this->getRawAddOnsAttribute();
        return $rawAddOns && is_array($rawAddOns) && count($rawAddOns) > 0;
    }
    
    /**
     * الحصول على الإضافات مع التنسيق الصحيح
     */
    public function getFormattedAddOnsAttribute()
    {
        $rawAddOns = $this->getRawAddOnsAttribute();
        if (!$rawAddOns || !is_array($rawAddOns)) {
            return [];
        }
        
        return collect($rawAddOns)->map(function ($addOn) {
            return [
                'option' => $addOn['option'] ?? '',
                'name' => $addOn['name'] ?? '',
                'price' => isset($addOn['price']) ? floatval($addOn['price']) : 0,
                'image' => $addOn['image'] ?? null,
                'formatted_price' => isset($addOn['price']) ? number_format(floatval($addOn['price']), 2) . ' ر.س' : '0.00 ر.س'
            ];
        })->toArray();
    }

    /**
     * الحصول على الإضافات كمجموعة
     */
    public function getAddOnsAttribute()
    {
        $rawAddOns = $this->getRawAddOnsAttribute();
        if (!$rawAddOns || !is_array($rawAddOns)) {
            return collect();
        }
        
        return collect($rawAddOns)->map(function ($addOn) {
            return (object) [
                'option' => $addOn['option'] ?? '',
                'name' => $addOn['name'] ?? '',
                'price' => isset($addOn['price']) ? floatval($addOn['price']) : 0,
                'image' => $addOn['image'] ?? null
            ];
        });
    }

    /**
     * الحصول على الإضافات الخام من قاعدة البيانات
     */
    public function getRawAddOnsAttribute()
    {
        return $this->attributes['add_ons'] ? json_decode($this->attributes['add_ons'], true) : null;
    }

    /**
     * الحصول على مصفوفة الصور الخام
     */
    public function getRawImagesAttribute()
    {
        return $this->attributes['image_gallery'] ? json_decode($this->attributes['image_gallery'], true) : [];
    }

    /**
     * الحصول على معرفات مقدمي الخدمات الخام من قاعدة البيانات
     */
    public function getRawServiceProviderIdsAttribute()
    {
        return $this->attributes['service_provider_ids'] ? json_decode($this->attributes['service_provider_ids'], true) : null;
    }

    /**
     * فلترة المنتجات النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * فلترة المنتجات غير النشطة
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
} 