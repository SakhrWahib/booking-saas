<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use AppModelsConcernsBelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'customer_type',
        'email',
        'latitude',
        'longitude',
        'address',
        'otp_code',
        'otp_expires_at',
        'otp_attempts',
        'last_mobile_login',
        'device_type'
    ];

    protected $hidden = [
        'otp_code'
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'last_mobile_login' => 'datetime',
    ];

    /**
     * العلاقة مع الحجوزات
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * العلاقة مع الفواتير
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * الحصول على آخر حجز للعميل
     */
    public function latestBooking()
    {
        return $this->hasOne(Booking::class)->latestOfMany();
    }

    /**
     * الحصول على الحجوزات النشطة
     */
    public function activeBookings()
    {
        return $this->hasMany(Booking::class)->whereIn('status', ['pending', 'confirmed', 'in_progress']);
    }

    /**
     * توليد رمز OTP مع تاريخ انتهاء الصلاحية
     */
    public function generateOtp($expiryMinutes = 10)
    {
        $otp = rand(1000, 9999);
        $this->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes($expiryMinutes),
            'otp_attempts' => 0
        ]);
        return $otp;
    }

    /**
     * التحقق من رمز OTP مع التحقق من الصلاحية والمحاولات
     */
    public function verifyOtp($otp, $maxAttempts = 3)
    {
        // التحقق من عدد المحاولات
        if ($this->otp_attempts >= $maxAttempts) {
            return false;
        }

        // التحقق من انتهاء الصلاحية
        if ($this->otp_expires_at && now()->isAfter($this->otp_expires_at)) {
            return false;
        }

        // التحقق من صحة الرمز
        if ($this->otp_code == $otp) {
            $this->clearOtp();
            return true;
        }

        // زيادة عدد المحاولات الخاطئة
        $this->increment('otp_attempts');
        return false;
    }

    /**
     * مسح رمز OTP وبياناته
     */
    public function clearOtp()
    {
        $this->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0
        ]);
    }

    /**
     * التحقق من صلاحية OTP
     */
    public function isOtpValid()
    {
        return $this->otp_code && 
               $this->otp_expires_at && 
               now()->isBefore($this->otp_expires_at);
    }

    /**
     * التحقق من تجاوز عدد المحاولات
     */
    public function hasExceededOtpAttempts($maxAttempts = 3)
    {
        return $this->otp_attempts >= $maxAttempts;
    }

    /**
     * تسجيل دخول العميل عبر الجوال
     */
    public function recordMobileLogin($deviceType = null)
    {
        $this->update([
            'last_mobile_login' => now(),
            'device_type' => $deviceType
        ]);
    }

    /**
     * التحقق من دخول العميل عبر الجوال
     */
    public function hasMobileLogin()
    {
        return !is_null($this->last_mobile_login);
    }

    /**
     * الحصول على آخر دخول عبر الجوال
     */
    public function getLastMobileLogin()
    {
        return $this->last_mobile_login;
    }

    /**
     * الحصول على نوع الجهاز
     */
    public function getDeviceType()
    {
        return $this->device_type;
    }

    /**
     * الحصول على عدد الأيام منذ آخر دخول
     */
    public function getDaysSinceLastMobileLogin()
    {
        if (!$this->last_mobile_login) {
            return null;
        }
        return (int) abs(now()->diffInDays($this->last_mobile_login));
    }

    /**
     * التحقق من أن العميل نشط
     */
    public function isActive()
    {
        // يمكنك تعديل هذا المنطق حسب احتياجاتك
        // مثلاً: العميل نشط إذا لم يكن محظوراً أو معلقاً
        return true; // افتراضي: جميع العملاء نشطون
    }
} 