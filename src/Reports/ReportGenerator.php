<?php
/**
 * ============================================================================
 *  GENERACION DE REPORTES
 *  Patron: DECORATOR (estructural)
 * ============================================================================
 *
 *  El boolean trap (generate con 3 parámetros bool) fue eliminado.
 *  Ahora se usan decoradores combinables:
 *
 *    $reporte = new BasicReport('Pedido 1');
 *    $reporte = new DigitalSignatureDecorator($reporte);
 *    $reporte = new PdfReportDecorator($reporte);
 *    echo $reporte->generate();
 *
 *  Consecuencia: el orden de decoradores cambia el resultado.
 *  Firmar → PDF no es lo mismo que PDF → firmar.
 * ============================================================================
 */

class ReportGenerator
{
    /**
     * Genera un reporte simple sin decoradores.
     * Para usar decoradores, crear la cadena directamente.
     */
    public function generate(string $contenido): string
    {
        $reporte = new BasicReport($contenido);
        return $reporte->generate();
    }
}
