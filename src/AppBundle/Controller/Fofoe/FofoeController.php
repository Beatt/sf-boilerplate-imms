<?php


namespace AppBundle\Controller\Fofoe;


class FofoeController extends \AppBundle\Controller\DIEControllerController
{
  
    public function menuAction()
    {
        $user = $this->getUser();
        
        //$hasAccess_Reportes = $this->isGranted('ROLE_R');

        return $this->render('fofoe/menu.html.twig', [
            'usuario' => $user
        ]);
    }
}