<?php

namespace Src\Pricing;

final class PrepaidStrategy implements PricingStrategy
{
    public function calculate(float $amount): float
    {
        return $amount * 0.80; // 20% de descuento para prepagas
    }
}
