<?php

/**
 * ✅ Servicio cohesivo: elige la estrategia de precio segun el tipo de paciente.
 *    Es el UNICO lugar que sabe que "obra_social" usa InsuranceStrategy.
 */
final class OrderPricing
{
    public function total(float $monto, string $tipoPaciente): float
    {
        $estrategia = match ($tipoPaciente) {
            'obra_social' => new InsuranceStrategy(),
            'jubilado'    => new RetiredPatientStrategy(),
            default       => new PrivatePatientStrategy(),
        };

        return (new PriceCalculator($estrategia))->calculate($monto);
    }
}
