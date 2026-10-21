# Registro Formal de Deuda Técnica — Grupo UTN

| Síntoma observado | Evidencia (archivo:línea) | Tipo de deuda | Patrón aplicado | Consecuencia asumida |
|---|---|---|---|---|
| Requires manuales redundantes en el punto de entrada | `public/index.php:24` | Proceso (Unidad 1) | Autoloading dinámico | Sobrecarga mínima en el sistema de archivos al comprobar rutas. |
| Credenciales de base de datos expuestas en el repositorio | `public/index.php:47` | Proceso (Unidad 1) | Configuración Externa | El equipo debe gestionar y coordinar plantillas `.example` a mano. |
| Estructura switch condicional para calcular tarifas comerciales | `PriceCalculator.php:25` | Diseño (Unidad 2) | Strategy | Incremento en la cantidad de archivos físicos dentro del proyecto. |
| Conexión nueva abierta en el servidor por cada consulta SQL ejecutada | `Connection.php:35` | Diseño (Unidad 2) | Singleton | Introduce un estado global en memoria difícil de mockear en tests. |
| Bloques condicionales if repetidos por tipo de aviso en 3 archivos | `NotificationSender.php:28` | Diseño (Unidad 2) | Factory | Acoplamiento indirecto hacia una firma o contrato global estricto. |
| El controlador maneja consultas de datos e imprime HTML directo | `OrderController.php:37` | Diseño (Unidad 2) | MVC + Facade | Mayor fraccionamiento de código para resolver flujos muy simples. |
