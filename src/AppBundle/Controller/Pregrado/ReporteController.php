<?php

namespace AppBundle\Controller\Pregrado;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Entity\CampoClinico;
use AppBundle\Repository\SolicitudRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReporteController extends DIEControllerController
{
    /**
     * @Route("/pregrado/reporte", methods={"GET"}, name="pregrado.reporte.show")
     */
    public function showAction(Request $request)
    {

        $campos = ["page", "limit", "search", "estatus", "cicloAcademico", "delegacion", "carrera"];
        $isSomeValueSet = false;
        $filtros = [];

        foreach ($campos as $f) {
          $valF = $request->query->get($f);
          if (isset($valF) && $valF != "null") {
            $isSomeValueSet = true;
            $filtros[$f] = $valF;
          }
        }

      if($isSomeValueSet) {
        $result =  $this->getDoctrine()
          ->getRepository(CampoClinico::class)
          ->getAllCamposByPage($filtros);

        $campos = $result[0];

        return new JsonResponse([
          'camposClinicos' => $this->getNormalizeCampos($campos),
          'total' => $result[1],
          'numPags' => $result[2]
        ]);

      }

        return $this->render('pregrado/reporte/index.html.twig');
    }

  /**
   * @param $camposClinicos
   * @return array
   */
  private function getNormalizeCampos($camposClinicos) {
    return $this->get('serializer')->normalize(
      $camposClinicos,
      'json',
      ['attributes' => [
        'id',
        'solicitud' => ['id', 'noSolicitud'],
        'convenio' => ['carrera' => ['nombre', 'nivelAcademico' => ['nombre']], 'delegacion' => ['nombre'],
          'institucion' => ['nombre'] , 'cicloAcademico' => ['nombre'] ],
        'fechaInicial',
        'fechaFinal',
        'horario',
        'unidad' => ['nombre'],
        'promocion',
        'lugaresSolicitados',
        'lugaresAutorizados',
        'estatus' => ['nombre']
      ]]
    );
  }
}