<?php

/**
 * Repository: clase encargada de guardar y obtener pedidos.
 * Separa el acceso a datos del modelo Order.
 */
final class OrderRepository
{
    public function save(Order $order): void
    {
        Connection::getInstance()->ejecutar(
            "INSERT INTO orders (id, patient, amount) VALUES ({$order->id}, '{$order->patient}', {$order->amount})"
        );
    }

    public function all(): array
    {
        return [
            new Order(1, 'Juan Perez', 15000, 'obra_social'),
            new Order(2, 'Ana Gomez', 22000, 'particular'),
        ];
    }
}
