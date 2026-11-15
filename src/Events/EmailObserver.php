<?php

require_once __DIR__ . '/OrderObserver.php';

/** Observador que avisa al paciente por email cuando se crea un pedido. */
final class EmailObserver implements OrderObserver
{
    public function update(Order $order): void
    {
        // Antes: new EmailNotification(), clase eliminada en el PR #27 (defecto #35).
        // Ahora la Factory entrega el canal de email, con el mismo contrato que los demas.
        NotificationFactory::make('email')->send("Pedido {$order->id} creado", 'paciente@mail.com');
    }
}
