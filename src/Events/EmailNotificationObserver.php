<?php

namespace Src\Events;

final class EmailNotificationObserver implements OrderObserver
{
    public function update(array $orderData): void
    {
        // Lógica aislada para avisar por Mail
        echo "Notificación por Email enviada para la orden: " . $orderData['id'];
    }
}
