<?php

namespace Src\Pricing;

interface PricingStrategy
{
    public function calculate(float $amount): float;
}
