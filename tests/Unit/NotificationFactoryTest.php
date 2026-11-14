<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas del patron Factory: NotificationFactory::make() es el unico
 * lugar que decide que clase de canal crear segun el tipo.
 */
final class NotificationFactoryTest extends TestCase
{
    // CP-40 (positivo): "email" devuelve un EmailChannel que cumple el contrato.
    public function testEmailDevuelveEmailChannel(): void
    {
        $canal = NotificationFactory::make('email');

        $this->assertInstanceOf(EmailChannel::class, $canal);
        // Tambien cumple la interfaz comun: el que lo usa no necesita la clase concreta
        $this->assertInstanceOf(NotificationChannel::class, $canal);
    }

    // CP-41 (positivo): "sms" devuelve un SmsChannel.
    public function testSmsDevuelveSmsChannel(): void
    {
        $this->assertInstanceOf(SmsChannel::class, NotificationFactory::make('sms'));
    }

    // CP-42 (negativo): un canal que no existe se rechaza con una excepcion clara.
    public function testCanalDesconocidoLanzaExcepcion(): void
    {
        // Se declara ANTES de la llamada que tiene que fallar
        $this->expectException(InvalidArgumentException::class);
        // expectExceptionMessage revisa que el mensaje CONTENGA este texto
        $this->expectExceptionMessage('El canal [whatsapp] no está soportado.');

        NotificationFactory::make('whatsapp');
    }

    // CP-43 (negativo): el tipo distingue mayusculas: "EMAIL" no es "email".
    public function testTipoEnMayusculasSeRechaza(): void
    {
        $this->expectException(InvalidArgumentException::class);

        NotificationFactory::make('EMAIL');
    }

    // CP-44 (positivo): NotificationSender usa la fabrica y envia por el canal pedido.
    public function testSenderEnviaPorElCanalPedido(): void
    {
        $this->expectOutputString('[SMS] a 3704000000: Hola<br>');

        (new NotificationSender())->enviar('sms', '3704000000', 'Hola');
    }
}
