#!/usr/bin/env bash
# ============================================================================
#  Verificacion "antes y despues" del refactor
# ============================================================================
#  Regla del TP: "No se rompe funcionalidad. Antes y despues, la app hace lo mismo."
#
#  Este script:
#    1. Revisa que todos los .php no tengan errores de sintaxis.
#    2. Ejecuta las acciones crear, listar, reporte y una inexistente,
#       y compara la salida con la del proyecto original (tests/esperado/).
#    3. Informa en cuantos lugares sigue escrito el descuento 0.7.
#
#  Uso (desde cualquier carpeta):   bash tests/verificar.sh
#  Devuelve 0 si todo coincide y 1 si algo fallo.
# ============================================================================

RAIZ="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ESPERADO="$RAIZ/tests/esperado"
FALLAS=0

if ! command -v php >/dev/null 2>&1; then
    echo "No se encontro php. En Ubuntu/Debian: sudo apt install php-cli"
    exit 1
fi

echo "== 1. Sintaxis de los archivos PHP"
ERRORES=$(find "$RAIZ/public" "$RAIZ/src" "$RAIZ/views" -name '*.php' \
    -exec php -l {} \; 2>&1 | grep -v '^No syntax errors')
if [ -z "$ERRORES" ]; then
    echo "   OK  ningun error de sintaxis"
else
    echo "$ERRORES" | sed 's/^/   /'
    FALLAS=$((FALLAS + 1))
fi

echo "== 2. Salida de cada accion comparada con el proyecto original"
for ACCION in crear listar reporte inexistente; do
    # Se ejecuta index.php como si el navegador pidiera ?accion=<ACCION>.
    SALIDA=$(cd "$RAIZ/public" && php -r '$_GET["accion"] = $argv[1]; include "index.php";' "$ACCION" 2>&1)

    # Los espacios y saltos de linea del HTML no se ven en el navegador,
    # asi que se ignoran al comparar.
    if [ "$(printf '%s' "$SALIDA" | tr -d '[:space:]')" = "$(tr -d '[:space:]' < "$ESPERADO/$ACCION.html")" ]; then
        echo "   OK     ?accion=$ACCION"
    else
        echo "   FALLA  ?accion=$ACCION"
        echo "          esperado: $(head -c 200 "$ESPERADO/$ACCION.html" | tr -s '[:space:]' ' ')"
        echo "          obtenido: $(printf '%s' "$SALIDA" | head -c 200 | tr -s '[:space:]' ' ')"
        FALLAS=$((FALLAS + 1))
    fi
done

echo "== 3. Lugares donde sigue escrito el 0.7 (la medida de la deuda)"
LUGARES=$(grep -rn --include='*.php' '0\.7' "$RAIZ/src" "$RAIZ/views" | grep -v ':[0-9]*:[[:space:]]*\*')
if [ -z "$LUGARES" ]; then
    echo "   Ninguno: el descuento vive en un solo lugar (InsuranceStrategy)."
else
    echo "$LUGARES" | sed "s|$RAIZ/|   |"
fi

echo
if [ "$FALLAS" -eq 0 ]; then
    echo "RESULTADO: OK, la app hace lo mismo que el proyecto original."
    exit 0
fi
echo "RESULTADO: $FALLAS verificacion(es) fallaron. No mergear hasta corregirlo."
exit 1
