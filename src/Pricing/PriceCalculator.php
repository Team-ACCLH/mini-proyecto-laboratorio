<?php
// src/Pricing/PriceCalculator.php

class PriceCalculator
{
    private PricingStrategy $strategy;

    public function __construct(PricingStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function calcular(float $monto): float
    {
        return $this->strategy->calculate($monto);
    }

    public function calcularConIva(float $monto): float
    {
        return $this->calcular($monto) * 1.21;
    }
}
