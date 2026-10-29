<?php

class SmsObserver implements OrderObserver
{
    public function update(Order $order): void
    {
        // Esto envía la notificación de SMS usando la fábrica
        (new NotificationSender())->enviar('sms', '3704000000', "Pedido {$order->id} creado");
    }
}
