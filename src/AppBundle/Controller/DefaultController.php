<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @throws \Exception
     */
    public function indexAction()
    {
        $roles = $this->getUser()->getRoles();
        switch($roles[0]) {
            case 'ROLE_SUPER':
                return $this->redirectToRoute('admin');
            case 'ROLE_CAME':
                return $this->redirectToRoute('solicitud.index');
            case 'ROLE_IE':
                return $this->redirectToRoute('solicitudes#index', [
                    'id' => $this->getUser()->getInstitucion()->getId()
                ]);
            case 'ROLE_FOFOE':
                break;
            default:
                throw new \Exception('El usuario no tiene un rol asignado');
        }
    }
}
