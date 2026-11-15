<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas del patron Singleton: Connection tiene una sola instancia
 * compartida durante todo el request.
 */
final class ConnectionTest extends TestCase
{
    // CP-45 (positivo): dos llamadas a getInstance() devuelven el MISMO objeto.
    public function testGetInstanceDevuelveSiempreLaMismaInstancia(): void
    {
        $primera = Connection::getInstance();
        $segunda = Connection::getInstance();

        // Con objetos, assertSame verifica que sean el mismo objeto en memoria,
        // no solo dos objetos parecidos
        $this->assertSame($primera, $segunda);
    }

    // CP-46 (positivo): el constructor es privado.
    public function testElConstructorEsPrivado(): void
    {
        $constructor = new ReflectionMethod(Connection::class, '__construct');

        $this->assertTrue($constructor->isPrivate());
    }

    // CP-47 (negativo): crear la conexion con new desde afuera esta prohibido.
    public function testNoSePuedeCrearConNew(): void
    {
        // PHP lanza Error: "Call to private Connection::__construct() ..."
        $this->expectException(Error::class);
        $this->expectExceptionMessage('private');

        new Connection();
    }

    // CP-48 (positivo): lo que se ejecuta desde una referencia se ve desde la otra,
    // porque las dos apuntan a la misma conexion.
    public function testLaConsultaEjecutadaSeVeDesdeCualquierReferencia(): void
    {
        $referencia = Connection::getInstance();
        // Contar antes: el estado static sobrevive entre pruebas
        $antes = count($referencia->consultas());

        Connection::getInstance()->ejecutar('SELECT 1');

        // end() necesita una variable, no el resultado directo de una funcion
        $consultas = $referencia->consultas();
        $this->assertCount($antes + 1, $consultas);
        $this->assertSame('SELECT 1', end($consultas));
    }
}
