<?php

class EmailObserver implements OrderObserver
{
    public function update(Order $order): void
    {
        // Esto envía la notificación usando la estructura limpia
        (new NotificationSender())->enviar('email', 'paciente@mail.com', "Pedido {$order->id}");
    }
}
