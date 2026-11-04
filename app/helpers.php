<?php

if (!function_exists('getProductImageUrl')) {
    function getProductImageUrl($imagePath, $default = 'images/default-product.svg') {
        if (empty($imagePath)) {
            return asset($default);
        }
        
        // تنظيف المسار - إزالة storage/app/public إذا كان موجوداً
        $cleanPath = str_replace(['storage/app/public/', 'storage/', 'public/'], '', $imagePath);
        
        // إنشاء URL مباشر للوصول للصور
        $url = url('storage/' . $cleanPath);
        
        // التحقق من وجود الملف في مجلد public/storage
        if (file_exists(public_path('storage/' . $cleanPath))) {
            return $url;
        }
        
        // التحقق من وجود الملف في مجلد storage/app/public
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($cleanPath)) {
            return $url;
        }
        
        // إذا لم توجد الصورة، إرجاع الصورة الافتراضية
        return asset($default);
    }
}

if (!function_exists('getFirstProductImage')) {
    function getFirstProductImage($imageGallery) {
        if (is_array($imageGallery) && count($imageGallery) > 0) {
            return getProductImageUrl($imageGallery[0]);
        } elseif (is_string($imageGallery)) {
            return getProductImageUrl($imageGallery);
        }
        return getProductImageUrl(null);
    }
}

// دالة جديدة لاختبار الصور مباشرة
if (!function_exists('testImageUrl')) {
    function testImageUrl($imagePath) {
        if (empty($imagePath)) {
            return false;
        }
        
        $cleanPath = str_replace(['storage/app/public/', 'storage/', 'public/'], '', $imagePath);
        return file_exists(public_path('storage/' . $cleanPath));
    }
}

// دوال الصلاحيات
// رقم النشاط الحالي (Multi-tenancy)
if (!function_exists('tenant_id')) {
    function tenant_id(): ?int {
        return \App\Support\Tenancy::id();
    }
}

if (!function_exists('hasPermission')) {
    function hasPermission($permissionName) {
        if (!auth()->check()) {
            return false;
        }
        return auth()->user()->hasPermission($permissionName);
    }
}

if (!function_exists('hasAnyPermission')) {
    function hasAnyPermission($permissions) {
        if (!auth()->check()) {
            return false;
        }
        return auth()->user()->hasAnyPermission($permissions);
    }
}

if (!function_exists('hasAllPermissions')) {
    function hasAllPermissions($permissions) {
        if (!auth()->check()) {
            return false;
        }
        return auth()->user()->hasAllPermissions($permissions);
    }
}

if (!function_exists('canView')) {
    function canView($permissionName) {
        return hasPermission($permissionName . '.view');
    }
}

if (!function_exists('canCreate')) {
    function canCreate($permissionName) {
        return hasPermission($permissionName . '.create');
    }
}

if (!function_exists('canEdit')) {
    function canEdit($permissionName) {
        return hasPermission($permissionName . '.edit');
    }
}

if (!function_exists('canDelete')) {
    function canDelete($permissionName) {
        return hasPermission($permissionName . '.delete');
    }
} 