<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class IEController extends Controller
{
    /**
     * @Route("/misolicitud", name="misolicitud")
     */
    public function index()
    {
        return $this->render('IEValInfo/index.html.twig',
        	['name' => 'Instituto universitario de Ciencias Médicas y Humanisticas de Nayarit',
        	 'no' => 'NS_007',
        	 'institucion' => [
        	 	'RFC' => 'RAGE930107',
        	 	'representante_legal' => 'Erik Rangel',
        	 	'domicilio' => 'Av Paseos de reforma',
        	 	'correo' => 'erikrangel25@gmailcom',
        	 	'telefono' => '123456',
        	 	'fax' => '2432j',
        	 	'pagina_web' => 'www.imss.gob.mx'
        	],
        	'Carrera' => [
                [
                    'grado' => 'Licenciatura',
                    'carrera' => 'Trabajo Social'
                ],
                [
                  	'grado' => 'Licenciatura',
                    'carrera' => 'Terapia física'
                ]
            ]
        ]);
    }
}
