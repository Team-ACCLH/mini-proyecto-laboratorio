<?php

// Mientras no este el autoload (PR #4), se cargan a mano
// los decoradores que usa este archivo.
require_once __DIR__ . '/../Reports/BasicReport.php';
require_once __DIR__ . '/../Reports/DigitalSignatureDecorator.php';
require_once __DIR__ . '/../Reports/PdfReportDecorator.php';

/**
 * ============================================================================
 * SERVICIO DE PEDIDOS
 * Patron esperado: FACADE (estructural)
 * + separacion en servicios cohesivos
 * ============================================================================
 *
 * ❌ DEUDA SEMBRADA
 * Un unico metodo procesarPedidoCompleto() que hace de todo:
 * valida, calcula, cobra, guarda, notifica, reporta
 * y ademas imprime HTML.
 *
 * ✅ FORMA CORRECTA
 * 1. Cada paso en su propio servicio cohesivo.
 * 2. Una fachada que ORQUESTA esos servicios.
 * 3. Las dependencias se inyectan por constructor.
 *
 * Una fachada COORDINA, no debe decidir reglas de negocio.
 * ============================================================================
 */

class OrderService
{
    /**
     * ❌ METODO MAL APLICADO: procesarPedidoCompleto()
     *
     * Tiene demasiadas responsabilidades:
     * validacion, precios, persistencia, notificaciones,
     * eventos, reportes y presentacion.
     */
    public function procesarPedidoCompleto(
        int $id,
        string $paciente,
        float $monto,
        string $tipoPaciente,
        string $tipoNotificacion,
        string $destino
    ): void {

        // ---- 1. Validacion ----
        if (trim($paciente) === '') {
            echo 'Falta el paciente<br>';
            return;
        }

        if ($monto <= 0) {
            echo 'Monto invalido<br>';
            return;
        }

        // ---- 2. Calculo de precio ----
        // Esta parte sera refactorizada por otro integrante.
        $total = $monto;

        if ($tipoPaciente === 'obra_social') {
            $total = $monto * 0.7;
        } elseif ($tipoPaciente === 'jubilado') {
            $total = $monto * 0.5;
        }

        // ---- 3. Persistencia mediante Repository ----
        $order = new Order(
            $id,
            $paciente,
            $total,
            $tipoPaciente
        );

        // ✅ PATRON REPOSITORY
        // El repositorio se encarga de guardar el pedido.
        (new OrderRepository())->save($order);

        // ---- 4. Notificacion mediante Factory ----
        (new NotificationSender())->enviar(
            $tipoNotificacion,
            $destino,
            "Pedido {$id} por $ {$total}"
        );

        // ---- 5. Avisos internos mediante Observer ----
        $eventos = new OrderEvents();

        $eventos->attach(new EmailObserver());
        $eventos->attach(new SmsObserver());
        $eventos->attach(new DashboardObserver());

        $eventos->notify($order);

        // ---- 6. Reporte mediante Decorator ----
        $reporte = new PdfReportDecorator(
            new DigitalSignatureDecorator(
                new BasicReport("Pedido {$id}")
            )
        );

        echo $reporte->generate() . '<br>';

        // ---- 7. Presentacion ----
        echo "<p>Pedido {$id} procesado. Total: $ {$total}</p>";
    }
}

/**
 * ============================================================================
 * ✅ FORMA CORRECTA — FACADE SOBRE SERVICIOS COHESIVOS
 * ============================================================================
 *
 * final class OrderFacade
 * {
 *     public function __construct(
 *         private OrderValidator $validator,
 *         private PriceCalculator $calculator,
 *         private OrderRepository $repository,
 *         private OrderSubject $events
 *     ) {}
 *
 *     public function createOrder(Order $order): void
 *     {
 *         $this->validator->validate($order);
 *         $total = $this->calculator->calculate($order->amount);
 *         $this->repository->save($order);
 *         $this->events->notify($order);
 *     }
 * }
 *
 * El controlador queda en una linea:
 * $facade->createOrder($order);
 *
 * Estructura:
 * Controller -> OrderFacade -> Validator | Pricing | Repository | Events
 *
 * EJERCICIO 6 (TP):
 * Dejar procesarPedidoCompleto() en menos de 10 lineas
 * sin perder funcionalidad.
 *
 * PREGUNTA PARA EL PR:
 * Facade elimina la complejidad o la mueve de lugar?
 * ============================================================================
 */
