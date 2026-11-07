<?php

namespace App\Support;

use App\Models\Coupon;
use App\Models\Product;

/**
 * تسعير الحجز من بيانات الخدمة في قاعدة البيانات فقط (لا نثق بأسعار تأتي من المتصفح).
 * الإضافات تُختار بالاسم وتؤخذ أسعارها من add_ons الخاصة بالخدمة.
 */
class BookingQuote
{
    public function __construct(
        public readonly array $addOns,       // الإضافات المختارة بأسعارها الحقيقية
        public readonly float $subtotal,
        public readonly float $discount,
        public readonly float $total,
        public readonly ?Coupon $coupon,
    ) {
    }

    public static function make(Product $product, array $addOnNames = [], int $quantity = 1, ?string $couponCode = null): self
    {
        $chosen = array_values(array_filter(
            collect($product->add_ons ?? [])->map(fn ($a) => (array) $a)->all(),
            fn ($a) => in_array($a['name'] ?? null, $addOnNames, true)
        ));
        $addOns = array_map(fn ($a) => [
            'option' => $a['option'] ?? '',
            'name' => $a['name'],
            'price' => (float) ($a['price'] ?? 0),
            'image' => $a['image'] ?? null,
        ], $chosen);

        $quantity = max(1, $quantity);
        $subtotal = round(((float) $product->price + array_sum(array_column($addOns, 'price'))) * $quantity, 2);

        $coupon = null;
        $discount = 0.0;
        if ($couponCode) {
            $candidate = Coupon::findValidByCode($couponCode);
            if ($candidate && $candidate->isValidForAmount($subtotal)) {
                $coupon = $candidate;
                $discount = round(min($subtotal, (float) $candidate->calculateDiscount($subtotal)), 2);
            }
        }

        return new self($addOns, $subtotal, $discount, max(0, $subtotal - $discount), $coupon);
    }
}
