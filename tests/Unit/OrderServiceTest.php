<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas del patron Facade: OrderService solo coordina
 * validacion, precio, persistencia y notificacion.
 */
final class OrderServiceTest extends TestCase
{
    // CP-26 (positivo): listarPedidos devuelve los pedidos con el total ya calculado.
    public function testListarPedidosDevuelveTotalesCalculados(): void
    {
        $service = new OrderService();

        $pedidos = $service->listarPedidos();

        // El repositorio en memoria tiene 2 pedidos
        $this->assertCount(2, $pedidos);

        // Pedido 1: Juan Perez, obra social, 15000 con 30% de descuento
        $this->assertSame(1, $pedidos[0]['id']);
        $this->assertSame('Juan Perez', $pedidos[0]['paciente']);
        $this->assertEqualsWithDelta(10500, $pedidos[0]['total'], 0.01);

        // Pedido 2: Ana Gomez, particular, sin descuento
        $this->assertEqualsWithDelta(22000, $pedidos[1]['total'], 0.01);
    }

    // CP-27 (positivo): crearPedido devuelve el total y avisa al paciente por email.
    public function testCrearPedidoDevuelveTotalYEnviaEmail(): void
    {
        $service = new OrderService();
        $order = new Order(10, 'Ana Lopez', 15000, 'obra_social');

        // Lo que tiene que imprimir el canal de email al final
        $this->expectOutputString(
            '[EMAIL] para paciente@mail.com | Pedido del laboratorio: Pedido 10 creado<br>'
        );

        $total = $service->crearPedido($order);

        $this->assertEqualsWithDelta(10500, $total, 0.01);
    }

    // CP-28 (negativo): sin paciente, el pedido se rechaza y no se envia nada.
    public function testProcesarSinPacienteMuestraErrorYNoSigue(): void
    {
        $service = new OrderService();

        // Solo se imprime el error: ni email, ni SMS, ni reporte
        $this->expectOutputString('Falta el paciente<br>');

        $service->procesarPedidoCompleto(11, '', 15000, 'obra_social', 'email', 'a@mail.com');
    }

    // CP-29 (negativo): con monto negativo, el pedido se rechaza.
    public function testProcesarConMontoNegativoMuestraError(): void
    {
        $service = new OrderService();

        $this->expectOutputString('Monto invalido<br>');

        $service->procesarPedidoCompleto(12, 'Juan Perez', -100, 'obra_social', 'email', 'a@mail.com');
    }
}
