<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas del patron Strategy: cada estrategia calcula el precio
 * de un tipo de paciente, y PriceCalculator usa la que le inyectan.
 */
final class PricingStrategyTest extends TestCase
{
    // CP-20 (positivo): obra social descuenta 30% por defecto, o el descuento que se le pase.
    public function testObraSocialAplicaSuDescuento(): void
    {
        // Sin parametro: usa el 0.30 por defecto del constructor
        $this->assertEqualsWithDelta(10500, (new InsuranceStrategy())->calculate(15000), 0.01);

        // Con parametro: el descuento es configurable (si el laboratorio lo cambia al 25%)
        $this->assertEqualsWithDelta(11250, (new InsuranceStrategy(0.25))->calculate(15000), 0.01);
    }

    // CP-21 (positivo): cada una de las otras estrategias aplica su regla.
    public function testLasDemasEstrategiasAplicanSuRegla(): void
    {
        $this->assertEqualsWithDelta(5000, (new RetiredPatientStrategy())->calculate(10000), 0.01);   // 50%
        $this->assertEqualsWithDelta(8000, (new PrepaidStrategy())->calculate(10000), 0.01);          // 20%
        $this->assertEqualsWithDelta(10000, (new PrivatePatientStrategy())->calculate(10000), 0.01);  // sin descuento
    }

    // CP-22 (positivo): PriceCalculator delega en la estrategia y suma IVA del 21%.
    public function testCalculadoraConIvaUsaLaEstrategiaInyectada(): void
    {
        // Composicion: la calculadora RECIBE la estrategia, no la elige ella
        $calculadora = new PriceCalculator(new InsuranceStrategy());

        // 15000 con 30% de descuento = 10500; con IVA 10500 * 1.21 = 12705
        $this->assertEqualsWithDelta(10500, $calculadora->calculate(15000), 0.01);
        $this->assertEqualsWithDelta(12705, $calculadora->calculateWithIva(15000), 0.01);
    }

    // CP-23 (negativo / borde): un monto en cero da cero con cualquier estrategia.
    public function testMontoCeroDaCero(): void
    {
        $calculadora = new PriceCalculator(new InsuranceStrategy());

        $this->assertEqualsWithDelta(0, $calculadora->calculateWithIva(0), 0.01);
    }
}
