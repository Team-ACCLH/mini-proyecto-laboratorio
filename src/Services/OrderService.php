<?php

namespace App\Services;
use App\Pricing\PriceCalculator; 
use App\Models\OrderRepository;
use App\Notifications\NotificationSender; 
use App\Events\OrderEvents;

/**
 * ============================================================================
 *  SERVICIO DE PEDIDOS
 *  Patron esperado: FACADE (estructural) + separacion en servicios cohesivos
 * ============================================================================
 *
 *  ❌ DEUDA SEMBRADA
 *     Un unico metodo procesarPedidoCompleto() que hace de todo:
 *     valida, calcula, cobra, guarda, notifica, reporta y ademas imprime HTML.
 *     Es la clase que nadie quiere tocar: cualquier cambio puede romper otra cosa.
 *     Ademas repite el if por tipo de notificacion que ya existe en NotificationSender.
 *
 *  ✅ FORMA CORRECTA
 *     1. Cada paso en su propio servicio cohesivo (validacion, precio, pago...).
 *     2. Una fachada que ORQUESTA esos servicios en un metodo corto y legible.
 *     3. Las dependencias se inyectan por constructor, no se crean con new adentro.
 *
 *  ⚠️ Una fachada COORDINA. Si empieza a DECIDIR reglas de negocio, se convierte
 *     en la nueva clase que hace todo y volvimos al punto de partida.
 * ============================================================================
 */

/** 
 *class OrderService (fachada)
 *
 * Cumple con el pátron Facade y el principio de responsabilidad única (SRP):
 * Coordina la creación de un pedido, delegando tareas a servicios especializados.
 * para procesar un pedido en menos de 10 líneas de código.
 */

class OrderService
{

    public function __construct(
        private PriceCalculator $priceCalculator,
        private OrderRepository $orderRepository,
        private NotificationSender $notificationSender,
        private OrderEvents $orderEvents
    ) {}

/**
 * Procesa un pedido completo orquestando las capas correspondientes.
 */

public function processOrder(Order $order): void
{
    // 1. Validar reglas de negocio del pedido
    $this->validator->validate($order);

    // 2. Calcular el total final aplicando descuentos o recargos.
    $order->total = $this->calculator->calculate($order->$amount, $order->type);

    // 3. Persistir la orden en la base de datos a través del repositorio.
    $this->orderRepository->save($order);

    // 4. Notificar y disparar eventos del sistema
    $this->orderEvents->notify($order);
}
}



/**
 * PREGUNTA PARA EL PR: Facade elimina la complejidad o la mueve de lugar?
 */
