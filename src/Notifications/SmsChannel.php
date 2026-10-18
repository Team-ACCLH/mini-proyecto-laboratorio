<?php

require_once __DIR__ . '/NotificationChannel.php';

final class SmsChannel implements NotificationChannel
{
    public function send(string $message, string $recipient): void
    {
        echo "[SMS] a {$recipient}: {$message}<br>";
    }
}
