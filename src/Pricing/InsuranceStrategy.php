<?php
final class InsuranceStrategy implements PricingStrategy
{
    private float $discount;

    public function __construct(float $discount = 0.30)
    {
        $this->discount = $discount;
    }

    public function calculate(float $amount): float
    {
        return $amount * (1 - $this->discount);
    }
}
