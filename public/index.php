<?php
/**
 * ============================================================================
 *  laboratorio-pedidos — PUNTO DE ENTRADA
 *  Metodología de Sistemas II — UTN FRRe (sede Formosa)
 * ============================================================================
 *
 *  ❌ DEUDA SEMBRADA EN ESTE ARCHIVO
 *     1. Carga manual de dependencias con require (se rompe al agregar clases).
 *     2. Credenciales de base de datos escritas en el código y versionadas.
 *     3. Ruteo resuelto con una cadena de if que crece con cada pantalla.
 *
 *  ✅ FORMA CORRECTA
 *     1. spl_autoload_register() o Composer con PSR-4.
 *     2. Configuración en /config/database.php, fuera del repositorio (.gitignore).
 *     3. Un mapa de rutas (array ruta => callable).
 *
 *  Unidad 1: los puntos 2 y 3 también son deuda de PROCESO, no solo de diseño.
 * ============================================================================
 */


✅ FORMA CORRECTA (reemplaza a los 11 require de arriba):

 spl_autoload_register(function (string $class): void {
     $file = __DIR__ . '/../src/' . str_replace('\\', '/', $class) . '.php';
     if (is_file($file)) {
         require_once $file;
     }
 });


 ✅ FORMA CORRECTA:
$config = require __DIR__ . '/../config/database.php';   
   // ignorado por git  El repositorio versiona config/database.example.php con valores vacíos.
$accion = $_GET['accion'] ?? 'crear';




// ❌ MAL APLICADO: ruteo con if encadenados. Viola Abierto/Cerrado:
//    cada pantalla nueva obliga a MODIFICAR este bloque.
$controller = new OrderController();

if ($accion === 'crear') {
    $controller->create();
} elseif ($accion === 'listar') {
    $controller->index();
} elseif ($accion === 'reporte') {
    $controller->report();
} else {
    echo 'Accion no encontrada';
}

/*
 * ✅ FORMA CORRECTA: tabla de rutas. Agregar una pantalla ya no modifica el if.
 *
 * $rutas = [
 *     'crear'   => fn() => $controller->create(),
 *     'listar'  => fn() => $controller->index(),
 *     'reporte' => fn() => $controller->report(),
 * ];
 * ($rutas[$accion] ?? fn() => http_response_code(404))();
 */
