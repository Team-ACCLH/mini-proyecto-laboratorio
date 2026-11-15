<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas del patron Observer: OrderEvents (el sujeto) avisa a
 * todos los OrderObserver suscriptos cuando se crea un pedido.
 */
final class OrderEventsTest extends TestCase
{
    /** Crea un observador espia que guarda cada pedido que recibe. */
    private function crearObservadorEspia(): OrderObserver
    {
        return new class implements OrderObserver {
            /** @var Order[] pedidos recibidos en cada aviso */
            public array $recibidos = [];

            public function update(Order $order): void
            {
                $this->recibidos[] = $order;
            }
        };
    }

    // CP-33 (positivo): un observador suscripto recibe el aviso con el pedido.
    public function testObservadorSuscriptoRecibeElPedido(): void
    {
        $eventos = new OrderEvents();
        $espia = $this->crearObservadorEspia();
        $order = new Order(5, 'Juan Perez', 15000, 'obra_social');

        $eventos->attach($espia);
        $eventos->notify($order);

        $this->assertCount(1, $espia->recibidos);
        $this->assertSame($order, $espia->recibidos[0]);
    }

    // CP-34 (positivo): con varios observadores, todos reciben el aviso.
    public function testTodosLosObservadoresRecibenElAviso(): void
    {
        $eventos = new OrderEvents();
        $espia1 = $this->crearObservadorEspia();
        $espia2 = $this->crearObservadorEspia();

        $eventos->attach($espia1);
        $eventos->attach($espia2);
        $eventos->notify(new Order(6, 'Ana Gomez', 22000, 'particular'));

        $this->assertCount(1, $espia1->recibidos);
        $this->assertCount(1, $espia2->recibidos);
    }

    // CP-35 (negativo): notificar sin observadores no lanza ningun error.
    public function testNotificarSinObservadoresNoFalla(): void
    {
        $this->expectNotToPerformAssertions();

        (new OrderEvents())->notify(
            new Order(7, 'Juan Perez', 100, 'particular')
        );
    }

    // CP-36 (positivo): el observador del dashboard muestra el aviso.
    public function testDashboardObserverMuestraElAviso(): void
    {
        $this->expectOutputString(
            '[DASHBOARD] actualizado para el pedido 5<br>'
        );

        (new DashboardObserver())->update(
            new Order(5, 'Juan Perez', 15000, 'obra_social')
        );
    }
}
