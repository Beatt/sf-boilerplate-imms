<?php


namespace AppBundle\Controller\Fofoe;

use AppBundle\Controller\DIEControllerController;
use Symfony\Component\Routing\Annotation\Route;

class ReportesFofoeController extends DIEControllerController
{
  /**
   * @Route("/fofoe/reportes/", name="fofoe.reportes")
   */
  public function indexAction() {
    return $this->render('fofoe/reportes.html.twig');
  }
}