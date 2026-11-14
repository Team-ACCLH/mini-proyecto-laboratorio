<?php

use PHPUnit\Framework\TestCase;

/**
 * OrderPricing elige la estrategia segun el tipo de paciente.
 * Es el unico lugar que sabe que "obra_social" usa InsuranceStrategy.
 */
final class OrderPricingTest extends TestCase
{
    // CP-24 (positivo): cada tipo conocido usa su estrategia.
    public function testTiposConocidosUsanSuEstrategia(): void
    {
        $pricing = new OrderPricing();

        $this->assertEqualsWithDelta(10500, $pricing->total(15000, 'obra_social'), 0.01);
        $this->assertEqualsWithDelta(5000, $pricing->total(10000, 'jubilado'), 0.01);
        $this->assertEqualsWithDelta(22000, $pricing->total(22000, 'particular'), 0.01);
    }

    // CP-25 (negativo): un tipo de paciente desconocido cobra precio completo (sin descuento).
    public function testTipoDesconocidoCobraPrecioCompleto(): void
    {
        $pricing = new OrderPricing();

        $this->assertEqualsWithDelta(15000, $pricing->total(15000, 'tipo_inexistente'), 0.01);
    }
}
