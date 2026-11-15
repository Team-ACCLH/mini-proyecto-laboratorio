# laboratorio-pedidos — proyecto de práctica
[![CI](https://github.com/Team-ACCLH/mini-proyecto-laboratorio/actions/workflows/ci.yml/badge.svg)](https://github.com/Team-ACCLH/mini-proyecto-laboratorio/actions/workflows/ci.yml)

Sistema de gestión de pedidos de un laboratorio de análisis clínicos.
**PHP Vanilla, sin frameworks.** Corre en XAMPP tal cual está. Composer se usa solo para las
herramientas de desarrollo (pruebas y estilo).

> ⚠️ **Este proyecto está roto a propósito.**
> Cada archivo contiene deuda técnica sembrada, marcada con comentarios:
>
> ```php
> // ❌ MAL APLICADO: qué método está mal y qué principio viola
> // ✅ FORMA CORRECTA: qué patrón corresponde y cómo se estructura
> ```
>
> El objetivo no es que funcione mejor: ya funciona. El objetivo es que
> **se pueda cambiar sin miedo**.

---

## Cómo levantarlo

1. Copiar la carpeta dentro de `C:\xampp\htdocs\`.
2. Iniciar Apache desde el panel de XAMPP (MySQL **no** hace falta).
3. Abrir: `http://localhost/mini-proyecto-laboratorio/public/index.php`

Acciones disponibles:

| URL | Qué hace |
|---|---|
| `public/index.php?accion=crear` | Crea un pedido pasando por toda la deuda |
| `public/index.php?accion=listar` | Lista pedidos desde la vista |
| `public/index.php?accion=reporte` | Genera un reporte con banderas booleanas |

La persistencia está simulada en memoria para que el proyecto arranque sin
configurar MySQL. Eso **no** es parte de la deuda a corregir.

---

## Pruebas y CI

Requisitos: PHP 8.2 o superior y Composer.

```bash
composer install   # instala PHPUnit y PHP_CodeSniffer en vendor/
composer test      # corre las pruebas unitarias (tests/Unit)
composer lint      # revisa el estilo PSR-12
```

- **Pruebas:** PHPUnit 11.5, una clase de prueba por patrón refactorizado.
- **Casos de prueba documentados:** [`docs/CASOS-DE-PRUEBA.md`](docs/CASOS-DE-PRUEBA.md)
  (ID, precondiciones, datos, pasos, resultado esperado; positivos y negativos).
- **Integración continua:** GitHub Actions ([`.github/workflows/ci.yml`](.github/workflows/ci.yml))
  corre en cada push y en cada Pull Request: instala dependencias, ejecuta el lint y las pruebas.
- **Ramas protegidas:** `develop` y `main` solo aceptan cambios por Pull Request con 1 aprobación
  y el check `calidad` en verde.
  
---

## Mapa de deudas

| Archivo | Síntoma sembrado | Patrón / principio | Ejercicio |
|---|---|---|---|
| `public/index.php` | Requires manuales, credenciales en el código, ruteo con `if` | Autoload + config externa + tabla de rutas | — |
| `src/Database/Connection.php` | Una conexión nueva por consulta; excepción silenciada | **Singleton** | — |
| `src/Notifications/NotificationSender.php` | `if` por tipo repetido en 3 archivos | **Factory** | Ej. 2 |
| `src/Pricing/PriceCalculator.php` | `switch` con todos los algoritmos + lógica duplicada | **Strategy** | Ej. 1 |
| `src/Events/OrderEvents.php` | Avisos encadenados a mano a clases concretas | **Observer** | Ej. 5 |
| `src/Legacy/LegacyNotifier.php` | Clase de terceros modificada + copia y pega | **Adapter** | Ej. 3 |
| `src/Reports/ReportGenerator.php` | Parámetros booleanos (`boolean trap`) | **Decorator** | Ej. 4 |
| `src/Services/OrderService.php` | Método que hace de todo | **Facade** + SRP | Ej. 6 |
| `src/Controllers/OrderController.php` | SQL + negocio + HTML en el controlador | **MVC** + SRP | Ej. 7 |
| `src/Models/Order.php` | Modelo que se persiste y calcula precios | **Repository** + Strategy | — |
| `views/orders.php` | Consulta, calcula y no escapa la salida | **MVC** | Ej. 8 |

---

## La medida de la deuda de este proyecto

El descuento de obra social (**0.7**) está escrito en **cinco archivos distintos**:

```
src/Models/Order.php
src/Pricing/PriceCalculator.php   (dos veces)
src/Services/OrderService.php
src/Controllers/OrderController.php
views/orders.php
```

Cuando el laboratorio lo cambie al 25%, ese número es exactamente
cuántos lugares hay que tocar y cuántas oportunidades hay de olvidarse uno.

**Ese es el punto de toda la unidad.**

---

## Mecánica de la clase práctica

```
1. Cada grupo toma UN archivo del mapa de deudas.       (5 min)
2. Lee los comentarios ❌ y ✅.                          (5 min)
3. Refactoriza en una rama propia: feat/patron-<nombre>  (20 min)
4. Abre un Pull Request que responda tres cosas:
     - qué deuda encontró (con la línea exacta)
     - qué patrón aplicó y por qué ese y no otro
     - qué consecuencia negativa tiene su propia solución
5. Otro grupo revisa el PR y comenta.                    (10 min)
```

El PR revisado por otro grupo es evidencia del TP Integrador.

---

## Reglas del refactor

- **No se rompe funcionalidad.** Antes y después, la app hace lo mismo.
- **Un patrón por rama.** Nada de una rama con seis cambios mezclados.
- **Se borra el código viejo.** Dejar el método anterior "por las dudas" es
  deuda nueva.
- **Se documenta la consecuencia negativa.** Un patrón sin contras analizadas
  es sobreingeniería esperando su turno.

---

## Contributing

| Integrante | Ejercicios | Ramas |
|---|---|---|
| Mauricio Cardozo | Ej. 3 (Adapter), Ej. 4 (Decorator) | `feat/patron-adapter`, `feat/patron-decorator` |
