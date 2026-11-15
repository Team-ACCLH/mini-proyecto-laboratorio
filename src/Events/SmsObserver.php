<?php

require_once __DIR__ . '/OrderObserver.php';

/** Observador que avisa al paciente por SMS cuando se crea un pedido. */
final class SmsObserver implements OrderObserver
{
    public function update(Order $order): void
    {
        // Antes: new SmsNotification(), clase eliminada en el PR #27 (defecto #35).
        NotificationFactory::make('sms')->send("Pedido {$order->id} creado", '3704000000');
    }
}
