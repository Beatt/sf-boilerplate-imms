<?php

namespace AppBundle\Controller\InstitucionEducativa;

use AppBundle\Repository\EstatusCampoRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EstatusCampoClinicoController extends Controller
{
    /**
     * @Route("/estatus-campos-clinicos", name="estatus_campos_clinicos#index")
     * @param EstatusCampoRepositoryInterface $repository
     */
    public function indexAction(EstatusCampoRepositoryInterface $repository)
    {
        $estatus = $repository->findAll();
        return new Response(
            $this->get('serializer')
                ->serialize($estatus, 'json')
        );
    }
}
