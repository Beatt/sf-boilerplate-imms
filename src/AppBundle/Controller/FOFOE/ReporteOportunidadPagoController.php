<?php

namespace AppBundle\Controller\FOFOE;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Util\CVSUtil;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReporteOportunidadPagoController extends DIEControllerController
{
  /**
   * @Route("/fofoe/reporte_oportunidad_pago", methods={"GET"}, name="fofoe.reporte_oportunidad")
   * @param Request $request
   * @param PagoRepositoryInterface $reporteRepository
   * @return Response
   */
  public function indexAction(Request $request, PagoRepositoryInterface $reporteRepository) {

    list($filtros, $isSomeValueSet) = $this->setFilters($request);
    $pagos = $reporteRepository->getReporteOportunidadPago($filtros);
    $datos = $this->getNormalizePagos($pagos);

    if ($isSomeValueSet) {

      if (isset($filtros['export']) && $filtros['export']) {
        $responseCVS = new Response(
          "\xEF\xBB\xBF".
          $this->generarCVS(
            $this->getNormalizePagos($datos)
          ) );
        $today = date('Y-m-d');
        $filename = "exportReporteOportunidadPago\_$today.csv";

        $responseCVS->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $responseCVS->headers->set("Content-Disposition", "attachment; filename=\"$filename\"");

        return $responseCVS;
      }

      return new JsonResponse([
        'reporte' => $datos
      ]);
    }

    return $this->render('fofoe/reporte_oportunidad/index.html.twig',
      array('pagos' => $datos));
  }

  protected function generarCVS($datos) {
    $cvs = [];

    $headersCVS = [
      'Consecutivo', 'Delegación', 'Campo Clínico', 'Carrera',
      'Inicio', 'Fin', 'Institución', 'Alumnos', 'Importe',
      'Referencia', 'Fecha depósito', 'Fecha facturación',
      'Indicador', 'Días'
    ];
    $cvs[] = CVSUtil::arrayToCsvLine($headersCVS);

    $totalVal = 0;
    $totalPend = 0;
    $indexRow = 0;

    foreach($datos as $pago) {
      foreach ($pago['camposPagados']['campos'] as $campo)
      $cvs[] = CVSUtil::arrayToCsvLine(
        [ ++$indexRow, $campo['displayDelegacion'],
          $campo['displayCicloAcademico'],
          $campo['displayCarrera'],
          $campo['fechaInicialFormatted'],
          $campo['fechaFinalFormatted'],
          $pago['solicitud']['institucion']['nombre'],
          $campo['lugaresAutorizados'],
          $campo['monto'],
          $pago['referenciaBancaria'],
          $pago['fechaPagoFormatted'],
          "",
          $pago['camposPagados']['tiempos'][$campo['id']] >= 14 ?
            'CUMPLE' : 'NO CUMPLE',
          $pago['camposPagados']['tiempos'][$campo['id']],
        ]
      );
    }

    return implode("\r\n", $cvs);
  }

  private function setFilters(Request $request) {
    $campos_filtros = ["desde", "hasta", "export",
      "page", "limit", "search"];
    $isSomeValueSet = false;
    $filtros = [];

    foreach ($campos_filtros as $f) {
      $valF = $request->query->get($f);
      if (isset($valF) && $valF != "null") {
        $isSomeValueSet = true;
        $filtros[$f] = $valF;
      }
    }
    return array($filtros, $isSomeValueSet);
  }

  private function getNormalizePagos($datos)
  {

    return $this->get('serializer')->normalize($datos,
      'json',
      [
        'attributes' => [
          'solicitud' => [
            'institucion' => ['nombre', 'rfc'],
            'tipoPago'
          ],
          'monto',
          'fechaPagoFormatted',
          'referenciaBancaria',
          'camposPagados' => [
            'id',
            'fechaInicialFormatted',
            'fechaFinalFormatted',
            'lugaresSolicitados',
            'lugaresAutorizados',
            'displayCicloAcademico',
            'displayDelegacion',
            'monto',
            'displayCarrera'
          ]
        ]
      ]);
  }

}