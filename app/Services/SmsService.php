<?php

namespace App\Services;

/**
 * خدمة الرسائل: غير مربوطة بأي شركة رسائل.
 * كل رسالة (رمز التحقق، إشعار الحجز) تُكتب في storage/logs/laravel.log فقط ولا تُرسل لأي رقم.
 * لربط شركة رسائل لاحقًا: عدّل الدالة send() فقط.
 */
class SmsService
{
    /**
     * إرسال رمز OTP (يُسجَّل في اللوق فقط)
     */
    public function sendOtp($phoneNumber, $otpCode)
    {
        $message = "رمز التحقق الخاص بك في " . config('brand.name') . ": {$otpCode}\nصالح لمدة دقيقة ونصف";

        return $this->send([$this->formatPhoneNumber($phoneNumber)], $message);
    }

    /**
     * إشعار حجز جديد للمديرين (معطّل: لا توجد أرقام)
     */
    public function sendBookingNotificationToManager($bookingData)
    {
        $bookingDate = explode(' ', (string) $bookingData['booking_date'])[0];

        $message = "حجز جديد في " . config('brand.name') . "\n\n";
        $message .= "العميل: {$bookingData['customer_name']}\n";
        $message .= "التاريخ: {$bookingDate}\n";
        $message .= "الوقت: " . $this->convertTo12HourFormat($bookingData['booking_time']);

        return $this->send([], $message);
    }

    /**
     * نقطة الإرسال الوحيدة: تكتب الرسالة في اللوق بدل إرسالها
     */
    private function send(array $phones, string $message)
    {
        \Log::info('SMS (not sent - no SMS provider)', ['phones' => $phones, 'message' => $message]);

        return ['success' => true, 'response' => 'logged'];
    }

    /**
     * تنسيق رقم الجوال السعودي إلى 9665XXXXXXXX
     */
    private function formatPhoneNumber($phoneNumber)
    {
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (strlen($phone) === 9 && substr($phone, 0, 1) === '5') {
            return '966' . $phone;
        } elseif (strlen($phone) === 10 && substr($phone, 0, 2) === '05') {
            return '966' . substr($phone, 1);
        }

        return $phone;
    }

    /**
     * تحويل الوقت من 24 ساعة إلى 12 ساعة
     */
    private function convertTo12HourFormat($time24)
    {
        $time = \DateTime::createFromFormat('H:i', $time24);
        if (!$time) {
            return $time24;
        }

        $hour = (int) $time->format('H');
        $minute = $time->format('i');

        if ($hour == 0) {
            return "12:{$minute} صباحاً";
        } elseif ($hour < 12) {
            return "{$hour}:{$minute} صباحاً";
        } elseif ($hour == 12) {
            return "12:{$minute} مساءً";
        }

        return ($hour - 12) . ":{$minute} مساءً";
    }

    /**
     * توليد رمز OTP من 4 أرقام
     */
    public static function generateOtp()
    {
        return random_int(1000, 9999);
    }

    /**
     * التحقق من صلاحية رمز OTP
     */
    public static function verifyOtp($inputOtp, $storedOtp, $expiresAt)
    {
        if (!$storedOtp || !$expiresAt) {
            return false;
        }

        if (now()->gt($expiresAt)) {
            return false;
        }

        return hash_equals((string) $storedOtp, (string) $inputOtp);
    }
}
