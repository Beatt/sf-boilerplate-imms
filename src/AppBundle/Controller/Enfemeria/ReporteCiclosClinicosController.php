<?php

namespace AppBundle\Controller\Enfemeria;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Repository\Reportes\FOFOEIngresosRepositoryInterface;
use AppBundle\Util\CVSUtil;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReporteCiclosClinicosController extends DIEControllerController
{
  /**
   * @Route("/enfermeria/reporte_ciclos", methods={"GET"}, name="enfermeria.reporte_ciclos")
   * @param Request $request
   * @param PagoRepositoryInterface $reporteRepository
   * @return Response
   */
  public function indexAction(Request $request, PagoRepositoryInterface $reporteRepository) {

    return $this->render('enfermeria/reporte_ciclos/index.html.twig');
  }

}