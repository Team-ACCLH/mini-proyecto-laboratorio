<?php

namespace Src\Events;

final class LogOrderObserver implements OrderObserver
{
    public function update(array $orderData): void
    {
        // Lógica aislada para guardar en el Log
        echo "Orden registrada en el log del sistema: " . $orderData['id'];
    }
}
