<?php

namespace Src\Notifications;

final class SmsChannel implements NotificationChannel
{
    public function send(string $message, string $recipient): void
    {
        // Lógica real aislada para enviar un SMS
        echo "Enviando SMS a {$recipient}: {$message}";
    }
}
