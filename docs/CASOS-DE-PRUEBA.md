# Casos de prueba

## Strategy: calculo de precios

| ID | Descripcion | Precondiciones | Datos de entrada | Pasos | Resultado esperado | Tipo | Prueba automatizada | Estado |
|---|---|---|---|---|---|---|---|---|
| CP-20 | Obra social aplica su descuento | Autoload cargado | 15000; descuento 0.30 y 0.25 | 1. Crear InsuranceStrategy 2. calculate(15000) | 10500 con 30%; 11250 con 25% | Positivo | PricingStrategyTest::testObraSocialAplicaSuDescuento | Pasa |
| CP-21 | Jubilado, prepaga y particular aplican su regla | Autoload cargado | 10000 | 1. Crear cada estrategia 2. calculate(10000) | 5000 / 8000 / 10000 | Positivo | PricingStrategyTest::testLasDemasEstrategiasAplicanSuRegla | Pasa |
| CP-22 | La calculadora usa la estrategia y suma IVA | Autoload cargado | 15000, obra social | 1. PriceCalculator con InsuranceStrategy 2. calculateWithIva(15000) | 12705 | Positivo | PricingStrategyTest::testCalculadoraConIvaUsaLaEstrategiaInyectada | Pasa |
| CP-23 | Monto cero da cero | Autoload cargado | 0 | 1. calculateWithIva(0) | 0 | Negativo | PricingStrategyTest::testMontoCeroDaCero | Pasa |
| CP-24 | Cada tipo de paciente usa su estrategia | Autoload cargado | obra_social, jubilado, particular | 1. OrderPricing::total() con cada tipo | 10500 / 5000 / 22000 | Positivo | OrderPricingTest::testTiposConocidosUsanSuEstrategia | Pasa |
| CP-25 | Tipo desconocido cobra precio completo | Autoload cargado | 15000, "tipo_inexistente" | 1. OrderPricing::total() | 15000 | Negativo | OrderPricingTest::testTipoDesconocidoCobraPrecioCompleto | Pasa |

## Facade: OrderService

| ID | Descripcion | Precondiciones | Datos de entrada | Pasos | Resultado esperado | Tipo | Prueba automatizada | Estado |
|---|---|---|---|---|---|---|---|---|
| CP-26 | Listar pedidos con total calculado | Repositorio con 2 pedidos en memoria | Ninguno | 1. listarPedidos() | 2 pedidos; totales 10500 y 22000 | Positivo | OrderServiceTest::testListarPedidosDevuelveTotalesCalculados | Pasa |
| CP-27 | Crear pedido devuelve total y envia email | Autoload cargado | Order(10, "Ana Lopez", 15000, obra_social) | 1. crearPedido() | Devuelve 10500 e imprime el email del pedido 10 | Positivo | OrderServiceTest::testCrearPedidoDevuelveTotalYEnviaEmail | Pasa |
| CP-28 | Pedido sin paciente se rechaza | Autoload cargado | paciente = "" | 1. procesarPedidoCompleto() | Imprime "Falta el paciente" y nada mas | Negativo | OrderServiceTest::testProcesarSinPacienteMuestraErrorYNoSigue | Pasa |
| CP-29 | Pedido con monto negativo se rechaza | Autoload cargado | monto = -100 | 1. procesarPedidoCompleto() | Imprime "Monto invalido" | Negativo | OrderServiceTest::testProcesarConMontoNegativoMuestraError | Pasa |
