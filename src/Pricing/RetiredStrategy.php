<?php
final class RetiredStrategy implements PricingStrategy
{
    public function calculate(float $amount): float
    {
        return $amount * 0.5;
    }
}
