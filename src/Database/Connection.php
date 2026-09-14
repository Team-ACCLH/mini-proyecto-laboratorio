<?php
/**
 * ============================================================================
 *  CONEXION A LA BASE DE DATOS REFACTORIZADA
 *  Patrón: SINGLETON (Creacional)
 * ============================================================================
 */

class Connection
{
    // Almacenamos la instancia única aquí adentro
    private static ?Connection $instancia = null;
    private static array $tablaEnMemoria = [];

    // ✅ REPARADO: El constructor es PRIVADO. Nadie puede hacer "new Connection()" afuera.
    private function __construct() {}

    /**
     * ✅ REPARADO: Punto de acceso global unificado
     */
    public static function obtener(array $config = []): Connection
    {
        if (self::$instancia === null) {
            self::$instancia = new Connection();
        }
        return self::$instancia;
    }

    /**
     * ✅ REPARADO: Fallar rápido y lanzar excepciones en lugar de silenciarlas
     */
    public function ejecutar(string $sql): void
    {
        try {
            if (empty($sql)) {
                throw new InvalidArgumentException("La sentencia SQL no puede estar vacía.");
            }
            self::$tablaEnMemoria[] = $sql;
        } catch (Throwable $e) {
            error_log("Error crítico de BD: " . $e->getMessage());
            throw new RuntimeException("Error al ejecutar operación en la base de datos", 0, $e);
        }
    }

    public function consultas(): array
    {
        return self::$tablaEnMemoria;
    }
}
