<?php
/**
 * ============================================================================
 *  NOTIFICACION POR SMS
 * ============================================================================
 *
 *  Implementa Notification con el metodo send() definido por la interfaz.
 *  Antes tenia mandarSms() con firma distinta a EmailNotification.
 *  Ahora ambas comparten el mismo contrato => polimorfismo.
 * ============================================================================
 */

class SmsNotification implements Notification
{
    private string $numero;

    public function __construct(string $numero = '')
    {
        $this->numero = $numero;
    }

    public function send(string $message): void
    {
        echo "[SMS] a {$this->numero}: {$message}<br>";
    }
}
