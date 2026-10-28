<?php 

/**
 * Repository: clase encargada de guardar pedidos.
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
}
