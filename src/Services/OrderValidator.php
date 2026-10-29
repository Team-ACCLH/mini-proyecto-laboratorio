<?php

/**
 * ✅ Servicio cohesivo: solo valida los datos de un pedido.
 */
final class OrderValidator
{
    /** Devuelve el mensaje de error, o null si el pedido es valido. */
    public function validar(string $paciente, float $monto): ?string
    {
        if (trim($paciente) === '') {
            return 'Falta el paciente';
        }

        if ($monto <= 0) {
            return 'Monto invalido';
        }

        return null;
    }
}
