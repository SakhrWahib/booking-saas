<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'category',
        'description'
    ];

    /**
     * العلاقة مع الأدوار
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * التحقق من وجود الصلاحية
     */
    public static function hasPermission($permissionName)
    {
        return static::where('name', $permissionName)->exists();
    }

    /**
     * الحصول على الصلاحيات حسب الفئة
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * الحصول على الصلاحيات المجمعة حسب الفئة
     */
    public static function getGroupedPermissions()
    {
        return static::orderBy('category')
            ->orderBy('display_name')
            ->get()
            ->groupBy('category');
    }
} 