<?php

namespace App\Services;

class DeliveryFee
{
    /** Full meal = 15 MAD, otherwise 5-15 MAD depending on item price. */
    public static function for(array $item): int
    {
        if (!empty($item['meal'])) {
            return config('catalog.meal_delivery', 15);
        }
        $p = $item['price'];
        return match (true) {
            $p < 20 => 5,
            $p < 35 => 8,
            $p < 50 => 10,
            $p < 70 => 12,
            default => 15,
        };
    }

    /** Frais d'une commande complète : un menu complet = 15 MAD, sinon selon le sous-total. */
    public static function forOrder(int|float $subtotal, bool $hasMeal = false): int
    {
        if ($hasMeal) {
            return config('catalog.meal_delivery', 15);
        }
        return self::for(['price' => $subtotal]);
    }
}