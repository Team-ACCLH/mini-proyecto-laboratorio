<?php

require_once __DIR__ . '/OrderObserver.php';

final class EmailObserver implements OrderObserver
{
    public function update(Order $order): void
    {
        (new EmailNotification())->enviarEmail('paciente@mail.com', 'Pedido creado', "Pedido {$order->id}");
    }
}
