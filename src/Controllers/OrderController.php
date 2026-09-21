<?php

namespace App\Controllers; 

use App\Models\Order; 
use App\Services\OrderService;

/**
 * ============================================================================
 *  CONTROLADOR DE PEDIDOS
 *  Patron esperado: MVC bien aplicado (arquitectonico) + SRP
 * ============================================================================
 *
 *  ❌ DEUDA SEMBRADA — el "God Controller"
 *     1. Arma SQL directamente.
 *     2. Contiene reglas de negocio (descuentos).
 *     3. Imprime HTML con echo.
 *     4. Crea con new todas sus dependencias concretas.
 *     5. Repite por TERCERA vez el if de notificaciones.
 *
 *  Tener las carpetas /Controllers /Models /views NO significa aplicar MVC.
 *  MVC es separacion de responsabilidades, no estructura de directorios.
 *
 *  ✅ FORMA CORRECTA
 *     El controlador solo: recibe la entrada, la valida como entrada,
 *     llama a UN servicio o fachada, y elige que vista renderizar.
 * ============================================================================
 */

/**
 * Clase OrderController (MVC)
 * 
 * Única responsabilidad: Atender la petición HTTP, validar la entrada,
 * instanciar el modelo/fachada y redirigir a la vista correspondiente.
 */


Class OrderController
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Procesa la creación de un pedido completo, delegando la lógica a la fachada.
     */
    public function create(): void
    {
        // 1. Capturar y sanitizar entradas de formulario HTTP.
        $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        // Si los datos son inválidos, ridirige con mensaje de error.
        if (!$amount || !$type || !$email) {
            header('Location: /index.php?action=create&error=invalid_data');
            exit;
        }

        try {
        // 2. Crear la entidad de dominio con los datos recibidos.
        $order = new Order(amount: $amount, type: $type, userEmail: $email);
        // 3. Delegar todo el procesamiento a la Fachada (En una sola línea).
        $this->orderService->processOrder($order);
        // 4. Redirigir a la vista de éxito.
        header('Location: /index.php?action=list&status=success');
        exit;

        } catch (Exception $e) {
            // Manejo limpio de excepciones redirigiendo a la vista.
            header('Location: /index.php?action=create&error=' . urlencode($e->getMessage()));
            exit;
        }
    }
}

/*
 * ----------------------------------------------------------------------------
 * EJERCICIO 7 (TP): dejar create() con un maximo de 5 lineas ejecutables,
 * sin SQL, sin reglas de negocio y sin echo.
 *
 * PREGUNTA PARA EL PR: cuales de los 5 problemas de este archivo son deuda
 * de DISEÑO (Unidad 2) y cuales son deuda de PROCESO (Unidad 1)?
 * ----------------------------------------------------------------------------
 */


