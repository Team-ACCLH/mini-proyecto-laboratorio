<?php
/**
 * ============================================================================
 *  CONTRATO DE NOTIFICACIONES
 * ============================================================================
 *
 *  Interfaz comun para todas las notificaciones.
 *  Permite polimorfismo: el cliente no conoce la clase concreta.
 *
 *  Implementada por: EmailNotification, SmsNotification, LegacyNotifierAdapter
 * ============================================================================
 */

interface Notification
{
    public function send(string $message): void;
}
