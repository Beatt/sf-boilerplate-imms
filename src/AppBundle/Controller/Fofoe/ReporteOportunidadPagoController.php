<?php

namespace AppBundle\Controller\Fofoe;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\CampoClinico;
use AppBundle\Repository\CampoClinicoRepository;
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
   * @return Response
   */
  public function indexAction(Request $request) {

    list($filtros, $isSomeValueSet) = $this->setFilters($request);

    if ($isSomeValueSet) {
        $reporteRepository =  $this->getDoctrine()
            ->getRepository(CampoClinico::class);

      list($campos, $totalItems,$pagesCount, $pageSize) =
        $reporteRepository->getReporteOportunidadPago($filtros);

      $datos = $this->getNormalizeCampos($campos);
        //dump($datos);

      if (isset($filtros['export']) && $filtros['export']) {
        $responseCVS = new Response(
          "\xEF\xBB\xBF".
          $this->generarCVS($datos)
        );
        $today = date('Y-m-d');
        $filename = "ReporteOportunidadPago\_$today.csv";

        $responseCVS->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $responseCVS->headers->set("Content-Disposition", "attachment; filename=\"$filename\"");

        return $responseCVS;
      }

      return new JsonResponse([
        'reporte' => $datos,
        'totalItems' => $totalItems,
        'numPags' => $pagesCount,
        'pageSize' => $pageSize
      ]);
    }

    return $this->render('fofoe/reporte_oportunidad/index.html.twig');
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

    foreach($datos as $campo) {
      $cvs[] = CVSUtil::arrayToCsvLine(
        [ ++$indexRow, $campo['displayDelegacion'],
          $campo['displayCicloAcademico'],
          $campo['displayCarrera'],
          $campo['fechaInicialFormatted'],
          $campo['fechaFinalFormatted'],
          $campo['solicitud']['institucion']['nombre'],
          $campo['lugaresAutorizados'],
          $campo['monto'],
          $campo['referenciaBancaria'],
          $campo['lastPago'] ? $campo['lastPago']['fechaPagoFormatted'] : '',
          $campo['lastPago'] && $campo['lastPago']['factura'] ?
            $campo['lastPago']['factura']['fechaFacturacionFormatted'] : '',
          $campo['tiempoPago'] > -1000 ?
              ($campo['tiempoPago'] >= 14 ? 'CUMPLE' : 'NO CUMPLE')
                : 'PENDIENTE',
          $campo['tiempoPago'] > -1000 ? $campo['tiempoPago'] : '',
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

  private function getNormalizeCampos($datos)
  {

    return $this->get('serializer')->normalize($datos,
      'json',
      [
        'attributes' => [
            'id',
            'fechaInicialFormatted',
            'fechaFinalFormatted',
            'lugaresSolicitados',
            'lugaresAutorizados',
            'displayCicloAcademico',
            'displayDelegacion',
            'tiempoPago',
            'monto',
            'displayCarrera',
            'referenciaBancaria',
            'solicitud' => [
                'institucion' => ['nombre', 'rfc'],
                'tipoPago'
            ],
            'lastPago' => [
                'monto',
                'fechaPagoFormatted',
                'requiereFactura',
                'referenciaBancaria',
                'factura' => [  'id', 'folio', 'fechaFacturacionFormatted' ],
            ]
        ]
      ]);
  }

}