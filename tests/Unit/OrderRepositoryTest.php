<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas del patron Repository:
 * OrderRepository guarda y recupera pedidos.
 */
final class OrderRepositoryTest extends TestCase
{
    // CP-30 (positivo): save() registra un INSERT.
    public function testSaveRegistraLaConsultaDeInsert(): void
    {
        $repositorio = new OrderRepository();

        $antes = count(Connection::getInstance()->consultas());

        $repositorio->save(
            new Order(9, 'Test', 100, 'particular')
        );

        $consultas = Connection::getInstance()->consultas();

        $this->assertCount($antes + 1, $consultas);

        $this->assertSame(
            "INSERT INTO orders (id, patient, amount) VALUES (9, 'Test', 100)",
            end($consultas)
        );
    }

    // CP-31 (positivo): all() devuelve pedidos.
    public function testAllDevuelveLosPedidosComoObjetosOrder(): void
    {
        $pedidos = (new OrderRepository())->all();

        $this->assertCount(2, $pedidos);

        $this->assertContainsOnlyInstancesOf(
            Order::class,
            $pedidos
        );

        $this->assertSame('Juan Perez', $pedidos[0]->patient);

        $this->assertSame(
            'obra_social',
            $pedidos[0]->patientType
        );
    }

    // CP-32 (negativo): nombre con comilla sin escapar.
    public function testNombreConComillaQuedaSinEscaparEnLaConsulta(): void
    {
        (new OrderRepository())->save(
            new Order(13, "O'Brien", 100, 'particular')
        );

        $consultas = Connection::getInstance()->consultas();

        $this->assertStringContainsString(
            "'O'Brien'",
            end($consultas)
        );
    }
}
