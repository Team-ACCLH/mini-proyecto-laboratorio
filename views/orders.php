<?php
/**
 * VISTA: listado de pedidos
 *
 * La vista recibe los pedidos ya preparados desde el controlador.
 * No consulta datos ni calcula reglas de negocio.
 * La salida se escapa antes de mostrarse.
 */
?>

<h1>Pedidos</h1>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Paciente</th>
        <th>Total</th>
    </tr>

    <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <td><?= htmlspecialchars((string) $pedido['id'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($pedido['paciente'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string) $pedido['total'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
    <?php endforeach; ?>
</table>