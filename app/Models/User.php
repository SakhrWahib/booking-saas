<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // العلاقة مع جدول الأدوار
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // التحقق من صلاحيات المستخدم
    public function hasRole($role)
    {
        return $this->role->name === $role;
    }

    // التحقق من حالة المستخدم
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * التحقق من وجود صلاحية معينة
     */
    public function hasPermission($permissionName)
    {
        if (!$this->role) {
            return false;
        }
        return $this->role->hasPermission($permissionName);
    }

    /**
     * التحقق من وجود أي صلاحية من مجموعة صلاحيات
     */
    public function hasAnyPermission($permissions)
    {
        if (!$this->role) {
            return false;
        }
        
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * التحقق من وجود جميع الصلاحيات
     */
    public function hasAllPermissions($permissions)
    {
        if (!$this->role) {
            return false;
        }
        
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * الحصول على جميع صلاحيات المستخدم
     */
    public function getAllPermissions()
    {
        if (!$this->role) {
            return collect();
        }
        return $this->role->permissions;
    }
} 