<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    // العلاقة مع المستخدمين
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // العلاقة مع الصلاحيات
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    // التحقق من اسم الدور
    public function isAdmin()
    {
        return $this->name === 'admin';
    }

    public function isOffice()
    {
        return $this->name === 'office';
    }

    public function isOperator()
    {
        return $this->name === 'operator';
    }

    public function isCustomer()
    {
        return $this->name === 'customer';
    }

    /**
     * التحقق من وجود صلاحية معينة
     */
    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    /**
     * إضافة صلاحية للدور
     */
    public function addPermission($permissionName)
    {
        $permission = Permission::where('name', $permissionName)->first();
        if ($permission && !$this->hasPermission($permissionName)) {
            $this->permissions()->attach($permission->id);
        }
    }

    /**
     * إزالة صلاحية من الدور
     */
    public function removePermission($permissionName)
    {
        $permission = Permission::where('name', $permissionName)->first();
        if ($permission) {
            $this->permissions()->detach($permission->id);
        }
    }

    /**
     * الحصول على أسماء الصلاحيات
     */
    public function getPermissionNames()
    {
        return $this->permissions()->pluck('name')->toArray();
    }
} 