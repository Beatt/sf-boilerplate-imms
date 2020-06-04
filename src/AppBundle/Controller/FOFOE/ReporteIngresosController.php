<?php

namespace AppBundle\Controller\FOFOE;

use AppBundle\Controller\DIEControllerController;
use AppBundle\Repository\PagoRepositoryInterface;
use AppBundle\Repository\Reportes\FOFOEIngresosRepositoryInterface;
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

    $anio = date("Y");
    $ingresos = $reporteRepository->getReporteIngresosMes($anio);
    var_dump($ingresos);

    return $this->render('fofoe/reporte_ingresos/index.html.twig');
  }

}