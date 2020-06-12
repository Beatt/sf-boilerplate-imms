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

/*    if ($isSomeValueSet) {
      $anio = isset($filtros['anio']) ? $filtros['anio'] : date("Y");
      $ingresos = $reporteRepository->getReporteIngresosMes($anio);

      if (isset($filtros['export']) && $filtros['export']) {
        $responseCVS = new Response(
          "\xEF\xBB\xBF".
          $this->generarCVS(
            $this->getNormalizeReporteIngresos($ingresos)
          ) );
        $today = date('Y-m-d');
        $filename = "exportReporteIngresosMes$anio\_$today.csv";

        $responseCVS->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $responseCVS->headers->set("Content-Disposition", "attachment; filename=\"$filename\"");

        return $responseCVS;
      }

      return new JsonResponse([
        'reporte' => $this->getNormalizeReporteIngresos($ingresos)
      ]);
    }*/

    return $this->render('fofoe/reporte_oportunidad/index.html.twig',
      array('pagos' => $datos));
  }

  protected function generarCVS($datos) {
    $cvs = [];

    $headersCVS = [
      'Mes/Año', 'CCS/INT Validados', 'CCS/INT Pendientes'
    ];
    $cvs[] = CVSUtil::arrayToCsvLine($headersCVS);

    $totalVal = 0;
    $totalPend = 0;

    foreach($datos as $c) {
      $cvs[] = CVSUtil::arrayToCsvLine(
        [$c['Mes'].'/'.$c['Anio'],
          $c['ingVal'],
          $c['ingPend'],
        ]
      );
      $totalVal += intval($c['ingVal']);
      $totalPend += intval($c['ingPend']);
    }

    $cvs[] = CVSUtil::arrayToCsvLine(
      ['Total Ciclo',  $totalVal, $totalPend,]
    );

    return implode("\r\n", $cvs);
  }

  private function setFilters(Request $request) {
    $campos_filtros = ["export", "anio"];
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