<?php

namespace Src\Events;

final class OrderEvents
{
    private array $observers = [];

    // Permite añadir dinámicamente cualquier observador nuevo en el futuro
    public function attach(OrderObserver $observer): void
    {
        $this->observers[] = $observer;
    }

    // El método central que dispara los avisos desatados
    public function detenerYNotificar(array $orderData): void
    {
        // Recorre todos los suscriptores y les avisa de forma polimórfica
        foreach ($this->observers as $observer) {
            $observer->update($orderData);
        }
    }
}
