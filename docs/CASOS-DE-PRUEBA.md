# Casos de prueba

## Adapter: LegacyNotifierAdapter

| ID | Descripcion | Precondiciones | Datos de entrada | Pasos | Resultado esperado | Tipo | Prueba automatizada | Estado |
|---|---|---|---|---|---|---|---|---|
| CP-10 | El adapter cumple el contrato Notification | Autoload cargado | LegacyNotifier real | 1. Crear el adapter 2. Verificar su tipo | Es instancia de Notification | Positivo | LegacyNotifierAdapterTest::testElAdapterImplementaNotification | Pasa |
| CP-11 | send() muestra el mensaje con formato legacy | Autoload cargado | "Pedido 1 creado" | 1. Crear el adapter 2. Llamar send() | Imprime "[LEGACY] Pedido 1 creado<br>" | Positivo | LegacyNotifierAdapterTest::testSendMuestraElMensajeConFormatoLegacy | Pasa |
| CP-12 | send() delega en sendMessage() | Mock de LegacyNotifier | "hola" | 1. Crear mock 2. Crear adapter con el mock 3. Llamar send() | sendMessage() recibe "hola" una sola vez | Positivo | LegacyNotifierAdapterTest::testSendDelegaEnSendMessage | Pasa |
| CP-13 | Mensaje vacio no rompe el adapter | Autoload cargado | "" (vacio) | 1. Crear el adapter 2. Llamar send("") | Imprime "[LEGACY] <br>" sin error | Negativo | LegacyNotifierAdapterTest::testMensajeVacioNoLanzaError | Pasa |
| CP-14 | Rechaza un objeto que no es LegacyNotifier | Autoload cargado | new stdClass() | 1. Crear el adapter con stdClass | Lanza TypeError | Negativo | LegacyNotifierAdapterTest::testNoAceptaOtroObjetoQueNoSeaLegacyNotifier | Pasa |

## Decorator: reportes

| ID | Descripcion | Precondiciones | Datos de entrada | Pasos | Resultado esperado | Tipo | Prueba automatizada | Estado |
|---|---|---|---|---|---|---|---|---|
| CP-15 | Reporte base sin decorar | Autoload cargado | "Pedidos del dia" | 1. Crear BasicReport 2. Llamar generate() | "Reporte: Pedidos del dia" | Positivo | ReportDecoratorTest::testReporteBasicoSinDecorar | Pasa |
| CP-16 | Un decorador agrega su parte | Autoload cargado | "Pedidos del dia" | 1. Envolver en PdfReportDecorator 2. Llamar generate() | "Reporte: Pedidos del dia + PDF" | Positivo | ReportDecoratorTest::testUnDecoradorAgregaSuParte | Pasa |
| CP-17 | Cadena completa del controlador | Autoload cargado | "Pedidos del dia" | 1. Aplicar firma, PDF y marca de agua 2. Llamar generate() | "... + firma digital + PDF + marca de agua" | Positivo | ReportDecoratorTest::testCadenaCompletaDelControlador | Pasa |
| CP-18 | El orden de los decoradores cambia el resultado | Autoload cargado | "X" | 1. Firma y luego PDF 2. PDF y luego firma 3. Comparar | Los dos textos son distintos | Negativo | ReportDecoratorTest::testElOrdenDeLosDecoradoresCambiaElResultado | Pasa |
| CP-19 | Un decorador rechaza algo que no es Report | Autoload cargado | new stdClass() | 1. Crear WatermarkDecorator con stdClass | Lanza TypeError | Negativo | ReportDecoratorTest::testUnDecoradorNoAceptaAlgoQueNoSeaReport | Pasa |

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

## Repository: OrderRepository

| ID | Descripcion | Precondiciones | Datos de entrada | Pasos | Resultado esperado | Tipo | Prueba automatizada | Estado |
|---|---|---|---|---|---|---|---|---|
| CP-30 | save() registra el INSERT del pedido | Connection en memoria | Order(9, "Test", 100, particular) | 1. Contar consultas 2. save() 3. Leer la ultima | Una consulta mas; INSERT con id 9 | Positivo | OrderRepositoryTest::testSaveRegistraLaConsultaDeInsert | Pasa |
| CP-31 | all() devuelve objetos Order | Datos de ejemplo en memoria | Ninguno | 1. all() | 2 elementos, todos Order; el primero es Juan Perez | Positivo | OrderRepositoryTest::testAllDevuelveLosPedidosComoObjetosOrder | Pasa |
| CP-32 | Nombre con comilla queda sin escapar | Connection en memoria | paciente = "O'Brien" | 1. save() 2. Leer la ultima consulta | La consulta contiene 'O'Brien' (riesgo de inyeccion SQL, registrado como deuda) | Negativo | OrderRepositoryTest::testNombreConComillaQuedaSinEscaparEnLaConsulta | Pasa |

