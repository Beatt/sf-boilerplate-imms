<?php


namespace AppBundle\Controller\FOFOE;


use AppBundle\Controller\DIEControllerController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReporteIngresosController extends DIEControllerController
{
  /**
     * @Route("/fofoe/reporte_ingresos", methods={"GET"}, name="fofoe.reporte_ingresos.show")
   */
  public function showAction(Request $request) {

    return $this->render('fofoe/reporte_ingresos/index.html.twig');
  }

}