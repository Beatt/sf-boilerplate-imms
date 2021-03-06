<?php

namespace AppBundle\Controller\Fofoe;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Repository\Reportes\FOFOEIngresosRepositoryInterface;
use AppBundle\Util\CVSUtil;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReporteIngresosController extends DIEControllerController
{
  /**
   * @Route("/fofoe/reporte_ingresos", methods={"GET"}, name="fofoe.reporte_ingresos")
   * @param Request $request
   * @param PagoRepositoryInterface $reporteRepository
   * @return Response
   */
  public function indexAction(Request $request, PagoRepositoryInterface $reporteRepository) {

    list($filtros, $isSomeValueSet) = $this->setFilters($request);

    if ($isSomeValueSet) {
      $anio = isset($filtros['anio']) ? $filtros['anio'] : date("Y");
      $ingresos = $reporteRepository->getReporteIngresosMes($anio);

      if (isset($filtros['export']) && $filtros['export']) {
        $responseCVS = new Response(
          "\xEF\xBB\xBF".
          $this->generarCVS(
            $this->getNormalizeReporteIngresos($ingresos)
          ) );
        $today = date('Y-m-d');
        $filename = "ReporteIngresosMes$anio\_$today.csv";

        $responseCVS->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $responseCVS->headers->set("Content-Disposition", "attachment; filename=\"$filename\"");

        return $responseCVS;
      }

      return new JsonResponse([
        'reporte' => $this->getNormalizeReporteIngresos($ingresos)
      ]);
    }

    return $this->render('fofoe/reporte_ingresos/index.html.twig');
  }

  protected function generarCVS($datos) {
    $cvs = [];

    $headersCVS = [
      'Mes/Año', 'CCS Área de la Salud', 'Internado Médico', 'Total Mensual'
    ];
    $cvs[] = CVSUtil::arrayToCsvLine($headersCVS);

    foreach($datos as $c) {
      $cvs[] = CVSUtil::arrayToCsvLine(
        [$c['Mes'].'/'.$c['Anio'],
          $c['ingVal'],
          $c['ingPend']
        ]
      );
    }

    return //mb_convert_encoding(
      implode("\r\n", $cvs)
      //, 'UTF-16LE', 'UTF-8')
      ;
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

  private function getNormalizeReporteIngresos($datos)
  {

    return $this->get('serializer')->normalize(
      $datos,
      'json',
      [
        'attributes' => [
          'Mes',
          'Anio',
          'ingCCS',
          'ingInt',
          'Total'
        ]
      ]
    );
  }

}