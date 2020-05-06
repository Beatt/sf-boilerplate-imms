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

        $campos = ["offset", "search", "estatus", "cicloAcademico", "delegacion", "carrera"];
        $isSomeValueSet = false;
        $filtros = [];

        foreach ($campos as $f) {
          $valF = $request->query->get($f);
          if (isset($valF) && $valF != "null") {
            $isSomeValueSet = true;
            $filtros[$f] = $valF;
          }
        }

      $campos =  $this->getDoctrine()
        ->getRepository(CampoClinico::class)
        ->getAllCampos($filtros);

      if($isSomeValueSet) {
        return new JsonResponse([
          'camposClinicos' => $this->getNormalizeCampos($campos),
          'total' => round(count($campos) / SolicitudRepositoryInterface::PAGINATOR_PER_PAGE)
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