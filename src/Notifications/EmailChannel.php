<?php

namespace Src\Notifications;

final class EmailChannel implements NotificationChannel
{
    public function send(string $message, string $recipient): void
    {
        // Aquí iría la lógica de Laravel para enviar un correo real
        // Ej: Mail::to($recipient)->send(new GenericNotification($message));
    }
}
