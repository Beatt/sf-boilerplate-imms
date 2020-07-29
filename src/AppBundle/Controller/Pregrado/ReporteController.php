<?php

namespace AppBundle\Controller\Pregrado;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\CampoClinico;
use AppBundle\Util\CVSUtil;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReporteController extends DIEControllerController
{
    /**
     * @Route("/pregrado/reporte/", methods={"GET"}, name="pregrado.reporte.show")
     */
    public function showAction(Request $request)
    {
      list($filtros, $isSomeValueSet) = $this->setFilters($request);

      if($isSomeValueSet) {
        $result =  $this->getDoctrine()
          ->getRepository(CampoClinico::class)
          ->getAllCamposByPage($filtros);

        $campos = $result[0];

        if (isset($filtros['export']) && $filtros['export']) {
          $responseCVS = new Response(
            "\xEF\xBB\xBF".
            $this->generarCVS(
              $this->getNormalizeCampos($campos)
            ) );
          $today = date('Y-m-d');
          $filename = "ReporteDetallado_$today.csv";

          $responseCVS->headers->set('Content-Type', 'text/csv; charset=UTF-8');
          $responseCVS->headers->set("Content-Disposition", "attachment; filename=\"$filename\"");

          return $responseCVS;
        }

        return new JsonResponse([
          'camposClinicos' => $this->getNormalizeCampos($campos, 'json'),
          'totalItems' => $result[1],
          'numPags' => $result[2],
          'pageSize' => $result[3]
        ]);

      }

        return $this->render('pregrado/reporte/index.html.twig');
    }

    private function setFilters(Request $request) {
      $campos_filtros = ["page", "limit", "search", "estatus", "cicloAcademico", "delegacion",
        "carrera", "fechaIni", "fechaFin", "export"];
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

    protected function generarCVS($campos) {
      $cvs = [];

      $headersCVS = ['Id', 'Núm_Solicitud', 'Carrera', 'Delegación',
        'Unidad', 'Institución',
      'Ciclo_Académico', 'Lugares_Solicitados', 'Lugares_Autorizados',
        'Fecha_Inicio', 'Fecha_Término', 'Horario', 'Asignatura',
        'Estado_Solicitud'
      ];
      $cvs[] = CVSUtil::arrayToCsvLine($headersCVS);

      foreach($campos as $c) {
        $cvs[] = CVSUtil::arrayToCsvLine(
          [$c['id'], $c['solicitud']['noSolicitud'],
            $c['convenio']['carrera']['displayName'],
            $c['convenio']['delegacion']['nombre'],
            $c['unidad']['nombre'],
            $c['convenio']['institucion']['nombre'],
            $c['convenio']['cicloAcademico']['nombre'],
            $c['lugaresSolicitados'], $c['lugaresAutorizados'],
            $c['displayFechaInicial'], $c['displayFechaFinal'],
            $c['horario'], $c['asignatura'],
            $c['estatus']['nombre']
          ]
        );
      }

      return //mb_convert_encoding(
        implode("\r\n", $cvs)
      //, 'UTF-16LE', 'UTF-8')
      ;
    }

  /**
   * @param $camposClinicos
   * @return array
   */
  private function getNormalizeCampos($camposClinicos, $format=null) {
    return $this->get('serializer')->normalize(
      $camposClinicos,
      $format,
      ['attributes' => [
        'id',
        'solicitud' => ['id', 'noSolicitud'],
        'convenio' =>
          ['carrera' => ['displayName' ],
            'delegacion' => ['nombre'],
            'institucion' => ['nombre'] ,
            'cicloAcademico' => ['nombre'] ],
        'displayFechaInicial',
        'displayFechaFinal',
        'horario',
        'asignatura',
        'unidad' => ['nombre'],
        'promocion',
        'lugaresSolicitados',
        'lugaresAutorizados',
        'estatus' => ['nombre']
      ]]
    );
  }

}