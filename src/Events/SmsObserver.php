<?php

require_once __DIR__ . '/OrderObserver.php';

final class SmsObserver implements OrderObserver
{
    public function update(Order $order): void
    {
        (new SmsNotification())->mandarSms('3704000000', "Pedido {$order->id} creado");
    }
}
