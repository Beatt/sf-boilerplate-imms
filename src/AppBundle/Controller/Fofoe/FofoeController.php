<?php


namespace AppBundle\Controller\Fofoe;


class FofoeController extends \AppBundle\Controller\DIEControllerController
{
    public function menuAction()
    {
        $user = $this->getUser();
        return $this->render('fofoe/menu.html.twig', [
            'usuario' => $user
        ]);
    }
}