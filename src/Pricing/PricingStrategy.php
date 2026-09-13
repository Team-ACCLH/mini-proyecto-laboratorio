<?php
// src/Pricing/PricingStrategy.php

interface PricingStrategy
{
    public function calculate(float $amount): float;
}
