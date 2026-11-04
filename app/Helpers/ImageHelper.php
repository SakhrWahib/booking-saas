<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImageHelper
{
    /**
     * الحصول على مسار الصورة مع التحقق من وجودها
     */
    public static function getImageUrl($imagePath, $defaultImage = 'images/default-product.svg')
    {
        if (empty($imagePath)) {
            return asset($defaultImage);
        }

        // تنظيف المسار - إزالة storage/app/public إذا كان موجوداً
        $cleanPath = str_replace(['storage/app/public/', 'storage/', 'public/'], '', $imagePath);
        
        // إنشاء URL مباشر
        $imageUrl = asset('storage/' . $cleanPath);
        
        // التحقق من وجود الصورة في مجلد storage/app/public
        if (Storage::disk('public')->exists($cleanPath)) {
            return $imageUrl;
        }
        
        // التحقق من وجود الصورة في مجلد public/storage (للتوافق)
        if (File::exists(public_path('storage/' . $cleanPath))) {
            return $imageUrl;
        }
        
        // إرجاع الصورة الافتراضية
        return asset($defaultImage);
    }

    /**
     * الحصول على جميع صور المنتج
     */
    public static function getProductImages($imageGallery)
    {
        $images = [];
        
        if (is_array($imageGallery)) {
            foreach ($imageGallery as $image) {
                $images[] = self::getImageUrl($image);
            }
        } elseif (is_string($imageGallery)) {
            $images[] = self::getImageUrl($imageGallery);
        }
        
        return $images;
    }

    /**
     * الحصول على الصورة الأولى للمنتج
     */
    public static function getFirstProductImage($imageGallery)
    {
        if (is_array($imageGallery) && count($imageGallery) > 0) {
            return self::getImageUrl($imageGallery[0]);
        } elseif (is_string($imageGallery)) {
            return self::getImageUrl($imageGallery);
        }
        
        return self::getImageUrl(null);
    }

    /**
     * حفظ الصورة والتحقق من نجاح العملية
     */
    public static function saveImage($file, $path, $filename = null)
    {
        try {
            if (!$filename) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            }
            
            $fullPath = $path . '/' . $filename;
            
            // التأكد من وجود المجلد
            if (!Storage::disk('public')->exists($path)) {
                Storage::disk('public')->makeDirectory($path);
            }
            
            // حفظ الصورة
            $saved = $file->storeAs($path, $filename, 'public');
            
            if ($saved) {
                // إرجاع المسار النسبي للصورة (بدون storage/app/public)
                return $saved;
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error('فشل في حفظ الصورة: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * حذف الصورة
     */
    public static function deleteImage($imagePath)
    {
        try {
            $cleanPath = str_replace(['storage/app/public/', 'storage/', 'public/'], '', $imagePath);
            
            if (Storage::disk('public')->exists($cleanPath)) {
                return Storage::disk('public')->delete($cleanPath);
            }
            return true;
        } catch (\Exception $e) {
            \Log::error('فشل في حذف الصورة: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * التحقق من صحة الصورة
     */
    public static function isValidImage($file)
    {
        if (!$file || !$file->isValid()) {
            return false;
        }
        
        $allowedTypes = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        return in_array($extension, $allowedTypes) && $file->getSize() <= 5242880; // 5MB
    }

    /**
     * التحقق من وجود الصورة فعلياً
     */
    public static function imageExists($imagePath)
    {
        if (empty($imagePath)) {
            return false;
        }

        $cleanPath = str_replace(['storage/app/public/', 'storage/', 'public/'], '', $imagePath);
        
        // التحقق من وجود الصورة في مجلد storage/app/public
        if (Storage::disk('public')->exists($cleanPath)) {
            return true;
        }
        
        // التحقق من وجود الصورة في مجلد public/storage (للتوافق)
        return File::exists(public_path('storage/' . $cleanPath));
    }

    /**
     * تنظيف مسار الصورة
     */
    public static function cleanImagePath($imagePath)
    {
        if (empty($imagePath)) {
            return null;
        }

        return str_replace(['storage/app/public/', 'storage/', 'public/'], '', $imagePath);
    }
} 