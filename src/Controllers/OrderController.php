<?php
/**
 * ============================================================================
 *  CONTROLADOR DE PEDIDOS
 *  Patron: MVC (arquitectonico) + SRP
 * ============================================================================
 *
 *  ✅ El controlador solo: recibe la entrada, llama a UN servicio
 *     y elige que vista renderizar. Sin SQL, sin reglas de negocio y sin echo.
 * ============================================================================
 */

class OrderController
{
    public function __construct(
        private OrderService $service = new OrderService()
    ) {}

    public function create(): void
    {
        $order = new Order(
            (int) ($_GET['id'] ?? 1),
            (string) ($_GET['paciente'] ?? 'Juan Perez'),
            (float) ($_GET['monto'] ?? 15000),
            (string) ($_GET['tipo'] ?? 'obra_social')
        );
        $total = $this->service->crearPedido($order);

        require __DIR__ . '/../../views/order_created.php';
    }

    public function index(): void
    {
        require __DIR__ . '/../../views/orders.php';
    }

    /**
     * ✅ report(): el reporte se arma encadenando decoradores,
     *    sin banderas booleanas.
     */
    public function report(): void
    {
        // Decorator: cada agregado envuelve al anterior. El orden importa.
        $reporte = new WatermarkDecorator(
            new PdfReportDecorator(
                new DigitalSignatureDecorator(new BasicReport('Pedidos del dia'))
            )
        );
        echo $reporte->generate();
    }
}
