<?php
/**
 * ============================================================================
 *  NOTIFICACION POR EMAIL
 * ============================================================================
 *
 *  Implementa Notification con el metodo send() definido por la interfaz.
 *  Antes tenia enviarEmail() con firma distinta a SmsNotification.
 *  Ahora ambas comparten el mismo contrato => polimorfismo.
 * ============================================================================
 */

class EmailNotification implements Notification
{
    private string $destinatario;

    public function __construct(string $destinatario = '')
    {
        $this->destinatario = $destinatario;
    }

    public function send(string $message): void
    {
        echo "[EMAIL] para {$this->destinatario}: {$message}<br>";
    }
}