## Observer: OrderEvents y observadores

| ID | Descripcion | Precondiciones | Datos de entrada | Pasos | Resultado esperado | Tipo | Prueba automatizada | Estado |
|---|---|---|---|---|---|---|---|---|
| CP-33 | Un observador suscripto recibe el pedido | Observador espia | Order 5 | 1. attach() 2. notify() | Recibe 1 aviso con el mismo pedido | Positivo | OrderEventsTest::testObservadorSuscriptoRecibeElPedido | Pasa |
| CP-34 | Todos los observadores reciben el aviso | Dos espias | Order 6 | 1. attach() x2 2. notify() | Cada uno recibe 1 aviso | Positivo | OrderEventsTest::testTodosLosObservadoresRecibenElAviso | Pasa |
| CP-35 | Notificar sin observadores no falla | Ninguna | Order 7 | 1. notify() sin attach | No lanza error | Negativo | OrderEventsTest::testNotificarSinObservadoresNoFalla | Pasa |
| CP-36 | El dashboard muestra el aviso | Ninguna | Order 5 | 1. DashboardObserver::update() | "[DASHBOARD] actualizado para el pedido 5" | Positivo | OrderEventsTest::testDashboardObserverMuestraElAviso | Pasa |

## Factory: NotificationFactory

| ID | Descripcion | Precondiciones | Datos de entrada | Pasos | Resultado esperado | Tipo | Prueba automatizada | Estado |
|---|---|---|---|---|---|---|---|---|
| CP-40 | "email" crea un EmailChannel | Autoload cargado | tipo = "email" | 1. make("email") | EmailChannel que cumple NotificationChannel | Positivo | NotificationFactoryTest::testEmailDevuelveEmailChannel | Pasa |
| CP-41 | "sms" crea un SmsChannel | Autoload cargado | tipo = "sms" | 1. make("sms") | SmsChannel | Positivo | NotificationFactoryTest::testSmsDevuelveSmsChannel | Pasa |
| CP-42 | Canal no soportado se rechaza | Autoload cargado | tipo = "whatsapp" | 1. make("whatsapp") | InvalidArgumentException: "El canal [whatsapp] no esta soportado." | Negativo | NotificationFactoryTest::testCanalDesconocidoLanzaExcepcion | Pasa |
| CP-43 | El tipo distingue mayusculas | Autoload cargado | tipo = "EMAIL" | 1. make("EMAIL") | InvalidArgumentException | Negativo | NotificationFactoryTest::testTipoEnMayusculasSeRechaza | Pasa |
| CP-44 | El sender envia por el canal pedido | Autoload cargado | "sms", "3704000000", "Hola" | 1. NotificationSender::enviar() | `[SMS] a 3704000000: Hola<br>` | Positivo | NotificationFactoryTest::testSenderEnviaPorElCanalPedido | Pasa |

## Singleton: Connection

| ID | Descripcion | Precondiciones | Datos de entrada | Pasos | Resultado esperado | Tipo | Prueba automatizada | Estado |
|---|---|---|---|---|---|---|---|---|
| CP-45 | Siempre la misma instancia | Ninguna | Ninguno | 1. getInstance() dos veces 2. Comparar | Es el mismo objeto | Positivo | ConnectionTest::testGetInstanceDevuelveSiempreLaMismaInstancia | Pasa |
| CP-46 | El constructor es privado | Ninguna | Ninguno | 1. Inspeccionar __construct con Reflection | isPrivate() es verdadero | Positivo | ConnectionTest::testElConstructorEsPrivado | Pasa |
| CP-47 | No se puede crear con new | Ninguna | Ninguno | 1. new Connection() | Lanza Error por constructor privado | Negativo | ConnectionTest::testNoSePuedeCrearConNew | Pasa |
| CP-48 | La consulta se ve desde cualquier referencia | Ninguna | "SELECT 1" | 1. Contar consultas 2. ejecutar() desde otra referencia 3. Volver a contar | Una consulta mas, la ultima es "SELECT 1" | Positivo | ConnectionTest::testLaConsultaEjecutadaSeVeDesdeCualquierReferencia | Pasa |

