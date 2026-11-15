<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas de los observadores que envian avisos al paciente.
 * Detectaron el defecto #35: usaban EmailNotification y
 * SmsNotification, clases borradas en el PR #27.
 */
final class NotificationObserversTest extends TestCase
{
    // CP-37 (positivo): EmailObserver avisa al paciente por email.
    public function testEmailObserverEnviaEmailAlPaciente(): void
    {
        $this->expectOutputString(
            '[EMAIL] para paciente@mail.com | Pedido del laboratorio: Pedido 5 creado<br>'
        );

        (new EmailObserver())->update(
            new Order(5, 'Juan Perez', 15000, 'obra_social')
        );
    }

    // CP-38 (positivo): SmsObserver avisa al paciente por SMS.
    public function testSmsObserverEnviaSmsAlPaciente(): void
    {
        $this->expectOutputString(
            '[SMS] a 3704000000: Pedido 5 creado<br>'
        );

        (new SmsObserver())->update(
            new Order(5, 'Juan Perez', 15000, 'obra_social')
        );
    }

    // CP-39 (positivo, integracion): el pedido completo pasa por todos los observadores.
    // Antes del arreglo, este flujo se cortaba con "Class not found".
    public function testProcesarPedidoCompletoAvisaATodosLosObservadores(): void
    {
        $this->expectOutputRegex(
            '/\[SMS\].*\[DASHBOARD\].*Pedido 20 procesado/s'
        );

        (new OrderService())->procesarPedidoCompleto(
            20,
            'Juan Perez',
            15000,
            'obra_social',
            'email',
            'a@mail.com'
        );
    }
}
