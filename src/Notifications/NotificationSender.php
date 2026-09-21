<?php

namespace Src\Notifications;

final class NotificationSender
{
    public function enviarNotificacion(string $mensaje, string $destinatario, string $tipo): void
    {
        // La fábrica se encarga de darme el objeto correcto
        $canal = NotificationFactory::make($tipo);
        
        // Polimorfismo: no me importa qué canal sea, sé que tiene el método send()
        $canal->send($mensaje, $destinatario);
    }
}
