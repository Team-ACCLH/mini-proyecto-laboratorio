<?php

/**
 * Contrato comun de todos los canales de notificacion.
 * El resto del sistema conoce esta interfaz, no las clases concretas.
 */
interface NotificationChannel
{
    public function send(string $message, string $recipient): void;
}
