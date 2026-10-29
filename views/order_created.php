<?php
/**
 * VISTA: pedido creado.
 * Recibe $order y $total ya resueltos por el controlador. Solo muestra, escapando.
 */
?>
<h1>Pedido creado</h1><p>Paciente: <?= htmlspecialchars($order->patient, ENT_QUOTES, 'UTF-8') ?></p><p>Total: $ <?= htmlspecialchars((string) $total, ENT_QUOTES, 'UTF-8') ?></p>