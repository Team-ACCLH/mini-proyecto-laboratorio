<?php

// Mientras no este el autoload (PR #4), el sujeto carga sus observadores.
require_once __DIR__ . '/EmailObserver.php';
require_once __DIR__ . '/SmsObserver.php';
require_once __DIR__ . '/DashboardObserver.php';

final class OrderEvents
{
    /** @var OrderObserver[] coleccion de INTERFACES, no de clases concretas */
    private array $observers = [];

    // Permite añadir dinámicamente cualquier observador nuevo en el futuro
    public function attach(OrderObserver $observer): void
    {
        $this->observers[] = $observer;
    }

    // Recorre todos los suscriptores y les avisa de forma polimórfica
    public function notify(Order $order): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($order);
        }
    }
}
