<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ConvenioController extends Controller
{
    /**
     * @Route("/convenios", name="convenios")
     */
    public function index()
    {
        return $this->render('convenio/index.html.twig', [
            'convenios' => [
                [
                    'id' => 1,
                    'name' => 'Gabriel'
                ],
                [
                    'id' => 2,
                    'name' => 'Geovanni'
                ]
            ]
        ]);
    }
}
