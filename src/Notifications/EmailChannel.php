<?php

require_once __DIR__ . '/NotificationChannel.php';

final class EmailChannel implements NotificationChannel
{
    public function send(string $message, string $recipient): void
    {
        echo "[EMAIL] para {$recipient} | Pedido del laboratorio: {$message}<br>";
    }
}
